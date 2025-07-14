<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification to user
     */
    public function sendNotification($userId, $title, $message, $type = 'info', $actionUrl = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Send prescription completion notification
     */
    public function sendPrescriptionCompletionNotification($resep)
    {
        $user = $resep->user;
        $apotek = $resep->apotek;
        
        if (!$user) {
            return false;
        }

        $title = 'Resep Selesai - Siap Diambil';
        $message = "Resep Anda dengan nomor antrian {$resep->no_antrian} telah selesai diproses dan obat sudah siap untuk diambil di {$apotek->nama_apotek}.";
        $actionUrl = route('resep.show', $resep);

        return $this->sendNotification(
            $user->id,
            $title,
            $message,
            'success',
            $actionUrl
        );
    }

    /**
     * Send prescription processing notification
     */
    public function sendPrescriptionProcessingNotification($resep)
    {
        $user = $resep->user;
        $apotek = $resep->apotek;
        
        if (!$user) {
            return false;
        }

        $title = 'Resep Sedang Diproses';
        $message = "Resep Anda dengan nomor antrian {$resep->no_antrian} sedang diproses di {$apotek->nama_apotek}. Kami akan memberitahu Anda ketika selesai.";
        $actionUrl = route('resep.show', $resep);

        return $this->sendNotification(
            $user->id,
            $title,
            $message,
            'info',
            $actionUrl
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread notifications count for user
     */
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
} 