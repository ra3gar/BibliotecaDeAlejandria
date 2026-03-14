<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            // Libros
            'totalBooks'       => Book::count(),
            'booksOutOfStock'  => Book::where('available_copies', 0)->count(),

            // Préstamos
            'totalLoans'       => Loan::count(),
            'activeLoans'      => Loan::where('status', 'active')->count(),
            'overdueLoans'     => Loan::where('status', 'overdue')->count(),

            // Usuarios
            'totalUsers'       => User::where('role', 'user')->count(),
            'inactiveUsers'    => User::where('role', 'user')->where('is_active', false)->count(),

            // Actividad reciente
            'recentLoans'      => Loan::with(['user', 'book'])->latest()->take(5)->get(),
            'recentAuditLogs'  => AuditLog::with('user')->latest()->take(8)->get(),
        ]);
    }
}
