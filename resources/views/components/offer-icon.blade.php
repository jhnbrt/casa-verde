@props(['name' => null, 'variant' => 'card', 'position' => 0])

@php
    // The hand-drawn icons of the Special Offers cards. Typing one of these names (or its
    // emoji) in the admin shows the drawing; any other icon name, emoji or uploaded image
    // is shown as is. An empty icon keeps the original drawing for the first three cards.
    $card = $variant === 'card';

    $drawn = [
        'island' => ['viewBox' => '0 0 80 70', 'body' => '<path d="M8 59h64M12 64h55M26 56l9-32M35 24c-13-7-20-3-25 5 12-3 18-2 25-5ZM35 24c-7-13-17-14-25-11 10 3 16 7 25 11ZM35 24c3-15 11-20 20-18-9 5-14 11-20 18ZM35 24c13-7 23-4 29 4-13-2-21-1-29-4ZM35 24c7 4 12 12 13 20-6-8-10-14-13-20ZM48 57l10-24M58 33c-10-7-17-5-22 0 10-1 16 0 22 0ZM58 33c-3-10-10-14-18-12 9 4 13 8 18 12ZM58 33c5-11 13-13 20-10-10 2-15 6-20 10ZM58 33c9-4 16-1 20 5-10-2-15-3-20-5Z"/>'],
        'lotus' => $card
            ? ['viewBox' => '0 0 80 70', 'body' => '<path d="M40 57C25 43 24 28 40 9c16 19 15 34 0 48ZM40 57C20 55 11 43 13 25c19 3 29 14 27 32ZM40 57c20-2 29-14 27-32-19 3-29 14-27 32ZM40 57C24 65 11 60 3 47c13-9 26-7 37 10ZM40 57c16 8 29 3 37-10-13-9-26-7-37 10Z"/>']
            : ['viewBox' => '0 0 40 40', 'body' => '<path d="M20 35C8 25 9 16 20 5c11 11 12 20 0 30ZM20 35C8 33 4 26 6 17c10 2 15 8 14 18ZM20 35c12-2 16-9 14-18-10 2-15 8-14 18Z"/>'],
        'heart' => $card
            ? ['viewBox' => '0 0 80 70', 'body' => '<path d="M40 62 11 32C-3 14 20-2 40 22 60-2 83 14 69 32Z" stroke-width="3"/>']
            : ['viewBox' => '0 0 40 40', 'body' => '<path d="M20 35 5 19C-3 8 12 1 20 13 28 1 43 8 35 19Z" stroke-width="2.5"/>'],
        'tag' => ['viewBox' => '0 0 40 40', 'body' => '<path d="M5 21 22 4h12v12L17 35a4 4 0 0 1-6 0l-6-6a5 5 0 0 1 0-8Z"/><circle cx="27" cy="12" r="3"/>'],
    ];

    // Emojis that stand for a drawing (the starting values of the offers).
    $alias = ['🏝️' => 'island', '🏝' => 'island', '🪷' => 'lotus', '♡' => 'heart', '♥' => 'heart', '❤' => 'heart', '❤️' => 'heart', '🏷️' => 'tag', '🏷' => 'tag'];

    $value = trim((string) $name);
    $key = $alias[$value] ?? strtolower($value);

    if ($value === '') {
        $key = $card ? ['island', 'lotus', 'heart'][$position] ?? null : ['tag', 'lotus', 'heart'][$position] ?? null;
    }
@endphp

@if($key !== null && isset($drawn[$key]))
    <svg viewBox="{{ $drawn[$key]['viewBox'] }}">{!! $drawn[$key]['body'] !!}</svg>
@elseif($value !== '')
    <x-icon :name="$value" :size="$card ? 65 : 35" />
@endif
