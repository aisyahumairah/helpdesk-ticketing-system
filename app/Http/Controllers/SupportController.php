<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\Code;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display IT Support Dashboard.
     */
    public function dashboard()
    {
        $unassignedTickets = Ticket::whereNull('assigned_to')
            ->where('status', 'NEW')
            ->latest()
            ->limit(10)
            ->get();

        $myAssignedTickets = Ticket::where('assigned_to', Auth::id())
            ->whereIn('status', ['NEW', 'PEND', 'REOPEN'])
            ->latest()
            ->get();

        $stats = [
            'total_new' => Ticket::where('status', 'NEW')->count(),
            'total_pending' => Ticket::where('status', 'PEND')->count(),
            'total_resolved' => Ticket::whereIn('status', ['DONE', 'CLOSE'])->count(),
        ];

        return view('support.dashboard', compact('unassignedTickets', 'myAssignedTickets', 'stats'));
    }

    /**
     * Display a comprehensive listing of all tickets.
     */
    public function tickets(Request $request)
    {
        $query = Ticket::with(['user', 'categoryCode', 'urgencyCode', 'statusCode', 'assignedTo']);

        // Quick Search Filters
        if ($request->filled('ticket_id')) {
            $query->where('ticket_id', 'like', '%' . $request->ticket_id . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $tickets = $query->latest()->get();
        $statuses = Code::where('type', 'ticket_status')->get();

        return view('support.tickets', compact('tickets', 'statuses'));
    }

    /**
     * Export ticket reports.
     */
    public function report(Request $request)
    {
        $categories = Code::where('type', 'comp_type')->get();
        $statuses = Code::where('type', 'ticket_status')->get();

        if ($request->get('export') === 'pdf') {
            $query = Ticket::with(['user', 'categoryCode', 'urgencyCode', 'statusCode']);

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $tickets = $query->latest()->get();
            $data = [
                'tickets' => $tickets,
                'filters' => $request->all(),
                'date' => date('d M Y')
            ];

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('support.report_pdf', $data);
            return $pdf->download('ticket_report_' . date('Ymd_His') . '.pdf');
        }

        return view('support.reports', compact('categories', 'statuses'));
    }

    /**
     * Display system audit trails.
     */
    public function auditTrails(Request $request)
    {
        $query = \App\Models\AuditTrail::with(['user']);

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            $query->whereDate('created_at', date('Y-m-d'));
        }

        $trails = $query->latest()->get();

        return view('support.audit_trails', compact('trails'));
    }
}
