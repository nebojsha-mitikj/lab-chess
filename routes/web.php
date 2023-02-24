<?php

use App\Http\Controllers\ApiControllers\User\UserApiController;
use App\Http\Controllers\WebControllers\Analytics\AnalyticsController;
use App\Http\Controllers\WebControllers\Contact\ContactController;
use App\Http\Controllers\WebControllers\Course\CourseController;
use App\Http\Controllers\WebControllers\Course\LectureController;
use App\Http\Controllers\WebControllers\Leaderboard\LeaderboardController;
use App\Http\Controllers\WebControllers\Page\EmailVerifiedController;
use App\Http\Controllers\WebControllers\Page\LandingPageController;
use App\Http\Controllers\WebControllers\Page\ProfilePageController;
use App\Http\Controllers\WebControllers\Policy\PolicyController;
use App\Http\Controllers\WebControllers\Setting\AccountController;
use App\Http\Controllers\WebControllers\Setting\DailyGoalController;
use App\Http\Controllers\WebControllers\Setting\DisplayController;
use App\Http\Controllers\WebControllers\Setting\NotificationController;
use App\Http\Controllers\WebControllers\Setting\PasswordController;
use App\Http\Controllers\WebControllers\Setting\PrivacyController;
use App\Http\Controllers\WebControllers\Setting\ProfileController;
use App\Http\Controllers\WebControllers\Subscription\PremiumController;
use App\Http\Controllers\WebControllers\Subscription\PricingController;
use App\Http\Controllers\WebControllers\Subscription\SubscriptionController;
use App\Http\Controllers\WebControllers\Trainer\TrainerController;
use App\Http\Controllers\WebControllers\Trainer\TrainerPositionController;
use App\Http\Controllers\WebControllers\Trainer\TrainerVariantController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (App::environment('production') || App::environment('staging')) URL::forceScheme('https');

// Landing page
Route::get('/', [LandingPageController::class, 'index'])->name('welcome');

Route::prefix('support')->group(function() {
    Route::get('/contact', [ContactController::class, 'index'])->name('support.contact');
});

Route::get('/email-verified', [EmailVerifiedController::class, 'index'])->name('email-verified.index');
Route::get('/user/deleteRequest/{token}', [UserApiController::class, 'confirmDeleteRequest'])->name('confirmDeleteRequest');
Route::get('/unsubscribe/{token}', [UserApiController::class, 'unsubscribe'])->name('unsubscribe');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/terms-of-service', [PolicyController::class, 'termsOfService'])->name('termsOfService');
Route::get('/privacy-policy', [PolicyController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('/refund-policy', [PolicyController::class, 'refundPolicy'])->name('refundPolicy');


// Authentication
Route::middleware(['auth', 'dailyGoalCheck'])->group(function () {

    Route::get('/profile/{username}', [ProfilePageController::class, 'index'])->name('profile');

    // Subscription & Premium
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::get('/premium', [PremiumController::class, 'index'])->name('premium');

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/account', [AccountController::class, 'index'])->name('settings.account');
        Route::get('/profile', [ProfileController::class, 'index'])->name('settings.profile');
        Route::get('/password', [PasswordController::class, 'index'])->name('settings.password');
        Route::get('/display', [DisplayController::class, 'index'])->name('settings.display');
        Route::get('/daily-goal', [DailyGoalController::class, 'index'])->name('settings.daily-goal');
        Route::get('/privacy', [PrivacyController::class, 'index'])->name('settings.privacy');
        Route::get('/notifications', [NotificationController::class, 'index'])->name('settings.notifications');
    });

    // Courses
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses');
        Route::get('/{courseName}/{lecture}', [LectureController::class, 'index'])->name('courses.lecture.index');
    });

    // Leaderboard
    Route::prefix('leaderboard')->group(function () {
        Route::get('/', [LeaderboardController::class, 'index'])->name('leaderboard');
    });

    // Trainer
    Route::prefix('trainer')->group(function () {
        Route::get('/', [TrainerController::class, 'index'])->name('trainer');
        Route::get('/{code}/{variantNumber}', [TrainerVariantController::class, 'index'])->name('trainer.variant.index');
        Route::get('/{code}/{variantNumber}/{positionNumber}', [TrainerPositionController::class, 'index'])->name('trainer.position.index');
    });

    Route::middleware(['isAdmin'])->group(function () {
        Route::prefix('admin')->group(function () {

            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.analytics');

        });
    });

});

require __DIR__.'/auth.php';
