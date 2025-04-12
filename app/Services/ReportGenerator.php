<?php

namespace App\Services;

use App\Data\EmployeeData;
use App\Models\User;
use App\Notifications\StudentReportGenerated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReportGenerator
{
    /**
     * Expensive function with a potential of multiple API calls
     * Call this from a queue
     *
     * @param User $user
     * @param string $employeeId
     * @return void
     */
    public static function generateStudentReport(User $user,string $employeeId)
    {
        $wonde = new Wonde();
        $data = [];

        $employeeData = $wonde->employees()[$employeeId];
        foreach ($employeeData->classes['data'] as $class)
        {
            $students = [];
            foreach ($wonde->class($class['id'])->students['data'] as $student){
                $students[] = sprintf('%s %s',$student['forename'],$student['surname']);
            }

            $data[] = [
                $class['name'],
                ...$students
            ];
        }

        $filename = sprintf("Student Class Report %s for %s.csv",Carbon::now()->format('Y-m-d H-i-s'),$employeeData->getFullName());
        $path = Storage::path($filename);
        $file = fopen($path,'w');

        foreach ($data as $row){
            fputcsv($file,$row);
        }

        fclose($file);

        $user->notify(new StudentReportGenerated($path));

    }
}
