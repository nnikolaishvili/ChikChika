<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};

class VerifyEmailController extends Controller
{
    /**
     * Email verification view
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function view(Request $request): View|RedirectResponse
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return view('auth.verify-email');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Verify user's email
     *
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('dashboard');
    }

    /**
     * Send email verification notification
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
