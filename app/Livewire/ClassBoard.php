<?php

namespace App\Livewire;

use App\Data\ClassData;
use App\Jobs\SendEmployeeStudentReport;
use App\Models\Note;
use App\Services\ReportGenerator;
use App\Services\Wonde;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ClassBoard extends Component
{
    protected array $classes = [];
    protected Collection $employees;
    protected ?ClassData $classData = null;

    public ?string $selectedEmployeeId = null;
    public ?string $selectedClassId = null;

    public string $noteText = "";

    public function __construct()
    {
        $this->employees = collect();
    }

    /**
     * Action for selecting an employee to get their classes
     *
     * @param string $employeeid
     * @return void
     */
    public function selectEmployee(string $employeeId)
    {
        $this->selectedEmployeeId = $employeeId;
        $this->selectedClassId = null;
    }

    /**
     * Action for selecting a class to view its students
     *
     * @param string $classId
     * @return void
     */
    public function selectClass(string $classId)
    {
        $this->selectedClassId = $classId;
    }

    /**
     * Create a new note against this class
     *
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function saveNote()
    {
        $this->authorize('create',Note::class);

        $this->validate([
            'noteText' => ['required','min:1']
        ]);

        $note = new Note();
        $note->id = Str::uuid();
        $note->class_id = $this->selectedClassId;
        $note->content = $this->noteText;
        $note->user_id = auth()->user()->id;
        $note->save();

        // Clear input
        $this->noteText = "";
    }

    public function deleteNote(string $noteUuid)
    {
        $note = Note::query()->where('id',$noteUuid)->first();

        if (! $note instanceof Note)
        {
            return;
        }

        $this->authorize('delete',$note);

        $note->delete();
    }

    /**
     *
     *
     * @return void
     */
    public function generateReport()
    {
        SendEmployeeStudentReport::dispatch(auth()->user()->id,$this->selectedEmployeeId);
    }

    public function render()
    {
        $wonde = new Wonde();

        $employees = $wonde->employees();
        $employees->sortBy('surname');

        $notes = $this->selectedClassId?
            Note::query()->where('class_id',$this->selectedClassId)->get() : [];

        return view('livewire.class-board',[
            'classes' => $this->selectedEmployeeId ? $wonde->employees()[$this->selectedEmployeeId]->classes['data'] : [],
            'employees' => $employees,
            'classData' => $this->selectedClassId ? $wonde->class($this->selectedClassId) : null,
            'notes' => $notes,
        ]);
    }
}
