<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateProgramStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'program:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically updates the statuses of blood donation programs based on current system time compared to start/end dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting blood donation program status updates...');
        
        $now = now();
        $updatedCount = 0;

        // Retrieve all programs that are approved, ongoing, or ended and not soft-deleted
        // Note: Status 1 (Draft/Pending Approval) and Status 4 (Canceled) are left intact
        $programs = DB::table('ChuongTrinhHienMau')
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3, 5])
            ->get();

        foreach ($programs as $prog) {
            $startTime = Carbon::parse($prog->ThoiGianBatDau);
            $endTime = Carbon::parse($prog->ThoiGianKetThuc);
            
            $newStatus = $prog->TrangThai;
            $newDangDienRa = $prog->DangDienRa;

            if ($now->greaterThan($endTime)) {
                $newStatus = 5; // Đã kết thúc
                $newDangDienRa = 0;
            } elseif ($now->lessThan($startTime)) {
                $newStatus = 2; // Sắp diễn ra
                $newDangDienRa = 0;
            } else {
                $newStatus = 3; // Đang diễn ra
                $newDangDienRa = 1;
            }

            // Only trigger database update if values actually changed
            if ($newStatus !== $prog->TrangThai || $newDangDienRa !== $prog->DangDienRa) {
                DB::table('ChuongTrinhHienMau')
                    ->where('Id', $prog->Id)
                    ->update([
                        'TrangThai' => $newStatus,
                        'DangDienRa' => $newDangDienRa,
                        'updated_at' => now(),
                    ]);
                
                $updatedCount++;
                
                $this->line(sprintf(
                    'Updated program #%d ("%s"): Status %d -> %d, Active %d -> %d',
                    $prog->Id,
                    $prog->TenChuongTrinh,
                    $prog->TrangThai,
                    $newStatus,
                    $prog->DangDienRa,
                    $newDangDienRa
                ));
            }
        }

        $logMsg = sprintf('Completed program status updates. Total updated programs: %d', $updatedCount);
        $this->info($logMsg);
        Log::info($logMsg);

        return Command::SUCCESS;
    }
}
