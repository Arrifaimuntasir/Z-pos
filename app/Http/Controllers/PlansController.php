<?php

namespace App\Http\Controllers;

class PlansController extends Controller
{
    /**
     * Show all subscription plans (monthly + yearly), with the shop's current plan highlighted.
     */
    public function index()
    {
        $shop = auth()->user()->shop;
        $currentPackage = $shop->package ?? 'starter';

        return view('plans.index', compact('currentPackage'));
    }
}
