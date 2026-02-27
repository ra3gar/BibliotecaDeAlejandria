<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalBooks'       => Book::count(),
            'totalLoans'       => Loan::count(),
            'activeLoans'      => Loan::where('status', 'active')->count(),
            'totalUsers'       => User::where('role', 'user')->count(),
        ]);
    }
}
