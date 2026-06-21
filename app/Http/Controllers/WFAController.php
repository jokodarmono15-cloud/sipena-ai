<?php

namespace App\Http\Controllers;

use App\Models\WFA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WFAController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isTeacher()) {
            $wfas = $user->wfas()->latest()->paginate(15);
        } else {
            $wfas = WFA::with('user', 'approver')->latest()->paginate(15);
        }
        
        return view('wfa.index', compact('wfas'));
    }

    public function create()
    {
        return view('wfa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo_evidence' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'screenshot_evidence' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document_evidence' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'date' => $request->date,
            'activity' => $request->activity,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => WFA::STATUS_PENDING,
        ];

        if ($request->hasFile('photo_evidence')) {
            $data['photo_evidence'] = $request->file('photo_evidence')->store('wfa/photos', 'public');
        }

        if ($request->hasFile('screenshot_evidence')) {
            $data['screenshot_evidence'] = $request->file('screenshot_evidence')->store('wfa/screenshots', 'public');
        }

        if ($request->hasFile('document_evidence')) {
            $data['document_evidence'] = $request->file('document_evidence')->store('wfa/documents', 'public');
        }

        $wfa = WFA::create($data);

        logActivity(Auth::id(), 'CREATE_WFA', 'WFA', $wfa->id);

        return redirect()->route('wfa.index')->with('success', 'Pengajuan WFA berhasil dibuat');
    }

    public function approve(Request $request, WFA $wfa)
    {
        $this->authorize('approve', $wfa);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'nullable|string|max:255',
        ]);

        $wfa->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'rejection_reason' => $request->reason,
        ]);

        logActivity(Auth::id(), 'APPROVE_WFA', 'WFA', $wfa->id);

        return back()->with('success', 'WFA berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak'));
    }
}
