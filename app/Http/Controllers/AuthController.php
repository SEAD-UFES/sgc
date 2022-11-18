<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Responsibility;
use App\Models\User;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function __construct(private ResponsibilityRepository $responsibilityRepository)
    {
    }

    /**
     * @return RedirectResponse|View
     */
    public function getLoginForm(): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    /**
     * @param LoginRequest $request
     *
     * @return RedirectResponse
     *
     * @throws UnauthorizedHttpException
     */
    public function authenticate(LoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt(['login' => $request->login, 'password' => $request->password, 'active' => true])) {
            $request->session()->regenerate();

            /**
             * @var User $loggedUser
             */
            $loggedUser = User::where('login', $request->validated('login'))->first();

            $firstResponsibility = $this->responsibilityRepository->getFirstActiveResponsibilityByUserId($loggedUser->getAttribute('id'));

            session(['loggedInUser.currentResponsibility' => $firstResponsibility]);

            return redirect()->route('home');
        }

        abort(401);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.form');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function switchCurrentResponsibility(Request $request): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        /** @var int $newResponsibilityId */
        $newResponsibilityId = $request->get('responsibility_id');

        /** @var Responsibility $newResponsibility */
        $newResponsibility = $this->responsibilityRepository->getActiveResponsibilityByIdAssertUserId($newResponsibilityId, $currentUser->id);

        session(['loggedInUser.currentResponsibility' => $newResponsibility]);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function currentPasswordEdit(): View
    {
        $user = auth()->user();
        return view('user.currentPasswordEdit', compact('user'));
    }
}
