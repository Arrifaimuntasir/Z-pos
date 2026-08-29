<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Schema;

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

        // Generate vCard data
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "N:;" . $shop->name . ";;;\n";
        $vcard .= "FN:" . $shop->name . "\n";
        $vcard .= "ORG:" . $shop->name . "\n";
        if ($shop->phone) {
            $vcard .= "TEL;TYPE=WORK,VOICE:" . $shop->phone . "\n";
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
}
