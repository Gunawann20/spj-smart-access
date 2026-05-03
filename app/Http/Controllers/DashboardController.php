<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Try using documents table first, fallback to folders for compatibility
            $totalDocuments = Document::count();
            $pendingDocuments = Document::where('status', 'pending')->count();
            $approvedDocuments = Document::where('status', 'approved')->count();
            $rejectedDocuments = Document::where('status', 'rejected')->count();
            $totalUsers = User::where('role', 'karyawan')->count();

            return view('dashboard.admin', compact(
                'totalDocuments',
                'pendingDocuments',
                'approvedDocuments',
                'rejectedDocuments',
                'totalUsers'
            ));
        } else {
            // Get documents for the user
            $myDocuments = Document::where('user_id', $user->id)->count();
            $approvedDocuments = Document::where('user_id', $user->id)
                ->where('status', 'approved')
                ->count();
            $pendingDocuments = Document::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $recentDocuments = Document::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Keep recentFolders for backward compatibility with view
            $myFolders = $myDocuments;
            $approvedFolders = $approvedDocuments;
            $pendingFolders = $pendingDocuments;
            $recentFolders = $recentDocuments;

            return view('dashboard.karyawan', compact(
                'myFolders',
                'approvedFolders',
                'pendingFolders',
                'recentFolders'
            ));
        }
    }
}
