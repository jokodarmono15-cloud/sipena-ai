<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPENA AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        .navbar {
            background: var(--primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sidebar {
            background: var(--primary);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--secondary);
            color: white;
            border-left: 4px solid var(--success);
        }
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background: var(--secondary);
            border: none;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        .stat-card {
            background: white;
            border-left: 4px solid var(--secondary);
            padding: 20px;
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-graduation-cap"></i> SIPENA AI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @auth
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar">
                <div class="p-3 text-white">
                    <h5>{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</h5>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                    @if(Auth::user()->isTeacher())
                        <a class="nav-link" href="{{ route('attendance.index') }}">
                            <i class="fas fa-clock"></i> Absensi
                        </a>
                        <a class="nav-link" href="{{ route('wfa.index') }}">
                            <i class="fas fa-home"></i> WFA
                        </a>
                        <a class="nav-link" href="{{ route('leave.index') }}">
                            <i class="fas fa-file"></i> Izin/Sakit
                        </a>
                    @else
                        <a class="nav-link" href="{{ route('attendance.index') }}">
                            <i class="fas fa-users"></i> Absensi
                        </a>
                        <a class="nav-link" href="{{ route('wfa.index') }}">
                            <i class="fas fa-check"></i> Persetujuan WFA
                        </a>
                        <a class="nav-link" href="{{ route('leave.index') }}">
                            <i class="fas fa-list"></i> Persetujuan Izin
                        </a>
                    @endif
                    <a class="nav-link" href="{{ route('chat.internal') }}">
                        <i class="fas fa-comments"></i> AI Assistant
                    </a>
                </nav>
            </div>
            <div class="col-md-9" style="padding: 30px;">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">© 2026 SMK Negeri 5 Tanjungpinang. Developed by PT. Affishope Digital Bintan</p>
        <p class="mb-0"><small>{{ config('app.version', '2026') }}</small></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
