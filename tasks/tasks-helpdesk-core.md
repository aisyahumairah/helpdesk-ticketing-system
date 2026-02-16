# Tasks – Helpdesk Ticketing System Core Features

This document outlines the high-level tasks and detailed sub-tasks required to implement the core features of the Helpdesk Ticketing System as described in the PRD.

---

## Parent Tasks & Sub-Tasks

### 0.0 Create feature branch
- [ ] Create a new git branch for the helpdesk core feature development

### 1.0 Implement user authentication and account management
- [ ] Set up user registration (including Gmail OAuth signup)
- [ ] Implement user login and logout
- [ ] Implement password reset and forgot password functionality
- [ ] Implement profile management (edit name, phone number)
- [ ] Enforce email as unique user identifier
- [ ] Implement password change on first login after reset
- [ ] Add in-system alert notifications (notification icon in topbar)
- [ ] Add dashboard for users (own tickets, actionable tasks, analytics, ticket history)
- [ ] Add validation and error handling for all account actions
- [ ] Write feature and unit tests for authentication flows

### 2.0 Implement ticket creation and management
- [ ] Design ticket model and migration (category, urgency, description, status, attachments, auto-generated ticket ID: ID00001/YYYY)
- [ ] Design uploaded_files model and migration (morph concept: id, fileable_type, fileable_id, filename, filepath, filetype)
- [ ] Implement ticket creation form (with file upload: jpg, jpeg, png, video, pdf)
- [ ] Implement ticket status lifecycle (New, Pending, Closed, Done, Reopen)
- [ ] Implement ticket verification and reopening by users
- [ ] Implement "MyTicket" view for users (view, reply, reopen, verify, view details/actions/replies)
- [ ] Implement filtering/searching by year, status, and ticket ID (datatables)
- [ ] Add validation and error handling for ticket actions
- [ ] Write feature and unit tests for ticket management

### 3.0 Implement IT support ticket handling and escalation
- [ ] Implement IT support dashboard (notified of new tickets, actionable tasks, ticket analytics)
- [ ] Implement ticket assignment and acceptance (self-assign)
- [ ] Implement ticket escalation to higher support layers (unlimited)
- [ ] Implement ticket closure and resolution workflow
- [ ] Implement IT support ability to submit tickets on behalf of users
- [ ] Implement ticket report generation (PDF, Excel; filter by date, complaint type)
- [ ] Implement ticketing list (create on behalf, view, edit), filtering/searching by year, status, ticket ID (datatables)
- [ ] Implement audit trails (view/download, filter by date, event, user; default: current date)
- [ ] Add validation and error handling for IT support actions
- [ ] Write feature and unit tests for IT support workflows

### 4.0 Implement super admin user, role, and system management
- [ ] Implement user management (add, edit, delete, reset password)
- [ ] Implement role and permission management (using Spatie; dynamic assignment)
- [ ] Implement audit trail viewing and export (filter by date, event, user; default: current date)
- [ ] Implement system settings management (email, session timeout, password reset expiry, login attempt limit, lockout duration, email notifications)
- [ ] Enforce password change on first login after admin reset
- [ ] Implement super admin dashboard (ticket analytics, ticketing list, system configurations)
- [ ] Implement ticketing list (create on behalf, view, edit, delete), filtering/searching by year, status, ticket ID (datatables)
- [ ] Implement ticketing report (view/download, filter by date, complaint type)
- [ ] Add validation and error handling for admin actions
- [ ] Write feature and unit tests for admin workflows

### 5.0 Implement communication and notification features
- [ ] Implement ticket reply and threaded conversation (user and IT support)
- [ ] Implement file attachments in replies (pdf, images, videos)
- [ ] Display all replies and attachments in ticket view
- [ ] Implement real-time notifications for ticket updates, assignments, and replies
- [ ] Implement in-system alert notifications (notification icon in topbar)
- [ ] Implement email notifications for ticket updates (configurable)
- [ ] Add validation and error handling for communication features
- [ ] Write feature and unit tests for communication and notification flows

---

## Relevant Files
- app/Models/User.php – User model
- app/Models/Ticket.php – Ticket model (auto-generated ticket ID)
- app/Models/UploadedFile.php – Uploaded files model (morph concept)
- app/Models/Notification.php – Notification model (to be created)
- app/Models/AuditTrail.php – Audit trail model (to be created)
- app/Http/Controllers/AuthController.php – Authentication logic (to be created)
- app/Http/Controllers/TicketController.php – Ticket management (to be created)
- app/Http/Controllers/SupportController.php – IT support workflows (to be created)
- app/Http/Controllers/AdminController.php – Admin management (to be created)
- app/Http/Controllers/ReplyController.php – Ticket replies (to be created)
- app/Http/Controllers/NotificationController.php – Notification logic (to be created)
- app/Http/Controllers/AuditTrailController.php – Audit trail logic (to be created)
- app/Http/Controllers/UploadedFileController.php – Uploaded files logic (to be created)
- database/migrations/ – Database schema changes (including uploaded_files)
- resources/views/ – Blade templates for all UI (including dashboards, datatables, notifications)
- resources/js/app.js – Frontend logic (including real-time notifications)
- resources/css/app.css – Frontend styling
- tests/Feature/ – Feature tests
- tests/Unit/ – Unit tests

---

## Notes
- All tasks and sub-tasks must follow the Task List Management Guidelines in /docs/dev/process-tasks-list.md.
- Update the task list and "Relevant Files" section as work progresses.
- Add new tasks as requirements evolve.
- Ensure permissions are created for each action (create, edit, view, delete, reset password, assign/refer/assign to others, etc.) and roles/permissions can be dynamically assigned.

---
