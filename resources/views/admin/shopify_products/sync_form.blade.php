@extends('layouts.admin.app')

@section('title', 'Sync Shopify Products')

@section('content')
    <div class="container mt-4">
        <h1>Sync Shopify Products</h1>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.shopify-products.sync') }}" method="POST" id="sync-form"> 
                    @csrf
                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <select class="form-select" id="brand" name="brand" required>
                            <option value="">-- Select Brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Start Sync</button>
                </form>
                <div id="sync-status-message" class="alert alert-info mt-3" style="display:none;">
                    {{ session('status') }}
                </div>
            </div>
        </div>

    </div>
    <!-- Preloader Overlay -->
    <div id="preloader-overlay"
        style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;background:rgba(255,255,255,0.7);align-items:center;justify-content:center;">
        <div>
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-2 text-center">Syncing, please wait...</div>
        </div>
    </div>
    <script>
        document.querySelector('#sync-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to sync products?')) {
                return;
            }

            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const preloader = document.getElementById('preloader-overlay');

            // Show preloader overlay
            preloader.style.display = 'flex';

            submitBtn.disabled = true;

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Sync completed.');
                    document.getElementById('sync-status-message').style.display = 'block';
                    document.getElementById('sync-status-message').innerText = data.message ||
                        'Sync completed successfully.';
                    //update class of div
                    document.getElementById('sync-status-message').className = 'alert alert-success mt-3';
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Optionally, you can refresh the page or update the UI
                        window.location.href = '{{ route('admin.shopify-products.index') }}';
                    }
                })
                .catch(() => {
                    alert('An error occurred during sync. Please try again.');
                    document.getElementById('sync-status-message').style.display = 'block';
                    document.getElementById('sync-status-message').innerText =
                        'An error occurred during sync. Please try again.';
                    document.getElementById('sync-status-message').className = 'alert alert-danger mt-3';
                    console.error('Sync error:', error);
                })
                .finally(() => {
                    preloader.style.display = 'none';
                    submitBtn.disabled = false;
                });
        });
    </script>

@endsection
