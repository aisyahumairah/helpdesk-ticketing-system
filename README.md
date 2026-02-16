# Helpdesk Ticketing System

A robust, enterprise-grade IT Support Helpdesk Ticketing System built with Laravel 12. This system streamlines ticket management, facilitates communication between users and IT support, and provides comprehensive administrative controls.

## 🚀 Key Features

### 👥 User Roles & Dashboards
- **User**: Submit tickets, track progress, verify resolutions, and manage personal profile.
- **IT Support**: Manage ticket lifecycle, communicate with users, and access performance analytics.
- **Super Admin**: Full system control including user/role management, audit trails, and system configurations.

### 🎫 Ticket Management
- **Smart Ticket IDs**: Automatically generated unique IDs in the format `ID00001/YYYY`.
- **Rich Ticket Creation**: Support for urgency levels (Low to Critical), complaint types (Software, Hardware, Network, System), and multi-file attachments (Images, Video, PDF).
- **Lifecycle Tracking**: Clear status transitions: `New` ➔ `Pending` ➔ `Closed` / `Done` / `Reopen`.
- **Escalation Support**: Unlimited support layers for complex issue resolution.

### 💬 Communication & Notifications
- **Real-time Interaction**: Instant ticket updates and reply notifications.
- **In-System Alerts**: Notification center in the topbar for all users.
- **Audit Trails**: Complete logging of all critical actions for transparency and security.

### 🛠️ Administrative Power
- **Dynamic Access Control**: Manage roles and permissions on the fly using Spatie.
- **System Configuration**: Fine-tune session timeouts, password policies, and email notification settings.
- **Advanced Reporting**: Export ticket data and audit logs to PDF or Excel.

## 💻 Technology Stack

- **Backend**: [Laravel 12](https://laravel.com/)
- **Database**: PostgreSQL / MySQL / SQLite
- **Security**: [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- **Frontend**: Gentelella Admin Template (Bootstrap 5) & Blade Templates
- **Testing**: [Pest PHP](https://pestphp.com/)
- **Server**: Apache with Let's Encrypt SSL

## 📦 Installation & Setup

1. **Clone & Enter Directory**
   ```bash
   git clone <repository-url>
   cd helpdesk
   ```

2. **Backend Dependencies**
   ```bash
   composer install
   ```

3. **Frontend Dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Update `.env` with your database and mail server credentials.*

5. **Database Setup**
   ```bash
   php artisan migrate --seed
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

## 📖 Documentation
For detailed requirements and specifications, please refer to the `docs/dev/` directory:
- [Product Requirements Document (PRD)](docs/dev/PRD.md)
- [User Requirements Specification (URS)](docs/dev/URS.md)
- [Technology Stack Details](docs/dev/TECH_-_STACK.md)

## 📝 License
This project is open-source and available under the [MIT license](LICENSE).
https://opensource.org/licenses/MIT).
