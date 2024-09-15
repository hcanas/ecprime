<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserActivity;
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
            $options['limit'] = 15;

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Users/Index', [
            'can' => [
                'create_user' => Auth::user()->can('create', User::class),
            ],
            'users' => $users->paginate($request->get('per_page', 15))
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

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return redirect()->route('users.index')->with([
                'message' => [
                    'content' => 'A registration confirmation email has been sent to '.$request->get('email').'.',
                    'type' => 'success',
                ],
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [$status],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Request $request)
    {
        if (Gate::none(['updateRole', 'updateStatus'], $user)) abort(403);

        $activities = UserActivity::search($request->get('query'), function ($driver, $query, $options) use ($user) {
            $options['filter'] = 'user_id = ' . $user->id;
            $options['sort'] = ['created_at:desc'];

            return $driver->search($query, $options);
        });

        $user->load('lastModifiedBy');

        return Inertia::render('Users/ManageUser', [
            'user' => $user,
            'activities' => $activities->get()->map(fn ($activity) => [
                'details' => $activity->details,
                'created_at' => $activity->created_at,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Gate::none(['updateRole', 'updateStatus'], $user)) abort(403);

        $user->fill($request->validated());

        if ($user->isDirty('role')) {
            $message = [
                'content' => 'Role has been updated.',
                'type' => 'success',
            ];
        } elseif ($user->isDirty('status')) {
            $message = [
                'content' => 'Status has been updated.',
                'type' => 'success',
            ];
        }

        $user->save();

        return back()->with('message', $message ?? null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if (count($user->activities)) {
            return back()->with('message', [
                    'content' => 'Failed to delete user account.',
                    'type' => 'error',
                ]);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('message', [
                'content' => $user->email.'\'s account has been deleted.',
                'type' => 'error',
            ]);
    }
}
