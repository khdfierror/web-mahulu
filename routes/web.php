<?php

use App\Http\Controllers\Admin\CagarBudayaController;
use App\Http\Controllers\Admin\CaseController;
use App\Http\Controllers\Admin\DataRequestController;
use App\Http\Controllers\Admin\Gratifikasi\JenisController;
use App\Http\Controllers\Admin\Gratifikasi\LaporanController as GratifikasiLaporanController;
use App\Http\Controllers\Admin\Gratifikasi\PeristiwaController;
use App\Http\Controllers\Admin\PermitCategoriesController;
use App\Http\Controllers\Admin\PermitController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AplikasiController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\Wbs\KategoriController;
use App\Http\Controllers\Admin\Wbs\LaporanController;
use App\Http\Controllers\AskOtpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\Web\GratifikasiController;
use App\Http\Controllers\Web\JendelaController;
use App\Http\Controllers\Web\PelaporanController;
use App\Http\Controllers\Web\PerizinanController;
use App\Http\Controllers\Web\PermohonanController;
use App\Http\Controllers\Web\PostController;
use App\Http\Controllers\Web\TemuanController;
use App\Http\Controllers\Web\WbsController;
use App\Http\Controllers\Web\BeritaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', HomeController::class)->name('home');
Route::post('/request-otp', AskOtpController::class)->name('request-otp');
Route::post('/send-message', SendMessageController::class)->name('send-message');

Route::name('web.')->group(function () {
    // Posts
    Route::controller(PostController::class)
    ->prefix('/post')
    ->name('post.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{post:ulid}/show', 'show')->name('show');
    });

    Route::controller(BeritaController::class)
    ->prefix('/berita')
    ->name('berita.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{berita:ulid}/show', 'show')->name('show');
    });

    // Layanan
    Route::controller(JendelaController::class)
    ->prefix('/layanan')
    ->name('layanan.')
    ->group(function () {
        // Route::get('/', 'index')->name('index');
        Route::get('/{slug}/show', 'show')->name('show');
    });

    Route::prefix('/services')->name('services.')->group(function () {
        // Route::get('/permohonan-data', 'permohonanData')->name('permohonan-data');
        // Route::get('/pelaporan-kasus', 'kasusCb')->name('pelaporan-kasus');

        // Perizinan
        Route::controller(PerizinanController::class)
        ->prefix('/perizinan')
        ->name('perizinan.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Pelaporan Temuan
        Route::controller(TemuanController::class)
        ->prefix('/temuan')
        ->name('temuan.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Permohonan Data CB
        Route::controller(PermohonanController::class)
        ->prefix('/permohonan')
        ->name('permohonan.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Pelaporan Kasus CB
        Route::controller(PelaporanController::class)
        ->prefix('/pelaporan')
        ->name('pelaporan.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Whistleblowing System
        Route::controller(WbsController::class)
        ->prefix('/wbs')
        ->name('wbs.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // Gratifikasi
        Route::controller(GratifikasiController::class)
        ->prefix('/gratifikasi')
        ->name('gratifikasi.')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::name('admin.services.')->prefix('/admin/services')->group(function () {
        Route::controller(PermitController::class)
        ->prefix('/permit')
        ->name('permit.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{permit:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{permit:ulid}', 'update')->name('update');
            Route::delete('/{permit:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(ReportController::class)
        ->prefix('/report')
        ->name('report.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{report:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{report:ulid}', 'update')->name('update');
            Route::delete('/{report:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(DataRequestController::class)
        ->prefix('/data-request')
        ->name('data-request.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{data:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{data:ulid}', 'update')->name('update');
            Route::delete('/{data:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(CaseController::class)
        ->prefix('/case')
        ->name('case.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{offense:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{offense:ulid}', 'update')->name('update');
            Route::delete('/{offense:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(CagarBudayaController::class)
        ->prefix('/cagar-budaya')
        ->name('cagar-budaya.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{cagar_budaya:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{cagar_budaya:ulid}', 'update')->name('update');
            Route::delete('/{cagar_budaya:ulid}', 'destroy')->name('destroy')->withTrashed();
        });
    });

    Route::name('admin.wbs.')->prefix('/admin/wbs')->group(function () {
        Route::controller(KategoriController::class)
        ->prefix('/kategori')
        ->name('kategori.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{kategori:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{kategori:ulid}', 'update')->name('update');
            Route::delete('/{kategori:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(LaporanController::class)
        ->prefix('/laporan')
        ->name('laporan.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{laporan:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{laporan:ulid}', 'update')->name('update');
            Route::delete('/{laporan:ulid}', 'destroy')->name('destroy')->withTrashed();
        });
    });

    Route::name('admin.gratifikasi.')->prefix('/admin/gratifikasi')->group(function () {
        Route::controller(JenisController::class)
        ->prefix('/jenis')
        ->name('jenis.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{jenis:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{jenis:ulid}', 'update')->name('update');
            Route::delete('/{jenis:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(PeristiwaController::class)
        ->prefix('/peristiwa')
        ->name('peristiwa.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{peristiwa:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{peristiwa:ulid}', 'update')->name('update');
            Route::delete('/{peristiwa:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

        Route::controller(GratifikasiLaporanController::class)
        ->prefix('/laporan')
        ->name('laporan.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{laporan:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{laporan:ulid}', 'update')->name('update');
            Route::delete('/{laporan:ulid}', 'destroy')->name('destroy')->withTrashed();
            Route::get('/{laporan:ulid}/show', 'show')->name('show')->withTrashed();
        });
    });

    Route::controller(PermitCategoriesController::class)
    ->prefix('/admin/permit-categories')
    ->name('admin.permit-categories.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware(['remember']);
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category:ulid}/edit', 'edit')->name('edit')->withTrashed();
        Route::put('/{category:ulid}', 'update')->name('update');
        Route::delete('/{category:ulid}', 'destroy')->name('destroy')->withTrashed();
    });


    Route::controller(AgendaController::class)
        ->prefix('/admin/agenda')
        ->name('admin.agenda.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{agenda:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{agenda:ulid}', 'update')->name('update');
            Route::delete('/{agenda:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

    Route::controller(NewsController::class)
        ->prefix('/admin/news')
        ->name('admin.news.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{news:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{news:ulid}', 'update')->name('update');
            Route::delete('/{news:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

    Route::controller(AplikasiController::class)
        ->prefix('/admin/aplikasi')
        ->name('admin.aplikasi.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{aplikasi:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{aplikasi:ulid}', 'update')->name('update');
            Route::delete('/{aplikasi:ulid}', 'destroy')->name('destroy')->withTrashed();
        });

    Route::controller(DocumentController::class)
        ->prefix('/admin/document')
        ->name('admin.document.')
        ->group(function () {
            Route::get('/', 'index')->name('index')->middleware(['remember']);
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{document:ulid}/edit', 'edit')->name('edit')->withTrashed();
            Route::put('/{document:ulid}', 'update')->name('update');
            Route::delete('/{document:ulid}', 'destroy')->name('destroy')->withTrashed();
        });
});
