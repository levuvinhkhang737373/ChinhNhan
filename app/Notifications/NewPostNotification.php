<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $news;

    public function __construct($news)
    {
        $this->news = $news;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $description = $this->news->newsDesc;
        return [
            'news_id' => $this->news->id,
            'title'   => $description->title ?? 'Thông báo mới',
            'message' => 'Có bài viết mới: ' . ($description->title ?? ''),
            'link'    => '/news/' . ($description->friendly_title ?? $this->news->id),
        ];
    }
}
