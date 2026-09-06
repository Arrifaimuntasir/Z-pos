<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = $user->notifications();

        // Apply filters
        $filter = $request->query('filter', 'all');
        
        if ($filter === 'today') {
            $query->where('created_at', '>=', \Carbon\Carbon::now()->subHours(24));
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $notifications = $query->latest()->paginate(20)->appends(['filter' => $filter]);

        // Mark ALL unread as read in one query (more efficient)
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()
            ->view('notifications.index', compact('notifications', 'filter'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (empty($ids)) {
            return back()->with('error', 'Please select at least one notification to delete.');
        }

        auth()->user()->notifications()->whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected notifications deleted successfully.');
    }
}
