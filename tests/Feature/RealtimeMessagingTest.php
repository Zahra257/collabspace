<?php

use App\Events\MessageSent;
use App\Events\NotificationCreated;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('message event broadcasts immediately on the recipient private channel', function () {
    $message = new Message([
        'sender_id' => 7,
        'receiver_id' => 42,
        'message' => 'Hello in real time',
    ]);
    $message->created_at = now();
    $event = new MessageSent($message);
    $channels = $event->broadcastOn();
    $payload = $event->broadcastWith();

    $this->assertInstanceOf(ShouldBroadcastNow::class, $event);
    $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
    $this->assertSame('private-chat.42', $channels[0]->name);
    $this->assertSame('MessageSent', $event->broadcastAs());
    $this->assertSame(7, $payload['message']['sender_id']);
    $this->assertSame(42, $payload['message']['receiver_id']);
    $this->assertSame('Hello in real time', $payload['message']['message']);
});

test('notification event broadcasts immediately on the recipient private channel', function () {
    $user = User::factory()->create();
    $notification = Notification::create([
        'user_id' => $user->id,
        'title' => 'New message',
        'message' => 'Hello in real time',
        'type' => 'message',
    ]);
    $event = new NotificationCreated($notification);

    $this->assertInstanceOf(ShouldBroadcastNow::class, $event);
    $this->assertInstanceOf(PrivateChannel::class, $event->broadcastOn()[0]);
    $this->assertSame('private-users.'.$user->id, $event->broadcastOn()[0]->name);
    $this->assertSame('notification.created', $event->broadcastAs());
    $this->assertSame($notification->id, $event->broadcastWith()['id']);
});

test('message endpoints require authentication', function () {
    $this->getJson('/api/v1/messages')->assertUnauthorized();
});

test('private chat and notification channels authorize only their owner', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $channels = Broadcast::getChannels();
    $authorizeChat = $channels->get('chat.{userId}');
    $authorizeNotifications = $channels->get('users.{userId}');

    $this->assertIsCallable($authorizeChat);
    $this->assertIsCallable($authorizeNotifications);
    $this->assertTrue($authorizeChat($user, $user->id));
    $this->assertFalse($authorizeChat($user, $otherUser->id));
    $this->assertTrue($authorizeNotifications($user, $user->id));
    $this->assertFalse($authorizeNotifications($user, $otherUser->id));
});

test('users can only list messages they sent or received', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $thirdUser = User::factory()->create();

    \App\Models\Message::create([
        'sender_id' => $otherUser->id,
        'receiver_id' => $thirdUser->id,
        'message' => 'Private to other users',
    ]);

    $this->actingAs($user)
        ->getJson('/api/v1/messages')
        ->assertOk()
        ->assertJsonPath('data.data', []);
});

test('sending a message stores a notification and dispatches realtime events', function () {
    Event::fake([MessageSent::class, NotificationCreated::class]);

    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $response = $this->actingAs($sender)->postJson('/api/v1/messages', [
        'receiver_id' => $receiver->id,
        'message' => 'Hello in real time',
    ]);

    $response->assertCreated();

    $messageId = $response->json('data.id');
    $this->assertDatabaseHas('messages', [
        'id' => $messageId,
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'message' => 'Hello in real time',
    ]);
    $this->assertDatabaseHas('notifications', [
        'user_id' => $receiver->id,
        'title' => 'New message from '.$sender->name,
        'message' => 'Hello in real time',
        'type' => 'message',
        'is_read' => false,
    ]);

    Event::assertDispatched(MessageSent::class, fn (MessageSent $event) => $event->message->id === $messageId);
    Event::assertDispatched(NotificationCreated::class, fn (NotificationCreated $event) => $event->notification->user_id === $receiver->id);
});
