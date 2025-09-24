@extends('layouts.guest')
@section('content')
    <style>
        .page-container {
            background-color: #ffffff;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 60vh;
            text-align: center;
            padding: 20px
        }

        .brand-logo {
            width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .offline-title {
            font-size: 2rem;
            font-weight: bold;
            color: #ff5f00;
        }

        .offline-subtitle {
            font-size: 1.2rem;
            margin-top: 0.5rem;
            color: #666;
        }

        .btn-refresh {
            margin-top: 2rem;
            background-color: #ff5f00;
            color: white;
            border: none;
        }
    </style>
    <div class="page-container">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="brand-logo">

        <div class="offline-title">You Are Offline</div>
        <div class="offline-subtitle">Please check your internet connection and try again.</div>

        <button class="btn btn-refresh" onclick="window.location.reload();">Try Again</button>
    </div>
@endsection
