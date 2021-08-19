<?php

namespace App\CustomClasses;

use App\Models\Bond;
use App\Models\User;

class SessionUser
{
    public ?User $currentUser = null;
    public String $email = '';
    public bool $hasEmployee = false;
    public string $name = '';
    public string $genderArticle = '';
    public bool $hasBond = false;
    public $bonds = null;
    public ?Bond $currentBond = null;

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

            $bondResult = Bond::with('role')->where('employee_id', $employeeResult->id)->/* whereDate('end', '>', Carbon::today())-> */whereNull('terminated_at')/* ->whereNotNull('uaba_checked_at') */->where('impediment', false)->whereHas('role', function ($query) {
                $query->where('name', 'LIKE', 'Coordenador de curso%');
            })->get();
            if (!is_null($bondResult)) {
                $this->hasBond = true;
                $this->bonds = $bondResult;
                $this->currentBond = $bondResult[0];
            }
        }

        //set $currentUTA_id
        $activeUTAs = $this->currentUser->getActiveUTAs;
        $hasUTAs = $activeUTAs->count();
        if ($hasUTAs > 0) {
            $this->currentUTA_id = $activeUTAs->first()->id;
        }
    }

    public function setCurrentBond(int $bondId)
    {
        if ($this->hasBond) {
            //dd($this->bonds);
            //dd($this->bonds->firstWhere('id', $bondId));
            $this->currentBond = $this->bonds->firstWhere('id', $bondId);
        }
    }

    public function hasUTAs()
    {
        $has = $this->currentUser->getActiveUTAs->count();
        return $has > 0 ? true : false;
    }

    public function getActiveUTAs()
    {
        return $this->currentUser->getActiveUTAs;
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
