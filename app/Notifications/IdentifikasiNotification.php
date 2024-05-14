<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdentifikasiNotification extends Notification
{
    use Queueable;
    protected $tindakLanjut;

    /**
     * Create a new notification instance.
     */
    public function __construct($tindakLanjut)
    {
        $this->tindakLanjut = $tindakLanjut;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Periksa apakah pengguna memiliki unit kerja yang sesuai
        if ($notifiable->unit_kerja == $this->tindakLanjut->unit_kerja) {
            return [
                'tindak_lanjut_id' => $this->tindakLanjut->id,
                'title' => 'Hasil Identifikasi Bukti Tindak Lanjut',
                'message' => $this->tindakLanjut->status_tindak_lanjut_by . ' telah mengidentifikasi bukti tindak lanjut.',
                'url' => '/tindak-lanjut/' . $this->tindakLanjut->id,
            ];
        }

        // Jika tidak sesuai, kembalikan array kosong
        return [];
    }
}
