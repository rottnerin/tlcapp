@props([
    'type' => 'schedule_item',
    'id' => null,
    'isSelected' => false,
    'size' => 'default', // 'default', 'small', 'large'
])

@php
    $sizeClasses = match($size) {
        'small' => 'px-2 py-1 text-xs gap-1',
        'large' => 'px-4 py-2 text-sm gap-2',
        default => 'px-3 py-1.5 text-xs gap-1.5',
    };
@endphp

<button 
    type="button"
    class="add-to-my-pl-btn inline-flex items-center rounded-full font-semibold transition-all duration-200 {{ $sizeClasses }} {{ $isSelected ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-gray-900 hover:bg-amber-500' }}"
    data-type="{{ $type }}"
    data-id="{{ $id }}"
    onclick="toggleMyPL('{{ $type }}', {{ $id }}, this)"
>
    <i class="fas {{ $isSelected ? 'fa-check' : 'fa-plus' }}"></i>
    <span>{{ $isSelected ? 'Added' : 'Add to My PL' }}</span>
</button>

@once
@push('scripts')
<script>
function toggleMyPL(type, id, button) {
    fetch('{{ route('my-pl.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            selectable_type: type,
            selectable_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'added') {
            button.classList.remove('bg-amber-400', 'text-gray-900', 'hover:bg-amber-500');
            button.classList.add('bg-emerald-500', 'text-white');
            button.innerHTML = '<i class="fas fa-check"></i><span>Added</span>';
        } else if (data.status === 'removed') {
            button.classList.remove('bg-emerald-500', 'text-white');
            button.classList.add('bg-amber-400', 'text-gray-900', 'hover:bg-amber-500');
            button.innerHTML = '<i class="fas fa-plus"></i><span>Add to My PL</span>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
@endonce
