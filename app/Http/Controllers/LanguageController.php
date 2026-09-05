<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang(Request $request, $lang)
    {
        if (array_key_exists($lang, config('app.locales', ['en' => 'English', 'sw' => 'Swahili']))) {
            Session::put('applocale', $lang);
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forever('applocale_persist', $lang));
            if (auth()->check()) {
                auth()->user()->update(['locale' => $lang]);
            }
        }
        
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }
        
        return redirect()->back();
    }
}
