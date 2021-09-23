<?php

namespace App\CustomClasses;

use App\Models\User;

class SessionUser
{
    public ?User $currentUser = null;
    public String $email = '';
    public bool $hasEmployee = false;
    public string $name = '';
    public string $genderArticle = '';

    //permission system
    public $currentUTA_id = null;

    public function __construct(User $user)
    {
        $this->currentUser = $user;
        $this->email = $user->email;
        $this->name = $user->email;
        $this->genderArticle = 'o(a)';

        $employeeResult = $user->employee;
        if (!is_null($employeeResult)) {
            $this->hasEmployee = true;
            $this->name = $employeeResult->name;
            if ($employeeResult->gender != null)
                $this->genderArticle = (($employeeResult->gender->name) === 'Masculino') ? 'o' : 'a';
        }

        //set $currentUTA_id
        $activeUTAs = $this->currentUser->getActiveUTAs;
        $hasUTAs = $activeUTAs->count();
        if ($hasUTAs > 0) {
            $this->currentUTA_id = $activeUTAs->first()->id;
        }
    }

    public function hasUTAs()
    {
        $has = $this->currentUser->getActiveUTAs()->get()->count();
        return $has > 0 ? true : false;
    }

    public function getActiveUTAs()
    {
        return $this->currentUser->getActiveUTAs()->get();
    }

    public function getCurrentUTA()
    {
        $user_type_assignment = $this
            ->currentUser
            ->getActiveUTAs()
            ->where('user_type_assignments.id', $this->currentUTA_id)
            ->first();
        return $user_type_assignment;
    }

    public function setCurrentUTA(int $user_type_assignment_id)
    {
        $user_type_assignment = $this
            ->currentUser
            ->getActiveUTAs()
            ->where('user_type_assignments.id', $user_type_assignment_id)
            ->firstOrFail();
        $this->currentUTA_id = $user_type_assignment->id;
    }
}
