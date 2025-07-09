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
        $this->user = $user; 
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');

        $usuario = [
            'isAdmin' => $this->isAdmin,
            'isTeacher' => $this->isTeacher,
        ];
        return $usuario;
    }

    public function getUserDataFromID($userID)
    {
        $user = Auth::user()->find($userID);
        $this->user = $user;
        $this->isAdmin = $user->hasRole('Super-Admin');
        $this->isTeacher = $user->hasRole('profesor');
        $grados = null;
        $grupos = null;
        if(!$this->isTeacher){
            $grados =  $this->user->grados()->get()->first()->grado;
            $grupos = $this->user->grupos()->get()->first()->grupo;
        }
        
        
        $userData = [
            'nombre' => $this->user->nombre,
            'apellido' => $this->user->apellido,
            'nuip' => $this->user->nuip,
            'correo' => $this->user->correo,
            'grados' => $grados,
            'grupos' => $grupos
        ];
        return $userData;
    }
}