<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PythonController extends Controller
{
    /**
     * Run a single Python script and capture output/errors
     */
    private function runPythonScript($scriptPath)
    {
        $absPath = realpath($scriptPath);

        if (!$absPath || !file_exists($absPath)) {
            return [
                'script' => basename($scriptPath),
                'status' => 'error',
                'message' => 'File not found'
            ];
        }

        // Force UTF-8 encoding for Windows to handle emojis
        $command = "set PYTHONIOENCODING=utf-8 && python \"$absPath\"";

        // Run the script and capture stdout + stderr
        $output = shell_exec($command . " 2>&1");

        // Determine status
        $status = empty(trim($output))
            ? 'no_output'
            : (stripos($output, 'error') !== false ? 'failed' : 'success');

        return [
            'script' => basename($absPath),
            'status' => $status,
            'output' => $output ?: 'No output from script'
        ];
    }

    /**
     * Run multiple scripts and return JSON
     */
    public function runScripts(Request $request)
    {
        // Specify your Python scripts here (adjust paths)
        $scripts = [
            base_path('public/python/test_connections.py'),
            base_path('public/python/mysql_sync_complete.py'),
            base_path('public/python/script.py'),
            base_path('public/python/script_1.py'),
            base_path('public/python/script_2.py'),
        ];

        $results = [];
        foreach ($scripts as $script) {
            $results[] = $this->runPythonScript($script);
        }

        return response()->json($results);
    }
}
