<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $users = User::search($request->get('query'), function ($meilisearch, $query, $options) use ($request) {
            if (!empty($request->get('role'))) {
                $options['filter'] = 'role = '.$request->get('role');
            }

            $options['sort'] = ['updated_at:desc'];

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Users/Index', [
            'can' => [
                'create_user' => Auth::user()->can('create', User::class),
            ],
            'users' => $users->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'can' => [
                        'update_role' => Auth::user()->can('updateRole', $user),
                        'update_status' => Auth::user()->can('updateStatus', $user),
                        'delete_user' => Auth::user()->can('delete', $user),
                    ],
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);

        return Inertia::render('Users/UserRegistrationForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterUserRequest $request)
    {
        Gate::authorize('create', User::class);

        User::create([
            ...$request->validated(),
            'status' => 'active',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back();
        }

        throw ValidationException::withMessages([
            'email' => [$status],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (Gate::none(['updateRole', 'updateStatus'], $user)) abort(403);

        $user->load('lastModifiedBy');

        return Inertia::render('Users/ManageUser', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Gate::none(['updateRole', 'updateStatus'], $user)) abort(403);

        $user->fill($request->validated())->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('message', $user->email.'\'s account has been deleted.');
    }
}
