<?php

namespace App\Http\Controllers;

use App\Models\MailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MailTemplateController extends Controller
{
    public function index()
    {
        $templates = MailTemplate::all();
        return view('admin.mail-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.mail-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mail_templates,name',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'available_placeholders' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);
            $validated['available_placeholders'] = json_encode($validated['available_placeholders'] ?? []);
            
            MailTemplate::create($validated);

            return redirect()->route('admin.mail_templates.index')
                ->with('success', 'Mail template created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to create mail template: ' . $e->getMessage());
        }
    }

    public function show(MailTemplate $mailTemplate)
    {
        return view('admin.mail-templates.show', compact('mailTemplate'));
    }

    public function edit(MailTemplate $mailTemplate)
    {
        return view('admin.mail-templates.edit', compact('mailTemplate'));
    }

    public function update(Request $request, MailTemplate $mailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mail_templates,name,' . $mailTemplate->id,
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'available_placeholders' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);
            $validated['available_placeholders'] = json_encode($validated['available_placeholders'] ?? []);
            
            $mailTemplate->update($validated);

            return redirect()->route('admin.mail_templates.index')
                ->with('success', 'Mail template updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update mail template: ' . $e->getMessage());
        }
    }

    public function destroy(MailTemplate $mailTemplate)
    {
        try {
            $mailTemplate->delete();
            return redirect()->route('admin.mail_templates.index')
                ->with('success', 'Mail template deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete mail template: ' . $e->getMessage());
        }
    }

    public function preview(Request $request, MailTemplate $mailTemplate)
    {
        $data = $request->input('data', []);
        $preview = $mailTemplate->render($data);
        
        return response()->json([
            'success' => true,
            'subject' => $mailTemplate->getSubject($data),
            'body' => $preview
        ]);
    }
}
