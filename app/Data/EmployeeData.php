<?php

namespace App\Data;

use Livewire\Wireable;
use stdClass;

readonly class EmployeeData implements Wireable
{
    public string $id;
    public string $title;
    public string $forename;
    public string $surname;
    public bool $current;
    public array $classes;

    /**
     * Employee class data
     *
     * @param object $data
     */
    public function __construct(object | array $data)
    {
        if (is_object($data)){
            $data = json_decode(json_encode($data),true);
        }

        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->forename = $data['forename'];
        $this->surname = $data['surname'];
        $this->classes = $data['classes'];

    }

    /**
     * Get full name with title as single string
     *
     * @return string
     */
    public function getFullName() : string
    {
        return sprintf('%s %s %s',$this->title,$this->forename,$this->surname);
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'forename' => $this->forename,
            'surname' => $this->surname,
            'classes' => $this->classes,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
