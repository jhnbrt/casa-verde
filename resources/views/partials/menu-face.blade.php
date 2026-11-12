{{-- One face of the menu book: a picture (cover, picture pages) or a dish page built from the admin. --}}
@if(($face['type'] ?? 'image') === 'dishes')
    @include('partials.menu-page', ['page' => $face])
@else
    <img src="{{ asset($face['image']) }}" alt="{{ $face['alt'] }}" draggable="false" @if(!empty($lazy)) loading="lazy" @endif>
@endif
