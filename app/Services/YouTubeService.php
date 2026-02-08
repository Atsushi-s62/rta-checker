<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\Response;

class YouTubeService
{
    private $base = 'https://www.googleapis.com/youtube/v3';

    // 複数チャンネルのライブ情報取得

    // チャンネルID配列からchannnels
    public static function getUploadPlaylists(array $channelIds): array
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/channels', [
        'part' => 'snippet,contentDetails',
        'id' => implode(',', $channelIds),
        'key' => config('services.youtube.key'),
        ]);


    return collect($response->json('items'))->mapWithKeys(function ($item) {
        return [
                
            $item['id'] => [
                'channel_id'          => $item['id'],
                'uploads_playlist_id' => $item['contentDetails']['relatedPlaylists']['uploads'],
                'thumbnail_url'       => $item['snippet']['thumbnails']['high']['url'],
            ],        
        ];
    })->toArray();

    }

    // playlistItems.list(最新動画IDの取得)　ライブ配信も動画IDで取得可能　
    public static function getLatestVideoIds(array $uploadPlaylistIds): array
    {
        $channelLists = [];

        foreach($uploadPlaylistIds as $playListId){

            $response = Http::get('https://www.googleapis.com/youtube/v3/playlistItems', [
            'part' => 'contentDetails',
            'playlistId' => $playListId['uploads_playlist_id'],
            'maxResults' => 1,
            'key' => config('services.youtube.key'),
            ]);

            $videoId = data_get($response->json(), 'items.0.contentDetails.videoId');
            $addVideoId = ['videoId' => $videoId];

                $channelLists[] = [ 
                    $playListId['channel_id'] => [
                        'channel_id' => $playListId['channel_id'],
                        'uploads_playlist_id' => $playListId['uploads_playlist_id'],
                        'thumbnail_url' => $playListId['thumbnail_url'],
                        'videoId' => $videoId,

                    ],
                ];


        }

        $channelListsMerge = array_merge(...$channelLists);

        return $channelListsMerge;

    }

    // videos.list (ライブ情報取得)
    public static function getLiveDetails(array $videoIds): array
    {

        $videoIdColumn = array_column($videoIds, 'videoId');

        $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
            'part' => 'snippet,liveStreamingDetails',
            'id' => implode(',', $videoIdColumn),
            'key' => config('services.youtube.key'),
        ]);


        $videoMap = collect($response->json('items'))
            ->mapWithKeys(function($item) {

                return [
                    $item['snippet']['channelId'] => [
                    // 'videId'       => $item['id'],
                    'channel_name' => $item['snippet']['channelTitle'],
                    'title'        => $item['snippet']['title'],
                    'viewer_count' => (int) ($item['liveStreamingDetails']['concurrentViewers'] ?? 0),
                    'thumbnail'    => $item['snippet']['thumbnails']['high']['url'],
                    'started_at'   => $item['liveStreamingDetails']['actualStartTime'] ?? null,
                    'is_live'      =>
                        isset($item['liveStreamingDetails']['actualStartTime']) &&
                        !isset($item['liveStreamingDetails']['actualEndTime']),
                    ],

                ];
            })->toArray();


            $channelListMerge = array_replace_recursive($videoIds, $videoMap);

            $liveChannelList = array_filter($channelListMerge, function($item) {
                return $item['is_live'] === true;
            });
        
            return $liveChannelList;
    }

    public static function separateBatchProcessing($channelIds) 
    {
        $allLiveData = [];

        foreach(array_chunk($channelIds, 50) as $channelIdsChunk) {

            // 30分ごとにchannel.listAPIを叩く
            $playLists = Cache::remember(
                'yt_playlists_' . md5(implode(',', $channelIdsChunk)),
                now()->addMinutes(30),
                fn() => self::getUploadPlaylists($channelIdsChunk)
            );

            // 30分ごとにplaylistsItems.listAPIを叩く
            $playListIds = array_column($playLists, 'uploads_playlist_id');

            $videoIds = Cache::remember(
                'yt_video_ids_' . md5(implode(',', $playListIds)),
                now()->addMinutes(30),
                fn() => self::getLatestVideoIds($playLists)
            );
            

            //15分ごとにvideo.listAPIを叩く
            $videoIdList = array_column($videoIds, 'videoId');
            
            $liveData = Cache::remember(
                'yt_live_' . md5(implode(',', $videoIdList)),
                now()->addMinutes(15),
                fn() => self::getLiveDetails($videoIds)
            );

            $allLiveData += $liveData;

        }

        // dd($liveData);
        return $allLiveData;
    }

}
