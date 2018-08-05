<li class="notifications {{ $notification->read() ? '' : 'unread' }}">
    <a href="{{$notification->unread() ? '/notifications/'. $notification->id .'?redirect_url=/message/' .$notification->data['from_user_id'] : '/message/'. $notification->data['from_user_id'] }}'\">
        {{ $notification->data['from_user_name'] }} 给你发了一条私信
    </a>
</li>