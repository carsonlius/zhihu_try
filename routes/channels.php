<?php

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message-to-created.{user_id}', function($user, $user_id){
    return (int)$user_id === (int) $user->id;
});

Broadcast::channel('message-to-counter.{user_id}', function($user, $user_id){
    return (int)$user_id === (int) $user->id;
});
