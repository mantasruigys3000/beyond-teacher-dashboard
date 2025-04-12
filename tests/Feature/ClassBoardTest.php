<?php

use App\Jobs\SendEmployeeStudentReport;
use App\Livewire\ClassBoard;
use App\Models\Note;
use App\Models\User;
use App\Notifications\StudentReportGenerated;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseCount;

test('Can make a note against a class',function(){

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ClassBoard::class)
        ->set('noteText','Note Content')
        ->set('selectedClassId','A1145866588')
        ->runAction('saveNote')
        ->assertHasNoErrors();

    assertDatabaseCount('notes',1);
    $note = Note::query()->first();

    expect($note->content)->toEqual('Note Content');
    expect($note->user_id)->toEqual($user->getKey());
    expect($note->class_id)->toEqual('A1145866588');
});

test('cannot make a note with empty content', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ClassBoard::class)
        ->set('noteText','')
        ->set('selectedClassId','A1145866588')
        ->runAction('saveNote')
        ->assertHasErrors(['noteText']);

    assertDatabaseCount('notes',0);
});

test('a user cannot delete another users note', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $note = Note::factory()->forUser($user1)->create();
    expect($note->user_id)->toEqual($user1->getKey());

    $user = User::factory()->create();
    Livewire::actingAs($user2)
        ->test(ClassBoard::class)
        ->runAction('deleteNote',$note->id)
        ->assertForbidden();

});

test('generating a student report dispatches a job to the queue', function () {
    Bus::fake();

    $user = User::factory()->create();
    Livewire::actingAs($user)
        ->test(ClassBoard::class)
        ->set('selectedEmployeeId','A500460806')
        ->runAction('generateReport');

    Bus::assertDispatched(SendEmployeeStudentReport::class);
});

test('generating a student report dispatches a job to send an email notification', function () {
    Notification::fake();

    $user = User::factory()->create();
    Livewire::actingAs($user)
        ->test(ClassBoard::class)
        ->set('selectedEmployeeId','A500460806')
        ->runAction('generateReport');

    Notification::assertSentTo($user,StudentReportGenerated::class);
});
