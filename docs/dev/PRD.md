# Product Requirements Document (PRD)

## Project Overview


This document outlines the requirements for the Helpdesk Ticketing System. The system is designed to facilitate IT support for users, streamline ticket management, and provide robust administrative controls.

---


## Recent Enhancements

- Tickets are auto-generated with a unique ID in the format `ID00001/YYYY` (e.g., ID00001/2026), where the year is detected dynamically.
- IT Support can fully manage ticket lists (create, edit, delete, view).
- Real-time notifications are provided for ticket updates and communications.
- All users receive in-system alert notifications (notification icon in topbar).
- Dashboards for all roles with actionable tasks and analytics:
	- IT Support: notified of new tickets, view actionable tasks, ticket analytics.
	- Super Admin: view analytics data based on ticketings.
	- User: dashboard for own tickets, actionable tasks (replies, verification), analytics, and ticket history.
- Ticket management modules for each role:
	- User: MyTicket (view, reply, reopen, verify, view details/actions/replies), filter/search by year/status/ID (with datatables).
	- IT Support: Ticketing List (create on behalf, view, edit), filter/search by year/status/ID (with datatables), audit trails (view/download, filter by date/event/user), ticketing report (view/download, filter by date/complaint type).
	- Super Admin: Ticketing List (create on behalf, view, edit, delete), filter/search by year/status/ID (with datatables), audit trails (view/download, filter by date/event/user), ticketing report (view/download, filter by date/complaint type), system configurations.
- Permissions for each action (create, edit, view, delete, reset password, assign/refer/assign to others, etc.)
- Roles and permissions can be dynamically assigned by users as needed.

---

## 1. Objectives

- Provide a platform for users to submit IT-related complaints, requests, or questions.
- Enable IT Support to manage, resolve, and track tickets efficiently.
- Allow Super Admins to manage users, roles, permissions, and system settings.

---

## 2. User Roles & Permissions

### 2.1 User
- Register and login (including Gmail signup).
- Create tickets for IT issues (complaints, requests, questions).
- Select ticket urgency (Low, Medium, High, Critical).
- Specify complaint type (Software, Hardware, Network, System Applications).
- For System Applications, select the specific system.
- Provide ticket title and description.
- Upload files (jpg, jpeg, png, video, pdf).
- View, verify, or reopen tickets.
- View and reply to IT Support responses.
- View all personal tickets (MyTicket).
- Edit profile information (name, phone number).
- Change password and use forgot password functionality.
- Receive in-system alert notifications (notification icon in topbar).
- Dashboard: view own tickets, actionable tasks (replies, verification), analytics, and ticket history.

### 2.2 IT Support
- Receive new tickets (status: New) with real-time notification.
- Accept and assign tickets (status: Pending).
- Close tickets upon resolution.
- Escalate tickets to higher support layers (unlimited layers).
- Reply to user tickets and upload files (pdf, images, videos).
- Submit tickets on behalf of users (auto-assigned, status: Pending).
- View and download ticket reports (PDF, Excel).
- Manage ticket lists: create, edit, delete, and view tickets.
- Dashboard: notified of new tickets, view actionable tasks, ticket analytics.
- Audit trails: view/download, filter by date/event/user (default: current date).

### 2.3 Super Admin
- Manage users (add, edit, delete, reset password).
- Reset password via default or email link.
- Enforce password change on first login after reset.
- Manage roles and permissions (add, edit, delete, configure; dynamic assignment).
- View and download audit trails (filter by date/event/user, default: current date).
- Configure system settings (email notifications, session timeout, password reset expiry, login attempt limit, lockout duration).
- Enable/disable email notifications.
- Reset lockout duration upon password reset.
- Dashboard: view analytics data based on ticketings.
- Ticketing List: create (on behalf), view, edit, delete; filter/search by year/status/ID (datatables).
- Ticketing report: view/download, filter by date/complaint type.

---

## 3. Functional Requirements

### 3.1 Authentication & Account Management
- All users must have an account to file tickets.
- Email is the unique identifier for users.
- Password management (change, reset, forgot password).
- Profile management (name, phone number).

### 3.2 Ticket Management
- Ticket creation with category, urgency, and description.
- Auto-generate ticket ID in the format `ID00001/YYYY` (current year).
- File uploads (jpg, jpeg, png, video, pdf).
- Ticket status lifecycle: New, Pending, Closed, Done, Reopen.
- Ticket verification and reopening by users.
- Ticket escalation across support layers.
- Ticket report generation (PDF, Excel).
- IT Support and Super Admin can create (on behalf), edit, delete, and view tickets.
- MyTicket (User): view, reply, reopen, verify, view details/actions/replies; filter/search by year/status/ID (datatables).
- Ticketing List (IT Support/Super Admin): filter/search by year/status/ID (datatables).

### 3.3 Communication & Notifications
- IT Support and users can exchange replies on tickets.
- Display all replies and attachments.
- Real-time notifications for ticket updates, assignments, and replies.
- All users receive in-system alert notifications (notification icon in topbar).

### 3.4 Administration
- User, role, and permission management (dynamic assignment of roles/permissions).
- Audit trail viewing and export (filter by date/event/user).
- System settings configuration.
- Email notification management.

---

## 4. Non-Functional Requirements

- Secure authentication and authorization.
- Responsive and user-friendly interface.
- Reliable file upload and storage.
- Audit logging for all critical actions.
- Configurable system settings.

---

## 5. Constraints & Considerations

- Only registered users can submit tickets.
- Email is the primary user identifier.
- File upload formats are restricted to jpg, jpeg, png, video, pdf.
- Unlimited support layers for ticket escalation.
- Password reset and lockout policies are configurable.
- Permissions must be created for each action (create, edit, view, delete, reset password, assign ticket, refer/assign to others, etc.).
- Roles and permissions can be dynamically assigned by users as needed.

---

## 6. Future Enhancements

- Integration with additional communication channels (WhatsApp, etc.).
- Advanced reporting and analytics.

---

## 7. References

- Laravel 12, Pest, Tailwind CSS, Gentelella Admin Template

---

*This PRD will be updated as requirements evolve.*
