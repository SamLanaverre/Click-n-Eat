<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Authentifier l'utilisateur
            $request->authenticate();
            
            // Régénérer la session pour éviter les attaques de fixation de session
            $request->session()->regenerate();
            
            // Ajouter le jeton CSRF à la session
            $request->session()->put('_token', csrf_token());
            
            // Rediriger vers le dashboard approprié
            return redirect()->route(Auth::user()->getDashboardRoute());
        } catch (\Exception $e) {
            // En cas d'erreur, rediriger vers la page de connexion avec un message d'erreur
            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Une erreur s\'est produite lors de la connexion. Veuillez réessayer.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}