<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ApplyRequest;
use App\Models\Apply;
use App\Models\Post;


class ApplyController extends Controller
{
    //
    public function index()
    {
        return view('applies.index');
    }

    public function store(ApplyRequest $request)
    {
        $applies = new Apply();
        $applies->name_id = $request->name_id;
        $applies->twitch_id = $request->twitch_id;
        $applies->youtube_id = $request->youtube_id;
        $applies->x_id = $request->x_id;
        $applies->save();

        return redirect()->route('applies.index');
    }

    public function judge()
    {
        $applies = Apply::orderBy('id', 'asc')->get();

        return view('applies.judge')->with(['applies' => $applies]);
    }

    public function move(Apply $apply)
    {

        DB::transaction(function() use ($apply) {
            Post::create([
                'name_id' => $apply->name_id,
                'twitch_id' => $apply->twitch_id,
                'youtube_id' => $apply->youtube_id,
                'x_id' => $apply->x_id,
            ]);

            $apply->delete();
        });

        return back()->with('permission', $apply->name_id . 'を許可しました');
    }

    public function destroy(Apply $apply)
    {
        $apply->delete();

        return back()->with('not-allowed', $apply->name_id . 'を不許可しました');
    }
}
