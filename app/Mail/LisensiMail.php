<?php

namespace App\Mail;

use App\Models\Akun;
use App\Models\Lisensi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LisensiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lisensi $lisensi,
        public Akun $akun,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'License Key POS Anda — ' . $this->lisensi->paket->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lisensi',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
