<li class="notifications {{ $notification->read() ? '' : 'unread' }}">
    <a href="/notifications/{{ $notification->id }}?redirect_url=/notifications">
        {{ $notification->data['name'] }} 关注了你
    </a>
</li>