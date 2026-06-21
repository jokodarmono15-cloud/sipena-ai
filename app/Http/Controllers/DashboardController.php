<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\WFA;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            return $this->teacherDashboard($user);
        } elseif ($user->isHeadmaster()) {
            return $this->headmasterDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function teacherDashboard($user)
    {
        $today = Carbon::now()->toDateString();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        $todayAttendance = $user->attendances()->whereDate('date', $today)->first();
        $monthlyAttendance = $user->attendances()
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();
        $monthlyWFA = $user->wfas()
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->where('status', 'approved')
            ->count();
        $monthlyLeave = $user->leaves()
            ->whereMonth('start_date', $thisMonth)
            ->whereYear('start_date', $thisYear)
            ->where('status', 'approved')
            ->count();

        $recentAttendances = $user->attendances()->latest()->take(5)->get();
        $pendingRequests = collect()
            ->merge($user->wfas()->where('status', 'pending')->get())
            ->merge($user->leaves()->where('status', 'pending')->get())
            ->sortByDesc('created_at')
            ->take(5);

        return view('dashboard.teacher', compact(
            'todayAttendance',
            'monthlyAttendance',
            'monthlyWFA',
            'monthlyLeave',
            'recentAttendances',
            'pendingRequests'
        ));
    }

    private function headmasterDashboard()
    {
        $today = Carbon::now()->toDateString();
        $teachers = User::role('guru')->get();

        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->distinct('user_id')
            ->count();
        $wfaToday = WFA::whereDate('date', $today)
            ->where('status', 'approved')
            ->distinct('user_id')
            ->count();
        $lateToday = Attendance::whereDate('date', $today)
            ->where('status', 'terlambat')
            ->distinct('user_id')
            ->count();
        $absenceToday = $teachers->count() - $presentToday - $wfaToday - $lateToday;

        $pendingApprovals = collect()
            ->merge(WFA::where('status', 'pending')->with('user')->get())
            ->merge(Leave::where('status', 'pending')->with('user')->get())
            ->sortByDesc('created_at')
            ->take(10);

        return view('dashboard.headmaster', compact(
            'presentToday',
            'wfaToday',
            'lateToday',
            'absenceToday',
            'pendingApprovals'
        ));
    }

    private function adminDashboard()
    {
        $totalUsers = User::count();
        $totalTeachers = User::role('guru')->count();
        $todayAttendances = Attendance::whereDate('date', Carbon::now()->toDateString())->count();
        $totalAttendances = Attendance::count();
        $pendingWFAs = WFA::where('status', 'pending')->count();
        $pendingLeaves = Leave::where('status', 'pending')->count();

        $recentAttendances = Attendance::with('user')->latest()->take(10)->get();
        $systemLogs = \App\Models\AuditLog::latest()->take(10)->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalTeachers',
            'todayAttendances',
            'totalAttendances',
            'pendingWFAs',
            'pendingLeaves',
            'recentAttendances',
            'systemLogs'
        ));
    }
}
