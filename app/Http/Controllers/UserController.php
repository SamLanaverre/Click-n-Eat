<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Authenticate]
    public function index(): View
    {
        // Vérifier que l'utilisateur est un administrateur
        Gate::authorize('viewAny', User::class);
        
        $users = User::orderBy('role')->orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    #[Authenticate]
    public function create()
    {
        // Vérifier que l'utilisateur est un administrateur
        Gate::authorize('create', User::class);
        
        // Code pour afficher le formulaire de création d'utilisateur
        return view('users.create');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    #[Authenticate]
    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est un administrateur
        Gate::authorize('create', User::class);
        
        // Logique de création d'utilisateur à implémenter
    }

    /**
     * Display the specified resource.
     */
    #[Authenticate]
    public function show(User $user): View
    {
        // Vérifier que l'utilisateur a le droit de voir cet utilisateur
        Gate::authorize('view', $user);
        
        return view('users.show', compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    #[Authenticate]
    public function edit(User $user): View
    {
        // Vérifier que l'utilisateur a le droit de modifier cet utilisateur
        Gate::authorize('update', $user);
        
        return view('users.edit', compact('user'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    #[Authenticate]
    public function update(Request $request, User $user): RedirectResponse
    {
        // Vérifier que l'utilisateur a le droit de modifier cet utilisateur
        Gate::authorize('update', $user);
        
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:client,restaurateur,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Mettre à jour les données de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Vérifier si l'utilisateur peut changer le rôle
        // Un admin peut toujours changer son propre rôle
        // Un admin peut changer le rôle d'un non-admin
        if (auth()->user()->role === 'admin') {
            // Si c'est son propre compte ou si l'utilisateur n'est pas admin
            if (auth()->id() === $user->id || $user->role !== 'admin') {
                $user->role = $validated['role'];
            }
        }
        
        // Mettre à jour le mot de passe si fourni
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('users.index')
            ->with('status', 'L\'utilisateur a été mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    #[Authenticate]
    public function destroy(User $user): RedirectResponse
    {
        // Vérifier que l'utilisateur a le droit de supprimer cet utilisateur
        Gate::authorize('delete', $user);
        
        // Logique de suppression d'utilisateur à implémenter
        return redirect()->route('users.index');
    }
}
