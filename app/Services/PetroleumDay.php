<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\FuelDailyClosing;
use App\Models\FuelDailyClosingLine;
use App\Models\FuelDailyOpening;
use App\Models\FuelDailyOpeningTank;
use App\Models\FuelDelivery;
use App\Models\FuelNozzle;
use App\Models\FuelPumpReading;
use App\Models\FuelSale;
use App\Models\FuelTank;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class PetroleumDay
{
    public const TANK_TOLERANCE_LITRES = 0.5;
    public const CASH_TOLERANCE_TZS = 500;

    public const PAYMENT_METHODS = [
        'cash' => 'Cash',
        'card' => 'Card',
        'mobile_money' => 'Mpesa',
        'bank' => 'Bank',
        'other' => 'Other',
    ];

    public static function openDay(int $branchId): ?FuelDailyOpening
    {
        return FuelDailyOpening::where('branch_id', $branchId)->whereNull('closed_at')->latest('id')->first();
    }

    public static function status(float $variance, float $tolerance): string
    {
        if (abs($variance) <= $tolerance) {
            return 'balanced';
        }

        return $variance > 0 ? 'over' : 'short';
    }

    public static function open(int $branchId, string $date, float $openingCash, int $userId): FuelDailyOpening
    {
        return DB::transaction(function () use ($branchId, $date, $openingCash, $userId) {
            if ($current = FuelDailyOpening::where('branch_id', $branchId)->whereNull('closed_at')->lockForUpdate()->first()) {
                throw new RuntimeException('Day ' . $current->business_date->format('d M Y') . ' is still open. Close it before opening a new day.');
            }
            if (FuelDailyOpening::where('branch_id', $branchId)->whereDate('business_date', $date)->exists()) {
                throw new RuntimeException('This date has already been opened. Pick the next date.');
            }

            $now = now();
            $day = FuelDailyOpening::create([
                'branch_id' => $branchId,
                'business_date' => $date,
                'opening_cash' => $openingCash,
                'opened_by' => $userId,
                'opened_at' => $now,
            ]);

            foreach (FuelTank::where('branch_id', $branchId)->where('status', 'active')->get() as $tank) {
                FuelDailyOpeningTank::create([
                    'fuel_daily_opening_id' => $day->id,
                    'fuel_tank_id' => $tank->id,
                    'litres' => $tank->current_stock,
                ]);
            }

            foreach (FuelNozzle::where('branch_id', $branchId)->where('status', 'active')->get() as $nozzle) {
                FuelPumpReading::create([
                    'branch_id' => $branchId,
                    'fuel_daily_opening_id' => $day->id,
                    'fuel_nozzle_id' => $nozzle->id,
                    'reading_type' => 'opening',
                    'meter' => $nozzle->meter_reading,
                    'recorded_by' => $userId,
                    'recorded_at' => $now,
                ]);
            }

            return $day;
        });
    }

    public static function recordDelivery(int $branchId, array $d, int $userId): FuelDelivery
    {
        return DB::transaction(function () use ($branchId, $d, $userId) {
            $tank = FuelTank::lockForUpdate()->where('branch_id', $branchId)->findOrFail($d['fuel_tank_id']);
            if ($tank->status !== 'active') {
                throw new RuntimeException("Tank {$tank->name} is not active.");
            }

            $qty = round((float) $d['quantity'], 3);
            $space = round((float) $tank->capacity - (float) $tank->current_stock, 3);
            if ($qty > $space + 0.0005) {
                throw new RuntimeException("Tank {$tank->name} has room for only " . number_format($space, 2) . ' L more.');
            }

            $unitCost = round((float) ($d['unit_cost'] ?? 0), 2);
            $delivery = FuelDelivery::create([
                'branch_id' => $branchId,
                'fuel_tank_id' => $tank->id,
                'product_id' => $tank->product_id,
                'supplier_id' => $d['supplier_id'] ?? null,
                'delivery_date' => $d['delivery_date'],
                'delivery_time' => $d['delivery_time'] ?? null,
                'invoice_number' => $d['invoice_number'] ?? null,
                'delivery_note_number' => $d['delivery_note_number'] ?? null,
                'driver' => $d['driver'] ?? null,
                'vehicle_number' => $d['vehicle_number'] ?? null,
                'quantity' => $qty,
                'unit_cost' => $unitCost,
                'total_cost' => round($qty * $unitCost, 2),
                'received_by' => $userId,
                'approved_by' => $userId,
                'notes' => $d['notes'] ?? null,
            ]);

            $supplierName = $delivery->supplier_id ? Supplier::find($delivery->supplier_id)?->name : null;
            PetroleumStock::record(
                $tank,
                'delivery',
                $qty,
                0,
                $d['invoice_number'] ?: ($d['delivery_note_number'] ?: 'DEL-' . $delivery->id),
                'Delivery' . ($supplierName ? " from {$supplierName}" : '') . ($d['vehicle_number'] ? ", vehicle {$d['vehicle_number']}" : '')
            );

            return $delivery;
        });
    }

    public static function recordSale(int $branchId, array $d, int $userId): FuelSale
    {
        $day = self::openDay($branchId);
        if (!$day) {
            throw new RuntimeException('Open the station day before selling fuel.');
        }

        return DB::transaction(function () use ($branchId, $d, $userId, $day) {
            $nozzle = FuelNozzle::with('pump')->where('branch_id', $branchId)->where('status', 'active')->findOrFail($d['fuel_nozzle_id']);
            $tank = FuelTank::lockForUpdate()->findOrFail($nozzle->fuel_tank_id);
            if ($tank->status !== 'active') {
                throw new RuntimeException("Tank {$tank->name} is not active.");
            }

            $litres = round((float) $d['litres'], 3);
            if ($litres <= 0) {
                throw new RuntimeException('Litres must be more than zero.');
            }
            if ($litres > (float) $tank->current_stock + 0.0005) {
                throw new RuntimeException("Not enough fuel in {$tank->name}: only " . number_format($tank->current_stock, 2) . ' L left.');
            }

            $product = Product::findOrFail($tank->product_id);
            $price = round((float) $product->selling_price, 2);
            $gross = round($litres * $price, 2);
            $discount = round((float) ($d['discount_amount'] ?? 0), 2);
            if ($discount < 0 || $discount > $gross) {
                throw new RuntimeException('Discount cannot be more than the sale amount (' . number_format($gross, 2) . ').');
            }
            $net = round($gross - $discount, 2);
            $method = $d['payment_method'];

            $receipt = self::nextReceiptNumber();
            $sale = Sale::create([
                'customer_id' => $d['customer_id'] ?? null,
                'user_id' => $userId,
                'reference_no' => $receipt,
                'sale_date' => $day->business_date->toDateString(),
                'total_amount' => $net,
                'paid_amount' => $net,
                'payment_method' => self::PAYMENT_METHODS[$method],
                'payment_status' => 'paid',
                'branch_id' => $branchId,
                'notes' => "Fuel sale · Pump {$nozzle->pump->number} · Nozzle {$nozzle->number}",
            ]);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $litres,
                'unit_cost' => $product->cost_price,
                'unit_price' => round($net / $litres, 2),
                'subtotal' => $net,
            ]);

            PetroleumStock::record($tank, 'sale', 0, $litres, $receipt, "Pump {$nozzle->pump->number} nozzle {$nozzle->number}");
            $nozzle->update(['meter_reading' => (float) $nozzle->meter_reading + $litres]);

            return FuelSale::create([
                'branch_id' => $branchId,
                'fuel_daily_opening_id' => $day->id,
                'receipt_number' => $receipt,
                'business_date' => $day->business_date->toDateString(),
                'fuel_pump_id' => $nozzle->fuel_pump_id,
                'fuel_nozzle_id' => $nozzle->id,
                'fuel_tank_id' => $tank->id,
                'product_id' => $product->id,
                'user_id' => $userId,
                'customer_id' => $d['customer_id'] ?? null,
                'sale_id' => $sale->id,
                'litres' => $litres,
                'price_per_litre' => $price,
                'gross_amount' => $gross,
                'discount_amount' => $discount,
                'net_amount' => $net,
                'payment_method' => $method,
                'notes' => $d['notes'] ?? null,
            ]);
        });
    }

    private static function nextReceiptNumber(): string
    {
        do {
            $number = 'FS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (FuelSale::withoutGlobalScopes()->where('shop_id', auth()->user()->shop_id)->where('receipt_number', $number)->exists());

        return $number;
    }

    /**
     * Expected figures for a station day. Used by both the closing preview and the closing itself,
     * so the numbers shown before closing are the numbers that get stored.
     */
    public static function reconcile(FuelDailyOpening $day): array
    {
        $sales = FuelSale::where('fuel_daily_opening_id', $day->id)->get();

        $tanks = [];
        foreach ($day->tanks()->with('tank.product')->get() as $line) {
            $tank = $line->tank;
            $opening = (float) $line->litres;
            $delivered = (float) FuelDelivery::where('branch_id', $day->branch_id)
                ->where('fuel_tank_id', $tank->id)
                ->where('created_at', '>=', $day->opened_at)
                ->sum('quantity');
            $sold = (float) $sales->where('fuel_tank_id', $tank->id)->sum('litres');
            $tanks[] = [
                'tank' => $tank,
                'opening' => $opening,
                'delivered' => $delivered,
                'sold' => $sold,
                'expected' => round($opening + $delivered - $sold, 3),
            ];
        }

        $nozzles = [];
        foreach ($day->readings()->where('reading_type', 'opening')->with('nozzle.pump')->get() as $reading) {
            $nozzle = $reading->nozzle;
            $opening = (float) $reading->meter;
            $sold = (float) $sales->where('fuel_nozzle_id', $nozzle->id)->sum('litres');
            $nozzles[] = [
                'nozzle' => $nozzle,
                'opening' => $opening,
                'sold' => $sold,
                'expected' => round($opening + $sold, 3),
            ];
        }

        $cashSales = (float) $sales->where('payment_method', 'cash')->sum('net_amount');
        $expectedCash = round((float) $day->opening_cash + $cashSales, 2);

        return [
            'tanks' => $tanks,
            'nozzles' => $nozzles,
            'cash_sales' => $cashSales,
            'opening_cash' => (float) $day->opening_cash,
            'expected_cash' => $expectedCash,
            'sales' => $sales,
        ];
    }

    public static function close(FuelDailyOpening $day, array $tankPhysical, array $nozzlePhysical, float $physicalCash, int $userId, ?string $notes): FuelDailyClosing
    {
        return DB::transaction(function () use ($day, $tankPhysical, $nozzlePhysical, $physicalCash, $userId, $notes) {
            $day = FuelDailyOpening::lockForUpdate()->findOrFail($day->id);
            if (!$day->isOpen()) {
                throw new RuntimeException('This day is already closed.');
            }

            $r = self::reconcile($day);

            foreach ($r['tanks'] as $row) {
                if (!array_key_exists($row['tank']->id, $tankPhysical) || $tankPhysical[$row['tank']->id] === '' || $tankPhysical[$row['tank']->id] === null) {
                    throw new RuntimeException("Enter the physical level of {$row['tank']->name}.");
                }
            }
            foreach ($r['nozzles'] as $row) {
                $value = $nozzlePhysical[$row['nozzle']->id] ?? null;
                if ($value === null || $value === '') {
                    throw new RuntimeException("Enter the closing meter of pump {$row['nozzle']->pump->number} nozzle {$row['nozzle']->number}.");
                }
                if ((float) $value < $row['opening']) {
                    throw new RuntimeException("Closing meter of pump {$row['nozzle']->pump->number} nozzle {$row['nozzle']->number} cannot be less than its opening meter.");
                }
            }

            $cashVariance = round($physicalCash - $r['expected_cash'], 2);
            $closing = FuelDailyClosing::create([
                'branch_id' => $day->branch_id,
                'fuel_daily_opening_id' => $day->id,
                'business_date' => $day->business_date->toDateString(),
                'expected_cash' => $r['expected_cash'],
                'physical_cash' => round($physicalCash, 2),
                'cash_variance' => $cashVariance,
                'cash_status' => self::status($cashVariance, self::CASH_TOLERANCE_TZS),
                'closed_by' => $userId,
                'closed_at' => now(),
                'notes' => $notes,
            ]);

            foreach ($r['tanks'] as $row) {
                $tank = FuelTank::lockForUpdate()->findOrFail($row['tank']->id);
                $physical = round((float) $tankPhysical[$tank->id], 3);
                $variance = round($physical - $row['expected'], 3);

                FuelDailyClosingLine::create([
                    'fuel_daily_closing_id' => $closing->id,
                    'line_type' => 'tank',
                    'fuel_tank_id' => $tank->id,
                    'label' => $tank->name . ' · ' . ($tank->product->name ?? ''),
                    'opening' => $row['opening'],
                    'delivered' => $row['delivered'],
                    'sold' => $row['sold'],
                    'expected' => $row['expected'],
                    'physical' => $physical,
                    'variance' => $variance,
                    'status' => self::status($variance, self::TANK_TOLERANCE_LITRES),
                ]);

                // Book stock moves to the physical dip; the difference stays visible in the ledger.
                if (abs($variance) > 0.0005) {
                    PetroleumStock::record(
                        $tank,
                        'reconciliation',
                        max($variance, 0),
                        max(-$variance, 0),
                        'CLOSE-' . $closing->id,
                        'Closing reconciliation to physical dip (' . number_format($variance, 3) . ' L)'
                    );
                } else {
                    PetroleumStock::syncProductBranchStock($tank->branch_id, $tank->product_id);
                }
            }

            foreach ($r['nozzles'] as $row) {
                $nozzle = FuelNozzle::findOrFail($row['nozzle']->id);
                $physical = round((float) $nozzlePhysical[$nozzle->id], 3);
                $expectedMeter = $row['expected'];
                $variance = round($physical - $expectedMeter, 3);

                FuelPumpReading::create([
                    'branch_id' => $day->branch_id,
                    'fuel_daily_opening_id' => $day->id,
                    'fuel_nozzle_id' => $nozzle->id,
                    'reading_type' => 'closing',
                    'meter' => $physical,
                    'recorded_by' => $userId,
                    'recorded_at' => now(),
                ]);

                FuelDailyClosingLine::create([
                    'fuel_daily_closing_id' => $closing->id,
                    'line_type' => 'nozzle',
                    'fuel_nozzle_id' => $nozzle->id,
                    'label' => 'Pump ' . $nozzle->pump->number . ' · Nozzle ' . $nozzle->number,
                    'opening' => $row['opening'],
                    'delivered' => 0,
                    'sold' => $row['sold'],
                    'expected' => $expectedMeter,
                    'physical' => $physical,
                    'variance' => $variance,
                    'status' => self::status($variance, self::TANK_TOLERANCE_LITRES),
                ]);

                $nozzle->update(['meter_reading' => $physical]);
            }

            $day->update(['closed_at' => now(), 'closed_by' => $userId]);

            return $closing;
        });
    }
}
