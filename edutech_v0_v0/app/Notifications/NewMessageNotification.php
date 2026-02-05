<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $conversation;

    public function __construct($message, $conversation)
    {
        $this->message = $message;
        $this->conversation = $conversation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new message from ' . $this->message->sender->name,
            'link' => $notifiable->isTeacher()
                ? route('teacher.messages.show', $this->conversation->id)
                : route('parent.messages.show', $this->conversation->id),
            'sender_name' => $this->message->sender->name,
            'student_name' => $this->conversation->student->user->name,
            'preview' => substr($this->message->message, 0, 50) . '...',
        ];
    }
}
