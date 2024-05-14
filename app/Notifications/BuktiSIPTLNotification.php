<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuktiSIPTLNotification extends Notification
{
    use Queueable;
    protected $rekomendasi_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($rekomendasi_id)
    {
        $this->rekomendasi_id = $rekomendasi_id;
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
        return [
            'rekomendasi_id' => $this->rekomendasi_id,
            'title' => 'Tindak Lanjut Rekomendasi Selesai',
            'message' => 'Tindak Lanjut telah selesai dilakukan. Silahkan upload bukti SIPTL.',
            'url' => '/pemutakhiran-status/'. $this->rekomendasi_id,
        ];
    }
}
