# Implementation Phases – Helpdesk Ticketing System Core

This document breaks down the implementation of the Helpdesk Ticketing System core features into logical phases. Each phase includes the main objectives, key deliverables, and the files to be created or modified (migrations, models, controllers, tests, etc.).

---

## Phase 1: Foundation & Authentication

### Objectives
- Set up project structure and version control
- Implement user authentication and account management

### Deliverables
- User registration (including Gmail OAuth)
- User login/logout
- Password reset/forgot password
- Profile management (name, phone)
- Email as unique identifier
- Password change on first login after reset
- Validation and error handling
- Feature and unit tests for authentication

### Files
- migrations: users table, password resets
- models: User
- controllers: AuthController
- views: registration, login, profile, password reset
- tests: Feature/Auth, Unit/User

---

## Phase 2: Ticket Management Core

### Objectives
- Implement ticket creation, management, and file uploads

### Deliverables
- Ticket model with auto-generated ID (ID00001/YYYY)
- Ticket creation form (with file upload: jpg, jpeg, png, video, pdf)
- Ticket status lifecycle (New, Pending, Closed, Done, Reopen)
- MyTicket view for users (view, reply, reopen, verify, details)
- Filtering/searching by year, status, ticket ID (datatables)
- Uploaded files (morph concept)
- Validation and error handling
- Feature and unit tests for ticket management

### Files
- migrations: tickets, uploaded_files
- models: Ticket, UploadedFile
- controllers: TicketController, UploadedFileController
- views: ticket creation, MyTicket, datatables
- tests: Feature/Ticket, Unit/Ticket

---

## Phase 3: IT Support & Escalation

### Objectives
- Implement IT support workflows, escalation, and reporting

### Deliverables
- IT support dashboard (new tickets, actionable tasks, analytics)
- Ticket assignment, escalation, closure
- Submit tickets on behalf of users
- Ticketing list (create, view, edit), filtering/searching (datatables)
- Ticket report generation (PDF, Excel; filter by date, complaint type)
- Audit trails (view/download, filter by date, event, user)
- Validation and error handling
- Feature and unit tests for IT support workflows

### Files
- migrations: audit_trails
- models: AuditTrail
- controllers: SupportController, AuditTrailController
- views: IT support dashboard, ticketing list, audit trails, reports
- tests: Feature/Support, Unit/AuditTrail

---

## Phase 4: Super Admin & System Management

### Objectives
- Implement super admin management, roles/permissions, and system settings

### Deliverables
- User management (add, edit, delete, reset password)
- Role and permission management (dynamic, Spatie)
- Audit trail viewing/export (filter by date, event, user)
- System settings (email, session timeout, password reset expiry, login attempt limit, lockout duration, email notifications)
- Super admin dashboard (analytics, ticketing list, system config)
- Ticketing report (view/download, filter by date, complaint type)
- Validation and error handling
- Feature and unit tests for admin workflows

### Files
- migrations: roles, permissions, system_settings
- models: Role, Permission, SystemSetting
- controllers: AdminController
- views: admin dashboard, user/role/permission management, system settings
- tests: Feature/Admin, Unit/Role, Unit/Permission

---

## Phase 5: Communication & Notifications

### Objectives
- Implement ticket replies, notifications, and real-time features

### Deliverables
- Ticket reply and threaded conversation (user and IT support)
- File attachments in replies
- Display all replies and attachments
- Real-time notifications (ticket updates, assignments, replies)
- In-system alert notifications (notification icon in topbar)
- Email notifications (configurable)
- Validation and error handling
- Feature and unit tests for communication/notification flows

### Files
- migrations: notifications, replies
- models: Notification, Reply
- controllers: ReplyController, NotificationController
- views: ticket conversation, notifications
- js: real-time notification logic
- tests: Feature/Reply, Feature/Notification, Unit/Notification

---

*Each phase should be completed and tested before proceeding to the next. Update the task list and relevant files as work progresses.*
