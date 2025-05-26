<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailNotification extends Notification implements ShouldQueue
{

      /**
     * The name of the queue connection.
     *
     * @var string
     */
    public $connection = 'database';

    /**
     * The name of the queue.
     *
     * @var string
     */
    public $queue = '';

    /**
     * The delay before the notification is sent.
     *
     * @var \DateTime|int
     */
    public $delay = 0;

    /**
     * The data to be sent in the notification.
     *
     * @var string
     */
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];  // Send notification via email
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $appName = env('APP_NAME');

        return (new MailMessage)
                    ->subject("Сообщение от $appName")
                    ->view('emails.backup-notification', ['data' => $this->message]);
                    // ->line($this->message);
    }

    /**
     * Determine if the notification should be sent to the database.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
