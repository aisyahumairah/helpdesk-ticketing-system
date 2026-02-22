<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Ticket;
use App\Models\AuditTrail;
use App\Models\SystemSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display Admin Dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_tickets' => Ticket::count(),
            'recent_audits' => AuditTrail::with('user')->latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            // password is optional; leave blank to use the system default (abc123)
            'password'  => 'nullable|string|min:6|confirmed',
            'roles'     => 'nullable|array',
            'roles.*'   => 'exists:roles,name',
        ]);

        $defaultPassword = 'abc123';
        $usingDefault    = empty($request->password);

        $user = User::create([
            'name'                    => $request->name,
            'email'                   => $request->email,
            'password'                => Hash::make($usingDefault ? $defaultPassword : $request->password),
            'require_password_change' => true, // always force change on first login
        ]);

        // Auto-assign USER role; admin can add additional roles from the form
        $roles = $request->input('roles', []);
        if (!in_array('user', $roles)) {
            $roles[] = 'user';
        }
        $user->assignRole($roles);

        $message = $usingDefault
            ? 'User created successfully. Default password (abc123) has been set.'
            : 'User created successfully.';

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Show form to edit user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $roles = $request->input('roles', []);
        if (!in_array('user', $roles)) {
            $roles[] = 'user';
        }
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Soft delete user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Reset user password.
     */
    public function resetUserPassword(User $user)
    {
        $defaultPassword = 'abc123';
        $user->update([
            'password'                => Hash::make($defaultPassword),
            'require_password_change' => true,
        ]);

        return redirect()->back()->with('success', "Password has been reset to the system default. The user will be required to change it on next login.");
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
