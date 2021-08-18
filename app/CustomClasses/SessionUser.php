<?php

namespace App\CustomClasses;

use App\Models\Bond;
use App\Models\User;
use App\Models\UserTypeAssignment;
use Carbon\Carbon;

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
    public $activeUserTypeAssignments = null;
    public bool $hasAnyActiveUserTypeAssignment = false;
    public ?UserTypeAssignment $currentUserTypeAssignment = null;

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
    }

    public function setCurrentBond(int $bondId)
    {
        if ($this->hasBond) {
            //dd($this->bonds);
            //dd($this->bonds->firstWhere('id', $bondId));
            $this->currentBond = $this->bonds->firstWhere('id', $bondId);
        }
    }

    public function getActiveUserTypeAssignments()
    {
        $user_id = $this->currentUser->id ?? null;
        $userTypeAssignments = UserTypeAssignment::with('course')
            ->where('user_id', $user_id);
        return $userTypeAssignments;
    }
}
