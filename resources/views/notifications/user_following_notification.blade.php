<li class="notifications {{ $notification->read() ? '' : 'unread' }}">
    <a href="/notifications/{{ $notification->id }}?redirect_url=/notifications">您关注了{{ $notification->data['following_name'] }}</a>
</li>