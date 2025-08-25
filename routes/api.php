<?php

use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Roles\RolePermissionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Settings\AgentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('sendOtp', [\App\Http\Controllers\Auth\AuthController::class, 'sendOtp']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);


Route::group(['middleware' => ['jwt']], function () {
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\Auth\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\Auth\AuthController::class, 'me']);
    Route::get('app-supporting-data', [\App\Http\Controllers\Common\HelperController::class, 'appSupportingData']);
});


Route::group(['middleware' => ['jwt:api']], function () {

    // ADMIN USERS
    Route::group(['prefix' => 'user'],function () {
        Route::post('list', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'index']);
        //User Modal Data
        Route::get('modal',[\App\Http\Controllers\Admin\Users\AdminUserController::class,'userModalData']);
        Route::post('add', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'store']);
        Route::get('get-user-info/{UserID}',[\App\Http\Controllers\Admin\Users\AdminUserController::class,'getUserInfo']);
        Route::post('update', [\App\Http\Controllers\Admin\Users\AdminUserController::class, 'update']);
        Route::post('password-change',[\App\Http\Controllers\Common\HelperController::class,'passwordChange']);
    });
    Route::group(['middleware' => ['jwt:api'],'prefix' => 'roles'], function () {
        Route::post('list', [RoleController::class, 'index']);
        Route::get('get-role-info/{roleId}', [RoleController::class, 'getRoleInfo']);
        Route::post('create', [RoleController::class, 'store']);
        Route::post('update/{roleId}', [RoleController::class, 'update']);
        Route::get('all', [RoleController::class, 'all']);
        Route::post('permission', [RoleController::class, 'permissions']);
    });

    Route::group(['middleware' => ['jwt:api'],'prefix' => 'role-permission'], function () {
        Route::post('list', [RolePermissionController::class, 'index']);
        Route::get('get-role-info/{roleId}', [RolePermissionController::class, 'getRoleInfo']);
        Route::post('create', [RolePermissionController::class, 'store']);
        Route::post('update/{roleId}', [RolePermissionController::class, 'update']);
        Route::get('modal-data',[RolePermissionController::class,'modalData']);
        Route::get('get-role-info',[RolePermissionController::class,'getRoleInfo']);
    });
    Route::group(['prefix' => 'approval'],function () {
        Route::post('shop-list', [\App\Http\Controllers\Admin\Approval\ShopRequisitionApprovalController::class, 'index']);
        Route::post('add-reject-shop-requisition', [\App\Http\Controllers\Admin\Approval\ShopRequisitionApprovalController::class, 'approveRejectRequisition']);

    });

    Route::group(['prefix' => 'reports'],function () {
        Route::post('all-shop-information',[\App\Http\Controllers\Admin\Reports\ReportController::class,'getAllShopInformation']);
        Route::get('get-shop-information/{shopId}',[\App\Http\Controllers\Admin\Reports\ReportController::class,'getSingleShopInformation']);

    });
    // Partner Management Routes Group
    Route::group(['middleware' => ['jwt:api'],'prefix' => 'partner'], function () {
        Route::get('get-supporting-data', [PartnerController::class, 'getSupportingData']);
        Route::post('list', [PartnerController::class, 'index']);           // GET /api/partner/list
        Route::post('add', [PartnerController::class, 'store']);           // POST /api/partner/add
        Route::get('get-partner-info/{id}', [PartnerController::class, 'show']); // GET /api/partner/get-partner-info/1
        Route::post('update', [PartnerController::class, 'update']);       // POST /api/partner/update
        Route::delete('delete/{id}', [PartnerController::class, 'destroy']); // DELETE /api/partner/delete/1
        Route::get('modal', [PartnerController::class, 'getModalData']);   // GET /api/partner/modal (for any dropdown data)
    });

    // Insurance Management Routes Group
    Route::group(['middleware' => ['jwt:api'],'prefix' => 'insurance'], function () {
        Route::post('list', [\App\Http\Controllers\Settings\InsuranceController::class, 'index']);           // GET /api/partner/list
        Route::post('add', [\App\Http\Controllers\Settings\InsuranceController::class, 'store']);           // POST /api/partner/add
        Route::get('get-insurance-info/{id}', [\App\Http\Controllers\Settings\InsuranceController::class, 'show']); // GET /api/partner/get-partner-info/1
        Route::post('update', [\App\Http\Controllers\Settings\InsuranceController::class, 'update']);       // POST /api/partner/update
        Route::delete('delete/{id}', [\App\Http\Controllers\Settings\InsuranceController::class, 'destroy']); // DELETE /api/partner/delete/1
        Route::get('modal', [\App\Http\Controllers\Settings\InsuranceController::class, 'getModalData']);   // GET /api/partner/modal (for any dropdown data)
    });

    // Service Management Routes Group
    Route::group(['middleware' => ['jwt:api'],'prefix' => 'service'], function () {
        Route::post('list', [\App\Http\Controllers\ServiceController::class, 'index']);           // GET /api/partner/list
        Route::post('add', [\App\Http\Controllers\ServiceController::class, 'store']);           // POST /api/partner/add
        Route::get('get-service-info/{id}', [\App\Http\Controllers\ServiceController::class, 'show']); // GET /api/partner/get-partner-info/1
        Route::post('update', [\App\Http\Controllers\ServiceController::class, 'update']);       // POST /api/partner/update
        Route::delete('delete/{id}', [\App\Http\Controllers\ServiceController::class, 'destroy']); // DELETE /api/partner/delete/1
        Route::get('modal', [\App\Http\Controllers\ServiceController::class, 'getModalData']);   // GET /api/partner/modal (for any dropdown data)
    });

    // package Management Routes Group
    Route::group(['middleware' => ['jwt:api'],'prefix' => 'package'], function () {
        Route::get('get-supporting-data', [\App\Http\Controllers\PackageController::class, 'getSupportingData']);
        Route::post('list', [\App\Http\Controllers\PackageController::class, 'index']);           // GET /api/partner/list
        Route::post('add', [\App\Http\Controllers\PackageController::class, 'store']);           // POST /api/partner/add
        Route::get('get-package-info/{id}', [\App\Http\Controllers\PackageController::class, 'show']); // GET /api/partner/get-partner-info/1
        Route::post('update', [\App\Http\Controllers\PackageController::class, 'update']);       // POST /api/partner/update
        Route::delete('delete/{id}', [\App\Http\Controllers\PackageController::class, 'destroy']); // DELETE /api/partner/delete/1
        Route::get('modal', [\App\Http\Controllers\PackageController::class, 'getModalData']);   // GET /api/partner/modal (for any dropdown data)
    });

    // Agents Routes Group
    Route::prefix('agent')->group(function () {
        Route::get('get-supporting-data', [AgentController::class, 'getSupportingData']);
        Route::post('list', [AgentController::class, 'index']);
        Route::post('add', [AgentController::class, 'store']);
        Route::post('update', [AgentController::class, 'update']);
        Route::get('get-agent-info/{id}', [AgentController::class, 'show']);
        Route::delete('delete/{id}', [AgentController::class, 'destroy']);
    });
    // Sales routes
    Route::prefix('sale')->group(function () {
        Route::get('get-supporting-data', [SaleController::class, 'getSupportingData']); // Route for fetching partner and package data
        Route::post('list', [SaleController::class, 'index']); // Route to list sales
        Route::post('add', [SaleController::class, 'store']); // Route to add a new sale
        Route::post('update', [SaleController::class, 'update']); // Route to update an existing sale
        Route::get('get-sale-info/{id}', [SaleController::class, 'show']); // Route to get detailed info for a sale
        Route::delete('delete/{id}', [SaleController::class, 'destroy']); // Route to delete a sale
    });

    // Payments routes
    Route::prefix('payments')->group(function () {
        Route::get('get-supporting-data', [\App\Http\Controllers\PaymentController::class, 'getSupportingData']); // Route for fetching partner and package data
        Route::post('list', [\App\Http\Controllers\PaymentController::class, 'index']); // Route to list sales
        Route::post('add', [\App\Http\Controllers\PaymentController::class, 'store']); // Route to add a new sale
        Route::post('update', [\App\Http\Controllers\PaymentController::class, 'update']); // Route to update an existing sale
        Route::get('get-payment-info/{id}', [\App\Http\Controllers\PaymentController::class, 'show']); // Route to get detailed info for a sale
        Route::delete('delete/{id}', [\App\Http\Controllers\PaymentController::class, 'destroy']); // Route to delete a sale
    });

});

