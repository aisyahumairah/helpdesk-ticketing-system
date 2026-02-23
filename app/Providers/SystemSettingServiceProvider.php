<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SystemSettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     * Loads SMTP / mail config & security settings from the DB into the
     * live Laravel config on every request boot, so the admin's changes
     * in the Settings page take effect without touching .env.
     */
    public function boot(): void
    {
        // Guard against running during migrations (table may not exist yet)
        try {
            if (!Schema::hasTable('system_settings')) {
                return;
            }

            $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');

            // ── SMTP / Mail ─────────────────────────────────────────────────
            if ($settings->get('smtp_host')) {
                Config::set('mail.default', 'smtp');
                Config::set('mail.mailers.smtp.host',       $settings->get('smtp_host'));
                Config::set('mail.mailers.smtp.port',       (int) $settings->get('smtp_port', 587));
                Config::set('mail.mailers.smtp.encryption', $settings->get('smtp_encryption', 'tls'));
                Config::set('mail.mailers.smtp.username',   $settings->get('smtp_username'));
                Config::set('mail.mailers.smtp.password',   $settings->get('smtp_password'));
            }

            if ($settings->get('smtp_from_address')) {
                Config::set('mail.from.address', $settings->get('smtp_from_address'));
                Config::set('mail.from.name',    $settings->get('smtp_from_name', config('app.name')));
            }

            // ── Session lifetime ────────────────────────────────────────────
            if ($settings->get('session_timeout')) {
                Config::set('session.lifetime', (int) $settings->get('session_timeout'));
            }

        } catch (\Exception $e) {
            // Silently skip if DB is unavailable (e.g., during fresh install)
        }
    }

    public function register(): void
    {
        //
    }
}
