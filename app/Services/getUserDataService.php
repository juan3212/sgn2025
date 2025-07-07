<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;


class getUserDataService
{
    public $user;
    public $isAdmin;
    public $isTeacher;
    public function __construct()
    {
        //
    }

    public function  getUserDataFromAuth()
    {
        $user = Auth::user();
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');

        return $this->user;
    }

    public function getUserDataFromID($userID)
    {
        $user = Auth::user()->find($userID);
        $this->user = $user; // Almacenar el objeto usuario para usos potenciales
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');

        return $this->user;
    }
}