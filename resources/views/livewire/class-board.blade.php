<div>
    <div class="flex flex-row items-center">
    </div>
    <div class="flex flex-wrap mx-10 h-48 max-h-48 overflow-scroll">
        @foreach($employees as $employee)
            <div wire:key="{{$employee->id}}" wire:click="selectEmployee('{{$employee->id}}')"  class=" bg-gray-400 mx-5 my-2 rounded-md flex flex-row items-center py-2 w-1/5 hover:cursor-pointer hover:bg-gray-300 hover:opacity-75 ">
                <div class="w-12 h-12 rounded-full bg-white mx-4">

                </div>
                <div class="px-4"> {{$employee->title}} {{$employee->forename }} {{$employee->surname }}</div>
            </div>
        @endforeach
    </div>

    <h1 class="text-center">{{ $classes? 'Classes' : 'Select a teacher to view their classes' }}</h1>

    @if(count($classes) > 0)
        <div class="flex flex-row justify-center">
            <button class="text-center rounded-md bg-red-500 px-6 py-3" wire:click="generateReport"> Send me a report </button>
        </div>
    @endif

    <div class="flex flex-wrap mx-10 my-10">
    @foreach($classes as $class)
            <div wire:click="selectClass('{{$class['id']}}')" wire:key="{{ $class['id']}} " class="bg-blue-500 mx-5 my-2 rounded-md hover:bg-blue-400 w-1/5 text-center font-bold hover:cursor-pointer">
                <div> {{ $class['name'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-row">
        <div class="w-1/2">
            @if(isset($classData))
                <h1 class="text-center">Students</h1>
                <div class="flex flex-wrap my-10 mx-10">
                    @foreach($classData->students['data'] as $student)
                        <div wire:key="{{ $student['id']}} " class="bg-blue-500 mx-5 my-2 rounded-md  w-1/5 text-center font-bold ">
                            <div> {{ $student['forename'] }} {{ $student['surname'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            @if(isset($classData))
                <div>
                    <h1> Notes: </h1>

                    <div class="flex flex-col">
                        <textarea wire:model="noteText" placeholder="Note..."></textarea>
                        <div>@error('noteText') {{ $message }} @enderror</div>
                        <button  wire:click.prevent="saveNote" >Create note</button>
                    </div>

                    <div>
                        @foreach($notes as $note)
                            <div>{{$note->content}}</div>
                            <button wire:click="deleteNote('{{$note->id}}')">delete note</button>
                        @endforeach
                    </div>

                </div>
            @endif
        </div>
    </div>
</div>


