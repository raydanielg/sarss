<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');
        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->search) {
            $query->where('action', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        $modules = AuditLog::distinct()->pluck('module')->sort();
        return view('audit-logs.index', compact('logs', 'modules'));
    }
}
