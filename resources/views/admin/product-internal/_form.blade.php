@php
    // for old-values convenience
    $p = $productInternal ?? new \App\Models\ProductInternal();
@endphp

<div class="row g-3">
    {{-- SKU --}}
    <div class="col-md-6">
        <label class="form-label" for="sku">SKU</label>
        <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku"
            value="{{ old('sku', $p->sku) }}">
        @error('sku')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Internal Code --}}
    <div class="col-md-6">
        <label class="form-label" for="internal_code">Internal Code</label>
        <input type="text" class="form-control @error('internal_code') is-invalid @enderror" id="internal_code"
            name="internal_code" value="{{ old('internal_code', $p->internal_code) }}">
        @error('internal_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Stock Qty --}}
    <div class="col-md-4">
        <label class="form-label" for="stock_quantity">Stock Qty</label>
        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity"
            name="stock_quantity" value="{{ old('stock_quantity', $p->stock_quantity) }}">
        @error('stock_quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Min Threshold --}}
    <div class="col-md-4">
        <label class="form-label" for="min_stock_threshold">Min Threshold</label>
        <input type="number" class="form-control @error('min_stock_threshold') is-invalid @enderror"
            id="min_stock_threshold" name="min_stock_threshold"
            value="{{ old('min_stock_threshold', $p->min_stock_threshold) }}">
        @error('min_stock_threshold')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Max Threshold --}}
    <div class="col-md-4">
        <label class="form-label" for="max_stock_threshold">Max Threshold</label>
        <input type="number" class="form-control @error('max_stock_threshold') is-invalid @enderror"
            id="max_stock_threshold" name="max_stock_threshold"
            value="{{ old('max_stock_threshold', $p->max_stock_threshold) }}">
        @error('max_stock_threshold')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Pricing --}}
    {{-- in your Blade form (e.g. resources/views/product_internals/_form.blade.php) --}}
    <div class="row g-3">
        {{-- Cost Price --}}
        <div class="col-md-4">
            <label class="form-label" for="price">Cost Price</label>
            <input type="text" class="form-control euro-mask @error('price') is-invalid @enderror" id="price"
                name="price" value="{{ old('price', $p->price) }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Wholesale Price --}}
        <div class="col-md-4">
            <label class="form-label" for="wholesale_price">Wholesale Price</label>
            <input type="text" class="form-control euro-mask @error('wholesale_price') is-invalid @enderror"
                id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price', $p->wholesale_price) }}">
            @error('wholesale_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Retail Price --}}
        <div class="col-md-4">
            <label class="form-label" for="retail_price">Retail Price</label>
            <input type="text" class="form-control euro-mask @error('retail_price') is-invalid @enderror"
                id="retail_price" name="retail_price" value="{{ old('retail_price', $p->retail_price) }}">
            @error('retail_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


    {{-- Type & Status --}}
    <div class="col-md-6">
        <label class="form-label" for="product_type">Type</label>
        <select name="product_type" id="product_type" class="form-select @error('product_type') is-invalid @enderror">
            @foreach (['physical' => 'Physical', 'digital' => 'Digital', 'service' => 'Service'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('product_type', $p->product_type) === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('product_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="status">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            @foreach (['in_stock' => 'In Stock', 'out_of_stock' => 'Out of Stock', 'archived' => 'Archived'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', $p->status) === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- File Uploads --}}
    <div class="col-md-6">
        <label class="form-label" for="image_path">Product Image</label>
        <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path"
            name="image_path">
        @error('image_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($p->image_path)
            <img src="{{ env('STORAGE_URL') . '/' . $p->image_path }}" class="img-fluid h-auto" alt="">
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label" for="file_path">Digital File</label>
        <input type="file" class="form-control @error('file_path') is-invalid @enderror" id="file_path"
            name="file_path">
        @error('file_path')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($p->download_url)
            <small class="text-muted">Current: <a href="{{ $p->download_url }}" target="_blank">Download</a></small>
        @endif
    </div>

    {{-- Submit --}}
    <div class="col-12 text-end mt-3">
        <a href="{{ route('admin.product-internal.index') }}" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const euroOpts = {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 2,

            // currencySymbolPlacement: 's', // suffix
            unformatOnSubmit: true // so your form posts raw number
        };

        // initialize on all inputs with class .euro-mask
        document.querySelectorAll('.euro-mask').forEach(el => {
            new AutoNumeric(el, euroOpts);
        });
    });
</script>
