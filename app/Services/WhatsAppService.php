<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Placeholder WhatsApp gateway.
     *
     * Simulasi pengiriman WhatsApp. Ganti isi method ini dengan integrasi
     * riil (mis. Twilio / WA WA) saat siap production.
     *
     * @param  string  $phone  Nomor tujuan (dengan kode negara, contoh: 6281234567890)
     * @param  string  $message  Isi pesan
     * @return bool true jika terkirim, false jika gagal
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // TODO: Integrasikan gateway WhatsApp riil.
        // Saat ini mengembalikan true (simulasi) agaraksi tetap berjalan.
        return true;
    }
}
