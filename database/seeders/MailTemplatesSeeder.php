<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MailTemplate;

class MailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Ticket Created - IT Support Notification',
                'slug' => 'ticket-created-it-support',
                'subject' => 'New Ticket Created: {{ticket_id}}',
                'body_html' => $this->getTicketCreatedTemplate(),
                'available_placeholders' => json_encode(['ticket_id', 'ticket_title', 'user_name', 'category', 'urgency', 'description', 'ticket_url']),
                'is_active' => true,
            ],
            [
                'name' => 'Ticket Assigned - IT Support Notification',
                'slug' => 'ticket-assigned-it-support',
                'subject' => 'Ticket Assigned to You: {{ticket_id}}',
                'body_html' => $this->getTicketAssignedTemplate(),
                'available_placeholders' => json_encode(['ticket_id', 'ticket_title', 'assigned_by', 'ticket_url']),
                'is_active' => true,
            ],
            [
                'name' => 'Ticket Resolved - User Notification',
                'slug' => 'ticket-resolved-user',
                'subject' => 'Your Ticket Has Been Resolved: {{ticket_id}}',
                'body_html' => $this->getTicketResolvedTemplate(),
                'available_placeholders' => json_encode(['ticket_id', 'ticket_title', 'user_name', 'resolved_by', 'ticket_url']),
                'is_active' => true,
            ],
            [
                'name' => 'Ticket Reopened - IT Support Notification',
                'slug' => 'ticket-reopened-it-support',
                'subject' => 'Ticket Reopened: {{ticket_id}}',
                'body_html' => $this->getTicketReopenedTemplate(),
                'available_placeholders' => json_encode(['ticket_id', 'ticket_title', 'user_name', 'reopen_count', 'ticket_url']),
                'is_active' => true,
            ],
            [
                'name' => 'New Reply - Notification',
                'slug' => 'new-reply-notification',
                'subject' => 'New Reply on Ticket: {{ticket_id}}',
                'body_html' => $this->getNewReplyTemplate(),
                'available_placeholders' => json_encode(['ticket_id', 'ticket_title', 'reply_by', 'reply_content', 'ticket_url']),
                'is_active' => true,
            ],
            [
                'name' => 'User Registration - Welcome Email',
                'slug' => 'user-registration-welcome',
                'subject' => 'Welcome to {{app_name}}!',
                'body_html' => $this->getUserRegistrationTemplate(),
                'available_placeholders' => json_encode(['user_name', 'app_name', 'login_url']),
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset - Notification',
                'slug' => 'password-reset-notification',
                'subject' => 'Reset Your Password',
                'body_html' => $this->getPasswordResetTemplate(),
                'available_placeholders' => json_encode(['user_name', 'reset_url', 'app_name']),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            MailTemplate::create($template);
        }

        $this->command->info('Mail templates seeded successfully!');
    }

    private function getTicketCreatedTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .ticket-info { background: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0; }
        .ticket-info p { margin: 5px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Ticket Created</h1>
        </div>
        <div class="content">
            <p>Hello IT Support Team,</p>
            <p>A new support ticket has been created and requires your attention.</p>
            
            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> {{ticket_id}}</p>
                <p><strong>Title:</strong> {{ticket_title}}</p>
                <p><strong>Created by:</strong> {{user_name}}</p>
                <p><strong>Category:</strong> {{category}}</p>
                <p><strong>Urgency:</strong> {{urgency}}</p>
                <p><strong>Description:</strong></p>
                <p>{{description}}</p>
            </div>
            
            <a href="{{ticket_url}}" class="button">View Ticket</a>
            
            <p>Please review and assign this ticket as soon as possible.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getTicketAssignedTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .ticket-info { background: #f8f9fa; border-left: 4px solid #f5576c; padding: 15px; margin: 20px 0; }
        .ticket-info p { margin: 5px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #f5576c; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket Assigned to You</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>A ticket has been assigned to you by {{assigned_by}}.</p>
            
            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> {{ticket_id}}</p>
                <p><strong>Title:</strong> {{ticket_title}}</p>
            </div>
            
            <a href="{{ticket_url}}" class="button">View & Respond</a>
            
            <p>Please review the ticket and provide assistance to the user.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getTicketResolvedTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .ticket-info { background: #f8f9fa; border-left: 4px solid #4facfe; padding: 15px; margin: 20px 0; }
        .ticket-info p { margin: 5px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #4facfe; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket Resolved</h1>
        </div>
        <div class="content">
            <p>Hello {{user_name}},</p>
            <p>Great news! Your support ticket has been resolved by our IT team.</p>
            
            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> {{ticket_id}}</p>
                <p><strong>Title:</strong> {{ticket_title}}</p>
                <p><strong>Resolved by:</strong> {{resolved_by}}</p>
            </div>
            
            <p>Please verify that the issue has been resolved to your satisfaction.</p>
            
            <a href="{{ticket_url}}" class="button">Verify Resolution</a>
            
            <p>If the issue persists, you can reopen the ticket from the link above.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getTicketReopenedTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .ticket-info { background: #f8f9fa; border-left: 4px solid #fa709a; padding: 15px; margin: 20px 0; }
        .ticket-info p { margin: 5px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #fa709a; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ticket Reopened</h1>
        </div>
        <div class="content">
            <p>Hello IT Support Team,</p>
            <p>A ticket has been reopened by the user and requires further attention.</p>
            
            <div class="ticket-info">
                <p><strong>Ticket ID:</strong> {{ticket_id}}</p>
                <p><strong>Title:</strong> {{ticket_title}}</p>
                <p><strong>Reopened by:</strong> {{user_name}}</p>
                <p><strong>Reopen Count:</strong> #{{reopen_count}}</p>
            </div>
            
            <a href="{{ticket_url}}" class="button">View Ticket</a>
            
            <p>Please review the ticket and provide additional assistance.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getNewReplyTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .reply-box { background: #f8f9fa; border-left: 4px solid #a8edea; padding: 15px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Reply on Your Ticket</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>{{reply_by}} has replied to ticket <strong>{{ticket_id}}</strong>.</p>
            
            <div class="reply-box">
                <p><strong>Reply:</strong></p>
                <p>{{reply_content}}</p>
            </div>
            
            <a href="{{ticket_url}}" class="button">View & Respond</a>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getUserRegistrationTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{app_name}}!</h1>
        </div>
        <div class="content">
            <p>Hello {{user_name}},</p>
            <p>Welcome to our helpdesk system! Your account has been successfully created.</p>
            <p>You can now log in and submit support tickets whenever you need assistance.</p>
            
            <a href="{{login_url}}" class="button">Login Now</a>
            
            <p>If you have any questions, feel free to reach out to our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function getPasswordResetTemplate(): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .button { display: inline-block; padding: 12px 30px; background: #f5576c; color: #fff; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="content">
            <p>Hello {{user_name}},</p>
            <p>We received a request to reset your password for {{app_name}}.</p>
            
            <a href="{{reset_url}}" class="button">Reset Password</a>
            
            <div class="warning">
                <p><strong>Security Notice:</strong></p>
                <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
                <p>This link will expire in 60 minutes.</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2026 Helpdesk System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
