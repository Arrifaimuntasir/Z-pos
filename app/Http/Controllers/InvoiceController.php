<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices (sales).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $isAdmin = auth()->user()->hasRole('Administrator');
        $invoices = Sale::with('customer')
            ->when(!$isAdmin, function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q1) use ($search) {
                    $q1->where('reference_no', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->appends(['search' => $search]);
            
        return view('invoices.index', compact('invoices', 'search'));
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Sale::with(['customer', 'items.product'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Download the invoice as a PDF.
     */
    public function downloadPdf($id)
    {
        $invoice = Sale::with(['customer', 'items.product'])->findOrFail($id);
        $shop = auth()->user()->shop ?? null;
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'shop'));

        
        return $pdf->download('Invoice_' . $invoice->reference_no . '.pdf');
    }
}
