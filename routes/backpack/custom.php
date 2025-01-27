<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('curso-persona', 'CursoPersonaCrudController');
    //Route::crud('articulo-pagos', 'ArticuloPagosCrudController');
    Route::crud('curso', 'CursoCrudController');
    //Route::crud('curso-pagos', 'CursoPagosCrudController');
    Route::crud('persona', 'PersonaCrudController');
    //Route::crud('persona-cuota', 'PersonaCuotaCrudController');
    //Route::crud('inventario', 'InventarioCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('persona-externa', 'PersonaExternaCrudController');
    Route::crud('curso-persona-externa', 'CursoPersonaExternaCrudController');
    Route::crud('inventario', 'InventarioCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
