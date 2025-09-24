<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;

class UserController extends Controller
{
    public function __construct()
    {
        // Only Admin role can manage users
        $this->middleware(['auth:web']);
        $this->middleware(RoleMiddleware::class . ':Admin');
        // $this->middleware(RoleMiddleware::class . ':Admin');
        // Only users with the 'user.read' permission can view index & show
        $this->middleware('permission:user.read')
            ->only(['index', 'show']);
        // Only users with the 'user.create' permission can see the create form & store
        $this->middleware('permission:user.create')
            ->only(['create', 'store']);
        // Only users with the 'user.update' permission can edit & update
        $this->middleware('permission:user.update')
            ->only(['edit', 'update']);
        // Only users with the 'user.delete' permission can destroy
        $this->middleware('permission:user.delete')
            ->only('destroy');
    }

    public function index()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::pluck('name')->toArray();
        return view('admin.users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,name'
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.form', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
