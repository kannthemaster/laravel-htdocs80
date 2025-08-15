<?php


use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\QueueController;
use App\Exports\VisitExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\LabSubController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\LabExportController;
use App\Http\Controllers\VisitFollowupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HealthController;

Route::get('/report/dashboard', [ReportController::class, 'dashboard'])->name('report.dashboard');
Route::get('/report/yearly-dashboard', [ReportController::class, 'yearlyDashboard'])->name('report.yearly');

Route::get('/visit_followups/{visit_id}', [VisitFollowupController::class, 'index'])->name('visit_followups.index');
Route::post('/visit_followups/store', [VisitFollowupController::class, 'store'])->name('visit_followups.store');
Route::get('/followups/create/{visit}', [VisitFollowupController::class, 'create'])->name('followups.create');
Route::delete('/followups/{followup}', [VisitFollowupController::class, 'destroy'])->name('followups.destroy');
Route::get('/followups/{followup}/edit', [VisitFollowupController::class, 'edit'])->name('followups.edit');
Route::put('/followups/{followup}', [VisitFollowupController::class, 'update'])->name('followups.update');
Route::put('/patient/{id}/update-phone-changed', [PatientController::class, 'updatePhoneChanged'])->name('patient.updatePhoneChanged');
Route::get('/get-patient-details/{id}', [PatientController::class, 'getPatientDetails']);

Route::post('/check-id-card', [PatientController::class, 'checkIdCard'])->name('check.id_card');
Route::delete('patient/{id}/delete-photo', [PatientController::class, 'deletePhoto'])->name('patient.deletePhoto');
Route::get('/patient/photo/{id}', [PatientController::class, 'showPhoto'])->name('patient.showPhoto');
Route::get('/read-card', [App\Http\Controllers\CardReaderController::class, 'readCard']);
Route::get('/healthz', [HealthController::class, 'index']);

Route::get('/modern', function () {
    return view('dashboard', ['title' => 'Modern Dashboard']);
});


// หน้านี้อยู่แล้ว
Route::get('visit/{visit}/edit', [VisitController::class, 'edit'])->name('visit.edit');

Route::get('/patient', [\App\Http\Controllers\PatientController::class, 'index']);

Route::get('/lab-sub/print/{id}', [LabSubController::class, 'print'])->name('lab-sub.print');

Route::get('/visit/export', function () {
    return Excel::download(new VisitExport, 'visit_list.xlsx');
})->name('visit.export');

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
// routes/web.php
Route::get('/search/countries', 'PatientController@searchCountries')->name('search.countries');
Route::post('/queue', [QueueController::class, 'createQueue']);
Route::get('/queues', [QueueController::class, 'getQueues']);
Route::get('/lab-sub/table/{patient_id}', [LabSubController::class, 'table'])->name('lab-sub.table');

Route::get('/queues', function () {
    return view('queues');
});
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('backend')->middleware('auth')->group(function () {

    Route::get('/', function () {

        if(!Auth::user()->hasRole('Admin')){
            return redirect('/');
        }
        return view('backend.welcome');
    });

    Route::resource('users', App\Http\Controllers\Backend\UserController::class);
    Route::resource('roles', App\Http\Controllers\Backend\RoleController::class);
    Route::resource('config', App\Http\Controllers\Backend\ConfigController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/visit/appointments', [VisitController::class, 'appointment'])->name('appointments');
    Route::get('/visit/appointment', [App\Http\Controllers\VisitController::class,'appointment'] )->name('visit.appointment');
    Route::post('/patient/create-visit', [App\Http\Controllers\PatientController::class, 'createVisit'])->name('patient.createVisit');
    Route::resource('patient', App\Http\Controllers\PatientController::class);
    Route::get('/visit/room/{status}', [App\Http\Controllers\VisitController::class,'room'] )->name('visit.room');
    Route::get('/visit/medicine/{id}', [App\Http\Controllers\VisitController::class,'medicine'] )->name('visit.medicine');
    Route::get('/visit/lab/{id}', [App\Http\Controllers\VisitController::class,'lab'] )->name('visit.lab');
    Route::post('/visit/change-status/{room}', [App\Http\Controllers\VisitController::class,'changeStatus'] )->name('visit.change-status');
    Route::resource('visit', App\Http\Controllers\VisitController::class );
    Route::post('/visit/add-contact-person/{id}', [App\Http\Controllers\VisitController::class,'addContactPerson'] )->name('visit.addContactPerson');
    


    Route::resource('contact-person', App\Http\Controllers\ContactPersonController::class);

    Route::post('/lab/{id}/update-all-sub', [App\Http\Controllers\LabController::class, 'updateAllSub'])->name('lab-sub.updateAllSub');
    Route::get('/lab/room', [App\Http\Controllers\LabController::class,'room'] )->name('lab.room');
    Route::resource('lab', App\Http\Controllers\LabController::class);
    Route::get('/lab/report', [LabController::class, 'report'])->name('lab.report');
    Route::post('/lab/export', [LabExportController::class, 'export'])->name('lab.export');
    Route::get('/lab/export', [LabController::class, 'export'])->name('lab.export');

    Route::resource('lab-item', App\Http\Controllers\LabItemController::class);

    Route::get('/lab-sub/add', [App\Http\Controllers\LabSubController::class, 'add'])->name('lab-sub.add');
    Route::get('/lab-sub/config', [App\Http\Controllers\LabSubController::class, 'config'])->name('lab-sub.config');
    Route::post('/lab-sub/save-config', [App\Http\Controllers\LabSubController::class, 'saveConfig'])->name('lab-sub.save-config');
    
    Route::get('/lab-sub/{id}/result', [App\Http\Controllers\LabSubController::class,'result'] )->name('lab-sub.result');
    Route::resource('lab-sub', App\Http\Controllers\LabSubController::class);
   


    Route::resource('lab-blood', App\Http\Controllers\LabBloodController::class);
    
    Route::resource('address', App\Http\Controllers\AddressController::class);
    // Route::post('address/getSubDistrict',[App\Http\Controllers\AddressController::class,'getSubDistricts'])->name('address.getSubDistricts');
    Route::get('/visit-medicine/add', [App\Http\Controllers\VisitMedicineController::class, 'add'])->name('visit-medicine.add');
    Route::resource('visit-medicine', App\Http\Controllers\VisitMedicineController::class );

    // Route::get('/diagnosis/add', [App\Http\Controllers\DiagnosisController::class, 'add'])->name('diagnosis.add');
    Route::resource('diagnosis', App\Http\Controllers\DiagnosisController::class);
    
    Route::resource('medicine', App\Http\Controllers\MedicineController::class );
    Route::get('excel', function(){
    Route::post('/print-labs', [App\Http\Controllers\LabController::class, 'printLabs'])->name('print.labs');



  
      
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="myfile.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
    });

});


// Route::resource('backend/address', 'Backend\AddressController');
// Route::resource('backend/contact-person', 'Backend\ContactPersonController');
// Route::resource('backend/visit-contact-person', 'Backend\VisitContactPersonController');
// Route::resource('backend/lab-item', 'Backend\LabItemController');
// Route::resource('backend/lab', 'Backend\LabController');
// Route::resource('backend/contact-person', 'Backend\ContactPersonController');
// Route::resource('backend/lab-sub', 'Backend\LabSubController');
// Route::resource('backend/visit-medicine', 'Backend\VisitMedicineController');
// Route::resource('backend/diagnosis', 'Backend\DiagnosisController');
//Route::resource('backend/medicine', 'Backend\MedicineController');

