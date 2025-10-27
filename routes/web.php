<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BischoolController;
use App\Http\Controllers\RutasController;
use App\Http\Controllers\VerseController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\WorshipController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScheduleOverrideController;
use App\Http\Controllers\HomeContentController;
use App\Http\Controllers\BibleController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::group(['middleware' => 'auth'], function () {

    //Rutas para administrador:
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::get('/profile', [AdminController::class, 'profile']);

    //Rutas para administrador: Programación

    // ===================================================================
    // RUTAS PARA ADMINISTRADOR: PROGRAMACIÓN DE RADIO
    // ===================================================================

    // Programación regular
    Route::get('/show-schedule', [ScheduleController::class, 'show'])->name('schedule.index');
    Route::post('/addschedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::get('/view-schedule/{id}', [ScheduleController::class, 'view'])->name('schedule.view');
    Route::post('/update-schedule/{id}', [ScheduleController::class, 'edit'])->name('schedule.update');
    Route::get('/delete-schedule/{id}', [ScheduleController::class, 'delete'])->name('schedule.softDelete');
    Route::get('/activate-schedule/{id}', [ScheduleController::class, 'activate'])->name('schedule.activate');
    Route::get('/realdelete-schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

    // Importación CSV
    Route::get('/import-schedule', function () {
        return view('admin.schedule.import-schedule');
    })->name('schedule.import.view');
    Route::post('/import-schedule-csv', [ScheduleController::class, 'importFromCSV'])->name('schedule.import');

    // API para obtener programa actual (para reproductor web)
    Route::get('/api/current-program', [ScheduleController::class, 'getCurrentProgram'])->name('api.current.program');
    Route::get('/api/schedule/day/{day}', [ScheduleController::class, 'getDaySchedule'])->name('api.day.schedule');

    // ===================================================================
    // RUTAS PARA DÍAS FESTIVOS Y PROGRAMACIÓN ESPECIAL
    // ===================================================================

    Route::get('/holiday-schedule', [ScheduleOverrideController::class, 'index'])->name('override.index');
    Route::post('/add-override', [ScheduleOverrideController::class, 'store'])->name('override.store');
    Route::post('/update-override/{id}', [ScheduleOverrideController::class, 'update'])->name('override.update');
    Route::get('/toggle-override/{id}', [ScheduleOverrideController::class, 'toggleActive'])->name('override.toggle');
    Route::get('/delete-override/{id}', [ScheduleOverrideController::class, 'destroy'])->name('override.destroy');

    // API para obtener programación considerando overrides
    Route::get('/api/schedule/date/{date}', [ScheduleOverrideController::class, 'getScheduleForDate'])->name('api.date.schedule');

    //Rutas para administrador: Culto dominical
    Route::get('/show-worship', [WorshipController::class, 'show']);
    Route::post('/addworship', [WorshipController::class, 'store']);
    Route::get('/view-worship/{id}', [WorshipController::class, 'view']);
    Route::post('update-worship/{id}', [WorshipController::class, 'edit']);
    Route::get('/delete-worship/{id}', [WorshipController::class, 'destroy']);
    Route::get('/activate-worship/{id}', [WorshipController::class, 'activate']);
    Route::get('/realdelete-worship/{id}', [WorshipController::class, 'delete']);


    
    //Rutas para administrador: Versículo
    Route::get('/show-quote', [VerseController::class, 'show']);
    Route::post('/addverse', [VerseController::class, 'store']);
    Route::get('/view-quote/{id}', [VerseController::class, 'view']);
    Route::post('/update-quote/{id}', [VerseController::class, 'edit']);
    Route::get('/delete-quote/{id}', [VerseController::class, 'destroy']);
    Route::get('/activate-quote/{id}', [VerseController::class, 'activate']);
    Route::get('/realdelete-quote/{id}', [VerseController::class, 'delete']);


    //Rutas para administrador: Banner
    Route::get('/show-slider', [BannerController::class, 'show']);
    Route::post('/addslider', [BannerController::class, 'store']);
    Route::get('/view-slider/{id}', [BannerController::class, 'view']);
    Route::post('/update-slider/{id}', [BannerController::class, 'edit']);
    Route::get('/delete-slider/{id}', [BannerController::class, 'destroy']);
    Route::get('/activate-slider/{id}', [BannerController::class, 'activate']);
    Route::get('/realdelete-slider/{id}', [BannerController::class, 'delete']);


    //Rutas para administrador: Mensaje de la semana (nueva versión)
    Route::get('/show-news', [NewsController::class, 'show']);
    Route::get('/show-looks', [NewsController::class, 'look']);
    Route::post('/addnews', [NewsController::class, 'store']);
    Route::get('/view-news/{id}', [NewsController::class, 'view']);
    Route::post('/update-news/{id}', [NewsController::class, 'edit']);
    Route::get('/delete-news/{id}', [NewsController::class, 'destroy']);
    Route::get('/activate-news/{id}', [NewsController::class, 'activate']);
    Route::get('/realdelete-news/{id}', [NewsController::class, 'delete']);
    Route::get('/admin/news', [NewsController::class, 'adminindex'])->name('news.index');

    //Rutas para administrador: Roles de usuario
    Route::get('/allroles', [RoleController::class, 'index']);
    Route::get('/new-role', [RoleController::class, 'roles']);
    Route::post('/addrole', [RoleController::class, 'store']);
    Route::get('/show-roles', [RoleController::class, 'show']);
    Route::get('/update-role/{id}', [RoleController::class, 'update']);
    Route::post('/updaterole/{id}', [RoleController::class, 'edit']);
    Route::get('/delete-role/{id}', [RoleController::class, 'delete']);

    //Rutas para administrador: Usuarios
    Route::get('/show-users', [AdminController::class, 'ushow']);
    Route::post('/adduser', [AdminController::class, 'ustore']);
    Route::get('/view-user/{id}', [AdminController::class, 'uview']);
    Route::post('/update-user/{id}', [AdminController::class, 'uedit']);
    Route::get('/delete-user/{id}', [AdminController::class, 'udestroy']);
    Route::get('/activate-user/{id}', [AdminController::class, 'uactivate']);
    Route::get('/realdelete-user/{id}', [AdminController::class, 'udelete']);

    //Rutas para administrador:
    Route::get('/dashboard', [AdminController::class, 'index']);


    //Rutas para administrador: Centro de notificaciones
    Route::get('/topbar', [AdminController::class, 'center']);

    //Rutas para administrador: Categorías de audios
    Route::get('/new-category', [CategoryController::class, 'category']);
    Route::post('/addcategory', [CategoryController::class, 'store']);
    Route::get('/show-categories', [CategoryController::class, 'show']);
    Route::get('/update-category/{id}', [CategoryController::class, 'update']);
    Route::post('/updatecategory/{id}', [CategoryController::class, 'edit']);
    Route::get('/delete-category/{id}', [CategoryController::class, 'delete']);



    //Rutas para administrador: Podcast
    Route::get('/new-podcast', [PodcastController::class, 'podcast']);
    Route::post('/addpodcast', [PodcastController::class, 'store']);
    Route::get('/show-podcasts', [PodcastController::class, 'show']);
    Route::get('/update-podcast/{id}', [PodcastController::class, 'update']);
    Route::post('/updatepodcast/{id}', [PodcastController::class, 'edit']);
    Route::get('/delete-podcast/{id}', [PodcastController::class, 'delete']);
});

Route::get('/opinion', [NewsController::class, 'opinion']);

Route::get('/history', [NewsController::class, 'history']);

Route::get('/newsweek', [NewsController::class, 'newsweek']);


Route::get('/worship-home', [VerseController::class, 'history']);
Route::get('/single-feed/{date}', [VerseController::class, 'single']);


Route::get('/showpost/{slug}', [NewsController::class, 'index']);

Route::get('/showworship/{slug}', [WorshipController::class, 'index']);

Route::get('/bischool-home', [BischoolController::class, 'history']);


//Rutas para suscripción de email para mailing list

Route::get('/donate', [DonateController::class, 'index']);
Route::post('/adddonor', [DonateController::class, 'create']);


Route::get('/', [HomeContentController::class, 'index']);
Route::get('/seeds', [HomeContentController::class, 'seeds']);
Route::post('/newsuscriber', [HomeContentController::class, 'suscriberemail']);
Route::get('/search', [HomeContentController::class, 'search']);
// Ruta API para obtener la información del programa actual
Route::get('/api/programa-actual', [HomeContentController::class, 'getProgramaActual']);


Route::get('/objetivos', [RutasController::class, 'objetivos']);
Route::get('/mision', [RutasController::class, 'mision']);
Route::get('/privacy', [RutasController::class, 'privacy']);
Route::get('/rights', [RutasController::class, 'rights']);
Route::get('/pqr', [RutasController::class, 'pqrs']);
Route::get('/data', [RutasController::class, 'data']);
Route::get('/meta', [RutasController::class, 'meta']);
Route::get('/declaracion', [RutasController::class, 'declaracion']);
Route::get('/mensajero', [RutasController::class, 'mensajero']);
Route::get('/lumbrera', [RutasController::class, 'lumbrera']);
Route::get('/live', [HomeContentController::class, 'liveverse']);
Route::get('/ojos', [RutasController::class, 'ojos']);
Route::get('/herencia', [RutasController::class, 'herencia']);
Route::get('/worships', [RutasController::class, 'worship']);

Route::fallback(function () {
    return view('errors.404');
});


Route::prefix('biblia')->name('biblia.')->group(function () {
    // Página
    Route::get('/', [BibleController::class, 'index'])->name('index');

    // Primero las rutas específicas (para que no las capture {libro})
    Route::get('/api/buscar', [BibleController::class, 'apiSearch'])->name('api.search');
    Route::get('/api/libros', [BibleController::class, 'apiBooks'])->name('api.books');

    // Luego las dinámicas, con restricciones
    Route::get('/api/{libro}/{cap}', [BibleController::class, 'apiChapter'])
        ->where('libro', '[a-z0-9\-]+')
        ->where('cap', '\d+')
        ->name('api.chapter');

    Route::get('/api/{libro}', [BibleController::class, 'apiChapters'])
        ->where('libro', '[a-z0-9\-]+')
        ->name('api.chapters');
});

// Rutas públicas para ver programación
Route::get('/programacion', function() {
    $schedules = \App\Models\Schedule::whereNull('deleted_at')
        ->orderBy('day')
        ->orderBy('start')
        ->get()
        ->groupBy('day');
    
    return view('public.schedule', compact('schedules'));
})->name('public.schedule');

Route::get('/programa-actual', [ScheduleController::class, 'getCurrentProgram'])
    ->name('public.current.program');