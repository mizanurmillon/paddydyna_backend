<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-channel.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});

Broadcast::channel('react-channel.{messageId}', function ($user, $messageId) {
    return (int) $messageId;
});

Broadcast::channel('unread-message-channel.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId;
});