<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pt-5 pb-10">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    <h2 class="text-xl font-semibold mb-6">{{ __("Actualización de Aplicativos") }}</h2>

                    <form method="POST" action="{{ route('aplicativos.update', $aplicativo->id) }}">
                        @csrf
                        @method('PUT')  <!-- Metodo para actualizar registros -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-8">

                            <!-- Nombre del aplicativo -->
                            <div>
                                <x-input-label for="aplicativo">Nombre del aplicativo</x-input-label>
                                <x-text-input id="aplicativo" class="block mt-1 w-full" type="text" name="aplicativo" value="{{ old('aplicativo', $aplicativo->aplicativo) }}"/>

                                @error('aplicativo')                                                
                                    {{-- Muestra el mensaje de error que viene del controlador --}}
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Tipo de software -->
                            <div>
                                <x-input-label for="tipo_software">Tipo de software</x-input-label>
                                <x-text-input id="tipo_software" class="block mt-1 w-full" type="text" name="tipo_software" value="{{ old('tipo_software', $aplicativo->tipo_software) }}"/>
                            </div>

                                @error('tipo_software')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            <!-- Fecha de Inicio -->
                            <div>
                                <x-input-label for="fecha_inicio">Fecha de Inicio</x-input-label>
                                <x-text-input id="fecha_inicio" class="block mt-1 w-full" type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $aplicativo->fecha_inicio) }}" />

                                @error('fecha_inicio')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Fecha Final -->
                            <div>
                                <x-input-label for="fecha_final">Fecha Final</x-input-label>
                                <x-text-input id="fecha_final" class="block mt-1 w-full" type="date" name="fecha_final" value="{{ old('fecha_final', $aplicativo->fecha_final) }}" />

                                @error('fecha_final')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror

                            </div>

                            <!-- Estatus -->
                            <div>
                                <x-input-label for="estatus">Estatus</x-input-label>
                                <select id="estatus" name="estatus" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option disabled selected>Elige una opción</option>
                                    <option value="planificado" @selected(old('estatus', $aplicativo->estatus) == 'planificado')>Planificado</option>
                                    <option value="en desarrollo" @selected(old('estatus', $aplicativo->estatus) == 'en desarrollo')>Desarrollo</option>
                                    <option value="pruebas" @selected(old('estatus', $aplicativo->estatus) == 'pruebas')>Pruebas</option>
                                    <option value="culminado" @selected(old('estatus', $aplicativo->estatus) == 'culminado')>Culminado</option>
                                </select>

                                @error('estatus')
                                    <span class="alert alert-danger text-sm">{{ $message }}</span>
                                @enderror                                

                            </div>

                        <!-- Checkbox PAP -->
                        <div class="flex items-center">
                            <input id="pap" type="checkbox" name="pap" 
                                @checked(old('pap', $aplicativo->pap))
                                class="form-check-input rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                            <label for="pap" class="form-check-label ml-2 text-sm text-gray-600 dark:text-gray-400 font-medium cursor-pointer">PAP (Pase a Producción)</label>
                        </div>

                        <!-- PAP Estatus -->
                        <div>
                            <x-input-label for="pap_estatus">PAP Estatus</x-input-label>
                            <x-text-input id="pap_estatus" class="block mt-1 w-full disabled:bg-gray-200 dark:disabled:bg-gray-800 disabled:cursor-not-allowed" type="text" name="pap_estatus" value="{{ old('pap_estatus', $aplicativo->pap_estatus) }}" />

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
                        <div class="gap-2">
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 border-b-4 border-indigo-700 transition active:bg-indigo-700 active:border-indigo-800 rounded">Actualizar</button>
                            <button class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 border-b-4 border-indigo-700 transition active:bg-indigo-700 active:border-indigo-800 rounded"><a href="{{ route('aplicativos.index') }}" >Volver</a></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>