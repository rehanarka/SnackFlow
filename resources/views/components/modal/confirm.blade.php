@props([
    'modalId',
    'overlayId',
    'panelId',
    'title',
    'message',
    'messageId' => null,
    'closeButtonId' => null,
    'cancelButtonId' => null,
    'cancelLabel' => 'Batal',
    'submitLabel' => 'Simpan',
    'submitClass' => 'bg-slate-900 text-white shadow-lg shadow-slate-200 hover:bg-slate-800',
    'cancelClass' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
    'formId' => null,
    'formAction' => null,
    'method' => 'POST',
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
        <h2 class="text-lg font-semibold text-slate-900">{{ $title }}</h2>
    </x-slot:header>

    @if ($formAction)
        <form
            @if ($formId) id="{{ $formId }}" @endif
            action="{{ $formAction }}"
            method="POST"
            class="space-y-4"
        >
            @csrf
            @if (!in_array(strtoupper($method), ['GET', 'POST'], true))
                @method($method)
            @endif

            <p @if ($messageId) id="{{ $messageId }}" @endif class="text-sm leading-6 text-slate-600">
                {{ $message }}
            </p>

            {{ $slot }}

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    @if ($cancelButtonId) id="{{ $cancelButtonId }}" @endif
                    class="rounded-2xl px-4 py-2 text-sm font-semibold transition duration-300 hover:cursor-pointer {{ $cancelClass }}"
                >
                    {{ $cancelLabel }}
                </button>
                <button
                    type="submit"
                    class="rounded-2xl px-4 py-2 text-sm font-semibold transition duration-300 hover:-translate-y-0.5 hover:cursor-pointer {{ $submitClass }}"
                >
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    @else
        <div class="space-y-4">
            <p @if ($messageId) id="{{ $messageId }}" @endif class="text-sm leading-6 text-slate-600">
                {{ $message }}
            </p>

            {{ $slot }}

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    @if ($cancelButtonId) id="{{ $cancelButtonId }}" @endif
                    class="rounded-2xl px-4 py-2 text-sm font-semibold transition duration-300 hover:cursor-pointer {{ $cancelClass }}"
                >
                    {{ $cancelLabel }}
                </button>
                <button
                    type="button"
                    class="rounded-2xl px-4 py-2 text-sm font-semibold transition duration-300 hover:-translate-y-0.5 hover:cursor-pointer {{ $submitClass }}"
                >
                    {{ $submitLabel }}
                </button>
            </div>
        </div>
    @endif
</x-modal.base>
