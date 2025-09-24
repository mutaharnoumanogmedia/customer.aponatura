@extends('layouts.customer.app')

@section('content')

    @php
        $user = Auth::guard('customer')->user();

        $avatar = $user->avatar ?? null;
        $avatarUrl = $avatar
            ? asset('storage/avatars/' . $avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff&size=128';

    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h4 class="mb-4 text-orange">{{ __('profile.edit_profile_title') }}</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') ?? __('profile.success_message') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <li>{{ __('profile.error_message') }}</li>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4">
                                <!-- Profile Picture -->
                                <div class="col-md-3 text-center">
                                    <div class="mb-3">
                                        <img id="avatarPreview" src="{{ $avatarUrl }}"
                                            class="rounded-circle border border-3"
                                            style="width: 120px; height: 120px; object-fit: cover;">
                                    </div>
                                    <label for="avatarUpload" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="bi bi-upload me-1"></i> {{ __('profile.upload_new_photo') }}
                                    </label>
                                    <input id="avatarUpload" type="file" name="avatar" class="d-none" accept="image/*"
                                        onchange="previewAvatar(event)">
                                    @error('avatar')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @else
                                        <small class="text-muted d-block mt-2">{{ __('profile.avatar_hint') }}</small>
                                    @enderror
                                </div>

                                <!-- Profile Fields -->
                                <div class="col-md-9">
                                    <h6 class="text-orange mb-3 border-bottom pb-1">{{ __('profile.personal_info') }}</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('profile.first_name') }}</label>
                                            <input type="text" name="first_name" class="form-control"
                                                value="{{ old('first_name', $profile->first_name ?? '') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('profile.last_name') }}</label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="{{ old('last_name', $profile->last_name ?? '') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('profile.email') }}</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}"
                                                disabled>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">{{ __('profile.bio') }}</label>
                                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $profile->bio ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <h6 class="text-orange mt-4 mb-3 border-bottom pb-1">
                                        {{ __('profile.social_links_section') }}</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('profile.twitter_url') }}</label>
                                            <input type="url" name="twitter" class="form-control"
                                                value="{{ old('twitter', $profile->twitter ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('profile.github_url') }}</label>
                                            <input type="url" name="instagram" class="form-control"
                                                value="{{ old('instagram', $profile->instagram ?? '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">{{ __('profile.linkedin_url') }}</label>
                                            <input type="url" name="linkedin" class="form-control"
                                                value="{{ old('linkedin', $profile->linkedin ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit"
                                            class="btn btn-primary px-4">{{ __('profile.update_profile') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-orange {
            color: #ff7300;
        }
    </style>

@endsection
