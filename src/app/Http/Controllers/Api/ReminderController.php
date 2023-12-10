<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        // limit
        $limit = 10;
        if ($request->has('limit') && $request->limit != '') $limit = $request->limit;

        // order by field
        $column = 'created_at';
        if ($request->has('column') && $request->column != '') $column = $request->column;

        // order direction
        $dir = 'desc';
        if ($request->has('dir') && $request->dir != '') $dir = $request->dir;

        // query
        $query = Reminder::where('user_id', auth()->user()->id)->orderBy($column, $dir);

        // filter & search
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // get data
        $data = $query->paginate($limit);

        // retrun response
        return response()->json([
            'ok' => true,
            'data' => $data
        ], 200);
    }
}
