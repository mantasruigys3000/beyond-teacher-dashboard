<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\ReportGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmployeeStudentReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $userId, protected $employeeId)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::query()->where('id',$this->userId)->sole();
        ReportGenerator::generateStudentReport($user,$this->employeeId);
    }
}
