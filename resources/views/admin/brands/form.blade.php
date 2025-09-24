<div class="row">
    <!-- Basic Information Column -->
    <div class="col-md-6">
        <div class="form-section mb-4">
            <h5 class="section-title text-primary mb-3">
                <i class="fas fa-info-circle mr-2"></i>Basic Information
            </h5>

            <div class="form-group">
                <label class="font-weight-bold">Brand Name *</label>
                <input name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $brand->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Slug</label>
                <input name="slug" class="form-control @error('slug') is-invalid @enderror"
                    value="{{ old('slug', $brand->slug) }}">
                @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Leave empty to auto-generate from name</small>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Domain *</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://</span>
                    </div>
                    <input name="domain" class="form-control @error('domain') is-invalid @enderror"
                        value="{{ old('domain', $brand->domain) }}" required>
                </div>
                @error('domain')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Website URL *</label>
                <div class="input-group">
                    <input name="website_url" class="form-control @error('website_url') is-invalid @enderror"
                        type="url" placeholder="https://example.com"
                        value="{{ old('website_url', $brand->website_url) }}" required>
                </div>
                @error('website_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Title</label>
                <input name="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $brand->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Slogan</label>
                <input name="slogan" class="form-control @error('slogan') is-invalid @enderror"
                    value="{{ old('slogan', $brand->slogan) }}">
                @error('slogan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Brand Assets Column -->
    <div class="col-md-6">
        <div class="form-section mb-4">
            <h5 class="section-title text-primary mb-3">
                <i class="fas fa-image mr-2"></i>Brand Assets
            </h5>

            @if ($action == 'create')
                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <div class="border rounded p-3 text-center position-relative" id="logo-dropzone">

                        <input type="file" name="logo_path" id="logo-input" accept="image/*" class="d-none">

                        {{-- Preview Image --}}
                        <img id="logo-preview" class="img-thumbnail mt-2 {{ $brand->logo_path ? '' : 'd-none' }}"
                            style="height: 60px;"
                            src="{{ $brand->logo_path ? env('STORAGE_PATH') . '/' . $brand->logo_path : '' }}" />

                        {{-- Dropzone Text & Upload Button --}}
                        <div id="dropzone-text" @if ($brand->logo_path) style="display:none;" @endif>
                            <p class="mb-2">Drag & drop or</p>
                            <button type="button" id="upload-button" class="btn btn-sm btn-outline-primary">Upload
                                Logo</button>
                        </div>

                        {{-- Remove Link --}}


                        <input type="hidden" name="remove_logo" id="remove-logo" value="0">
                    </div>
                </div>
            @else
                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <div class="border rounded p-3 text-center" id="logo-dropzone">
                    </div>
                </div>
            @endif

            @if ($action == 'create')
                <div class="mb-3">
                    <label class="form-label">Favicon</label>

                    <div class="border rounded p-3 text-center" id="favicon-dropzone" style="cursor: pointer;">

                        <input type="file" name="favicon_path" id="favicon-input" accept="image/*" class="d-none">
                        <img id="favicon-preview" class="img-thumbnail mt-2 d-none" style="height: 32px;" />
                        <div id="dropzone-text" @if ($brand->favicon_path) style="display:none;" @endif>
                            <p class="mb-2">Drag & drop or</p>
                            <button type="button" id="upload-button" class="btn btn-sm btn-outline-primary">Upload
                                Favicon</button>
                        </div>

                        @if ($brand->favicon_path)
                            <div class="mt-2">
                                <p class="text-muted mb-1">Current Favicon:</p>
                                <img src="{{ env('STORAGE_PATH') . '/' . $brand->favicon_path }}" class="img-thumbnail"
                                    style="height: 32px;">
                            </div>
                        @endif

                    </div>

                </div>
            @else
                <div class="mb-3">
                    <label class="form-label">Favicon</label>
                    <div class="border rounded p-3 text-center" id="favicon-dropzone">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <!-- Colors Column -->
    <div class="col-md-6">
        <div class="form-section mb-4">
            <h5 class="section-title text-primary mb-3">
                <i class="fas fa-palette mr-2"></i>Brand Colors
            </h5>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Primary Color</label>

                <input name="primary_color" class="w-100 @error('primary_color') is-invalid @enderror"
                    value="{{ old('primary_color', $brand->primary_color) }}" type="color">

                @error('primary_color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Secondary Color</label>

                <input name="secondary_color" class="w-100 @error('secondary_color') is-invalid @enderror"
                    value="{{ old('secondary_color', $brand->secondary_color) }}" type="color">


                @error('secondary_color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Settings Column -->
    <div class="col-md-6">
        <div class="form-section mb-4">
            <h5 class="section-title text-primary mb-3">
                <i class="fas fa-cog mr-2"></i>Settings
            </h5>

            <div class="form-group">
                <label class="font-weight-bold">Support Email</label>
                <input type="email" name="support_email"
                    class="form-control @error('support_email') is-invalid @enderror"
                    value="{{ old('support_email', $brand->support_email) }}">
                @error('support_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-4">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="isActiveSwitch" name="is_active"
                        value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold" for="isActiveSwitch">Brand is active</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-section mb-4">
    <h5 class="section-title text-primary">
        Footer Links
    </h5>
    <div class="form-group mb-4">
        <label class="font-weight-bold">Terms and Conditions</label>
        <input name="term_condition" class="form-control @error('term_condition') is-invalid @enderror"
            value="{{ old('term_condition', $brand->term_condition ?? '') }}">
        @error('term_condition')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="font-weight-bold">Privacy Policy</label>
        <input name="privacy_policy" class="form-control @error('privacy_policy') is-invalid @enderror"
            value="{{ old('privacy_policy', $brand->privacy_policy ?? '') }}">
        @error('privacy_policy')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group mb-4">
        <label class="font-weight-bold">Legal Notice</label>
        <input name="imprint" class="form-control @error('imprint') is-invalid @enderror"
            value="{{ old('imprint', $brand->imprint ?? '') }}">
        @error('imprint')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


</div>

<!-- Advanced Configuration -->
<div class="form-section mb-4">
    <h5 class="section-title text-primary mb-3">
        <i class="fas fa-code mr-2"></i>Advanced Configuration
    </h5>

    <div class="form-group">
        <label class="font-weight-bold">Configuration (JSON)</label>
        <textarea name="config" class="form-control @error('config') is-invalid @enderror" rows="5"
            placeholder='{"key": "value"}'>{{ old('config') }}</textarea>
        @error('config')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
