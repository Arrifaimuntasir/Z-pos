<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = \App\Models\Shop::with('users')->get();
        return view('superadmin.shops.index', compact('shops'));
    }

    public function edit(\App\Models\Shop $shop)
    {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Shop $shop)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'valid_until' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $shop->name = $request->name;
        $shop->valid_until = $request->valid_until;
        $shop->is_active = $request->has('is_active');
        $shop->save();

        return redirect()->route('superadmin.shops.index')->with('success', 'Shop updated successfully.');
    }

    public function toggleStatus(\App\Models\Shop $shop)
    {
        $shop->is_active = !$shop->is_active;
        $shop->save();

        $status = $shop->is_active ? 'activated' : 'suspended';
        return back()->with('success', "Shop has been successfully {$status}.");
    }

    public function destroy(\App\Models\Shop $shop)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($shop) {
                $shopId = $shop->id;
                
                // Disable foreign key checks to prevent cascade order issues
                \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
                
                // Safisha data zote za duka hili (Delete everything)
                \Illuminate\Support\Facades\DB::table('sale_items')->whereIn('sale_id', function($q) use ($shopId) {
                    $q->select('id')->from('sales')->where('shop_id', $shopId);
                })->delete();
                \Illuminate\Support\Facades\DB::table('sales')->where('shop_id', $shopId)->delete();
                
                \Illuminate\Support\Facades\DB::table('purchase_items')->whereIn('purchase_id', function($q) use ($shopId) {
                    $q->select('id')->from('purchases')->where('shop_id', $shopId);
                })->delete();
                \Illuminate\Support\Facades\DB::table('purchases')->where('shop_id', $shopId)->delete();
                
                \Illuminate\Support\Facades\DB::table('expenses')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('payments')->where('shop_id', $shopId)->delete();
                
                \Illuminate\Support\Facades\DB::table('branch_product')->whereIn('branch_id', function($q) use ($shopId) {
                    $q->select('id')->from('branches')->where('shop_id', $shopId);
                })->delete();
                \Illuminate\Support\Facades\DB::table('products')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('warranties')->where('shop_id', $shopId)->delete();
                
                \Illuminate\Support\Facades\DB::table('categories')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('brands')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('units')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('customers')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('suppliers')->where('shop_id', $shopId)->delete();
                \Illuminate\Support\Facades\DB::table('branches')->where('shop_id', $shopId)->delete();
                
                // Safisha users na roles zao
                $userIds = \Illuminate\Support\Facades\DB::table('users')->where('shop_id', $shopId)->pluck('id');
                if ($userIds->isNotEmpty()) {
                    \Illuminate\Support\Facades\DB::table('model_has_roles')
                        ->where('model_type', 'App\Models\User')
                        ->whereIn('model_id', $userIds)
                        ->delete();
                    \Illuminate\Support\Facades\DB::table('users')->whereIn('id', $userIds)->delete();
                }
                
                // Mwisho futa duka lenyewe
                \Illuminate\Support\Facades\DB::table('shops')->where('id', $shopId)->delete();
                
                // Re-enable checks
                \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            });
            
            return back()->with('success', 'Shop has been permanently deleted.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
            return back()->with('error', 'Error deleting shop: ' . $e->getMessage());
        }
    }
}
