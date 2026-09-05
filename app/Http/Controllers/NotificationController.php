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

        // Mark unread as read on this page
        foreach ($notifications as $notification) {
            if ($notification->unread()) {
                $notification->markAsRead();
            }
        }

        return view('notifications.index', compact('notifications', 'filter'));
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
