<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        if (request()->ajax()) {
            return response()->json(['message' => 'User deleted successfully.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            if (request()->ajax()) {
                return response()->json(['message' => 'You cannot deactivate your own account.'], 403);
            }
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        if (request()->ajax()) {
            return response()->json(['message' => "User {$status} successfully.", 'is_active' => $user->is_active]);
        }

        return redirect()->back()->with('success', "User {$status} successfully.");
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

        if (request()->ajax()) {
            return response()->json(['message' => "Password has been reset to the system default."]);
        }

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
        $request->validate([
            'app_name'         => 'nullable|string|max:100',
            'support_email'    => 'nullable|email|max:255',
            'email_enabled'    => 'required|in:0,1',
            'session_timeout'  => 'nullable|integer|min:5|max:1440',
            'login_limit'      => 'nullable|integer|min:1|max:20',
            'lockout_duration' => 'nullable|integer|min:1|max:1440',
            'smtp_host'        => 'nullable|string|max:255',
            'smtp_port'        => 'nullable|integer|in:25,465,587,2525',
            'smtp_encryption'  => 'nullable|in:tls,ssl,',
            'smtp_username'    => 'nullable|string|max:255',
            'smtp_password'    => 'nullable|string|max:255',
            'smtp_from_address'=> 'nullable|email|max:255',
            'smtp_from_name'   => 'nullable|string|max:100',
        ]);

        $data = $request->except('_token');

        // If smtp_password is blank, keep the existing one (don't overwrite with empty)
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }

        foreach ($data as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Apply SMTP settings to the running config immediately
        $this->applySmtpConfig();

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    /**
     * Apply SMTP settings from DB into the live Laravel mail config.
     * Called after saving, and can be called from a ServiceProvider on boot.
     */
    public static function applySmtpConfig()
    {
        $s = SystemSetting::all()->pluck('value', 'key');

        if ($s->get('smtp_host')) {
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host',       $s->get('smtp_host'));
            Config::set('mail.mailers.smtp.port',       (int) $s->get('smtp_port', 587));
            Config::set('mail.mailers.smtp.encryption', $s->get('smtp_encryption', 'tls'));
            Config::set('mail.mailers.smtp.username',   $s->get('smtp_username'));
            Config::set('mail.mailers.smtp.password',   $s->get('smtp_password'));
        }

        if ($s->get('smtp_from_address')) {
            Config::set('mail.from.address', $s->get('smtp_from_address'));
            Config::set('mail.from.name',    $s->get('smtp_from_name', config('app.name')));
        }

        // Apply session lifetime (in minutes)
        if ($s->get('session_timeout')) {
            Config::set('session.lifetime', (int) $s->get('session_timeout'));
        }
    }

    /**
     * Send a test email using the current (saved) SMTP settings.
     */
    public function testEmail(Request $request)
    {
        $request->validate(['to' => 'required|email']);

        // Ensure latest DB settings are applied before testing
        self::applySmtpConfig();

        try {
            $to      = $request->input('to');
            $appName = SystemSetting::where('key', 'app_name')->value('value') ?? config('app.name');

            Mail::raw(
                "Hello,\n\nThis is a test email from {$appName} to verify your SMTP configuration is working correctly.\n\nIf you received this, your mail settings are configured correctly.",
                function ($message) use ($to, $appName) {
                    $message->to($to)
                            ->subject("[{$appName}] SMTP Test Email");
                }
            );

            return response()->json([
                'success' => true,
                'message' => "Test email sent successfully to {$to}. Please check your inbox.",
            ]);
        } catch (\Exception $e) {
            Log::error('SMTP test failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage(),
            ], 422);
        }
    }
}
