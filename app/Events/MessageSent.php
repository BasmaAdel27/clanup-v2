<?php

namespace App\Events;


use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     *
     * @var User
     */

    /**
     * Message details
     *
     * @var Message
     */
    public $message;
    public $user;
    public $group;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message,User $user)
    {
        $this->message = $message;
        $this->user = $user;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('groups.'.$this->message->group->id);
    }




}
