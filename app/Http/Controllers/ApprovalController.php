<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{

    public function pending()
    {
        // Use Document model for pending approvals with user relationship
        $documents = Document::where('status', 'pending')
            ->with('user')
            ->whereHas('user')  // Only get documents with valid users
            ->paginate(10);
        
        // Keep compatibility with view variable name
        $folders = $documents;
        
        return view('approval.pending', compact('folders'));
    }

    public function history()
    {
        // Get document approval history from documents with status != pending
        $approvals = Document::whereIn('status', ['approved', 'rejected'])
            ->with('user')
            ->whereHas('user')  // Only get documents with valid users
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('approval.history', compact('approvals'));
    }
}

