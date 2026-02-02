<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Apply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\ApplyController;
use App\Http\Requests\ApplyRequest;
use App\Http\Requests\PostRequest;
use App\Services\TwitchService;
use App\Services\YouTubeService;


class PostController extends Controller
{
    // private $lists =[
    //     ['サムネイル', 'twitch', 'time1', 'アイコン1', '配信者名１', '視聴数20', 'topic-yo', 'categry-mario', 'sns-x', 'log1'],
    //     ['サムネイル', 'youtube', 'time2', 'アイコン2', '配信者名2', '視聴数100', 'topic-sa', 'categry-pokemon', 'sns-insta', 'log2'],
    // ];

    public function index()
    {
        $users = Post::get()->toArray();
        $twitchIds = collect($users)->pluck('twitch_id')->filter()->toArray();
        // dd($twitchIds);
        $youtubeIds = collect($users)->pluck('youtube_id')->filter()->toArray();
        // $twtichIds = array_keys();
        // $users = Post::pluck('twitch_id', 'x_id')->toArray();
        // $twitchStreams = TwitchService::getStreams($twitchIds);
        $twitchStreams = TwitchService::twitchStreams($twitchIds);
        $youtubeStreams = YouTubeService::separateBatchProcessing($youtubeIds);
        $now = Carbon::now('UTC');
        $twitchStreamData = [];
        $youtubeStreamData = [];
        $joinStreamData = [];
        $rtaStreamData = [];
        $notRtaStreamData = [];
        $keyWords = ['RTA', 'speedrun', 'SpeedRun'];

        // dd($twitchStreams);

        // dd($youtubeStreams);

        // twitchのデータ整理
        foreach($users as $user) {
            foreach($twitchStreams as $twitchStream) {
                // dd($twitchStream['user_login']);
                // dd($twitchStreams);
                if ($user['twitch_id'] == $twitchStream['user_login']) {
                    $startTime = Carbon::parse($twitchStream['started_at']);
                    $streamTime = $startTime->diff($now);
                    $thumbUrlFormat = str_replace(['{width}', '{height}'], ['160', '90'], $twitchStream['thumbnail_url']);
                    $twitchStreamData[] = [
                        'name_id' => $user['name_id'],
                        'twitch_id' => $user['twitch_id'],
                        'youtube_id' => $user['youtube_id'],
                        'url' => "https://www.twitch.tv/" . $user['twitch_id'],
                        'x_id' => $user['x_id'], 
                        'user_name' => $twitchStream['user_name'],
                        'game_name' => $twitchStream['game_name'],
                        'title' => $twitchStream['title'],
                        'stream_time' => $streamTime->h . ' 時間' . $streamTime->i . ' 分',
                        // 'stream_time' => $streamTime->format('%h 時間 %i 分'),
                        'viewer_count' => $twitchStream['viewer_count'],
                        'thumb_url' => $thumbUrlFormat,
                        'profile_image_url' => $twitchStream['profile_image_url'],
                        'stream_icon' => 'images\twitch_logo_purple.png',

                    ];
                    // var_dump($streamTime);
                }
            }  
        }

        // youtubeのデータ整理
        foreach($users as $user) {
            foreach($youtubeStreams as $youtubeStream) {
                // dd($youtubeStream);
                if($user['youtube_id'] == $youtubeStream['channel_id']) {
                    $startTime = Carbon::parse($youtubeStream['started_at']);
                    $streamTime = $startTime->diff($now);
                    $youtubeStreamData[] = [
                        'name_id' => $user['name_id'],
                        'twitch_id' => $user['twitch_id'],
                        'youtube_id' => $user['youtube_id'],
                        'url' => "https://www.youtube.com/watch?v=" . $youtubeStream['videoId'],
                        'videoId' => $youtubeStream['videoId'],
                        'x_id' => $user['x_id'],
                        'user_name' => $youtubeStream['channel_name'],
                        'game_name' => null,
                        'title' => $youtubeStream['title'],
                        // 'stream_time' => $streamTime->format('%h 時間 %i 分'),
                        'stream_time' => $streamTime->h . ' 時間' . $streamTime->i . ' 分',
                        'viewer_count' => $youtubeStream['viewer_count'],
                        'thumb_url' => $youtubeStream['thumbnail'],
                        'profile_image_url' => $youtubeStream['thumbnail_url'],
                        'stream_icon' => 'images\yt_icon_red_digital.png',
                    ];
                }
                
            }
        }

        // dd($twitchStreamData);
        // dd($youtubeStreamData);
        
        // すべてのストリームデータ
        $joinStreamData = array_merge_recursive($twitchStreamData, $youtubeStreamData);

        // dd($joinStreamData);

        // RTAに関わるワードが入るデータ
        $pattern = '/' . implode('|', array_map('preg_quote', $keyWords)) . '/u';
        $rtaStreamData = array_filter($joinStreamData, function($item) use ($pattern) {
            return preg_match($pattern, $item['title']);
        });


        // RTAに関わりのないデータ
        $notRtaStreamData = array_filter($joinStreamData, function($item) use ($keyWords) {
            foreach($keyWords as $word) {
                if(str_contains($item['title'], $word)) {
                    return false;
                }
            }
            // ワードが含まれていなければ保持
            return true;
        });
        

        // dd($rtaStreamData);
        // dd($notRtaStreamData);


        

        // $data = date('Y-m-d H:i:s');
        // var_dump($data);
        // var_dump($twitchStream);
        // var_dump($resultData);

        // foreach($twitchStreams as $twitchStream) {
            // dd($stream['$users['']']); murakamisuigun
            // $x_id = $users[$twitchStream['user_login']];
            // var_dump($twitchStream);

            // データを配列にまとめる
            // $resultData[] = [
            //     'twitch_id' => ''
            // ];
        // }

        if (empty($users)) {
            response()->json([]);
        }

        


        // return view('posts.index')->with(['lists' => $lists]);
        return view('posts.index', compact('joinStreamData', 'rtaStreamData', 'notRtaStreamData'));
        // return view('posts.index');
    }

    public function edit()
    {
        $posts = Post::orderBy('id', 'asc')->get();

        return view('posts.edit')->with(['posts' => $posts]);
    }

    public function editId(Post $post)
    {
        
        return view('posts.editId')->with(['post' => $post]);
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->name_id = $request->name_id;
        $post->twitch_id = $request->twitch_id;
        $post->youtube_id = $request->youtube_id;
        $post->x_id = $request->x_id;
        $post->save();

        return redirect()->route('posts.edit');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.edit');
    }








}
