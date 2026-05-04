@props([
    'modalId',
    'overlayId',
    'panelId',
    'title',
    'message',
    'closeButtonId' => null,
    'actionButtonId' => null,
    'actionLabel' => 'OK',
    'messageId' => null,
    'titleClass' => 'text-slate-900',
    'actionClass' => 'bg-slate-900 text-white hover:bg-slate-800',
    'maxWidth' => 'max-w-md',
    'zIndex' => 'z-[70]',
])

<x-modal.base
    :modal-id="$modalId"
    :overlay-id="$overlayId"
    :panel-id="$panelId"
    :close-button-id="$closeButtonId"
    :max-width="$maxWidth"
    :z-index="$zIndex"
>
    <x-slot:header>
        <h2 class="text-lg font-semibold {{ $titleClass }}">{{ $title }}</h2>
    </x-slot:header>

    <p @if ($messageId) id="{{ $messageId }}" @endif class="text-sm leading-6 text-slate-600">
        {{ $message }}
    </p>

    {{ $slot }}

    <div class="mt-6 flex justify-end">
        <button
            type="button"
            @if ($actionButtonId) id="{{ $actionButtonId }}" @endif
            class="rounded-2xl px-4 py-2 text-sm font-semibold transition duration-300 hover:cursor-pointer {{ $actionClass }}"
        >
            {{ $actionLabel }}
        </button>
    </div>
</x-modal.base>
