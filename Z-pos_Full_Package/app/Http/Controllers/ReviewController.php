<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class ReviewController extends Controller
{
    /**
     * Colors cycled for auto-generated avatars, matching the curated testimonial style.
     */
    private const AVATAR_COLORS = ['primary', 'success', 'dark', 'warning'];

    /**
     * Show the logged-in user's existing review (if any) so they can edit it, or a blank form.
     */
    public function index()
    {
        $review = Testimonial::where('user_id', auth()->id())->first();

        return view('reviews.index', compact('review'));
    }

    /**
     * Save (or update) the logged-in user's rating and review.
     * Only 5-star reviews with written feedback are shown publicly on the homepage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        $user = auth()->user();
        $shop = $user->shop;

        $roleLabel = $user->hasRole('Administrator') ? 'Owner' : 'Staff';
        $position = $shop ? "{$roleLabel}, {$shop->name}" : $roleLabel;

        $nameParts = preg_split('/\s+/', trim($user->name));
        $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr($nameParts[1] ?? '', 0, 1));

        Testimonial::updateOrCreate(
            ['user_id' => $user->id],
            [
                'shop_id' => $user->shop_id,
                'name' => $user->name,
                'position' => $position,
                'avatar_initials' => $initials ?: 'U',
                'avatar_color' => self::AVATAR_COLORS[$user->id % count(self::AVATAR_COLORS)],
                'quote' => $request->review,
                'rating' => $request->rating,
                'is_active' => $request->rating == 5 && trim($request->review) !== '',
                'sort_order' => 100,
            ]
        );

        return back()->with('success', 'Thank you for your feedback!');
    }
}
