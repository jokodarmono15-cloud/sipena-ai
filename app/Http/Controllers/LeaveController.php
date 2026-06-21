<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isTeacher()) {
            $leaves = $user->leaves()->latest()->paginate(15);
        } else {
            $leaves = Leave::with('user', 'approver')->latest()->paginate(15);
        }
        
        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:izin,sakit,tahunan',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'document' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'doctor_note' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => Leave::STATUS_PENDING,
        ];

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('leaves/documents', 'public');
        }

        if ($request->hasFile('doctor_note')) {
            $data['doctor_note'] = $request->file('doctor_note')->store('leaves/doctor_notes', 'public');
        }

        $leave = Leave::create($data);

        logActivity(Auth::id(), 'CREATE_LEAVE', 'Leave', $leave->id);

        return redirect()->route('leave.index')->with('success', 'Pengajuan izin berhasil dibuat');
    }

    public function approve(Request $request, Leave $leave)
    {
        $this->authorize('approve', $leave);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'nullable|string|max:255',
        ]);

        $leave->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'rejection_reason' => $request->reason,
        ]);

        logActivity(Auth::id(), 'APPROVE_LEAVE', 'Leave', $leave->id);

        return back()->with('success', 'Izin berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak'));
    }
}
