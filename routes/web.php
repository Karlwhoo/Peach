<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ApplepeachController;
use App\Http\Controllers\AccountLedgerController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTransferController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TaxSettingController;
use App\Http\Controllers\BankLedgerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Ramsey\Uuid\Guid\Guid;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PaymentSettingController;

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
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Web Register Route
|--------------------------------------------------------------------------
*/
Route::resource('user', RegisteredUserController::class);
Route::post('user/assign/role', [UserController::class,'assignRole']);

Route::get('/', [ApplepeachController::class, 'index']);

Route::group(['middleware' => 'auth'],function(){
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Web Banks Route
    |--------------------------------------------------------------------------
    */

    Route::get('bank/trash', [BankController::class, 'trash']);
    Route::get('/bank/delete', [BankController::class, 'destroyAll']);
    Route::get('bank/{id}/restore', [BankController::class, 'restore']);
    Route::get('bank/restoreAll', [BankController::class, 'restoreAll']);
    Route::get('/bank/parmanently/delete/{id}', [BankController::class, 'forceDeleted']);
    Route::get('bank/emptyTrash', [BankController::class, 'emptyTrash']);
    Route::get('/bank/delete/{id}',[BankController::class,'destroy']);
    Route::resource('bank', BankController::class);


    /*
    |--------------------------------------------------------------------------
    | Web AccountLedgers Route
    |--------------------------------------------------------------------------
    */
    Route::get('acount/ledger/emptytrash', [AccountLedgerController::class, 'emtyTrash']);
    Route::get('acount/ledger/restoreall', [AccountLedgerController::class, 'restoreAll']);
    Route::get('acount/ledger/restore/{id}', [AccountLedgerController::class, 'restore']);
    Route::get('acount/ledger/delete/parmanently/{id}', [AccountLedgerController::class, 'forceDelete']);
    Route::get('acount/ledger/trash', [AccountLedgerController::class, 'trash']);
    Route::get('acount/ledger/delete', [AccountLedgerController::class, 'deleteAll']);
    Route::get('acount/ledger/delete/{id}', [AccountLedgerController::class, 'destroy']);
    Route::resource('acount/ledger', AccountLedgerController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Balance Route
    |--------------------------------------------------------------------------
    */
    Route::get('balance/emptytrash', [BalanceController::class, 'emtyTrash']);
    Route::get('balance/restoreall', [BalanceController::class, 'restoreAll']);
    Route::get('balance/{id}/restore', [BalanceController::class, 'restore']);
    Route::get('balance/{id}/delete/parmanently', [BalanceController::class, 'forceDelete']);
    Route::get('balance/trash', [BalanceController::class, 'trash']);
    Route::get('balance/delete', [BalanceController::class, 'deleteAll']);
    Route::resource('balance', BalanceController::class);

    /*
    |--------------------------------------------------------------------------
    | Web BankLedgers Route
    |--------------------------------------------------------------------------
    */
    Route::get('bankLedger/emptytrash', [BankLedgerController::class, 'emtyTrash']);
    Route::get('bankLedger/restoreall', [BankLedgerController::class, 'restoreAll']);
    Route::get('bankLedger/{id}/restore', [BankLedgerController::class, 'restore']);
    Route::get('bankLedger/{id}/delete/parmanently', [BankLedgerController::class, 'forceDelete']);
    Route::get('bankLedger/trash', [BankLedgerController::class, 'trash']);
    Route::get('bankLedger/delete', [BankLedgerController::class, 'deleteAll']);
    Route::get('bankLedger/delete/{id}', [BankLedgerController::class, 'destroy']);
    Route::resource('/bankLedger', BankLedgerController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Roomdgers Route
    |--------------------------------------------------------------------------
    */
    Route::get('/room/delete', [RoomController::class, 'destroyAll']);
    Route::get('/room/trash', [RoomController::class, 'trash']);
    Route::get('/room/{id}/restore', [RoomController::class, 'restore']);
    Route::get('/room/restoreAll', [RoomController::class, 'restoreAll']);
    Route::get('/room/{id}/parmanently/delete', [RoomController::class, 'forceDeleted']);
    Route::get('/Room/emptyTrash', [RoomController::class, 'emptyTrash']);
    Route::get('/room/delete/{id}', [RoomController::class, 'destroy']);
    Route::resource('room', RoomController::class);

    /*
    |--------------------------------------------------------------------------
    | Web RoomTansfer Route
    |--------------------------------------------------------------------------
    */
    Route::get('roomTransfer/trash', [RoomTransferController::class, 'trash']);
    Route::get('/roomTransfer/delete', [RoomTransferController::class, 'destroyAll']);
    Route::get('roomTransfer/{id}/restore', [RoomTransferController::class, 'restore']);
    Route::get('roomTransfer/restoreAll', [RoomTransferController::class, 'restoreAll']);
    Route::get('/roomTransfer/{id}/parmanently/delete', [RoomTransferController::class, 'forceDeleted']);
    Route::get('/roomTransfer/emptyTrash', [RoomTransferController::class, 'emptyTrash']);
    Route::get('/roomTransfer/delete/{id}', [RoomTransferController::class, 'destroy']);
    Route::resource('roomTransfer', RoomTransferController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Booking Route
    |--------------------------------------------------------------------------
    */
    Route::get('/booking/trash', [BookingController::class, 'trash']);
    Route::get('/booking/delete', [BookingController::class, 'destroyAll']);
    Route::get('/Booking/{id}/restore', [BookingController::class, 'restore']);
    Route::get('/booking/restoreAll', [BookingController::class, 'restoreAll']);
    Route::get('/Booking/{id}/parmanently/delete', [BookingController::class, 'forceDeleted']);
    Route::get('/booking/emptyTrash', [BookingController::class, 'emptyTrash']);
    Route::get('booking/delete/{id}', [BookingController::class, 'destroy']);
    Route::get('/booking/availability', [BookingController::class, 'getAvailability'])->name('booking.availability');
    Route::resource('booking', BookingController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Invoices Route
    |--------------------------------------------------------------------------
    */
    Route::get('/invoice/trash', [InvoiceController::class, 'trash']);
    Route::get('/invoice/delete', [InvoiceController::class, 'destroyAll']);
    Route::get('/invoice/{id}/restore', [InvoiceController::class, 'restore']);
    Route::get('/invoice/restoreAll', [InvoiceController::class, 'restoreAll']);
    Route::get('/invoice/{id}/parmanently/delete', [InvoiceController::class, 'forceDeleted']);
    Route::get('/invoice/emptyTrash', [InvoiceController::class, 'emptyTrash']);
    Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit']);
    Route::put('/invoice/{id}', [InvoiceController::class, 'update']);
    Route::get('/invoice/{id}/print', [InvoiceController::class, 'print'])->name('invoice.print');
    Route::get('/invoice/{id}', [InvoiceController::class, 'show']);
    Route::resource('invoice', InvoiceController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Guests Route
    |--------------------------------------------------------------------------
    */
    Route::get('/guest/trash', [GuestController::class, 'trash']);
    Route::get('/guest/delete', [GuestController::class, 'destroyAll']);
    Route::get('/guest/{id}/restore', [GuestController::class, 'restore']);
    Route::get('/guest/restoreAll', [GuestController::class, 'restoreAll']);
    Route::get('/guest/parmanently/delete/{id}', [GuestController::class, 'forceDelete']);
    Route::get('/guest/emptyTrash', [GuestController::class, 'emptyTrash']);
    Route::get('/guest/delete/{id}',[GuestController::class,'destroy']);
    Route::resource('guest', GuestController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Emplooyee Route
    |--------------------------------------------------------------------------
    */
    Route::get('/employee/trash', [EmployeeController::class, 'trash']);
    Route::get('/employee/delete', [EmployeeController::class, 'destroyAll']);
    Route::get('/employee/{id}/restore', [EmployeeController::class, 'restore']);
    Route::get('/employee/restoreAll', [EmployeeController::class, 'restoreAll']);
    Route::get('/employee/parmanently/delete/{id}', [EmployeeController::class, 'forceDeleted']);
    Route::get('/employee/emptyTrash', [EmployeeController::class, 'emptyTrash']);
    Route::get('/employee/delete/{id}',[EmployeeController::class,'destroy']);
    Route::resource('employee', EmployeeController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Hotels Route
    |--------------------------------------------------------------------------
    */
    Route::get('/hotel/trash', [HotelController::class, 'trash']);
    Route::get('/hotel/delete', [HotelController::class, 'destroyAll']);
    Route::get('hotel/{id}/restore', [HotelController::class, 'restore']);
    Route::get('/hotel/restoreAll', [HotelController::class, 'restoreAll']);
    Route::get('/hotel/parmanently/delete/{id}', [HotelController::class, 'forceDeleted']);
    Route::get('/hotel/emptyTrash', [HotelController::class, 'emptyTrash']);
    Route::get('/hotel/delete/{id}',[HotelController::class,'destroy']);
    Route::resource('hotel', HotelController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Incoms Route
    |--------------------------------------------------------------------------
    */
    Route::get('/income/category/trash', [IncomeCategoryController::class, 'trash']);
    Route::get('/income/category/delete', [IncomeCategoryController::class, 'destroyAll']);
    Route::get('/income/category/{id}/parmanently/delete',[IncomeCategoryController::class, 'forceDelete']);
    Route::get('/income/category/{id}/restore', [IncomeCategoryController::class, 'restore']);
    Route::get('/income/category/restoreAll', [IncomeCategoryController::class, 'restoreAll']);
    Route::get('/income/category/emptyTrash', [IncomeCategoryController::class, 'emptyTrash']);
    Route::resource('/income/category', IncomeCategoryController::class);

    /*Income Routes*/
    Route::get('/income/trash', [IncomeController::class, 'trash']);
    Route::get('/income/delete', [IncomeController::class, 'destroyAll']);
    Route::get('/income/parmanently/delete/{id}', [IncomeController::class, 'forceDelete']);
    Route::get('/income/{id}/restore', [IncomeController::class, 'restore'])->name('income.restore');
    Route::get('/income/restoreAll', [IncomeController::class, 'restoreAll'])->name('income.restoreAll');
    Route::get('/income/emptyTrash', [IncomeController::class, 'emptyTrash']);
    Route::get('/income/delete/{id}',[IncomeController::class,'destroy']);
    Route::resource('income', IncomeController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Expense Route
    |--------------------------------------------------------------------------
    */
    Route::get('/expense/category/trash', [ExpenseCategoryController::class, 'trash']);
    Route::get('/expense/category/delete', [ExpenseCategoryController::class, 'destroyAll']);
    Route::get('/expense/category/{id}/parmanently/delete', [ExpenseCategoryController::class, 'forceDelete']);
    Route::get('/expense/category/{id}/restore', [ExpenseCategoryController::class, 'restore']);
    Route::get('/expense/category/restoreAll', [ExpenseCategoryController::class, 'restoreAll']);
    Route::get('/expense/category/emptyTrash', [ExpenseCategoryController::class, 'emptyTrash']);
    Route::resource('/expense/category', ExpenseCategoryController::class);

    /*Expense Routes*/
    Route::get('/expense/trash', [ExpenseController::class, 'trash']);
    Route::get('/expense/delete', [ExpenseController::class, 'destroyAll']);
    Route::get('/expense/parmanently/delete/{id}', [ExpenseController::class, 'forceDelete']);
    Route::get('/expense/{id}/restore', [ExpenseController::class, 'restore']);
    Route::get('/expense/restoreAll', [ExpenseController::class, 'restoreAll']);
    Route::get('/expense/emptyTrash', [ExpenseController::class, 'emptyTrash']);
    Route::get('/expense/delete/{id}',[ExpenseController::class,'destroy']);
    Route::resource('expense', ExpenseController::class);

    /*
    |--------------------------------------------------------------------------
    | Web User Route
    |--------------------------------------------------------------------------
    */
    Route::get('/user/delete/{id}',[UserController::class,'destroy']);
    Route::get('/user/delete',[UserController::class, 'destroyAll']);
    Route::resource('user', UserController::class);

    /*
    |--------------------------------------------------------------------------
    | Web Taxsetting Route
    |--------------------------------------------------------------------------
    */
    Route::get('/taxSetting/trash', [TaxSettingController::class, 'trash']);
    Route::get('/taxSetting/delete', [TaxSettingController::class, 'destroyAll']);
    Route::get('/taxSetting/{id}/restore', [TaxSettingController::class, 'restore']);
    Route::get('/taxSetting/restoreAll', [TaxSettingController::class, 'restoreAll']);
    Route::get('/taxSetting/{id}/parmanently/delete', [TaxSettingController::class, 'forceDeleted']);
    Route::get('/taxSetting/emptyTrash', [TaxSettingController::class, 'emptyTrash']);
    Route::get('taxSetting/delete/{id}', [TaxSettingController::class, 'destroy']);
    Route::resource('taxSetting', TaxSettingController::class);

    /*
    |--------------------------------------------------------------------------
    | Web profile Route
    |--------------------------------------------------------------------------
    */
    Route::get('profile/show', [ProfileController::class, 'index']);
    Route::get('profile/edit', [ProfileController::class, 'edit']);
    Route::put('profile/update', [ProfileController::class, 'update']);

     /*
    |--------------------------------------------------------------------------
    | SMS Routes
    |--------------------------------------------------------------------------
    */
    Route::get('sms',[SMSController::class,'index']);
    Route::post('sms/send',[SMSController::class,'send']);

    /*
    |--------------------------------------------------------------------------
    | Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::get('payment',[PaymentController::class,'index']);
    Route::post('payment',[PaymentController::class,'order']);   

    Route::get('/view_booking', [App\Http\Controllers\BookingController::class, 'viewBookings'])->name('view_booking');

    Route::get('/checkouts', [BookingController::class, 'viewCheckouts'])->name('view_checkouts');

    Route::middleware(['auth'])->group(function () {
        Route::get('/assets/delete-all', [AssetController::class, 'destroyAll'])->name('assets.delete-all');
        Route::resource('assets', AssetController::class);
    });

    Route::get('/paymentSetting', [PaymentSettingController::class, 'index'])->name('paymentSetting.index');
    Route::post('/paymentSetting', [PaymentSettingController::class, 'store'])->name('paymentSetting.store');

    Route::get('/paymentSetting/create', [PaymentSettingController::class, 'create'])->name('paymentSetting.create');
    Route::post('/paymentSetting/store', [PaymentSettingController::class, 'store'])->name('paymentSetting.store');

    Route::get('/payment-settings/{paymentSetting}/edit', [PaymentSettingController::class, 'edit'])->name('paymentSetting.edit');
    Route::put('/payment-settings/{paymentSetting}', [PaymentSettingController::class, 'update'])->name('paymentSetting.update');
    Route::delete('/payment-settings/{paymentSetting}', [PaymentSettingController::class, 'destroy'])->name('paymentSetting.destroy');

});

Route::post('sslcommerz/success',[PaymentController::class,'success'])->name('payment.success');
Route::post('sslcommerz/failure',[PaymentController::class,'failure'])->name('payment.failure');
Route::post('sslcommerz/cancel',[PaymentController::class,'cancel'])->name('payment.cancel');
Route::post('sslcommerz/ipn',[PaymentController::class,'ipn'])->name('payment.ipn');

Route::get('/api/room-status/{roomId}/{year}/{month}', [BookingController::class, 'getRoomStatus']);
Route::get('/api/room-bookings/{roomId}/{year}/{month}', function ($roomId, $year, $month) {
    return DB::table('bookings')
        ->where('RoomID', $roomId)
        ->whereYear('CheckInDate', $year)
        ->whereMonth('CheckInDate', $month)
        ->get();
});

Route::put('/room/{id}', [RoomController::class, 'update'])->name('room.update');

Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit']);
Route::put('/invoice/{id}', [InvoiceController::class, 'update']);

Route::resource('assets', AssetController::class);
Route::get('assets/list', [AssetController::class, 'list'])->name('assets.list');
Route::get('assets/{asset}/schedule', [AssetController::class, 'getDepreciationSchedule']);
Route::get('/assets/{id}/depreciation-schedule', [AssetController::class, 'getDepreciationSchedule'])
    ->name('assets.depreciation-schedule');
Route::get('/assets/{id}', 'AssetController@show');
Route::delete('/assets/{id}', 'AssetController@destroy');
Route::get('/assets/{id}/edit', [AssetController::class, 'edit'])->name('assets.edit');
Route::patch('/assets/{id}', [AssetController::class, 'update'])->name('assets.update');

// Add this new route for calendar availability
Route::get('/booking/availability', [App\Http\Controllers\BookingController::class, 'getAvailability'])->name('booking.availability');

/*
|--------------------------------------------------------------------------
| Web Assets Route
|--------------------------------------------------------------------------
*/
Route::get('/assets/delete', [AssetController::class, 'destroyAll']);
Route::resource('assets', AssetController::class);