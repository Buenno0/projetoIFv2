<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getGreeting()
    {
        $currentHour = Carbon::now()->format('H');

        if ($currentHour < 12) {
            return 'Bom dia';
        } elseif ($currentHour < 18) {
            return 'Boa tarde';
        } else {
            return 'Boa noite';
        }
    }

    public function showDashboard()
    {
        $greeting = $this->getGreeting();
        return view('dashboard.index', ['greeting' => $greeting]);
    }
}
