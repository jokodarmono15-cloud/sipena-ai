<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isTeacher()) {
            $attendances = $user->attendances()->latest()->paginate(15);
        } else {
            $attendances = Attendance::with('user', 'approver')->latest()->paginate(15);
        }
        
        return view('attendance.index', compact('attendances'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        
        $existing = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini');
        }

        $photoPath = $request->file('photo')->store('attendance/check-in', 'public');

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'check_in_time' => Carbon::now(),
                'check_in_latitude' => $request->latitude,
                'check_in_longitude' => $request->longitude,
                'check_in_photo' => $photoPath,
                'type' => 'kantor',
                'status' => 'hadir',
            ]
        );

        logActivity($user->id, 'CHECK_IN', 'Attendance', $attendance->id, ['status' => 'hadir']);

        return back()->with('success', 'Check-in berhasil');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->firstOrFail();

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini');
        }

        $photoPath = $request->file('photo')->store('attendance/check-out', 'public');

        $attendance->update([
            'check_out_time' => Carbon::now(),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photoPath,
        ]);

        logActivity($user->id, 'CHECK_OUT', 'Attendance', $attendance->id, ['status' => 'hadir']);

        return back()->with('success', 'Check-out berhasil');
    }

    public function history()
    {
        $user = Auth::user();
        $attendances = $user->attendances()->orderBy('date', 'desc')->paginate(30);
        
        return view('attendance.history', compact('attendances'));
    }

    public function approve(Request $request, Attendance $attendance)
    {
        $this->authorize('approve', $attendance);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'nullable|string|max:255',
        ]);

        $attendance->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'notes' => $request->reason,
        ]);

        logActivity(Auth::id(), 'APPROVE_ATTENDANCE', 'Attendance', $attendance->id);

        return back()->with('success', 'Absensi berhasil ' . ($request->status === 'approved' ? 'disetujui' : 'ditolak'));
    }

    public function report(Request $request)
    {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        
        $user = Auth::user();
        
        if ($user->isTeacher()) {
            $attendances = Attendance::where('user_id', $user->id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get();
        } else {
            $attendances = Attendance::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->with('user')
                ->get();
        }

        return view('attendance.report', compact('attendances', 'month', 'year'));
    }
}
