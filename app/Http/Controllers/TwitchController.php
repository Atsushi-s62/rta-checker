<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwitchService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use App\Models\Post;



class TwitchController extends Controller
{
    // public function streamInfo(string $login)
    // {
        
    //     $token = TwitchService::getAccessToken();

    //     $response = Http::withHeaders([
    //         'Client-ID' => config('services.twitch.client_id'),
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get('https://api.twitch.tv/helix/streams', [
    //         'user_login' => $login,
    //     ]);

    //     assert($response instanceof Response);

    //     return response()->json($response->json());
    // }

    public function streams()
    {
        //有効なチャンネルだけ取得
    //     $logins = Post::pluck('twitch_id')->toArray();


    //     if (empty($logins)) {
    //         return response()->json([]);
    //     }

    //     $streams = TwitchService::getStreams($logins);
    //     return response()->json($streams);
    
             
    }

    
}

