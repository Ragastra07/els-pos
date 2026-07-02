<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return redirect()->to('/login');
    }

    // Function for testing database connection and query execution
    
}
