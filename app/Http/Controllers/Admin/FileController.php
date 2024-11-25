<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TableTemplateExport;
use App\Imports\TableImport;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    protected $restrictedTables = [
        'users','documents','attachments','material_histories', 'roles', 'role_has_permissions', 'model_has_roles', 'model_has_permissions','migrations','failed_jobs', 'permissions', 'password_reset_tokens', 'personal_access_tokens', 'profiles', 'settings', 'permission_role'
    ];

    public function index()
    {
        // Récupérer la liste des tables dans la base de données
        $tables = Schema::getAllTables();
        $tableNames = array_map('current', $tables); // Extraire les noms des tables

        // Filtrer les tables restreintes
        $tableNames = array_filter($tableNames, function ($tableName) {
            return !in_array($tableName, $this->restrictedTables);
        });

        return view('admin.files.index', compact('tableNames'));
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
        ]);

        $tableName = $request->input('table_name');
        $tables = Schema::getAllTables();
        $tableNames = array_map('current', $tables);

        // Filtrer les tables restreintes
        $tableNames = array_filter($tableNames, function ($tableName) {
            return !in_array($tableName, $this->restrictedTables);
        });

        if (!in_array($tableName, $tableNames)) {
            return redirect()->back()->with('error', 'La table spécifiée n\'existe pas ou est restreinte.');
        }

        $columns = Schema::getColumnListing($tableName);
        return Excel::download(new TableTemplateExport($columns), "{$tableName}_template.xlsx");
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'table_name' => 'required|string',
        ]);

        $tableName = $request->input('table_name');
        $tables = Schema::getAllTables();
        $tableNames = array_map('current', $tables);

        // Filtrer les tables restreintes
        $tableNames = array_filter($tableNames, function ($tableName) {
            return !in_array($tableName, $this->restrictedTables);
        });

        if (!in_array($tableName, $tableNames)) {
            return redirect()->back()->with('error', 'La table spécifiée n\'existe pas ou est restreinte.');
        }

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $data = Excel::toArray(new TableImport($tableName), $file);
            $rowCount = count($data[0]);

            Excel::import(new TableImport($tableName), $file);

            return redirect()->back()->with('success', "Fichier importé avec succès. Nombre de lignes : $rowCount");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }
}