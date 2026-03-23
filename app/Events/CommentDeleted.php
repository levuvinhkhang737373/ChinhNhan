<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;
    public $comment;
    public $news_id;
    public function __construct($comment, $news_id)
    {
        $this->comment = $comment;
        $this->news_id = $news_id;
    }
    public function broadcastOn()
    {
        return new Channel('comments.' . $this->news_id);
    }
    public function broadcastAs()
    {
        return 'comment.delete';
    }
}
