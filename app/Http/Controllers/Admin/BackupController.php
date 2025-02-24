<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Tasks\Backup\BackupJob;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Artisan;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->files(config('backup.backup.name'));
        
        $backups = [];
        foreach ($files as $file) {
            if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                $backups[] = [
                    'file_name' => str_replace(config('backup.backup.name') . '/', '', $file),
                    'file_size' => $disk->size($file),
                    'last_modified' => $disk->lastModified($file),
                    'path' => $file
                ];
            }
        }

        $backups = array_reverse($backups);

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Lancer la sauvegarde
            Artisan::call('backup:run');

            return redirect()
                ->route('admin.backups.index')
                ->with('success', 'La sauvegarde a été lancée avec succès.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $file = config('backup.backup.name') . '/' . $fileName;

        if ($disk->exists($file)) {
            return response()->download($disk->path($file));
        }

        return redirect()
            ->route('admin.backups.index')
            ->with('error', 'Le fichier de sauvegarde n\'existe pas.');
    }

    public function delete($fileName)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $file = config('backup.backup.name') . '/' . $fileName;

        if ($disk->exists($file)) {
            $disk->delete($file);
            return redirect()
                ->route('admin.backups.index')
                ->with('success', 'La sauvegarde a été supprimée avec succès.');
        }

        return redirect()
            ->route('admin.backups.index')
            ->with('error', 'Le fichier de sauvegarde n\'existe pas.');
    }

    public function clean()
    {
        try {
            // Nettoyer les anciennes sauvegardes
            Artisan::call('backup:clean');

            return redirect()
                ->route('admin.backups.index')
                ->with('success', 'Les anciennes sauvegardes ont été nettoyées avec succès.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.backups.index')
                ->with('error', 'Erreur lors du nettoyage : ' . $e->getMessage());
        }
    }
} 