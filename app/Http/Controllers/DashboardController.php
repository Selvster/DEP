<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        // Get the latest 3 activities
        $recentActivities = Activity::with(['causer'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get last backup date
        $lastBackupDate = $this->getLastBackupDate();

        return view('dashboard', compact('recentActivities', 'lastBackupDate'));
    }

    /**
     * Get the last backup date from the actual backup file
     */
    private function getLastBackupDate()
    {
        $backupPath = storage_path('app/private/Laravel');
        
        if (!is_dir($backupPath)) {
            return null;
        }
        
        // Get all SQL files in the backup directory
        $sqlFiles = glob($backupPath . '/*.sql');
        
        if (empty($sqlFiles)) {
            return null;
        }
        
        // Get the most recently modified file
        $latestFile = null;
        $latestTime = 0;
        
        foreach ($sqlFiles as $file) {
            $fileTime = filemtime($file);
            if ($fileTime > $latestTime) {
                $latestTime = $fileTime;
                $latestFile = $file;
            }
        }
        
        if ($latestFile) {
            return \Carbon\Carbon::createFromTimestamp($latestTime);
        }
        
        return null;
    }
}