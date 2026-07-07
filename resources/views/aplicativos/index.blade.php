<x-app-layout>
    <div class="py-12">
        <div class="mx-auto sm:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pt-5 pb-10">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h2 class="text-xl font-semibold mb-6">{{ __("Registro de Aplicativos") }}</h2>

                    <form method="POST" action="{{ route('aplicativos.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-8">

                            <!-- Nombre del aplicativo -->
                            <div>
                                <x-input-label for="aplicativo">Nombre del aplicativo</x-input-label>
                                <x-text-input id="aplicativo" class="block mt-1 w-full" type="text" name="aplicativo" autofocus value="{{ old('aplicativo') }}"/>

                                @error('aplicativo')                                                
                                    {{-- Muestra el mensaje de error que viene del controlador --}}
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Tipo de software -->
                            <div>
                                <x-input-label for="tipo_software">Tipo de software</x-input-label>
                                <x-text-input id="tipo_software" class="block mt-1 w-full" type="text" name="tipo_software" value="{{ old('tipo_software') }}"/>
                            </div>

                                @error('tipo_software')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            <!-- Fecha de Inicio -->
                            <div>
                                <x-input-label for="fecha_inicio">Fecha de Inicio</x-input-label>
                                <x-text-input id="fecha_inicio" class="block mt-1 w-full" type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" />

                                @error('fecha_inicio')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Fecha Final -->
                            <div>
                                <x-input-label for="fecha_final">Fecha Final</x-input-label>
                                <x-text-input id="fecha_final" class="block mt-1 w-full" type="date" name="fecha_final" value="{{ old('fecha_final') }}" />

                                @error('fecha_final')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Estatus -->
                            <div>
                                <x-input-label for="estatus">Estatus</x-input-label>
                                <select id="estatus" name="estatus" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option disabled selected>Elige una opción</option>
                                    <option value="planificado" @selected(old('estatus') == 'planificado')>Planificado</option>
                                    <option value="en desarrollo" @selected(old('estatus') == 'en desarrollo')>Desarrollo</option>
                                    <option value="pruebas" @selected(old('estatus') == 'pruebas')>Pruebas</option>
                                    <option value="culminado" @selected(old('estatus') == 'culminado')>Culminado</option>
                                </select>

                                @error('estatus')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror                                

                            </div>

                            <!-- Checkbox PAP -->
                            <div class="flex items-center">
                                <input id="pap" type="checkbox" name="pap"
                                    @checked(old('pap'))
                                    class="form-check-input rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <label for="pap" class="form-check-label ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium cursor-pointer">PAP (Pase a Producción)</label>
                            </div>

                            <!-- PAP Estatus -->
                            <div>
                                <x-input-label for="pap_estatus">PAP Estatus</x-input-label>
                                <x-text-input id="pap_estatus" class="block mt-1 w-full disabled:bg-gray-200 dark:disabled:bg-gray-800 disabled:cursor-not-allowed" type="text" name="pap_estatus" value="{{ old('pap_estatus') }}" />

                                @error('pap_estatus')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                        <script>

                            const pap = document.querySelector("#pap");
                            const pap_estatus = document.querySelector("#pap_estatus");

                            function togglePapEstatus() {
                                pap_estatus.disabled = !pap.checked;

                                if (!pap.checked) {
                                    pap_estatus.value = "";
                                }
                            }

                            // 2. Escuchar el cambio cuando el usuario interactúa
                            pap.addEventListener('change', togglePapEstatus);

                            // 3. NUEVO: Ejecutar la función inmediatamente al cargar la página
                            togglePapEstatus();
                        </script>

                        <!-- Botón Enviar -->
                        <div>
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 border-b-4 border-indigo-700 transition active:bg-indigo-700 active:border-indigo-800 rounded">Enviar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <br><br>

            <div class="input max-w-xs flex justify-end">
                <span class="text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                <input type="search" class="grow border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Buscar" id="leadingIconDefault" />
            </div>
        <br>
        <div class="shadow-md rounded-lg border border-slate-800 bg-[#0f172a] overflow-x-auto">
            
            <!-- Pagination -->

            <div class="flex flex-col md:flex-row justify-between items-center p-4 bg-[#0c101b] gap-4">
                <p class="text-sm text-slate-400 ">
                    Mostrando <span class="font-semibold text-white"> {{ $aplicativos->firstItem() }} - {{ $aplicativos->lastItem() }} de {{ $aplicativos->total() }}</span> resultados
                </p>

            <div class="flex items-center gap-2">
                <p class="text-sm text-slate-400">Registros por página:</p>
                <select id="perPage" name="perPage" class="form-select rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <option value="5" @selected(request('perPage') == 5) >5</option>     <!-- selected(condition) selecciona el valor por defecto de la peticion y se queda con el valor si no se cambia -->
                    <option value="10" @selected(request('perPage') == 10)>10</option>
                </select>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const select = document.getElementById('perPage');
                    const currentUrl = new URL(window.location.href);

                    select.addEventListener('change', function (e) {
                        // Obtener el nuevo valor de registros por página
                        const perPage = e.target.value;
                        
                        // Actualizar el parámetro 'perPage' en la URL
                        currentUrl.searchParams.set('perPage', perPage);
                        
                        // Navegar a la nueva URL con el parámetro actualizado
                        window.location.href = currentUrl.toString();
                    });
                });
            </script>

            <nav aria-label="Paginacion">
                <div class="inline-flex items-center rounded-lg overflow-hidden shadow-md p-4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" role="group">

                    {{-- Botón Anterior --}}
                    @if ($aplicativos->onFirstPage())
                        <span class="inline-flex items-center justify-center w-11 h-11 bg-slate-800 text-slate-500 cursor-not-allowed">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.5 18.5 9 12l6.5-6.5"/></svg>
                        </span>
                    @else
                        <a href="{{ $aplicativos->previousPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 bg-slate-800 text-slate-500 hover:bg-slate-700 hover:text-slate-200 transition-colors duration-150">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.5 18.5 9 12l6.5-6.5"/></svg>
                        </a>
                    @endif

                    {{-- Indicador de página --}}
                    <div class="inline-flex items-center justify-center h-11 px-6 bg-slate-800 border-x border-slate-700/50 text-base font-medium text-slate-300 select-none">
                        <span class="text-slate-400">{{ $aplicativos->currentPage() }} de {{ $aplicativos->lastPage() }}</span>
                    </div>

                    {{-- Botón Siguiente --}}
                    @if ($aplicativos->hasMorePages())
                        <a href="{{ $aplicativos->nextPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 bg-slate-800 text-slate-500 hover:bg-slate-700 hover:text-slate-200 transition-colors duration-150">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.5 5.5 6.5 6.5-6.5 6.5"/></svg>
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-11 h-11 bg-slate-800 text-slate-500 cursor-not-allowed">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.5 5.5 6.5 6.5-6.5 6.5"/></svg>
                        </span>
                    @endif

                </div>
            </nav>

            <!-- End Pagination -->

            </div>
            <table class="w-full text-sm text-slate-300">
                <thead class="text-sm text-slate-400 bg-[#1e293b]/80 border-b border-slate-800 font-semibold uppercase tracking-wider">
                    <tr>
                        {{-- <th scope="col" class="px-6 py-4">ID</th> --}}
                        <th scope="col" class="px-6 py-4">Aplicativo</th>
                        {{-- <th scope="col" class="px-6 py-4">Tipo de Software</th> --}}
                        <th scope="col" class="px-6 py-4">Fecha Inicio</th>
                        <th scope="col" class="px-6 py-4">Fecha Final</th>
                        <th scope="col" class="px-6 py-4">Estatus</th>
                        <th scope="col" class="px-6 py-4">PAP</th>
                        <th scope="col" class="px-6 py-4">Estatus PAP</th>
                        {{-- <th scope="col" class="px-6 py-4">Creado</th> --}}
                        <th scope="col" class="px-6 py-4">Actualizado</th>
                        <th scope="col" class="px-6 py-4">Acciones</th>
                    </tr>                        
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse ( $aplicativos as $aplicativo) 
                    <tr class="bg-[#0f172a] hover:bg-slate-800/30 transition-colors duration-150">
                        {{-- <td class="px-6 py-4 font-medium text-slate-400 whitespace-nowrap">{{ $aplicativo->id }}</td> --}}
                        <td class="px-6 py-4 font-semibold text-white whitespace-nowrap">{{ $aplicativo->aplicativo }}</td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-slate-300 text-center">{{ $aplicativo->tipo_software ?: '—' }}</td> --}}
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300 text-center">{{ $aplicativo->fecha_inicio ?: '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300 text-center">{{ $aplicativo->fecha_final ?: '—' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if(strtolower($aplicativo->estatus) == 'planificado')
                                <span class="px-2.5 py-1 text-sm font-semibold rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Planificado
                                </span>
                            @elseif(strtolower($aplicativo->estatus) == 'en desarrollo')
                                <span class="px-2.5 py-1 text-sm font-semibold rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    Desarrollo
                                </span>
                            @elseif(strtolower($aplicativo->estatus) == 'pruebas')
                                <span class="px-2.5 py-1 text-sm font-semibold rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                    Pruebas
                                </span>
                            @elseif(strtolower($aplicativo->estatus) == 'culminado')
                                <span class="px-2.5 py-1 text-sm font-semibold rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    Culminado
                                </span>
                            @else
                                {{ $aplicativo->estatus ?: '—' }}
                        @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($aplicativo->pap)
                                <span class="inline-flex items-center text-emerald-400 gap-1 text-sm font-medium">
                                    Si
                                </span>
                            @else
                                <span class="inline-flex items-center text-[#FF0038] gap-1 text-sm font-medium">
                                    No
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-300 text-center">{{ $aplicativo->pap_estatus ?: '—' }}</td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-sm">{{ \Carbon\Carbon::parse($aplicativo->created_at)->diffForHumans(['parts'=> 2, 'short'=>true ]) }}</td> --}}
                        <td class="px-6 py-4 whitespace-nowrap text-slate-400 text-xs">{{ \Carbon\Carbon::parse($aplicativo->updated_at)->diffForHumans(['parts'=> 2, 'short'=>true ]) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('aplicativos.edit', $aplicativo->id) }}" class="text-white-500 hover:text-white-400"> <!-- Se invoca la ruta edit y se le pasa el id del aplicativo a editar -->
                                    <svg class="w-5 h-5" data-slot="icon" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"></path>
                                        <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('aplicativos.destroy', $aplicativo->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white-500 hover:text-white-500">
                                        <svg class="w-5 h-5" data-slot="icon" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5h.3l.815 8.15A1.5 1.5 0 0 0 5.357 15h5.285a1.5 1.5 0 0 0 1.493-1.35l.815-8.15h.3a.75.75 0 0 0 0-1.5H11v-.75A2.25 2.25 0 0 0 8.75 1h-1.5A2.25 2.25 0 0 0 5 3.25Zm2.25-.75a.75.75 0 0 0-.75.75V4h3v-.75a.75.75 0 0 0-.75-.75h-1.5ZM6.05 6a.75.75 0 0 1 .787.713l.275 5.5a.75.75 0 0 1-1.498.075l-.275-5.5A.75.75 0 0 1 6.05 6Zm3.9 0a.75.75 0 0 1 .712.787l-.275 5.5a.75.75 0 0 1-1.498-.075l.275-5.5a.75.75 0 0 1 .786-.711Z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-8 h-8 text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2 2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-base font-medium text-slate-400">No se han encontrado registros</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>