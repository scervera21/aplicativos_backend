<?php

use App\Http\Controllers\ProfileController;
// use App\Models\Aplicativo;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AplicativoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })

// Route::view('/','welcome')->name('welcome');

Route::get('/', function () {

        return view('dashboard');

})->middleware(['auth'])->name('dashboard');



//* Grupo de rutas protegidas por autenticación (requieren login)


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* 
        se le da un nombre a la ruta para poder usar redirect()->route() sin usar la url directamente 
        si cambia la url no hay problema con el redirect 
    */


    Route::get('/test1/{name?}', function ($name = 'test1') {      
            /* 
                el signo ? indica que el parametro es opcional si no se le pasa nada, 
                se toma el valor por defecto en la funcion (si existe)
            */
            if($name == 'test2') {
                return redirect()->route('test.index'); /* redirect pero usando el nombre de la ruta, 
                                                        no la url como tal, mejor para el manejo de errores o cambios en la url 
                                                        ya que si cambia la url no hay problema con el redirect. */
            }    
        
            return 'Esta es mi vista '. $name .' en Laravel';
    });

    Route::get('/aplicativos', [AplicativoController::class, 'index'])->name('aplicativos.index');
    Route::post('/aplicativos', [AplicativoController::class, 'store'])->name('aplicativos.store');
    Route::get('/aplicativos/{id}/edit', [AplicativoController::class,'edit'])->name('aplicativos.edit');   // Se envia el id del aplicativo a editar en la ruta para editar 
    Route::put('/aplicativos/{id}', [AplicativoController::class,'update'])->name('aplicativos.update');   // Se envia el id del aplicativo a actualizar en la ruta para actualizar
    Route::delete('/aplicativos/{id}', [AplicativoController::class,'destroy'])->name('aplicativos.destroy');   // Se envia el id del aplicativo a eliminar en la ruta para eliminar
    
});
        require __DIR__.'/auth.php';

/* 

    route() - es una funcion global de laravel que se usa para generar una URL basada en el nombre de la ruta
    redirect() - es una funcion global de laravel que se usa para redirigir a una ruta
    route() - se puede usar con redirect() para redirigir a una ruta por su nombre
    redirect() - si no se le pasa nada, redirige a la url actual
    redirect() - si se le pasa una url, redirige a esa url
    redirect() - si se le pasa un nombre de ruta, redirige a esa ruta
    redirect()->route() - es la forma correcta de redirigir a una ruta por su nombre

    
*/



