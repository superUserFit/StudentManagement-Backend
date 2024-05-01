@component('mail::message')

<html>
<link rel="stylesheet" href="{{ asset('/resources/css/app.css') }}">
    <header class="email-header">
        <strong><h1>Hello {{ $user->username }}. Welcome to MyLaravelApp</h1></strong>
    </header>
    <main class="email-body">
        <p>This is your One-Time-Password. Do not share this code to others : </p><br>
        <h1>{{ $content['code'] }}</h1>
    </main>
</html>

@endcomponent