<li>
    您不在关注了
    @foreach($notification->data['list_user'] as $user)
        <a href="#">{{ $user['name'] }}</a>
    @endforeach
</li>