<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'in:complete,in progress,not started'
        ]);

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }
}
