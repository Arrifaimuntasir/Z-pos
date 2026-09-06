<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();
        return view('superadmin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('superadmin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'position'        => 'nullable|string|max:150',
            'avatar_initials' => 'nullable|string|max:5',
            'avatar_color'    => 'required|in:primary,success,dark,warning,danger,info',
            'quote'           => 'required|string',
            'rating'          => 'required|integer|min:1|max:5',
            'sort_order'      => 'nullable|integer',
        ]);

        Testimonial::create([
            'name'            => $request->name,
            'position'        => $request->position,
            'avatar_initials' => $request->avatar_initials ?: strtoupper(substr($request->name, 0, 2)),
            'avatar_color'    => $request->avatar_color,
            'quote'           => $request->quote,
            'rating'          => $request->rating,
            'is_active'       => $request->has('is_active'),
            'sort_order'      => $request->sort_order ?? 0,
        ]);

        return redirect()->route('superadmin.testimonials.index')
            ->with('success', 'Testimonial mpya imeongezwa!');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('superadmin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'position'        => 'nullable|string|max:150',
            'avatar_initials' => 'nullable|string|max:5',
            'avatar_color'    => 'required|in:primary,success,dark,warning,danger,info',
            'quote'           => 'required|string',
            'rating'          => 'required|integer|min:1|max:5',
            'sort_order'      => 'nullable|integer',
        ]);

        $testimonial->update([
            'name'            => $request->name,
            'position'        => $request->position,
            'avatar_initials' => $request->avatar_initials ?: strtoupper(substr($request->name, 0, 2)),
            'avatar_color'    => $request->avatar_color,
            'quote'           => $request->quote,
            'rating'          => $request->rating,
            'is_active'       => $request->has('is_active'),
            'sort_order'      => $request->sort_order ?? 0,
        ]);

        return redirect()->route('superadmin.testimonials.index')
            ->with('success', 'Testimonial imesasishwa!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('superadmin.testimonials.index')
            ->with('success', 'Testimonial imefutwa!');
    }
}
