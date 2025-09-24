@extends('layouts.customer.app')

@section('content')
    @props([
        'action' => env('APP_LOGIN_URL') . '/api/profile/update',
        'fetchUrl' => env('APP_LOGIN_URL') . '/api/profile',
        'userPlaceholderAvatar' => env('ASSET_URL') . '/' . 'images/user-placeholder.webp',
    ])
    <div class="container">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <h2>
                        {{ __('Profile Settings') }}
                    </h2>
                    <div class="avatar-section">
                        <div class="avatar-container">
                            <button type="button" class="btn btn-sm btn-link text-white position-absolute  bg-danger"
                                id="removeAvatar"
                                style="font-size:1rem;top:15px; right: 15px; z-index:2; border-radius:50%; width:32px; height:32px; display:none;">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="avatar-preview" id="avatarPreview">
                                <img src="{{ $userPlaceholderAvatar }}" alt="user avatar placeholder"
                                    class="img-fluid rounded-circle">
                            </div>
                            <button type="button" class="avatar-upload-btn"
                                onclick="document.getElementById('avatar').click()">
                                <i class="bi bi-camera"></i>
                            </button>
                        </div>
                        <input type="file" class="d-none" name="avatar" id="avatar" accept="image/*">
                        <div class="invalid-feedback" id="error_avatar"></div>
                    </div>
                </div>

                <div class="form-section">
                    <form id="profileForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name" class="form-label">
                                        {{ __('First Name') }}
                                    </label>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        placeholder="{{ __('Enter your first name') }}">
                                    <div class="invalid-feedback" id="error_first_name"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">
                                        {{ __('Last Name') }}
                                    </label>
                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                        placeholder="{{ __('Enter your last name') }}">
                                    <div class="invalid-feedback" id="error_last_name"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="username" class="form-label">
                                    {{ __('Username') }}
                                </label>
                                <div id="username-container">
                                    <div type="text" class="form-control" name="username" id="username"
                                        placeholder="{{ __('Choose a unique username') }}">
                                    </div>
                                </div>
                                <i class="fas fa-at input-icon"></i>
                                <div class="invalid-feedback" id="error_username"></div>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="phone" class="form-label">
                                    {{ __('Phone') }}
                                </label>
                                <br>
                                <input type="text" class="form-control" name="phone" id="phone"
                                    placeholder="{{ __('Enter your phone') }}"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" />


                                <i class="fas fa-at input-icon"></i>
                                <div class="invalid-feedback" id="error_phone"></div>
                            </div>
                        </div>
                        {{-- 
                    'phone', 'address', 'country', 'city', 'postal_code'
                    --}}
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="address" class="form-label">
                                    {{ __('Address') }}
                                </label>
                                <input type="text" class="form-control" name="address" id="address"
                                    placeholder="{{ __('Enter your address') }}">
                                <div class="invalid-feedback" id="error_address"></div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="country" class="form-label">
                                    {{ __('Country') }}
                                </label>

                                <select class="form-control form-select" name="country" id="country">
                                    <option value="">{{ __('Select your country') }}</option>
                                    @foreach ($countries as $c)
                                        <option value="{{ $c->id }}">
                                            {{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error_country"></div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="city" class="form-label">
                                    {{ __('City') }}
                                </label>
                                <input type="text" class="form-control" name="city" id="city"
                                    placeholder="{{ __('Enter your city') }}">
                                <div class="invalid-feedback" id="error_city"></div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="postal_code" class="form-label">
                                    {{ __('Postal Code') }}
                                </label>
                                <input type="text" class="form-control" name="postal_code" id="postal_code"
                                    placeholder="{{ __('Enter your postal code') }}">
                                <div class="invalid-feedback" id="error_postal_code"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio" class="form-label">
                                {{ __('Bio') }}
                            </label>
                            <textarea class="form-control" name="bio" id="bio" rows="4"
                                placeholder="{{ __('Tell us about yourself') }}..."></textarea>
                            <div class="invalid-feedback" id="error_bio"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                {{ __('Social Links') }}
                            </label>
                            <div class="social-links">
                                <!-- Twitter -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                                            <path
                                                d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                                        </svg>
                                    </span>
                                    <input type="url" class="form-control" name="twitter" id="twitter"
                                        placeholder="https://twitter.com/username">
                                </div>

                                <!-- Instagram -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-instagram" style="color: #e1306c;"></i>
                                    </span>
                                    <input type="" class="form-control" name="instagram" id="instagram"
                                        placeholder="@username">
                                </div>

                                <!-- LinkedIn -->
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-linkedin" style="color: #0077b5;"></i>
                                    </span>
                                    <input type="url" class="form-control" name="linkedin" id="linkedin"
                                        placeholder="https://linkedin.com/in/username">
                                </div>
                            </div>

                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span id="submitText">
                                    {{ __('Update Profile') }}
                                </span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-success border-0" role="alert" id="successToast">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                {{ __('Profile updated successfully') }}!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    <div class="toast align-items-center text-white bg-danger border-0" role="alert" id="errorToast">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ __('Error updating profile') }}!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
@endsection



@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("profileForm");
            const avatar = document.getElementById("avatar");
            const avatarPreview = document.getElementById("avatarPreview");
            const submitBtn = document.getElementById("submitBtn");
            const loadingSpinner = document.getElementById("loadingSpinner");
            const submitText = document.getElementById("submitText");
            const toast = new bootstrap.Toast(document.getElementById("successToast"));

            var iti; // Declare iti globally for intl-tel-input

            const phoneInput = document.querySelector("#phone");
            const errorDiv = document.querySelector("#error_phone");
            // Initialize intl-tel-input

            fetch("{{ $fetchUrl }}?user_id={{ Auth::guard('customer')->user()->id }}", {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                        Token: "{{ env('APP_LOGIN_TOKEN') }}"
                    },

                })
                .then(res => res.json())
                .then(data => {
                    console.log('data ', data.user.profile.phone);
                    var profile = data.user.profile;

                    var phone = (profile && typeof profile.phone !== 'undefined' && profile.phone !== null) ?
                        profile.phone : '';

                    form.first_name.value = profile ? profile.first_name : '';
                    form.last_name.value = profile ? profile.last_name : '';
                    document.getElementById("username").innerHTML = profile ? profile.username : ``;
                    console.log(profile, 'profile.username');
                    if (!profile || !profile.username) {
                        document.getElementById("username-container").innerHTML =
                            `<input type="text" class="form-control" name="username" id="username" placeholder="{{ __('Choose a unique username') }}">`;
                    }

                    form.bio.value = profile ? profile.bio : '';
                    form.twitter.value = profile ? profile.twitter : '';
                    form.instagram.value = profile ? profile.instagram : '';
                    form.linkedin.value = profile ? profile.linkedin : '';
                    //'phone', 'address', 'country', 'city', 'postal_code'
                    form.phone.value = phone ? phone : '';

                    form.address.value = profile ? (profile.address ?? '') : '';
                    if (profile && profile.country) {
                        // Try to match by id or name
                        let countrySelect = form.country;
                        let found = false;
                        for (let i = 0; i < countrySelect.options.length; i++) {
                            if (
                                countrySelect.options[i].value == profile.country ||
                                countrySelect.options[i].text.trim().toLowerCase() == profile.country.toString()
                                .trim().toLowerCase()
                            ) {
                                countrySelect.selectedIndex = i;
                                found = true;
                                break;
                            }
                        }
                        if (!found) {
                            countrySelect.selectedIndex = 0;
                        }
                    } else {
                        form.country.selectedIndex = 0;
                    }
                    form.city.value = profile ? (profile.city ?? '') : '';
                    form.postal_code.value = profile ? (profile.postal_code ?? '') : '';
                    // Set avatar preview
                    if (profile && profile.avatar && (profile.avatar != null && profile.avatar != '')) {
                        avatarPreview.innerHTML =
                            `<img src="${profile.avatar}" alt="Avatar Preview" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">`;
                        document.getElementById("removeAvatar").style.display = "none";
                    } else {
                        avatarPreview.innerHTML =
                            ` <img src="{{ $userPlaceholderAvatar }}" alt="user avatar placeholder" class="img-fluid rounded-circle">`;
                    };


                    // Initialize intl-tel-input with the phone number
                    initializeTelInput(profile ? (profile.phone ?? '') : '');
                });

            avatar.addEventListener("change", () => {
                const file = avatar.files[0];
                if (file && file.size > 2 * 1024 * 1024) {
                    alert("Avatar must be less than 2MB.");
                    avatar.value = "";
                } else if (file) {
                    avatarPreview.src = URL.createObjectURL(file);
                }
            });

            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                //append user id to form data
                formData.append("user_id", "{{ Auth::guard('customer')->user()->id }}");
                //APPEND AVATAR FILE
                formData.append("avatar", avatar.files[0]);
                // phone with full_phone
                formData.append("phone", document.querySelector("input[name='full_phone']").value);

                console.log(document.querySelector("input[name='full_phone']").value, ' full_phone value');


                submitBtn.disabled = true;
                loadingSpinner.classList.remove("d-none");
                submitText.textContent = "Updating...";


                $.ajax({
                    url: "{{ $action }}",
                    type: "POST",
                    headers: {
                        Accept: "application/json",
                        Token: "{{ env('APP_LOGIN_TOKEN') }}"
                    },
                    data: formData,
                    processData: false, // tell jQuery not to process the data
                    contentType: false, // tell jQuery not to set contentType
                    success: function(data) {
                        // on success
                        if (data.message) {
                            toast.show();
                        }
                        if (data.avatar_url) {
                            avatarPreview.src = data.avatar_url;
                            //all .user-avatar img should be updated
                            document.querySelectorAll(".user-avatar").forEach(userAvatar => {
                                userAvatar.innerHTML =
                                    `<img src="${data.avatar_url}" alt="user avatar" class="img-fluid rounded-circle">`;
                            });
                        }
                    },
                    error: function(jqXHR) {
                        // on failure
                        let response = jqXHR.responseJSON || {};

                        let errors = response.errors || {};
                        let errorMsg = Object.values(errors).flat().join('<br>') || response
                            .message
                        document.getElementById("errorToast").querySelector(".toast-body")
                            .innerHTML =
                            `<i class="fas fa-exclamation-circle me-2"></i>${errorMsg}`;
                        document.getElementById("errorToast").classList.add("show");
                        document.getElementById("errorToast").style.display = "block";
                        setTimeout(() => {
                            document.getElementById("errorToast").style.display =
                                "none";
                        }, 5000);
                    },
                    complete: function() {
                        // always runs
                        submitBtn.disabled = false;
                        loadingSpinner.classList.add("d-none");
                        submitText.textContent = "Update Profile";
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const avatarInput = document.getElementById("avatar");
            const avatarPreview = document.getElementById("avatarPreview");
            const errorFeedback = document.getElementById("error_avatar");

            avatarInput.addEventListener("change", function() {
                const file = this.files[0];

                // Reset error and previous preview
                errorFeedback.textContent = "";
                avatarPreview.innerHTML = "";

                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        errorFeedback.textContent = "Avatar must be less than 2MB.";
                        this.value = ""; // Clear input
                        avatarPreview.innerHTML =
                            ` <img src="{{ $userPlaceholderAvatar }}" alt="user avatar placeholder" class="img-fluid rounded-circle">`;
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.alt = "Avatar Preview";
                        img.classList.add("img-fluid", "rounded-circle");
                        img.style.width = "100%";
                        img.style.height = "100%";
                        img.style.objectFit = "cover";

                        avatarPreview.innerHTML = "";
                        avatarPreview.appendChild(img);
                        document.getElementById("removeAvatar").style.display = "block";
                    }

                    reader.readAsDataURL(file);
                } else {
                    avatarPreview.innerHTML =
                        ` <img src="{{ $userPlaceholderAvatar }}" alt="user avatar placeholder" class="img-fluid rounded-circle">`;
                }
                //for removing of added image
                document.getElementById("removeAvatar").addEventListener("click", function() {
                    avatarInput.value = "";
                    avatarPreview.innerHTML =
                        ` <img src="{{ $userPlaceholderAvatar }}" alt="user avatar placeholder" class="img-fluid rounded-circle">`;
                    this.style.display = "none";
                });
            });
        });



        function initializeTelInput(phoneNumber) {
            const phoneInput = document.querySelector("#phone");
            iti = window.intlTelInput(document.querySelector("#phone"), {
                initialCountry: "auto",
                preferredCountries: ["de", "at", "ch", "us"],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.17/js/utils.js",
                hiddenInput: "full_phone",
                formatOnDisplay: true,
                nationalMode: false, // ← store full international number
                autoHideDialCode: false,
            });


            // Set the phone number if provided


            if (phoneNumber) {
                iti.setNumber(phoneNumber);
            }

            // Update hidden input with full phone number
            // grab the hidden field that intl-tel-input created (or your own)

            // helper to sync the hidden input with the current full number
            var hiddenInput = document.querySelectorAll('input[name="full_phone"]')[0];

            hiddenInput.value = phoneNumber; // Initialize hidden input with full number
            console.log("hidden input = ", hiddenInput, "given phone ", hiddenInput.value);

            function syncFullPhone() {
                // getNumber() returns an E.164 string like "+4915123456789"
                hiddenInput.value = iti.getNumber();
                console.log(hiddenInput.value, 'hiddenInput value');
            }

            // wire it up to fire on every keystroke and whenever the country changes
            phoneInput.addEventListener("input", syncFullPhone);
            phoneInput.addEventListener("countrychange", syncFullPhone);

            // (optional) initialize on page-load in case there's a default value
            // syncFullPhone();
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.17/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.17/js/intlTelInput.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
@endsection
