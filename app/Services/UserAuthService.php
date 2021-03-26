<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

/**
 * This servis is used to store the autentication and roles logics
 */
class UserAuthService
{
    /**
     * Returns true if the user is autenticated, false otherwise
     */
    public function userIsAuthenticated()
    {
        if (!Auth::check()) {

            return false;
        }
        return true;
    }

    /**
     * Returns true if the user is a writer and is not an editor, false otherwise
     */
    public function userIsWriterOnly()
    {

        if ($this->userIsWriter() && !$this->userIsEditor()) {
            return true;
        }
        return false;
    }

    /**
     * Returns true if the user has the writer role, false otherwise
     */
    public function userIsWriter()
    {
        return $this->userHasRole('writer');
    }

    /**
     * Returns true if the user has the editor role, false otherwise
     */
    public function userIsEditor()
    {
        return $this->userHasRole('editor');
    }

    /**
     * Returns true if the user has the specified role, false otherwise
     */
    public function userHasRole($role)
    {
        if (!$this->userIsAuthenticated()) {
            return false;
        }

        $user = auth()->user();

        if ($user->hasRole($role)) {
            return true;
        }

        return false;
    }
}
