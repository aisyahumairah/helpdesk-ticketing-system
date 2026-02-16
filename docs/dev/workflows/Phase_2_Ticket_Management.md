# Phase 2: Ticket Management Workflow

## Context
Implementation of core ticket creation, management, lifecycle, and searching.

## Tasks

### 2.1 Database & Models
- [x] Design ticket model and migration (category, urgency, description, status, attachments, auto-generated ticket ID: ID00001/YYYY) <!-- id: 0 -->
- [x] Design uploaded_files model and migration (morph concept: id, fileable_type, fileable_id, filename, filepath, filetype) <!-- id: 1 -->

### 2.2 Ticket Operations
- [x] Implement ticket creation form (with file upload: jpg, jpeg, png, video, pdf) <!-- id: 2 -->
- [ ] Implement ticket status lifecycle (New, Pending, Closed, Done, Reopen) <!-- id: 3 -->
- [ ] Implement ticket verification and reopening by users <!-- id: 4 -->

### 2.3 User Views
- [x] Implement "MyTicket" view for users (view, reply, reopen, verify, view details/actions/replies) <!-- id: 5 -->
- [x] Implement filtering/searching by year, status, and ticket ID (datatables) <!-- id: 6 -->

### 2.4 Quality Assurance
- [x] Add validation and error handling for ticket actions <!-- id: 7 -->
- [ ] Write feature and unit tests for ticket management <!-- id: 8 -->

## Relevant Files
- app/Models/Ticket.php
- app/Models/UploadedFile.php
- app/Http/Controllers/TicketController.php
- resources/views/tickets/*
- tests/Feature/Ticket/*
