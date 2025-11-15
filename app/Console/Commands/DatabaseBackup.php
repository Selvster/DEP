<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a daily database backup using Spatie Laravel Backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء إنشاء نسخة احتياطية من قاعدة البيانات...');

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
                $this->info('تم إنشاء النسخة الاحتياطية بنجاح!');
                return Command::SUCCESS;
            } else {
                $this->error('فشل في إنشاء النسخة الاحتياطية');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('حدث خطأ أثناء إنشاء النسخة الاحتياطية: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

}