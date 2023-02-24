<?php

use App\Http\Controllers\ApiControllers\Contact\ContactApiController;
use App\Http\Controllers\ApiControllers\Course\LectureApiController;
use App\Http\Controllers\ApiControllers\Setting\AccountApiController;
use App\Http\Controllers\ApiControllers\Setting\BoardThemeApiController;
use App\Http\Controllers\ApiControllers\Setting\DailyGoalApiController;
use App\Http\Controllers\ApiControllers\Setting\NotificationApiController;
use App\Http\Controllers\ApiControllers\Setting\PasswordApiController;
use App\Http\Controllers\ApiControllers\Setting\PieceThemeApiController;
use App\Http\Controllers\ApiControllers\Setting\PrivacyApiController;
use App\Http\Controllers\ApiControllers\Setting\ProfileApiController;
use App\Http\Controllers\ApiControllers\Subscription\SubscriptionApiController;
use App\Http\Controllers\ApiControllers\Trainer\TrainerPositionApiController;
use App\Http\Controllers\ApiControllers\Trainer\TrainerVariantApiController;
use App\Http\Controllers\ApiControllers\User\UserFollowApiController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\ApiControllers\User\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (App::environment('production') || App::environment('staging')) URL::forceScheme('https');

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Support Routes
Route::prefix('support')->group(function () {
    Route::post('/contact', [ContactApiController::class,'contact']);
});


Route::middleware(['auth:api'])->group(function () {

    // Subscription Routes
    Route::post('/subscription/cancel', [SubscriptionApiController::class,'cancel']);

    // Resend
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class,'resend']);

    // Update Settings Routes
    Route::prefix('settings')->group(function () {
        Route::put('/updatePrivacy',[PrivacyApiController::class, 'update'])->name('updatePrivacy');
        Route::put('/updateNotification',[NotificationApiController::class, 'update'])->name('updateNotification');
        Route::put('/updateGoal', [DailyGoalApiController::class, 'update'])->name('updateGoal');
        Route::put('/updateBoard', [BoardThemeApiController::class, 'update'])->name('updateBoard');
        Route::put('/updatePiece', [PieceThemeApiController::class, 'update'])->name('updatePiece');
        Route::put('/updatePassword', [PasswordApiController::class, 'update'])->name('updatePassword');
        Route::put('/updateProfile', [ProfileApiController::class, 'update'])->name('updateProfile');
        Route::post('/updateAccount', [AccountApiController::class, 'update'])->name('updateAccount');
        Route::post('/updateProfilePicture', [ProfileApiController::class, 'updateProfilePicture'])->name('updateProfilePicture');
    });

    // User Routes
    Route::prefix('user')->group(function () {
        Route::get('/followers', [UserFollowApiController::class, 'followers'])->name('followers');
        Route::get('/following', [UserFollowApiController::class, 'following'])->name('following');
        Route::post('/follow', [UserFollowApiController::class, 'follow'])->name('follow');
        Route::post('/unfollow', [UserFollowApiController::class, 'unfollow'])->name('unfollow');
        Route::get('/search', [UserApiController::class, 'search'])->name('search');
        Route::post('/deleteRequest', [UserApiController::class, 'deleteRequest'])->name('deleteRequest');
    });

    // Trainer Routes
    Route::prefix('trainer')->group(function () {
        Route::get('/{code}/{variantNumber}', [TrainerVariantApiController::class, 'index'])->name('api.trainer.variant.index');
        Route::post('complete/{uuid}', [TrainerPositionApiController::class, 'complete'])->name('api.trainer.complete');
    });

    // Course Routes
    Route::prefix('course')->group(function () {

        Route::prefix('lecture')->group(function () {

            Route::post('/{lectureId}', [LectureApiController::class, 'complete'])->name('api.course.complete');

        });

    });

});
