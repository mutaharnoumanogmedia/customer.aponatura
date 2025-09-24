@extends('layouts.admin.app')
@section('title', 'Admin Profile')
@section('content')
    @props(['user' => auth()->user()])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-header-content">
                <h2><i class="bi bi-person-gear me-2"></i>Admin Profile Settings</h2>
                <p>Manage your account settings and preferences</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                        type="button" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="bi bi-person-circle"></i>
                        Update Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                        type="button" role="tab" aria-controls="password" aria-selected="false">
                        <i class="bi bi-shield-lock"></i>
                        Change Password
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabsContent">
                <!-- PROFILE TAB -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <!-- Success Alert -->
                    <div class="alert alert-success d-none" id="profileAlert">
                        <i class="bi bi-check-circle me-2"></i>Profile updated successfully!
                    </div>

                    <form id="profileForm" method="POST" action="#" enctype="multipart/form-data">
                        <!-- Avatar Section -->
                        <div class="avatar-section">
                            <img id="avatarPreview"
                                src="{{ $user->avatar_path ? env('STORAGE_URL') . '/' . $user->avatar_path : env('ASSET_URL') . '/' . 'images/user-placeholder.webp' }}"
                                alt="Avatar" class="avatar-preview">
                            <h5 class="mb-3">Profile Picture</h5>
                            <div class="file-input-wrapper">
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                                <label for="avatar" class="file-input-label">
                                    <i class="bi bi-camera"></i>
                                    Choose Photo
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">PNG, JPG up to 2MB</small>
                        </div>

                        <!-- Personal Information -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="adminName" name="name"
                                        value="{{ $user->name }}" required>
                                    <label for="adminName"><i class="bi bi-person me-2"></i>Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="adminEmail" readonly name="email"
                                        value="{{ $user->email }}" required>
                                    <label for="adminEmail"><i class="bi bi-envelope me-2"></i>Email Address</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="role" @readonly(true) name="role">
                                        <option value="super-admin" selected>Super Admin</option>
                                    </select>
                                    <label for="role"><i class="bi bi-shield-check me-2"></i>Role</label>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="timezone" name="timezone" class="form-select">
                                        <option value="UTC">UTC</option>
                                        <option value="Asia/Dubai" selected>Asia/Dubai</option>
                                        <option value="Berlin/Germany">Berlin/Germany</option>
                                    </select>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.getElementById('timezone').value = "{{ $user->timezone }}";
                                        });
                                    </script>


                                    <label for="timezone"><i class="bi bi-globe me-2"></i>Time Zone</label>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="bi bi-bell me-2"></i>Notification Preferences</h5>
                            <div class="notification-cards">
                                <div class="notification-card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="notif_system_emails" name="notifications[system_emails]" checked>
                                        <label class="form-check-label fw-bold" for="notif_system_emails">
                                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                            System Alerts
                                        </label>
                                    </div>
                                    <small class="text-muted">Critical system notifications and errors</small>
                                </div>

                                <div class="notification-card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="notif_security_emails" name="notifications[security_emails]" checked>
                                        <label class="form-check-label fw-bold" for="notif_security_emails">
                                            <i class="bi bi-shield-check text-success me-2"></i>
                                            Security Alerts
                                        </label>
                                    </div>
                                    <small class="text-muted">Login attempts and security events</small>
                                </div>

                                <div class="notification-card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="notif_order_updates" name="notifications[order_updates]">
                                        <label class="form-check-label fw-bold" for="notif_order_updates">
                                            <i class="bi bi-box text-primary me-2"></i>
                                            Activity Updates
                                        </label>
                                    </div>
                                    <small class="text-muted">Order status and activity notifications</small>
                                </div>

                                <div class="notification-card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="notif_marketing_emails" name="notifications[marketing_emails]">
                                        <label class="form-check-label fw-bold" for="notif_marketing_emails">
                                            <i class="bi bi-megaphone text-info me-2"></i>
                                            Marketing
                                        </label>
                                    </div>
                                    <small class="text-muted">Promotional emails and announcements</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-lg me-2"></i>Save Changes
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PASSWORD TAB -->
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <div class="alert alert-success d-none" id="passwordAlert">
                        <i class="bi bi-check-circle me-2"></i>Password updated successfully!
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <form id="passwordForm" method="POST" action="#">
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="currentPassword"
                                        name="current_password" required>
                                    <label for="currentPassword"><i class="bi bi-lock me-2"></i>Current Password</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="newPassword" name="new_password"
                                        required>
                                    <label for="newPassword"><i class="bi bi-key me-2"></i>New Password</label>
                                </div>




                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="new_password_confirmation" required>
                                    <label for="confirmPassword"><i class="bi bi-check2-square me-2"></i>Confirm New
                                        Password</label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="bi bi-shield-lock me-2"></i>Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Avatar preview
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let text = 'Weak';
            let color = '#ef4444';

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]/)) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;

            if (strength >= 75) {
                text = 'Strong';
                color = '#10b981';
            } else if (strength >= 50) {
                text = 'Medium';
                color = '#f59e0b';
            } else if (strength >= 25) {
                text = 'Fair';
                color = '#f97316';
            }

            strengthFill.style.width = strength + '%';
            strengthFill.style.background = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });

        // Form submissions with success alerts
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('profileAlert').classList.remove('d-none');
            setTimeout(() => {
                document.getElementById('profileAlert').classList.add('d-none');
            }, 3000);
        });

        // document.getElementById('passwordForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     document.getElementById('passwordAlert').classList.remove('d-none');
        //     setTimeout(() => {
        //         document.getElementById('passwordAlert').classList.add('d-none');
        //     }, 3000);
        // });

        (function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Helpers
            const showAlert = (el, msg, type = 'success') => {
                el.classList.remove('d-none', 'alert-success', 'alert-danger');
                el.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
                el.innerHTML =
                    `<i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>${msg}`;
            };

            const toErrorList = (errors) => {
                return Object.entries(errors).map(([field, msgs]) =>
                    `• ${field}: ${Array.isArray(msgs) ? msgs.join(', ') : msgs}`).join('<br>');
            };

            // Avatar live preview
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', (e) => {
                    const file = e.target.files?.[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = () => {
                            avatarPreview.src = reader.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // PROFILE FORM
            const profileForm = document.getElementById('profileForm');
            const profileAlert = document.getElementById('profileAlert');

            if (profileForm) {
                // Reset button (clear changes + hide alert)
                const resetBtn = profileForm.querySelector('button.btn-outline-secondary');
                if (resetBtn) {
                    resetBtn.addEventListener('click', () => {
                        profileForm.reset();
                        if (profileAlert) profileAlert.classList.add('d-none');
                        // Optionally reset avatar preview to original src
                        // avatarPreview.src = "{{ env('ASSET_URL') . '/' . 'images/user-placeholder.webp' }}";
                    });
                }

                profileForm.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const formData = new FormData(profileForm);
                    // Ensure unchecked checkboxes are represented as 0
                    const notifKeys = ['system_emails', 'security_emails', 'order_updates',
                        'marketing_emails'
                    ];
                    notifKeys.forEach(k => {
                        if (!formData.has(`notifications[${k}]`)) {
                            formData.append(`notifications[${k}]`, '0');
                        }
                    });

                    try {
                        const res = await fetch(`{{ route('admin.profile.update') }}`, {
                            method: 'POST', // Laravel will detect method override via _method if you add it, but let's send PUT directly
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: (() => {
                                // Laravel expects PUT; FormData can't set method header safely—add _method=PUT
                                formData.append('_method', 'PUT');
                                return formData;
                            })(),
                        });

                        if (res.ok) {
                            const data = await res.json();
                            showAlert(profileAlert, data.message || 'Profile updated successfully!',
                                'success');
                            if (data.user?.avatar_url && avatarPreview) {
                                avatarPreview.src = data.user.avatar_url;
                            }
                        } else if (res.status === 422) {
                            const err = await res.json();
                            showAlert(profileAlert, toErrorList(err.errors || {
                                'error': 'Validation failed'
                            }), 'danger');
                        } else {
                            const text = await res.text();
                            showAlert(profileAlert, 'Something went wrong. ' + text, 'danger');
                        }
                    } catch (err) {
                        showAlert(profileAlert, 'Network error: ' + err.message, 'danger');
                    }
                });
            }

            // PASSWORD FORM
            const passwordForm = document.getElementById('passwordForm');
            const passwordAlert = document.getElementById('passwordAlert');

            if (passwordForm) {
                passwordForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const payload = new FormData(passwordForm);
                    // payload.append('_method', 'PUT');

                    try {
                        const res = await fetch(`{{ route('admin.profile.password') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            body: payload
                        });

                        if (res.ok) {
                            const data = await res.json();
                            showAlert(passwordAlert, data.message || 'Password updated successfully!',
                                'success');
                            passwordForm.reset();
                        } else if (res.status === 422) {
                            const err = await res.json();
                            showAlert(passwordAlert, toErrorList(err.errors || {
                                'error': 'Validation failed'
                            }), 'danger');
                        } else {
                            const text = await res.text();
                            showAlert(passwordAlert, 'Something went wrong. ' + text, 'danger');
                        }
                    } catch (err) {
                        showAlert(passwordAlert, 'Network error: ' + err.message, 'danger');
                    }
                });
            }
        })();
    </script>

@endsection

@section('style')
    <style>
        .profile-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px 20px 0 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            color: #e2e8f0 !important
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff5f00' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .profile-header-content {
            position: relative;
            z-index: 1;
        }

        .profile-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff !important;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .profile-header p {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 0;
        }


        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            margin: 0;
            border-radius: 0;
            padding: 0 2rem;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            padding: 1.5rem 2rem;
            font-weight: 600;
            color: #ff5f00;
            background: transparent;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-tabs .nav-link:hover {
            border: none;
            color: #ff5f00;
            background: rgba(99, 102, 241, 0.05);
        }

        .nav-tabs .nav-link.active {
            color: #ff5f00;
            background: rgba(99, 102, 241, 0.05);
            border: none;
            border-bottom: 3px solid #ff5f00;
        }

        .nav-tabs .nav-link i {
            font-size: 1.2rem;
        }

        .tab-content {
            padding: 3rem;
            background: #fff;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating>.form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .form-floating>.form-control:focus {
            border-color: #ff5f00;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-floating>.form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .form-floating>.form-select:focus {
            border-color: #ff5f00;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-floating>label {
            color: #64748b;
            font-weight: 500;
        }

        .avatar-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px dashed #cbd5e1;
            text-align: center;
            transition: all 0.3s ease;
        }

        .avatar-section:hover {
            border-color: #ff5f00;
            background: linear-gradient(135deg, #d9770610 0%, #d9770610 100%);
        }

        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .avatar-preview:hover {
            transform: scale(1.05);
        }

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            margin-top: 1rem;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #ff5f00 0%, #d97706 100%);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .file-input-label:hover {
            transform: translateY(-2px);
        }

        .notification-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .notification-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .notification-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #ff5f00 0%, #d97706 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .notification-card:hover {
            border-color: #ff5f00;
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        }

        .notification-card:hover::before {
            opacity: 1;
        }

        .form-check-input:checked {
            background-color: #ff5f00;
            border-color: #ff5f00;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff5f00 0%, #d97706 100%);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }



        @media (max-width: 768px) {
            .container {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .profile-header {
                padding: 1.5rem;
            }

            .profile-header h2 {
                font-size: 2rem;
            }

            .tab-content {
                padding: 1.5rem;
            }

            .nav-tabs .nav-link {
                padding: 1rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection
