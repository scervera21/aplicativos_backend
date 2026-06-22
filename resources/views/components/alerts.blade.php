<div x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="fixed top-4 right-4 z-50 max-w-md px-4 space-y-2">

    <!-- ALERTA VERDE (CREATE) -->
    @if (session('status'))
        <div class="flex items-center justify-between p-4 rounded-lg shadow-sm border-2 font-medium"
             style="background-color:#f0fdf4; color:#15803d; border-color:#86efac;"
             role="alert">
            <div class="flex items-center gap-2">
                <svg class="flex-shrink-0 w-5 h-5" style="color:#16a34a;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span><span class="font-bold">¡Éxito!</span> {{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- ALERTA AZUL (UPDATE) -->
    @if (session('info'))
        <div class="flex items-center justify-between p-4 rounded-lg shadow-sm border-2 font-medium"
             style="background-color:#eef2ff; color:#4338ca; border-color:#a5b4fc;"
             role="alert">
            <div class="flex items-center gap-2">
                <svg class="flex-shrink-0 w-5 h-5" style="color:#4f46e5;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9 5a1 1 0 1 1 2 0v2a1 1 0 1 1-2 0V5Zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                </svg>
                <span><span class="font-bold">Atención:</span> {{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- ALERTA AMARILLA (DELETE) -->
    @if (session('success'))
        <div class="flex items-center justify-between p-4 rounded-lg shadow-sm border-2 font-medium"
             style="background-color:#fffbeb; color:#b45309; border-color:#fcd34d;"
             role="alert">
            <div class="flex items-center gap-2">
                <svg class="flex-shrink-0 w-5 h-5" style="color:#d97706;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <span><span class="font-bold">¡Listo!</span> {{ session('success') }}</span>
            </div>
        </div>
    @endif

</div>