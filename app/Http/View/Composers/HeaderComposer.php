<?php

namespace App\Http\View\Composers;

use App\Enums\Genders;
use App\Models\Employee;
use App\Models\Responsibility;
use App\Models\User;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HeaderComposer
{
    public function __construct(private ResponsibilityRepository $responsibilityRepository)
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            /** @var User */
            $authUser = auth()->user();

            /** @var array<int, Responsibility> */
            $activeResponsibilities = $this->responsibilityRepository->getActiveResponsibilitiesByUserId($authUser->id);

            /** @var Responsibility */
            $currentResponsibility = session('loggedInUser.currentResponsibility');

            $viewParams = [
                'currentUser' => $authUser,
                'gender' => $authUser->employee?->gender,
                'name' => $authUser->employee?->name ?? $authUser->email,
                'activeResponsibilities' => $activeResponsibilities,
                'currentResponsibility' => $currentResponsibility,
            ];

            $view->with($viewParams);
        }
    }
}
