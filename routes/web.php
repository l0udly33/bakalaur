<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TrainerProfileController;
use App\Http\Controllers\TrainerOrderController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\UserStatisticsController;
use App\Http\Controllers\AdminTrainerController;
use App\Http\Controllers\TrainerApplicationController;
use App\Http\Controllers\AdminApplicationController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\AdminPayoutController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\ForumController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/')->with('success', 'Sėkmingai atsijungėte.');
})->name('logout');

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin');

Route::get('/admin', [AdminController::class, 'index'])->name('admin');

Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');

Route::get('/trainers', [TrainerController::class, 'index'])->name('trainer.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/trainer/profile/edit', [TrainerProfileController::class, 'edit'])->name('trainer.profile.edit');
    Route::post('/trainer/profile/update', [TrainerProfileController::class, 'update'])->name('trainer.profile.update');
});

Route::get('/trainers/{id}', [TrainerProfileController::class, 'show'])->name('trainer.profile.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/trainer/orders', [TrainerOrderController::class, 'index'])->name('trainer.orders');
});

Route::middleware('auth')->group(function () {
    Route::get('/mano-uzsakymai', [UserOrderController::class, 'index'])->name('orders.user');
});

Route::post('/user/orders', [UserOrderController::class, 'store'])->name('user-orders.store');

Route::get('/user/orders', [UserOrderController::class, 'index'])->name('orders.user');

Route::get('/orders/{order}/chat', [ChatController::class, 'show'])->name('chat.show');
Route::post('/orders/{order}/chat', [ChatController::class, 'store'])->name('chat.store');

Route::get('/trainer/orders/{order}', [TrainerOrderController::class, 'show'])->middleware('auth')->name('trainer.orders.show');
Route::post('/trainer/orders/{order}/status', [TrainerOrderController::class, 'updateStatus'])->middleware('auth')->name('trainer.orders.status');

Route::middleware(['auth'])->group(function () {
    Route::get('/balance/add', [BalanceController::class, 'showAddForm'])->name('balance.add');
    Route::post('/balance/add', [BalanceController::class, 'addBalance'])->name('balance.add.post');

    Route::get('/balance/withdraw', [BalanceController::class, 'showWithdrawForm'])->name('balance.withdraw');
    Route::post('/balance/withdraw', [BalanceController::class, 'withdrawBalance'])->name('balance.withdraw.post');
});

Route::post('/orders/{order}/pay', [UserOrderController::class, 'pay'])->name('orders.pay');

Route::post('/trainer/orders/{order}/status', [TrainerOrderController::class, 'updateStatus'])->name('trainer.orders.status');

Route::post('/orders/{order}/review', [UserOrderController::class, 'submitReview'])->name('orders.review')->middleware('auth');

Route::get('/trainers', [TrainerController::class, 'index'])->name('trainer.index');

Route::get('/statistika', [UserStatisticsController::class, 'index'])->name('user.statistics')->middleware('auth');
Route::post('/statistika', [UserStatisticsController::class, 'fetchStats'])->name('user.statistics.fetch')->middleware('auth');

Route::get('/admin/trainer/{id}/orders', [AdminTrainerController::class, 'orders'])->name('admin.trainer.orders');
Route::get('/admin/chat/{orderId}', [AdminTrainerController::class, 'viewChat'])->name('admin.chat.view');
Route::post('/admin/chat/{orderId}/send', [AdminTrainerController::class, 'sendMessage'])->name('admin.chat.send');

Route::middleware(['auth'])->group(function () {
    Route::get('/tapti-treneriu', [TrainerApplicationController::class, 'showForm'])->name('trainer.application');
    Route::post('/tapti-treneriu', [TrainerApplicationController::class, 'submit'])->name('trainer.application.submit');
});

Route::get('/admin/applications/{userId}', [AdminApplicationController::class, 'view'])
    ->name('admin.application.view')
    ->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::get('/trainer/payout', [PayoutController::class, 'form'])->name('trainer.payout.form');
    Route::post('/trainer/payout', [PayoutController::class, 'submit'])->name('trainer.payout.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/payouts', [AdminPayoutController::class, 'index'])->name('admin.payouts');
    Route::post('/admin/payouts/{id}/status', [AdminPayoutController::class, 'updateStatus'])->name('admin.payouts.updateStatus');
});

Route::post('/orders/{order}/repeat', [UserOrderController::class, 'repeat'])->name('orders.repeat');
Route::get('/my-orders', [UserOrderController::class, 'index'])->name('user.orders');


Route::middleware(['auth'])->group(function () {
    Route::get('/nustatymai', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
    Route::put('/nustatymai', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
});

Route::delete('/admin/reviews/{id}', [UserOrderController::class, 'destroyReview'])->name('admin.reviews.destroy');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/bad-reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.bad');
    Route::delete('/admin/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
});

Route::get('/admin/bad-reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.bad');
Route::post('/admin/bad-words', [AdminReviewController::class, 'storeBadWord'])->name('admin.badwords.store');

Route::delete('/admin/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');


Route::post('/admin/applications/{user}/approve', [AdminApplicationController::class, 'approve'])
    ->middleware('auth')
    ->name('admin.application.approve');

Route::post('/admin/applications/{user}/reject', [AdminApplicationController::class, 'reject'])
    ->middleware('auth')
    ->name('admin.application.reject');

Route::post('/admin/applications/{user}/notes', [AdminApplicationController::class, 'saveNotes'])
    ->middleware('auth')
    ->name('admin.application.notes.save');

Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/create', [ForumController::class, 'create'])->middleware('auth')->name('forum.create');
Route::post('/forum', [ForumController::class, 'store'])->middleware('auth')->name('forum.store');
Route::get('/forum/{post}', [ForumController::class, 'show'])->name('forum.show');
Route::get('/forum/{post}/edit', [ForumController::class, 'edit'])->middleware('auth')->name('forum.edit');
Route::put('/forum/{post}', [ForumController::class, 'update'])->middleware('auth')->name('forum.update');
Route::post('/forum/{post}/comment', [ForumController::class, 'comment'])->middleware('auth')->name('forum.comment');
Route::post('/forum/{post}/upvote', [ForumController::class, 'upvote'])->middleware('auth')->name('forum.upvote');
Route::post('/forum/{post}/pin', [ForumController::class, 'pin'])->middleware('auth')->name('forum.pin');
Route::post('/forum/comment/{comment}/upvote', [ForumController::class, 'upvoteComment'])->middleware('auth')->name('forum.comment.upvote');
Route::post('/forum/comment/{comment}/pin', [ForumController::class, 'pinComment'])->middleware('auth')->name('forum.comment.pin');

