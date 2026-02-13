@php
    $flashItems = [];

    if (session('success')) {
        $flashItems[] = ['type' => 'success', 'icon' => 'fa-circle-check', 'text' => session('success')];
    }

    if (session('error')) {
        $flashItems[] = ['type' => 'danger', 'icon' => 'fa-circle-xmark', 'text' => session('error')];
    }

    if (session('warning')) {
        $flashItems[] = ['type' => 'warning', 'icon' => 'fa-triangle-exclamation', 'text' => session('warning')];
    }

    if (session('info')) {
        $flashItems[] = ['type' => 'info', 'icon' => 'fa-circle-info', 'text' => session('info')];
    }

    foreach ($errors->all() as $validationError) {
        $flashItems[] = ['type' => 'danger', 'icon' => 'fa-circle-xmark', 'text' => $validationError];
    }
@endphp

@if(count($flashItems) > 0)
    <div class="flash-stack" id="flashStack" aria-live="polite" aria-atomic="true">
        @foreach($flashItems as $item)
            <div class="flash-card flash-{{ $item['type'] }}" data-flash-item>
                <div class="flash-icon">
                    <i class="fas {{ $item['icon'] }}"></i>
                </div>
                <div class="flash-content">{{ $item['text'] }}</div>
                <button type="button" class="flash-close" data-flash-close aria-label="Tutup pesan">
                    <i class="fas fa-xmark"></i>
                </button>
                <span class="flash-progress"></span>
            </div>
        @endforeach
    </div>

    <script>
        (function () {
            const flashItems = document.querySelectorAll('[data-flash-item]');
            if (!flashItems.length) return;

            const removeFlash = function (element) {
                element.classList.add('is-hiding');
                setTimeout(function () {
                    element.remove();
                }, 240);
            };

            flashItems.forEach(function (item) {
                const closeButton = item.querySelector('[data-flash-close]');
                const autoHideTimer = window.setTimeout(function () {
                    removeFlash(item);
                }, 5000);

                if (closeButton) {
                    closeButton.addEventListener('click', function () {
                        window.clearTimeout(autoHideTimer);
                        removeFlash(item);
                    });
                }
            });
        })();
    </script>
@endif
