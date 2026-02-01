<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;

class TwitchService
{
    public static function getAccessToken(): string
    {
        return Cache::remember(
            'twitch_app_token',
            now()->addDays(50),
            function () {
                $response = Http::asForm()->post(
                    'https://id.twitch.tv/oauth2/token',
                    [
                        'client_id' => config('services.twitch.client_id'),
                        'client_secret' => config('services.twitch.client_secret'),
                        'grant_type' => 'client_credentials',
                    ]
                );

                assert($response instanceof Response);

                if (!$response->successful()) {
                    throw new \Exception('Twitch token の取得に失敗しました');
                }

                return $response->json('access_token');
            }
        );
    }

        /** 複数チャンネルの配信情報を取得 */
    public static function getStreams(array $logins): array
    {
        
        // dd($logins);
        // $loginsColumn = array_column($logins, 'user_login');

        // dd($loginsColumn);
        
            $response = Http::withHeaders([
                'Client-ID' => config('services.twitch.client_id'),
                'Authorization' => 'Bearer ' . self::getAccessToken(),
            ])->get('https://api.twitch.tv/helix/streams', [
                'user_login' => $logins,
                'first' => 100,
            ]);
        
            assert($response instanceof Response);

            // $data = $response->json('data') ?? [];
            // $result = array_merge($result, $data);
            // return collect($response->json())

        // dd($response->json('data'));
        $result = collect($response->json('data'))->mapWithKeys(function ($item) {
            return [
                $item['user_login'] => [
                    // 'user_login' => $item['user_login'],
                    'user_name' => $item['user_name'],
                    'game_name' => $item['game_name'],
                    'type' => $item['type'],
                    'title' => $item['title'],
                    'viewer_count' => $item['viewer_count'],
                    'started_at' => $item['started_at'],
                    'language' => $item['language'],
                    'thumbnail_url' => $item['thumbnail_url'],
                    'tags' => $item['tags'],
                ],
            ];

        })->toArray();

        return $result;
    }


    // プロフィールアイコンを取得
    public static function getUsers(array $userLogins): array
    {

        // $userLoginsColumn = array_column($userLogins, 'user_login');
        // dd($userLoginsColumn);

            // dd($userLogins);
            // dd($chunk);
            $response = Http::withHeaders([
                'Client-ID' => config('services.twitch.client_id'),
                'Authorization' => 'Bearer ' . self::getAccessToken(),
            ])->get('https://api.twitch.tv/helix/users', [
                'login' => $userLogins,
                'first' => 100,
            ]);

            assert($response instanceof Response);
            // dd($response->json('data'));
            // dd($userLogins);

            // $channelIcon = data_get($response->json(),);
            $channelIcon = collect($response->json('data'))->mapWithKeys(function ($data) {
                // dd($data);
                return [
                    $data['login'] => [
                        'user_login' => $data['login'],
                        'profile_image_url' => $data['profile_image_url'],
                    ],
                ];
            })->toArray();

            // dd($userLogins);
            // dd($channelIcon);

            // $result = array_merge_recursive($userLogins, $channelIcon);

            // dd($result);

        return $channelIcon;
    }



    public static function twitchStreams(array $twitchIds): array
    {
        $result = [];
        $liveList = [];

        foreach(array_chunk($twitchIds, 100) as $twitchIdsChunk) {

        // $getUsersIcon = self::getUsers($twitchIdsChunk);
        // 15分ごとに更新
        $getUsersIcon = Cache::remember(
            'twitch_users_' . md5(implode(',', $twitchIdsChunk)),
            now()->addMinutes(15),
            fn() => self::getUsers($twitchIdsChunk)
        );

        // dd($getUsersIcon);

        $getUserIconLogin = array_column($getUsersIcon, 'user_login');

        // dd($getUserIconLogin);

        // $getStreamsKey = self::getStreams($twitchIdsChunk);


        // $getStreamsKey = self::getStreams($getUsersIcon);

        // 15分毎に更新
        $getStreamsKey = Cache::remember(
            'twitch_streams_' . md5(implode(',', $getUserIconLogin)),
            now()->addMinutes(15),
            fn() => self::getStreams($getUserIconLogin)
        );

        // $getStreamsKey = Cache::remember(
        //     'twitch_streams_' . md5(implode(',', $twitchIdsChunk))
        // );


        // $getUsersIcon = self::getUsers($getStreamsKey);
        

        // $result = $getUsersIcon;

        // dd($getStreamsKey);

        $result = array_merge_recursive($getStreamsKey, $getUsersIcon);

        $liveList = array_filter($result, function($item) {
            return isset($item['type']) && $item['type'] === 'live';

        });

        
        // $result = array_merge($getStreamsKey, $getUsersIcon);

        // dd($result);
        // $result = array_merge($result, $getUsersIcon);

        }

        // dd($result);
        // dd($liveList);

        return $liveList;

    }



}
