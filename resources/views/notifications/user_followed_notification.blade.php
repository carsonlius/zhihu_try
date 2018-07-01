@component('mail::message')
# Introduction

#The body of your message.
您被
@component('mail::button', ['url' => '#', 'color' => 'green'])
    {{--{{ $user_following }}--}}
@endcomponent
关注了。
<br>
{{ config('app.name') }}
@endcomponent
