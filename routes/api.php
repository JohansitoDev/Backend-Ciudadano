<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Citizen\ProfileController;
use App\Http\Controllers\Api\Citizen\ServiceController;
use App\Http\Controllers\Api\Citizen\GovernmentPointController;
use App\Http\Controllers\Api\Citizen\AppointmentController;
use App\Http\Controllers\Api\Citizen\SupportTicketController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('puntos-gob', [GovernmentPointController::class, 'indexPublic']);
Route::get('servicios', [ServiceController::class, 'indexPublic']);
Route::post('tickets-soporte/invitado', [SupportTicketController::class, 'storeGuest']);
Route::middleware('auth:sanctum')->group(function () {


 
    Route::get('perfil', [ProfileController::class, 'show']);
 
    Route::put('perfil', [ProfileController::class, 'update']);
   
    Route::put('perfil/contrasena', [ProfileController::class, 'changePassword']);

    Route::post('logout', [AuthController::class, 'logout']);


    Route::apiResource('citas', AppointmentController::class);
 
    Route::put('citas/{cita}/cancelar', [AppointmentController::class, 'cancel']);


    Route::get('puntos-gob/cercanos', [GovernmentPointController::class, 'nearest']);

    Route::get('puntos-gob/{puntoGob}/servicios', [GovernmentPointController::class, 'showServices']);

    Route::get('tickets-soporte', [SupportTicketController::class, 'index']);

    Route::post('tickets-soporte', [SupportTicketController::class, 'store']);
    
    Route::get('tickets-soporte/{ticketSoporte}', [SupportTicketController::class, 'show']);
});