<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        return view('reminder.index');
    }

    public function create()
    {
        return view('reminder.create');
    }

    public function edit()
    {
        return view('reminder.edit');
    }
}
