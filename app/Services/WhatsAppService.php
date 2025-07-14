<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiToken;
    private $baseUrl;

    public function __construct()
    {
        $this->apiToken = '3uUWfjKE8GKCDBqGNozVQGzWrkxikDnXsjh4';
        $this->baseUrl = 'https://api.fonnte.com/send';
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Format phone number (remove +62 and add 62)
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])->post($this->baseUrl, [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $formattedPhone,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone' => $formattedPhone,
                    'response' => $response->json(),
                    'status' => $response->status()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send prescription completion notification
     */
    public function sendPrescriptionCompletionNotification($resep)
    {
        $user = $resep->user;
        $apotek = $resep->apotek;
        
        if (!$user || !$user->no_hp) {
            Log::warning('User or phone number not found for prescription notification', [
                'resep_id' => $resep->id,
                'user_id' => $resep->user_id
            ]);
            return false;
        }

        $message = $this->generatePrescriptionCompletionMessage($resep, $apotek);
        
        return $this->sendMessage($user->no_hp, $message);
    }

    /**
     * Generate prescription completion message
     */
    private function generatePrescriptionCompletionMessage($resep, $apotek)
    {
        $apotekName = $apotek ? $apotek->nama_apotek : 'Apotek';
        $apotekPhone = $apotek ? $apotek->whatsapp : '';
        
        $message = "🏥 *NOTIFIKASI RESEP SELESAI*\n\n";
        $message .= "Halo {$resep->nama_pasien},\n\n";
        $message .= "Resep Anda telah selesai diproses dan obat sudah siap untuk diambil.\n\n";
        $message .= "📋 *Detail Resep:*\n";
        $message .= "• Nomor Resep: #{$resep->id}\n";
        $message .= "• Tanggal Pengajuan: " . date('d/m/Y', strtotime($resep->tgl_pengajuan)) . "\n";
        $message .= "• Apotek: {$apotekName}\n";
        $message .= "• Status: Selesai\n\n";
        
        if ($apotekPhone) {
            $message .= "📞 *Kontak Apotek:*\n";
            $message .= "• WhatsApp: {$apotekPhone}\n\n";
        }
        
        $message .= "📍 *Lokasi Pengambilan:*\n";
        if ($apotek) {
            $message .= "{$apotek->alamat}\n\n";
        }
        
        $message .= "⏰ *Jam Operasional:*\n";
        $message .= "Senin - Sabtu: 08:00 - 21:00\n";
        $message .= "Minggu: 09:00 - 18:00\n\n";
        
        $message .= "💡 *Catatan:*\n";
        $message .= "• Silakan bawa bukti identitas saat mengambil obat\n";
        $message .= "• Obat akan disimpan maksimal 3 hari\n";
        $message .= "• Untuk informasi lebih lanjut, silakan hubungi apotek\n\n";
        
        $message .= "Terima kasih telah menggunakan layanan kami! 🙏";

        return $message;
    }

    /**
     * Format phone number for Fonnte API
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If starts with 0, replace with 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // If starts with +62, remove the +
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        }
        
        // If doesn't start with 62, add it
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
            ])->get('https://api.fonnte.com/device');

            if ($response->successful()) {
                Log::info('WhatsApp connection test successful', [
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('WhatsApp connection test failed', [
                    'response' => $response->json(),
                    'status' => $response->status()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp connection test error', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
} 