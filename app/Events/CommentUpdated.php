<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Laravel\Reverb\Protocols\Pusher\Channels\PrivateCacheChannel;

class CommentUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $comment;


    public function __construct($comment)
    {
        $this->comment=$comment;
    }

    public function broadcastOn()
    {
        return new Channel('comments.' . $this->comment->news_id);
    }
    public function broadcastAs()
    {

        return 'comment.update';
    }
}
