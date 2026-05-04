@props([
    'modalId',
    'overlayId',
    'panelId',
    'closeButtonId' => null,
    'zIndex' => 'z-[70]',
    'maxWidth' => 'max-w-md',
    'panelClass' => '',
    'bodyClass' => 'px-6 py-5',
    'showCloseButton' => true,
])

<div {{ $attributes->merge(['id' => $modalId, 'class' => "fixed inset-0 {$zIndex} hidden items-center justify-center px-4"]) }}>
    <div id="{{ $overlayId }}" class="absolute inset-0 bg-slate-950/45 opacity-0 transition-opacity duration-300 ease-out"></div>

    <div id="{{ $panelId }}" class="relative w-full {{ $maxWidth }} rounded-[1.75rem] bg-white opacity-0 scale-95 shadow-2xl transition-all duration-300 ease-out {{ $panelClass }}">
        @if (isset($header) || $showCloseButton)
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-4">
                <div class="min-w-0 flex-1">
                    {{ $header ?? '' }}
                </div>

                @if ($showCloseButton)
                    <button
                        type="button"
                        @if ($closeButtonId) id="{{ $closeButtonId }}" @endif
                        class="rounded-full bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-slate-200 hover:cursor-pointer"
                    >
                        &times;
                    </button>
                @endif
            </div>
        @endif

        <div class="{{ $bodyClass }}">
            {{ $slot }}
        </div>
    </div>
</div>
