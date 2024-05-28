<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RekomendasiNotification extends Notification
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
        // Pastikan notifiable memiliki role yang sesuai atau unit kerja yang cocok dengan tindak lanjut dari rekomendasi
        $tindakLanjut = $this->tindakLanjut;
        if ($notifiable->hasRole($tindakLanjut->tim_pemantauan)) {
            return [
                'tindak_lanjut_id' => $tindakLanjut->id,
                'rekomentasi_id' => $tindakLanjut->rekomendasi_id,
                'title' => 'Tugas Identifikasi',
                'message' => 'Anda memiliki tugas identifikasi baru.',
                'url' => '/identifikasi/' . $tindakLanjut->id,
            ];
        } elseif ($notifiable->unit_kerja === $tindakLanjut->unit_kerja) {
            return [
                'tindak_lanjut_id' => $tindakLanjut->id,
                'rekomentasi_id' => $tindakLanjut->rekomendasi_id,
                'title' => 'Tugas Tindak Lanjut',
                'message' => 'Anda memiliki tugas tindak lanjut baru.',
                'url' => '/tindak-lanjut/' . $tindakLanjut->id,
            ];
        }

        return [];
    }

}
