<li class="notifications {{ $notification->read()? '' : 'unread' }}">
    <a href="/notifications/{{ $notification->id }}?redirect_url=/notifications">您取消了对
    @foreach($notification->data['list_user'] as $user)
            {{ $user['name'] }}
    @endforeach
        的关注
    </a>
</li>