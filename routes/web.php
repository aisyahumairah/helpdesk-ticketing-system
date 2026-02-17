<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MailTemplateController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/', [AuthController::class, 'login']);

    // Socialite Google Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.change.update');
    Route::get('/notifications/read', [AuthController::class, 'markNotificationsAsRead'])->name('notifications.mark_read');

    // Ticket Routes
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/reply', [ReplyController::class, 'store'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/verify', [TicketController::class, 'verify'])->name('tickets.verify');
    Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');

    // IT Support Routes
    Route::middleware(['role:admin|it_support'])->group(function () {
        Route::get('/support/dashboard', [SupportController::class, 'dashboard'])->name('support.dashboard');
        Route::get('/support/tickets', [SupportController::class, 'tickets'])->name('support.tickets');
        Route::get('/support/reports', [SupportController::class, 'report'])->name('support.reports');
        Route::get('/support/audit-trails', [SupportController::class, 'auditTrails'])->name('support.audit_trails');
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/reassign', [TicketController::class, 'reassign'])->name('tickets.reassign');
        Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
        Route::post('/tickets/{ticket}/escalate', [TicketController::class, 'escalate'])->name('tickets.escalate');
    });

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::resource('users', AdminController::class);
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset_password');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle_status');
        
        // Roles & Permissions
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{role}/attach-permissions', [RoleController::class, 'attachPermissions'])->name('roles.attach_permissions');
        Route::resource('permissions', PermissionController::class);
        
        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
        
        // Mail Templates
        Route::resource('mail-templates', MailTemplateController::class)->names('mail_templates');
        Route::post('/mail-templates/{mailTemplate}/preview', [MailTemplateController::class, 'preview'])->name('mail_templates.preview');
    });
});

