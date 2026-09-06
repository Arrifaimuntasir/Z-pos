<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name'             => 'Amina M.',
                'position'         => 'Hardware Store, Kariakoo',
                'avatar_initials'  => 'AM',
                'avatar_color'     => 'primary',
                'quote'            => "Since I started using Z-pos, I've been able to control theft in my shop. The system is very easy to understand even for my young staff.",
                'rating'           => 5,
                'is_active'        => true,
                'sort_order'       => 1,
            ],
            [
                'name'             => 'John K.',
                'position'         => 'Supermarket, Mbezi',
                'avatar_initials'  => 'JK',
                'avatar_color'     => 'success',
                'quote'            => "The offline mode is a lifesaver! When Tanesco cuts power and internet goes down, my cashiers can still print receipts without any issues.",
                'rating'           => 5,
                'is_active'        => true,
                'sort_order'       => 2,
            ],
            [
                'name'             => 'Sarah J.',
                'position'         => 'Pharmacy Chain, Arusha',
                'avatar_initials'  => 'SJ',
                'avatar_color'     => 'dark',
                'quote'            => "I can see real-time sales on my phone while travelling. It gives me peace of mind knowing exactly what's happening in all my 3 branches.",
                'rating'           => 5,
                'is_active'        => true,
                'sort_order'       => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(['name' => $t['name'], 'position' => $t['position']], $t);
        }
    }
}
