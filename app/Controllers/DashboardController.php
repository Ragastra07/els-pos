<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{

    public function index()
    {
        // Check if the user is logged in before allowing access to the dashboard(protection perposes)
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('dashboard/index');
    }
}
