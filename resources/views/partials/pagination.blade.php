@if ($data->hasPages())
    <div class="mt-3">
        {{ $data->links() }}
    </div>
@endif
