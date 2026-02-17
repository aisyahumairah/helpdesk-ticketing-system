# Phase 4: Super Admin & System Workflow

## Context
Implementation of administrative features including user management, roles, and system settings.

## Tasks

### 4.1 User & Role Management
- [x] Implement user management (add, edit, delete, reset password) <!-- id: 0 -->
- [x] Implement role and permission management (using Spatie; dynamic assignment) <!-- id: 1 -->
- [x] Enforce password change on first login after admin reset <!-- id: 2 -->

### 4.2 System Administration
- [x] Implement audit trail viewing and export (filter by date, event, user; default: current date) <!-- id: 3 -->
- [x] Implement system settings management (email, session timeout, password reset expiry, login attempt limit, lockout duration, email notifications) <!-- id: 4 -->

### 4.3 Admin Dashboard
- [x] Implement super admin dashboard (ticket analytics, ticketing list, system configurations) <!-- id: 5 -->
- [x] Implement ticketing list (create on behalf, view, edit, delete), filtering/searching by year, status, ticket ID (datatables) <!-- id: 6 -->
- [x] Implement ticketing report (view/download, filter by date, complaint type) <!-- id: 7 -->

### 4.4 Quality Assurance
- [x] Add validation and error handling for admin actions <!-- id: 8 -->
- [x] Write feature and unit tests for admin workflows <!-- id: 9 -->

## Relevant Files
- app/Http/Controllers/AdminController.php
- app/Models/SystemSetting.php
- resources/views/admin/*
- tests/Feature/Admin/*
