@php
    $skip = request()->get('skip', 0);
    $perPage = request()->get('perPage', 500);
    $total = $total ?? 0;

    $prevSkip = max(0, $skip - $perPage);
    $nextSkip = $skip + $perPage;
@endphp
<div class="w-100 py-4">
    <div class="pagination float-end">
        {{-- Previous Button --}}
        @if ($skip > 0)
            <a href="{{ request()->fullUrlWithQuery(['skip' => $prevSkip, 'perPage' => $perPage]) }}"
                class="btn btn-primary btn-sm">
                Previous
            </a>
        @else
            <a href="javascript:void(0)" class="btn btn-primary btn-sm">
                Previous
            </a>
        @endif

        {{-- Page Info --}}
        <span class="mx-2">
            Loaded {{ $total > 0 ? $skip + 1 : 0 }} to {{ min($skip + $perPage, $total) }} of
            {{ format_compact_number($total) }}
        </span>

        {{-- Next Button --}}
        @if ($nextSkip < $total)
            <a href="{{ request()->fullUrlWithQuery(['skip' => $nextSkip, 'limit' => $perPage]) }}"
                class="btn btn-primary btn-sm">
                Next
            </a>
        @else
            <button class="btn btn-secondary btn-sm disabled">Next</button>
        @endif
    </div>
</div>
