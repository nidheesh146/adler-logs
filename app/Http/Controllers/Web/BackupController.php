<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    public function createBackup(Request $request)
    {
        // Run the backup command
        $backupFileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupzip='backup_' . date('Y-m-d_H-i-s'). '.zip';
        $folderPath=storage_path('app/backups/');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $backupPath = storage_path('app/backups/' . $backupFileName);

        DB::beginTransaction();
        try {
            $tables = DB::select("SHOW TABLES");           
            $content = '';
            foreach ($tables as $table) {            
                
                $tableName = reset($table); // Get the table name from the result set                
                $tableStructure = DB::select("SHOW CREATE TABLE $tableName")[0]->{'Create Table'};
                $content .= "$tableStructure;\n\n";
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $row = (array) $row;
                    $content .= "INSERT INTO $tableName (";
                    $content .= implode(', ', array_keys($row)) . ') ';
                    $content .= "VALUES ('" . implode("', '", array_values($row)) . "');\n";
                }
                $content .= "\n";            
           
            
            }

            // Save the SQL content to the backup file
            file_put_contents(storage_path("app/backups/$backupFileName"), $content);
            DB::commit();
             // Create a ZIP archive
            $sqlFilePath = storage_path('app/backups/'.$backupFileName);
            $zip = new ZipArchive;
            $zipFileName = storage_path('app/backups/'.$backupzip);

            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            // Add the SQL file to the ZIP archive
            $zip->addFile($sqlFilePath, $backupFileName);
            $zip->close();           
            // Remove the original SQL file if needed
            unlink($sqlFilePath);
            return response()->download($zipFileName)->deleteFileAfterSend(true);
            }

            return "Database backup created successfully: $backupFileName";
        } catch (\Exception $e) {
            DB::rollBack();
            return "Error creating database backup: " . $e->getMessage();
        }
     

    
}

}
