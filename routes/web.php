<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\FicheClientController;
use App\Http\Controllers\PeriodePaieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MultiStepFormController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\RelationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\TraitementPaieController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ConventionCollectiveController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BusinessBackupController;


Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('posts', PostController::class);
    Route::get('/search', [PostController::class, 'search'])->name('search');
    Route::delete('posts/attachments/{attachment}', [PostController::class, 'removeAttachment'])->name('posts.remove-attachment');

    // Profile routes
    Route::post('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/update-settings', [ProfileController::class, 'updateSettings'])->name('profile.update-settings');
    Route::post('/profile/update-account', [ProfileController::class, 'updateAccount'])->name('profile.update-account');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    // Search route
    // Route::get('/search', [HomeController::class, 'search'])->name('search');

    // Resource routes
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/manage-clients', [UserController::class, 'manageClients'])->name('users.manage_clients');
    Route::post('/users/{user}/attach-client', [UserController::class, 'attachClient'])->name('users.attach_client');
    Route::delete('/users/{user}/detach-client', [UserController::class, 'detachClient'])->name('users.detach_client');
    Route::post('/users/{user}/transfer-clients', [UserController::class, 'transferClients'])->name('users.transfer_clients');

    Route::resource('clients', ClientController::class);
    Route::resource('clients.materials', MaterialController::class);
    // Route::get('/clients/{client}/info', [ClientController::class, 'getInfo'])->name('clients.info');
    Route::post('/clients/store-partial', [ClientController::class, 'storePartial'])->name('clients.storePartial');
    Route::post('/clients/validate-step/{step}', [ClientController::class, 'validateStep'])->name('clients.validateStep');
    Route::put('/clients/{client}/update-partial/{step}', [ClientController::class, 'updatePartial'])->name('clients.updatePartial');
    Route::get('/clients/{client}/get-partial/{step}', [ClientController::class, 'getPartial'])->name('clients.getPartial');

    //laravel multistep
    // Route::get('/form/create', [MultiStepFormController::class, 'create'])->name('form.create');
    // Route::post('/form/store', [MultiStepFormController::class, 'store'])->name('form.store');
    // Route::get('/form/success', function () {
    //     return 'Formulaire soumis avec succès!';
    // })->name('form.success');

    Route::resource('materials', MaterialController::class);
    Route::get('materials/{id}/export/excel', [MaterialController::class, 'exportExcel'])->name('materials.export.excel');
    Route::get('materials/{id}/export/pdf', [MaterialController::class, 'exportPDF'])->name('materials.export.pdf');

    Route::resource('periodes-paie', PeriodePaieController::class);
    Route::patch('periodes-paie/{periodes_paie}/cloturer', [PeriodePaieController::class, 'cloturer'])->name('periodes-paie.cloturer');
    Route::patch('periodes-paie/{periodes_paie}/decloturer', [PeriodePaieController::class, 'decloturer'])->name('periodes-paie.decloturer');
    Route::patch('/periodes-paie/migrate', [PeriodePaieController::class, 'migrateAllClients'])->name('periodes-paie.migrate');
    // get infps client
    Route::get('clients/{id}/info', [ClientController::class, 'getInfo'])->name('clients.info');
    Route::resource('fiches-clients', FicheClientController::class)->except(['show']);
    Route::get('fiches-clients/{ficheClient}', [FicheClientController::class, 'show'])->name('fiches-clients.show');
    Route::get('fiches-clients/export/excel', [FicheClientController::class, 'exportExcel'])->name('fiches-clients.export.excel');
    Route::get('fiches-clients/export/pdf', [FicheClientController::class, 'exportPDF'])->name('fiches-clients.export.pdf');
    Route::post('fiches-clients/migrate', [FicheClientController::class, 'migrateToNewPeriod'])->name('fiches-clients.migrate');

    Route::put('periodes-paie/update-fiche-client/{ficheClient}', [PeriodePaieController::class, 'updateFicheClient'])->name('periodes-paie.updateFicheClient');
    Route::put('traitements-paie/update-fiche-client/{ficheClient}', [TraitementPaieController::class, 'updateFicheClient'])->name('traitements-paie.updateFicheClient');

    Route::get('/traitements-paie', [TraitementPaieController::class, 'index'])->name('traitements-paie.index');
    Route::get('/traitements-paie/create', [TraitementPaieController::class, 'create'])->name('traitements-paie.create');
    Route::post('/traitements-paie', [TraitementPaieController::class, 'store'])->name('traitements-paie.store');
    Route::get('/traitements-paie/{traitementPaie}', [TraitementPaieController::class, 'show'])->name('traitements-paie.show');
    Route::get('/traitements-paie/{traitementPaie}/edit', [TraitementPaieController::class, 'edit'])->name('traitements-paie.edit');
    Route::put('/traitements-paie/{traitementPaie}', [TraitementPaieController::class, 'update'])->name('traitements-paie.update');
    Route::delete('/traitements-paie/{traitementPaie}', [TraitementPaieController::class, 'destroy'])->name('traitements-paie.destroy');
    Route::get('/historique', [TraitementPaieController::class, 'historique'])->name('traitements-paie.historique');
    // Route::post('/traitements-paie/store', [TraitementPaieController::class, 'store'])->name('traitements-paie.store');
    Route::post('/traitements-paie/store-partial', [TraitementPaieController::class, 'storePartial'])->name('traitements-paie.storePartial');
    Route::post('/traitements-paie/cancel', [TraitementPaieController::class, 'cancel'])->name('traitements-paie.cancel');

    Route::resource('tickets', TicketController::class);
    Route::resource('convention-collectives', ConventionCollectiveController::class);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::get('/error', [ErrorController::class, 'show'])->name('error');
});

