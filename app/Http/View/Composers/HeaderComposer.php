<?php

namespace App\Http\View\Composers;

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
            /** @var User $authUser */
            $authUser = auth()->user();

            /** @var array<int, Responsibility> $activeResponsibilities */
            $activeResponsibilities = $this->responsibilityRepository->getActiveResponsibilitiesByUserId($authUser->id);

            /** @var Responsibility $currentResponsibility */
            $currentResponsibility = session('loggedInUser.currentResponsibility');

            $viewParams = [
                'currentUser' => $authUser,
                'gender' => $authUser->employee?->gender,
                'name' => $authUser->employee?->name ?? $authUser->login,
                'activeResponsibilities' => $activeResponsibilities,
                'currentResponsibility' => $currentResponsibility,
            ];

            $view->with($viewParams);
        }
    }
}
