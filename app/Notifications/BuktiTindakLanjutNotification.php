<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuktiTindakLanjutNotification extends Notification
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
        // Periksa apakah pengguna memiliki role yang sesuai
        if ($notifiable->hasRole($this->tindakLanjut->tim_pemantauan)) {
            return [
                'tindak_lanjut_id' => $this->tindakLanjut->id,
                'title' => 'Bukti Tindak Lanjut',
                // 'message' => $this->tindakLanjut->upload_by . ' telah mengunggah bukti tindak lanjut ' . strip_tags(html_entity_decode($this->tindakLanjut->tindak_lanjut)) . '.',
                'message' => $this->tindakLanjut->upload_by . ' telah mengunggah bukti tindak lanjut.',
                'url' => '/identifikasi/' . $this->tindakLanjut->id,
            ];
        }

        // Jika tidak sesuai, kembalikan array kosong
        return [];
    }

}
