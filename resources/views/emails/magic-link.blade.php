@extends('emails.template')
@section('content')
    <div class="header">Hallo!</div>

    <p>
        Klick unten auf den Button, um dich in deinen Account einzuloggen. Der Link läuft bald ab.
    </p>

    <div class="text-center">
        <a href="{{ $url }}" class="button"
            style="background-color: #ff5f00; color: #fff; padding: 12px 32px; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: bold; font-size: 16px;">
            In deinen Account einloggen
        </a>
    </div>

    <p class="notice">Wenn du diesen Login-Link nicht angefordert hast, ignoriere diese E-Mail einfach.</p>

    <div class="footer">
        Danke,<br>
        dein {{ $brand->name ?? 'baaboo' }} Team
    </div>
    <hr>
    <div class="header">Hello!</div>

    <p>Click the button below to log in to your account. This link will expire in 30 minutes.</p>

    <div class="text-center">
        <a href="{{ $url }}" class="button"
            style="background-color: #ff5f00; color: #fff; padding: 12px 32px; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: bold; font-size: 16px;">Login
            to Your Account</a>
    </div>

    <p class="notice">If you did not request this login link, please ignore this email.</p>

    <div class="footer">
        Thanks,<br>
        {{ $brand->name ?? 'baaboo' }} Team
    </div>
@endsection
