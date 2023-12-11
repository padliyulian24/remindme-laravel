<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
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
        $query = Reminder::where('user_id', auth('sanctum')->user()->id)->orderBy($column, $dir);

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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:250',
            'description' => 'required|string|max:1000',
            'remind_at' => 'required',
            'event_at' => 'required',
        ]);

        $reminder = new Reminder;
        $reminder->user_id = auth('sanctum')->user()->id;
        $reminder->title = $request->title;
        $reminder->description = $request->description;
        $reminder->remind_at = $request->remind_at;
        $reminder->event_at = $request->event_at;
        $reminder->save();

        return response()->json([
            'ok' => true,
            'data' => $reminder
        ], 200);
    }

    public function show($id)
    {
        $reminder = Reminder::where('id', $id)->where('user_id', auth('sanctum')->user()->id)->first();

        if (!$reminder) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => 'resource is not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $reminder
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:250',
            'description' => 'nullable|string|max:1000',
            'remind_at' => 'nullable',
            'event_at' => 'nullable',
        ]);

        $reminder = Reminder::where('id', $id)->where('user_id', auth('sanctum')->user()->id)->first();
        if (!$reminder) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => 'resource is not found'
            ], 404);
        }

        if ($request->title) $reminder->title = $request->title;
        if ($request->description) $reminder->description = $request->description;
        if ($request->remind_at) $reminder->remind_at = $request->remind_at;
        if ($request->event_at) $reminder->event_at = $request->event_at;
        $reminder->update();

        return response()->json([
            'ok' => true,
            'data' => $reminder
        ], 200);
    }

    public function destroy($id)
    {
        $reminder = Reminder::where('id', $id)->where('user_id', auth('sanctum')->user()->id)->first();
        if (!$reminder) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_NOT_FOUND',
                'msg' => 'resource is not found'
            ], 404);
        }

        $reminder->delete();

        return response()->json([
            'ok' => true
        ], 200);
    }
}
