<?php

namespace App\Data;

use Livewire\Wireable;
use stdClass;

class ClassData implements Wireable
{
    public readonly string $id;
    public readonly string $name;
    public readonly array $students;

    public function __construct(object | array $data)
    {
        if (is_object($data)){
            $data = json_decode(json_encode($data),true);
        }

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->students = $data['students'];
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'students' => $this->students,
        ];

    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
