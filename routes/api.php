<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\FoundAndLostController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WallController;
use App\Http\Controllers\WarningController;
use App\Http\Controllers\OpeningServiceOrderController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RentalController;

Route::get('/ping', function(){
    return ['pongo'=>true];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function(){
    Route::post('/auth/validate', [AuthController::class,'validateToken']);
    Route::post('/auth/logout', [AuthController::class,'logout']);
    
    //Mural de Avisos
    Route::get('/walls', [WallController::class, 'getAll']);
    Route::post('/wall/{id}/like', [WallController::class, 'like']);

    // Documentos
    Route::get('/docs',[DocController::class, 'getAll']);

    //Livro de Ocorrencias
    Route::get('/warnings', [WarningController::class, 'getMyWarnings']);
    Route::post('/warning', [WarningController::class, 'setWarning']);
    Route::post('/warning/file', [WarningController::class, 'addWarningFile']);

    //Boletos
    Route::get('/billets',[BilletController::class, 'getAll']);

    //Achados e perdidos
    Route::get('/foundandlost', [FoundAndLostController::class, 'getAll']);
    Route::post('/foundandlost', [FoundAndLostController::class, 'insert' ]);
    Route::put('/foundandlost/{id}', [FoundAndLostController::class, 'update']);

    // Unidade
    Route::get('/unit/{id}', [UnitController::class, 'getInfo']);
    Route::post('/unit/{id}/addperson', [UnitController::class, 'addPerson']);
    Route::post('/unit/{id}/addvehicle', [UnitController::class, 'addVehicle']);
    Route::post('/unit/{id}/addpet', [UnitController::class, 'addPet']);
    Route::post('/unit/{id}/removeperson', [UnitController::class, 'removePerson']);
    Route::post('/unit/{id}/removevehicle', [UnitController::class, 'removeVehicle']);
    Route::post('/unit/{id}/removepet', [UnitController::class, 'removePet']);

    // Reservas
    Route::get('/reservations', [ReservationController::class, 'getReservations']);
    Route::post('/reservation/{id}', [ReservationController::class, 'setReservation']);

    Route::get('/reservation/{id}/disableddates', [ReservationController::class, 'getDisabledDates']);
    Route::get('/reservation/{id}/times', [ReservationController::class, 'getTimes']);

    Route::get('/myreservations', [ReservationController::class, 'getMyReservations']);
    Route::delete('/myreservation/{id}', [ReservationController::class, 'delMyReservation']);

    //Ordem de serviço
    Route::get('/openingserviceorders', [OpeningServiceOrderController::class, 'getMyOpeningServiceOrders']);
    Route::post('/openingserviceorder', [OpeningServiceOrderController::class, 'setOpeningServiceOrder']);
    Route::post('/openingserviceorder/file', [OpeningServiceOrderController::class, 'addOpeningServiceOrderFile']);

    //Encomendas - package
    Route::get('/packages', [PackageController::class, 'getMyPackages']);

    // Rental Aluguel de areas
    Route::get('/rentals', [RentalController::class, 'getRentals']);
    Route::post('/rental/{id}', [RentalController::class, 'setRental']);

    Route::get('/rental/{id}/disableddates', [RentalController::class, 'getDisabledDates']);
    //Route::get('/rental/{id}/times', [RentalController::class, 'getTimes']);

    Route::get('/myrentals', [RentalController::class, 'getMyRentals']);
    Route::delete('/myrental/{id}', [RentalController::class, 'delMyRental']);



});