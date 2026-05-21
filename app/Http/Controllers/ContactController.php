<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\CorporateInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));

        return back()->with('success', 'Your message has been sent! We will get back to you soon.');
    }

    public function sendCorporateInquiry(Request $request)
    {
        $request->validate([
            'company_name'      => 'required|string|max:255',
            'contact_name'      => 'required|string|max:255',
            'email'             => 'required|email',
            'phone'             => 'required|string|max:20',
            'event_type'        => 'nullable|string|max:100',
            'expected_quantity' => 'nullable|integer|min:1',
            'required_date'     => 'nullable|date|after:today',
            'requirements'      => 'nullable|string|max:2000',
            'design_files'      => 'nullable|array|max:5',
            'design_files.*'    => 'file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
        ]);

        $filePaths = [];
        if ($request->hasFile('design_files')) {
            foreach ($request->file('design_files') as $file) {
                $filePaths[] = $file->store('corporate-designs', 'public');
            }
        }

        CorporateInquiry::create([
            ...$request->only(
                'company_name', 'contact_name', 'email', 'phone',
                'event_type', 'expected_quantity', 'required_date', 'requirements'
            ),
            'design_files' => $filePaths ?: null,
        ]);

        return back()->with('success', 'Your corporate inquiry has been submitted! Our team will contact you shortly.');
    }
}
