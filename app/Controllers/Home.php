<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    // Function for testing database connection and query execution
    public function dbTest()
    {
        // Load the database connection
        $db = \Config\Database::connect();

        // Execute a simple query to test the connection
        $query = $db->query('SELECT id, name, username, role FROM users');
        $users = $query->getResultArray();

        // Return the results
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Database connection and query executed successfully.',
            'data' => $users
        ]);
    }
}
