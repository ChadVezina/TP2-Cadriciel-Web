<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * Display a listing of the resource with optional search, ordering and pagination.
     *
     * Query params supported:
     * - search: string to match against user name (LIKE)
     * - per_page: integer (10..100) items per page
     * - name_order: 'asc' or 'desc' to order by name
     * - city_order: 'asc' or 'desc' to order by associated student's city name
     */
    public function index(Request $request): View
    {
        $users = $this->userService->getPaginatedUsers($request->query());

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());

        return redirect()
            ->route('login')
            ->with('success', __('users.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load('etudiant.city', 'articles');

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', __('users.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);

        return redirect()
            ->route('users.index')
            ->with('success', __('users.deleted'));
    }
}