// Admin routes
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:Admin']], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::get('roles/assign', [RoleController::class, 'assign'])->name('roles.assign');
    Route::post('roles/assign', [RoleController::class, 'assignRoles'])->name('roles.assignRoles');
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('users', UserController::class);
    // Route::resource('clients', ClientController::class);
    Route::resource('client_user', RelationController::class);
        // Routes pour la gestion des clients par utilisateur
    Route::get('/users/{user}/clients', [UserController::class, 'manageClients'])
        ->name('users.manage_clients');

    Route::post('users/{user}/attach-client', [UserController::class, 'attachClient'])->name('users.attachClient');
    Route::post('users/{user}/detach-client', [UserController::class, 'detachClient'])->name('users.detachClient');
    Route::post('users/{user}/transfer-clients', [UserController::class, 'transferClients'])->name('users.transferClients');

    //Upload Import Export
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::get('/download-template', [FileController::class, 'downloadTemplate'])->name('files.downloadTemplate');
    Route::post('/upload-excel', [FileController::class, 'uploadExcel'])->name('files.uploadExcel');
    // Clients relations
    Route::post('/client_user/transfer', [RelationController::class, 'transfer'])->name('client_user.transfer');
    Route::get('/client_user/filter', [RelationController::class, 'filter'])->name('client_user.filter');
    Route::post('client_user/{client_user}/attach-gestionnaire', [ClientController::class, 'attachGestionnaire'])->name('client_user.attachGestionnaire');
    Route::post('client_user/{client_user}/detach-gestionnaire', [ClientController::class, 'detachGestionnaire'])->name('client_user.detachGestionnaire');

    // Routes pour les paramètres
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/cache/clear', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::get('/settings/export', [SettingsController::class, 'export'])->name('settings.export');
    Route::post('/settings/import', [SettingsController::class, 'import'])->name('settings.import');

    // Routes pour les paramètres par groupe
    Route::get('/settings/{group}', [SettingsController::class, 'showGroup'])->name('settings.group');
    Route::put('/settings/{group}', [SettingsController::class, 'updateGroup'])->name('settings.update-group');

    //période paie
    Route::post('/periodes-paie/{periodes_paie}/valider', [PeriodePaieController::class, 'valider'])->name('periodes-paie.valider');
    Route::patch('/periodes-paie/{periodes_paie}/cloturer', [PeriodePaieController::class, 'cloturer'])->name('periodes-paie.cloturer');
    Route::patch('/periodes-paie/{periodes_paie}/decloturer', [PeriodePaieController::class, 'decloturer'])->name('periodes-paie.decloturer');

    //User Acces
    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('/users/{user}/revoke-role', [UserController::class, 'revokeRole'])->name('users.revoke-role');
    Route::post('/users/{user}/give-permission', [UserController::class, 'givePermission'])->name('users.give-permission');
    Route::post('/users/{user}/revoke-permission', [UserController::class, 'revokePermission'])->name('users.revoke-permission');

    Route::patch('/users/{user}/toggle-status', [RoleController::class, 'toggleUserStatus'])->name('users.toggle-status');

    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/telescope', function () {
        return redirect('/telescope');
    })->name('telescope');
    Route::get('/clockwork', function () {
        return redirect('/clockwork');
    })->name('clockwork');

    Route::get('/relations/filter', [RelationController::class, 'filter'])->name('admin.client_user.filter');
    Route::get('/relations', [RelationController::class, 'index'])->name('admin.client_user.index');
    Route::get('/relations/create', [RelationController::class, 'create'])->name('admin.client_user.create');
    Route::post('/relations', [RelationController::class, 'store'])->name('admin.client_user.store');
    Route::get('/relations/{relation}', [RelationController::class, 'show'])->name('admin.client_user.show');
    Route::get('/relations/{relation}/edit', [RelationController::class, 'edit'])->name('admin.client_user.edit');
    Route::put('/relations/{relation}', [RelationController::class, 'update'])->name('admin.client_user.update');
    Route::delete('/relations/{relation}', [RelationController::class, 'destroy'])->name('admin.client_user.destroy');

    // Route::get('client_user/{id}/edit', 'RelationController@edit')->name('client_user.edit');
    // Route::put('client_user/{id}', 'RelationController@update')->name('client_user.update');

    // Routes pour les sauvegardes système
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::get('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{fileName}', [BackupController::class, 'download'])->name('download');
        Route::get('/delete/{fileName}', [BackupController::class, 'delete'])->name('delete');
        Route::get('/clean', [BackupController::class, 'clean'])->name('clean');
    });

    // Routes pour les sauvegardes métier
    Route::prefix('business-backups')->name('business-backups.')->group(function () {
        Route::get('/', [BusinessBackupController::class, 'index'])->name('index');
        Route::post('/', [BusinessBackupController::class, 'create'])->name('create');
        Route::get('/preview/{fileName}', [BusinessBackupController::class, 'preview'])->name('preview');
        Route::get('/download/{fileName}/{format?}', [BusinessBackupController::class, 'download'])->name('download');
        Route::post('/restore/{fileName}', [BusinessBackupController::class, 'restore'])->name('restore');
        Route::delete('/{fileName}', [BusinessBackupController::class, 'delete'])->name('delete');
    });

});



require __DIR__ . '/auth.php';
