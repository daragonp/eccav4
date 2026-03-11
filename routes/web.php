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
use App\Http\Controllers\PrivacyController;


require __DIR__ . '/auth.php';

Route::group(['middleware' => ['auth', 'admin']], function () {

    //Rutas para administrador:
    Route::get('/dashboard', [AdminController::class, 'index']);
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    //Rutas para administrador: Programación

    // ===================================================================
    // RUTAS PARA ADMINISTRADOR: PROGRAMACIÓN DE RADIO
    // ===================================================================

    // Programación regular
    Route::get('/show-schedule', [ScheduleController::class, 'show'])->name('schedule.index');
    Route::post('/addschedule', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::get('/view-schedule/{id}', [ScheduleController::class, 'view'])->name('schedule.view');
    Route::post('/update-schedule/{id}', [ScheduleController::class, 'edit'])->name('schedule.update');
    Route::post('/delete-schedule/{id}', [ScheduleController::class, 'delete'])->name('schedule.softDelete');
    Route::post('/activate-schedule/{id}', [ScheduleController::class, 'activate'])->name('schedule.activate');
    Route::delete('/realdelete-schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

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
Route::post('/duplicate-override/{id}', [ScheduleOverrideController::class, 'duplicate'])->name('override.duplicate');
Route::post('/toggle-override/{id}', [ScheduleOverrideController::class, 'toggleActive'])->name('override.toggle');
Route::delete('/delete-override/{id}', [ScheduleOverrideController::class, 'destroy'])->name('override.destroy');


    // API para obtener programación considerando overrides
    Route::get('/api/schedule/date/{date}', [ScheduleOverrideController::class, 'getScheduleForDate'])->name('api.date.schedule');

    //Rutas para administrador: Culto dominical
Route::get('/show-worship', [WorshipController::class, 'show']);
Route::post('/addworship', [WorshipController::class, 'store']);
Route::get('/view-worship/{id}', [WorshipController::class, 'view']);
Route::post('update-worship/{id}', [WorshipController::class, 'edit']);
Route::post('/delete-worship/{id}', [WorshipController::class, 'destroy']);      // Soft delete
Route::post('/activate-worship/{id}', [WorshipController::class, 'activate']);   // Activar
Route::delete('/realdelete-worship/{id}', [WorshipController::class, 'delete']); // Eliminación permanente
Route::get('/reprocess-worship-ai/{id}', [WorshipController::class, 'reprocessWithAI']);


    
    //Rutas para administrador: Versículo
    Route::get('/show-quote', [VerseController::class, 'show']);
    Route::post('/addverse', [VerseController::class, 'store']);
    Route::get('/view-quote/{id}', [VerseController::class, 'view']);
    Route::post('/update-quote/{id}', [VerseController::class, 'edit']);
    Route::post('/delete-quote/{id}', [VerseController::class, 'destroy']);
    Route::post('/activate-quote/{id}', [VerseController::class, 'activate']);
    Route::delete('/realdelete-quote/{id}', [VerseController::class, 'delete']);


    //Rutas para administrador: Banner
    Route::get('/show-slider', [BannerController::class, 'show']);
    Route::post('/addslider', [BannerController::class, 'store']);
    Route::get('/view-slider/{id}', [BannerController::class, 'view']);
    Route::post('/update-slider/{id}', [BannerController::class, 'edit']);
    Route::post('/delete-slider/{id}', [BannerController::class, 'destroy']);
    Route::post('/activate-slider/{id}', [BannerController::class, 'activate']);
    Route::delete('/realdelete-slider/{id}', [BannerController::class, 'delete']);


    //Rutas para administrador: Mensaje de la semana (nueva versión)
    Route::get('/show-news', [NewsController::class, 'show']);
    Route::get('/show-looks', [NewsController::class, 'look']);
    Route::post('/addnews', [NewsController::class, 'store']);
    Route::get('/view-news/{id}', [NewsController::class, 'view']);
    Route::post('/update-news/{id}', [NewsController::class, 'edit']);
    Route::post('/delete-news/{id}', [NewsController::class, 'destroy']);
    Route::post('/activate-news/{id}', [NewsController::class, 'activate']);
    Route::delete('/realdelete-news/{id}', [NewsController::class, 'delete']);
    Route::get('/admin/news', [NewsController::class, 'adminindex'])->name('news.index');

    //Rutas para administrador: Roles de usuario
    Route::get('/allroles', [RoleController::class, 'index']);
    Route::get('/new-role', [RoleController::class, 'roles']);
    Route::post('/addrole', [RoleController::class, 'store']);
    Route::get('/show-roles', [RoleController::class, 'show']);
    Route::get('/update-role/{id}', [RoleController::class, 'update']);
    Route::post('/updaterole/{id}', [RoleController::class, 'edit']);
    Route::post('/delete-role/{id}', [RoleController::class, 'delete']);

    //Rutas para administrador: Usuarios
    Route::get('/show-users', [AdminController::class, 'ushow']);
    Route::post('/adduser', [AdminController::class, 'ustore']);
    Route::get('/view-user/{id}', [AdminController::class, 'uview']);
    Route::post('/update-user/{id}', [AdminController::class, 'uedit']);
    Route::post('/delete-user/{id}', [AdminController::class, 'udestroy']);
    Route::post('/activate-user/{id}', [AdminController::class, 'uactivate']);
    Route::delete('/realdelete-user/{id}', [AdminController::class, 'udelete']);

    // Gestión de accesos (solo superadministrador)
    Route::middleware('superadmin')->group(function () {
        Route::get('/access-control', [AdminController::class, 'accessControl'])->name('access.control');
        Route::post('/access-control/users/{user}', [AdminController::class, 'updateUserAccess'])->name('access.control.update');
        Route::post('/access-control/users/{user}/test-permission', [AdminController::class, 'testUserPermission'])->name('access.control.test');
    });

    //Rutas para administrador:
    Route::get('/dashboard', [AdminController::class, 'index']);


    //Rutas para administrador: Centro de notificaciones
    Route::get('/topbar', [AdminController::class, 'center']);
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    //Rutas para administrador: Categorías de audios
    Route::get('/new-category', [CategoryController::class, 'category']);
    Route::post('/addcategory', [CategoryController::class, 'store']);
    Route::get('/show-categories', [CategoryController::class, 'show']);
    Route::get('/update-category/{id}', [CategoryController::class, 'update']);
    Route::post('/updatecategory/{id}', [CategoryController::class, 'edit']);
    Route::post('/delete-category/{id}', [CategoryController::class, 'delete']);



    //Rutas para administrador: Podcast
    Route::get('/new-podcast', [PodcastController::class, 'podcast']);
    Route::post('/addpodcast', [PodcastController::class, 'store']);
    Route::get('/show-podcasts', [PodcastController::class, 'show']);
    Route::get('/update-podcast/{id}', [PodcastController::class, 'update']);
    Route::post('/updatepodcast/{id}', [PodcastController::class, 'edit']);
    Route::post('/delete-podcast/{id}', [PodcastController::class, 'delete']);
});

Route::post('/api/privacy-acceptance', [PrivacyController::class, 'recordAcceptance'])->name('privacy.acceptance');

// Rutas para las políticas
Route::prefix('legal')->group(function () {
    Route::get('privacy', [PrivacyController::class, 'privacy'])->name('privacy-policy');
    Route::get('cookies', [PrivacyController::class, 'cookies'])->name('cookies-policy');
    Route::get('terms', [PrivacyController::class, 'terms'])->name('terms-conditions');
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


Route::get('/', [HomeContentController::class, 'index'])->name('home');
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
    Route::get('/api/libros/organizados', [BibleController::class, 'apiBooksOrganized'])->name('api.books.organized');
    Route::get('/api/inicio', [BibleController::class, 'apiStart'])->name('api.start');

    // Luego las dinámicas, con restricciones
    Route::get('/api/{libro}/{cap}/page/{page}', [BibleController::class, 'apiChapterPaginated'])
        ->where('libro', '[a-z0-9\-]+')
        ->where('cap', '\d+')
        ->where('page', '\d+')
        ->name('api.chapter.paginated');
        
    Route::get('/api/{libro}/{cap}/{vers}', [BibleController::class, 'apiVerse'])
        ->where('libro', '[a-z0-9\-]+')
        ->where('cap', '\d+')
        ->where('vers', '\d+')
        ->name('api.verse');

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

// Rutas para ver los registros de culto en la parte pública
Route::get('/worship', [WorshipController::class, 'publicIndex'])->name('worship.public.index');
Route::get('/worship/{slug}', [WorshipController::class, 'publicShow'])->name('worship.public.show');
