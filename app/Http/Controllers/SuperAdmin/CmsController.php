<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CmsController extends Controller
{
    private array $pages = [
        'about'    => ['label' => 'About Us',     'icon' => 'bi-building',          'color' => 'primary'],
        'pricing'  => ['label' => 'Pricing',      'icon' => 'bi-tag-fill',          'color' => 'success'],
        'features' => ['label' => 'Features',     'icon' => 'bi-grid-fill',         'color' => 'info'],
        'contact'  => ['label' => 'Contact',      'icon' => 'bi-envelope-fill',     'color' => 'warning'],
        'privacy'  => ['label' => 'Privacy Policy','icon' => 'bi-shield-lock-fill', 'color' => 'secondary'],
        'terms'    => ['label' => 'Terms & Conditions', 'icon' => 'bi-file-text-fill','color' => 'dark'],
        'cookies'  => ['label' => 'Cookie Policy','icon' => 'bi-cookie',            'color' => 'danger'],
    ];

    public function index()
    {
        $pages = $this->pages;
        return view('superadmin.cms.index', compact('pages'));
    }

    public function edit(string $page)
    {
        if (!array_key_exists($page, $this->pages)) {
            abort(404);
        }

        $settings = CmsSetting::where('page', $page)->orderBy('sort_order')->get();
        $pageInfo = $this->pages[$page];

        return view('superadmin.cms.edit', compact('settings', 'page', 'pageInfo'));
    }

    public function update(Request $request, string $page)
    {
        if (!array_key_exists($page, $this->pages)) {
            abort(404);
        }

        $fields = $request->except(['_token', '_method']);

        foreach ($fields as $key => $value) {
            CmsSetting::where('page', $page)->where('key', $key)
                ->update(['value' => $value]);
        }

        // Clear all CMS cache for this page
        $settings = CmsSetting::where('page', $page)->get();
        foreach ($settings as $setting) {
            Cache::forget("cms_{$page}_{$setting->key}");
        }

        return redirect()->route('superadmin.cms.edit', $page)
            ->with('success', 'Content ya ukurasa wa ' . $this->pages[$page]['label'] . ' imehifadhiwa vizuri!');
    }
}
