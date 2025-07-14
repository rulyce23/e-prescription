<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notificationService = new NotificationService();
        $success = $notificationService->markAsRead($id, Auth::id());

        if (request()->ajax()) {
            return response()->json(['success' => $success]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai telah dibaca.');
    }

    public function markAllAsRead()
    {
        $notificationService = new NotificationService();
        $notificationService->markAllAsRead(Auth::id());

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai telah dibaca.');
    }

    public function getUnreadCount()
    {
        $notificationService = new NotificationService();
        $count = $notificationService->getUnreadCount(Auth::id());

        return response()->json(['count' => $count]);
    }
} 