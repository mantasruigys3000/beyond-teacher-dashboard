<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Can user create note
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return auth()->check();
    }

    /**
     * Can user delete note
     *
     * @param User $user
     * @param Note $note
     * @return bool
     */
    public function delete(User $user, Note $note)
    {
        return $note->user_id === $user->id;
    }
}
