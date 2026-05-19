<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CorporateInquiry;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function contacts()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return Inertia::render('Admin/Messages/Contacts', compact('messages'));
    }

    public function corporate()
    {
        $inquiries = CorporateInquiry::latest()->paginate(20);
        return Inertia::render('Admin/Messages/Corporate', compact('inquiries'));
    }

    public function markContactRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return back()->with('success', 'Marked as read.');
    }

    public function markCorporateRead(CorporateInquiry $inquiry)
    {
        $inquiry->update(['is_read' => true]);
        return back()->with('success', 'Marked as read.');
    }
}
