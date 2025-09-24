@extends('layouts.customer.app')
@section('content')
    <div class="container mt-4"></div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-card">
                <div class="profile-header">
                    <h2 class="profile-title">{{ __('Settings') }}</h2>
                </div>
                <div class=" form-section">
                    <form id="passwordResetForm" class="">
                        @csrf
                        <div class="mb-3" id="current_password_div">
                            <label for="current_password" class="form-label">
                                {{ __('Current Password') }}
                            </label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                {{ __('New Password') }}
                            </label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">
                                {{ __('Confirm New Password') }}
                            </label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update Password') }}
                        </button>
                    </form>
                    <div id="passwordResetMsg" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        //check with api that password currently exists 
        //ajax call to check current password

        $.ajax({
            url: "{{ env('APP_LOGIN_URL') }}/api/check-user-password-exists",
            type: "get",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                user_id: "{{ auth()->guard('customer')->user()->id }}"
            },
            headers: {
                'Accept': 'application/json',
                'Token': "{{ env('APP_LOGIN_TOKEN') }}"
            },
            success: function(response) {
                console.log(response.password_exists, 'password exists response');

                if (response.password_exists) {
                    document.getElementById('current_password_div').style.display = 'block';
                } else {
                    document.getElementById('current_password_div').style.display = 'none';
                }
            },
            error: function() {
                document.getElementById('current_password_div').style.display = 'none';
            }
        });




        document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = e.target;
            let formData = new FormData(form);
            //append user_id to formData
            formData.append('user_id', "{{ auth()->guard('customer')->user()->id }}");
            fetch("{{ env('APP_LOGIN_URL') }}/api/update-password", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Token': "{{ env('APP_LOGIN_TOKEN') }}"
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    let msgDiv = document.getElementById('passwordResetMsg');
                    if (data.success) {
                        msgDiv.innerHTML = '<div class="alert alert-success">{{__('password.success')}}</div>';
                        form.reset();
                    } else {
                        let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data
                            .message;
                        msgDiv.innerHTML = '<div class="alert alert-danger">' + errors + '</div>';
                    }
                })
                .catch(() => {
                    document.getElementById('passwordResetMsg').innerHTML =
                        '<div class="alert alert-danger">{{__('passowrd.error')}}.</div>';
                });
        });
    </script>
@endsection
