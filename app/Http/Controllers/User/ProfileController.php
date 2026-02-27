<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $loans = $user->loans()->with('book.authors')->latest()->get();

        return view('user.profile', compact('user', 'loans'));
    }
}
