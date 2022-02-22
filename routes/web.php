<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\FinancingController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MonthlyClosingController;
use App\Http\Controllers\OrganizationalUnitController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PoaClosingController;
use App\Http\Controllers\ProgrammaticObjectiveController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TrakingStatusController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\AxisCuscaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ExcelController;

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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes(['verify' => true, 'remember_me'=>false]);

Route::group(['middleware'=> ['auth', 'verified']], function () {
    Route::get('/api/role/user', [RoleController::class, 'getActualUserRoles']);


    Route::post('/api/user/actualUserRole', [UserController::class, 'getActualUserRoles']);


    //Reports
    Route::get('generate-pdf', [PDFController::class, 'generatePDF']);

    //Excel
    Route::get('export', [ExcelController::class, 'export']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::group(['middleware'=>['is.admin']], function () {
        // Apis
        Route::resource('/api/department', DepartmentController::class);
        Route::resource('/api/municipality', MunicipalityController::class);
        Route::resource('/api/direction', DirectionController::class);
        Route::resource('/api/financing', FinancingController::class);
        Route::resource('/api/indicator', IndicatorController::class);
        Route::resource('/api/institution', InstitutionController::class);
        Route::resource('/api/monthlyClosing', MonthlyClosingController::class);
        Route::resource('/api/organizationalUnit', OrganizationalUnitController::class);
        Route::resource('/api/period', PeriodController::class);
        Route::resource('/api/poaClosing', PoaClosingController::class);
        Route::resource('/api/unit', UnitController::class);
        Route::resource('/api/trakingStatus', TrakingStatusController::class);
        Route::resource('/api/month', MonthController::class);
        Route::resource('/api/year', YearController::class);
        Route::resource('/api/programmaticObjective', ProgrammaticObjectiveController::class);
        Route::resource('/api/axisCusca', AxisCuscaController::class);
        Route::resource('/api/user', UserController::class);
        Route::resource('/api/role', RoleController::class);

        // Views
        Route::get('/departments', function () {
            return view('department.index');
        });

        Route::get('/municipalities', function () {
            return view('municipality.index');
        });
       
        Route::get('/users', function () {
            return view('user.index');
        });

        Route::get('/directions', function () {
            return view('direction.index');
        });

        Route::get('/financings', function () {
            return view('financing.index');
        });

        Route::get('/indicators', function () {
            return view('indicator.index');
        });

        Route::get('/institutions', function () {
            return view('institution.index');
        });
        
        Route::get('/monthlyClosings', function () {
            return view('monthly_closing.index');
        });

        Route::get('/organizationalUnits', function () {
            return view('organizational_unit.index');
        });

        Route::get('/periods', function () {
            return view('period.index');
        });

        Route::get('/poaClosings', function () {
            return view('poa_closing.index');
        });

        Route::get('/units', function () {
            return view('unit.index');
        });

        Route::get('/trakingStatus', function () {
            return view('traking_status.index');
        });

        Route::get('/months', function () {
            return view('month.index');
        });

        Route::get('/years', function () {
            return view('year.index');
        });

        Route::get('/programmaticObjective', function () {
            return view('programmatic_objective.index');
        });

        Route::get('/axisCuscatlan', function () {
            return view('axis_cusca.index');
        });

    });
});

Route::post('import', [ExcelController::class, 'import']);
