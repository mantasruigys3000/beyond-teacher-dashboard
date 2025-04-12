<?php

namespace App\Services;

use App\Data\ClassData;
use App\Data\EmployeeData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Wonde\Client;
use Wonde\Endpoints\Schools;
use Wonde\Exceptions\InvalidTokenException;

class Wonde
{
    public Client $client;

    private float $cacheTime = 60;

    /**
     * @throws InvalidTokenException
     */
    public function __construct()
    {
        $this->client = new Client(config('services.wonde.key'));
        $this->cacheTime = config('services.wonde.cache_seconds');
    }

    /**
     * @return Schools
     */
    public function school() : Schools
    {
        return $this->client->school(config('services.wonde.school_id'));
    }

    /**
     * Return all employees for school
     *
     * @return Collection
     */
    public function employees() : Collection
    {
        return collect(Cache::remember('employees',$this->cacheTime,function(){
            // Get all employees, convert them to local DTO
            $employees = [];
            $employeesIterator = $this->school()->employees->all(['classes,employment_details'],['has_class' => true]);
            foreach ($employeesIterator as $item){
                $employees[$item->id] = new EmployeeData($item);
            }
            return $employees;
        }));
    }

    /**
     * Get class and student information
     *
     * @param string $classId
     * @return ClassData
     */
    public function class(string $classId) : ClassData
    {
        return Cache::remember('class-'.$classId,$this->cacheTime,function() use ($classId) {
            return new ClassData($this->school()->classes->get($classId,['students']));
        });
    }

}
