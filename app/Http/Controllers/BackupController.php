<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class BackupController extends Controller
{
    /**
     * Display a listing of backup files
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $backupPath = storage_path('app/private/Laravel');
            $backups = collect();
            
            if (is_dir($backupPath)) {
                $zipFiles = glob($backupPath . '/*.zip');
                $sqlFiles = glob($backupPath . '/*.sql');
                $files = array_merge($zipFiles, $sqlFiles);
                
                foreach ($files as $file) {
                    $backups->push([
                        'filename' => basename($file),
                        'path' => $file,
                        'size' => filesize($file),
                        'created_at' => Carbon::createFromTimestamp(filemtime($file)),
                    ]);
                }
            }
            
            return DataTables::of($backups)
                ->addColumn('filename', function ($backup) {
                    return $backup['filename'];
                })
                ->addColumn('created_at_formatted', function ($backup) {
                    return $backup['created_at']->format('Y-m-d H:i');
                })
                ->addColumn('size_formatted', function ($backup) {
                    return number_format($backup['size'] / 1024, 2) . ' KB';
                })
                ->addColumn('actions', function ($backup) {
                    return view('backups.partials.actions', compact('backup'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return view('backups.index');
    }

    /**
     * Create a new backup
     */
    public function create()
    {
        try {
            // Use direct mysqldump approach
            $backupPath = storage_path('app/private/Laravel');
            if (!is_dir($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filename = 'نسخة_احتياطية_' . date('Y-m-d') . '_' . rand(1000, 9999) . '.sql';
            $filePath = $backupPath . '/' . $filename;
            
            // Build mysqldump command
            $command = sprintf(
                'C:/wamp64/bin/mysql/mysql9.1.0/bin/mysqldump.exe --host=127.0.0.1 --port=3306 --user=root --password= --single-transaction --routines --triggers %s > %s',
                config('database.connections.mysql.database'),
                escapeshellarg($filePath)
            );
            
            // Execute command
            exec($command, $output, $exitCode);
            
            if ($exitCode === 0 && file_exists($filePath)) {
                return redirect()->route('backups.index')->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
            } else {
                return redirect()->route('backups.index')->with('error', 'فشل في إنشاء النسخة الاحتياطية - كود: ' . $exitCode);
            }
        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'حدث خطأ أثناء إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file
     */
    public function download(Request $request)
    {
        $filename = $request->get('filename');
        
        if (!$filename) {
            return response()->json(['error' => 'اسم الملف مطلوب'], 400);
        }
        
        $filePath = storage_path('app/private/Laravel/' . $filename);
        
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'الملف غير موجود'], 404);
        }
        
        return response()->download($filePath);
    }

    /**
     * Delete a backup file
     */
    public function destroy(Request $request)
    {
        $filename = $request->get('filename');
        
        if (!$filename) {
            return redirect()->route('backups.index')->with('error', 'اسم الملف مطلوب');
        }
        
        $filePath = storage_path('app/private/Laravel/' . $filename);
        
        if (!file_exists($filePath)) {
            return redirect()->route('backups.index')->with('error', 'الملف غير موجود');
        }
        
        if (unlink($filePath)) {
            return redirect()->route('backups.index')->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        } else {
            return redirect()->route('backups.index')->with('error', 'فشل في حذف النسخة الاحتياطية');
        }
    }

    /**
     * Get backup statistics
     */
    public function stats(): JsonResponse
    {
        $backupPath = storage_path('app/backups');
        $stats = [
            'total_files' => 0,
            'total_size' => 0,
            'latest_backup' => null,
            'oldest_backup' => null,
        ];
        
        if (is_dir($backupPath)) {
            $files = glob($backupPath . '/backup_*.sql');
            $stats['total_files'] = count($files);
            
            if (count($files) > 0) {
                $fileTimes = array_map('filemtime', $files);
                $fileSizes = array_map('filesize', $files);
                
                $stats['total_size'] = array_sum($fileSizes);
                $stats['latest_backup'] = Carbon::createFromTimestamp(max($fileTimes));
                $stats['oldest_backup'] = Carbon::createFromTimestamp(min($fileTimes));
            }
        }
        
        return response()->json($stats);
    }
}