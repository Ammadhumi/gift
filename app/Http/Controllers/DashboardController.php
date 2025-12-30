<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $greetings = Greeting::with(['wishes' => function ($query) {
            $query->latest();
        }])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('dashboard.index', [
            'greetings' => $greetings,
        ]);
    }
}
