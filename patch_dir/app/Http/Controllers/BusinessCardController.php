<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BusinessCardController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;

        // Auto-migrate business card fields if they don't exist
        if (!Schema::hasColumn('shops', 'card_theme')) {
            Schema::table('shops', function($table) {
                $table->string('card_theme')->nullable();
                $table->string('card_color')->nullable();
                $table->string('card_phone')->nullable();
                $table->string('card_email')->nullable();
                $table->string('card_message')->nullable();
            });
        }
        
        if (!$shop) {
            return redirect()->route('home')->with('error', 'No shop found for your account.');
        }

        $displayPhone = $shop->card_phone ?? $shop->phone;
        $displayEmail = $shop->card_email ?? auth()->user()->email;

        // Generate vCard data
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "N:;" . $shop->name . ";;;\n";
        $vcard .= "FN:" . $shop->name . "\n";
        $vcard .= "ORG:" . $shop->name . "\n";
        if ($displayPhone) {
            $vcard .= "TEL;TYPE=WORK,VOICE:" . $displayPhone . "\n";
        }
        if ($displayEmail) {
            $vcard .= "EMAIL;TYPE=WORK:" . $displayEmail . "\n";
        }
        if ($shop->address) {
            $vcard .= "ADR;TYPE=WORK:;;" . $shop->address . ";;;;\n";
        }
        $vcard .= "END:VCARD";

        // Generate QR Code with the vCard data
        $svg = QrCode::size(120)
            ->margin(1)
            ->color(15, 23, 42) // #0f172a
            ->generate($vcard);
            
        $qrCode = 'data:image/svg+xml;base64,' . base64_encode($svg);

        return view('shop.business_card', compact('shop', 'qrCode'));
    }

    public function save(Request $request)
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return response()->json(['success' => false, 'message' => 'No shop found']);
        }

        $shop->card_theme = $request->card_theme;
        $shop->card_color = $request->card_color;
        $shop->card_phone = $request->card_phone;
        $shop->card_email = $request->card_email;
        $shop->card_message = $request->card_message;
        $shop->save();

        return response()->json(['success' => true, 'message' => 'Card design saved successfully!']);
    }

    public function downloadQr()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            abort(404, 'Shop not found');
        }

        $url = url('/shop/' . $shop->id);

        // Parse custom color or use default green
        $colorHex = $shop->card_color ?? '#10b981';
        
        // Ensure hex has 6 digits (fallback if invalid)
        if (preg_match('/^#([a-f0-9]{3}){1,2}$/i', $colorHex)) {
            $colorHex = str_replace('#', '', $colorHex);
            if (strlen($colorHex) == 3) {
                $colorHex = $colorHex[0] . $colorHex[0] . $colorHex[1] . $colorHex[1] . $colorHex[2] . $colorHex[2];
            }
            $r = hexdec(substr($colorHex, 0, 2));
            $g = hexdec(substr($colorHex, 2, 2));
            $b = hexdec(substr($colorHex, 4, 2));
        } else {
            $r = 16; $g = 185; $b = 129; // default green
        }

        // Generate high quality SVG QR code
        $svg = QrCode::size(800) // Huge for poster printing
            ->margin(2)
            ->style('round')
            ->eye('circle')
            ->color($r, $g, $b)
            ->generate($url);
            
        $filename = Str::slug($shop->name) . '-Website-QR-Code.svg';

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
