<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/postsStyle.css') }}">
    <title>RTAchecker</title>
</head>
<body>
    <header>
        <h1>ＲＴＡチェッカー</h1>
    </header>

    <div id="apply_form">
        <div id="apply"><a href="{{ route('applies.index') }}">新規登録フォーム</a></div>
    </div>
    
    
    {{-- <div class="list">
        <div class="thumb">サムネイル</div>
        <div class="site">サイト名</div>
        <div class="streamer">配信者名</div>
        <div class="viewers">視聴者数</div>
        <div class="tpic">トピック</div>
        <div class="category">カテゴリ</div>
        <div class="sns">SNS</div>
    </div> --}}

        {{-- @foreach ($lists as $list)
        <div class="list">
            <div class="thumb">{{ $list[0] }}</div>
            <div class="site">{{ $list[1] }}</div>
            <div class="time">{{ $list[2] }}</div>
            <div class="icon">{{ $list[3] }}</div>
            <div class="streamer">{{ $list[4] }}</div>
            <div class="viewers">{{ $list[5] }}</div>
            <div class="topic">{{ $list[6] }}</div>
            <div class="category">{{ $list[7] }}</div>
            <div class="sns">{{ $list[8] }}</div>
            <div class="log">{{ $list[9] }}</div>
        </div>
        @endforeach --}}

        

        <div id="twitch-list">
            <p>読み込み中...</p>
        </div>

        <div id="rta-list">
            <p>RTA関連のライブ配信</p>

        @forelse ($rtaStreamData as $streamData)
        
        <div class="list">
            <a class="url_link" href="{{ $streamData['url'] }}" target="_blank">
                <div class="thumb">
                    <img src="{{ $streamData['thumb_url'] }}">
                </div>
                <div class="site">
                    <img src="{{ $streamData['stream_icon'] }}">
                </div>
                <div class="icon">
                    <img src="{{ $streamData['profile_image_url'] }}">
                </div>
                <div class="streamer">{{ $streamData['name_id'] }}</div>
            </a>
                <div class="time">{{ $streamData['stream_time'] }}</div>
                <div class="viewers">視聴数：{{ number_format($streamData['viewer_count']) }}人</div>
                <div class="topic">{{ $streamData['title'] }}</div>
            @if($streamData['game_name'] !== null)
                <div class="category">{{ $streamData['game_name'] }}</div>
            @endif
            @if($streamData['x_id'] !== null)
                <div class="x_id">
                    <a href="https://x.com/{{ $streamData['x_id'] }}" target="_blank">
                        <img src="images/xlogo-black.png">
                    </a>
                </div>
            @endif
        </div>
        @empty
            <div class="no-stream">現在放送しているチャンネルはありません</div>
        
        @endforelse
        </div>

        <div id="others">
        <p>その他のライブ配信</p>

        @forelse ($notRtaStreamData as $streamData)
        <div class="list">
            <a class="url_link" href="{{ $streamData['url'] }}" target="_blank">
                <div class="thumb">
                    <img src="{{ $streamData['thumb_url'] }}">
                </div>
                <div class="site">
                    <img src="{{ $streamData['stream_icon'] }}">
                </div>
                <div class="icon">
                    <img src="{{ $streamData['profile_image_url'] }}">
                </div>
                <div class="streamer">{{ $streamData['name_id'] }}</div>
            </a>
                <div class="time">{{ $streamData['stream_time'] }}</div>
                <div class="viewers">視聴数：{{ number_format($streamData['viewer_count']) }}人</div>
                <div class="topic">{{ $streamData['title'] }}</div>
            @if($streamData['game_name'] !== null)
                <div class="category">{{ $streamData['game_name'] }}</div>
            @endif
            @if($streamData['x_id'] !== null)
                <div class="x_id">
                    <a href="https://x.com/{{ $streamData['x_id'] }}" target="_blank">
                        <img src="images/xlogo-black.png">
                    </a>
                </div>
            @endif
        </div>
        @empty
            <div class="no-stream">現在放送しているチャンネルはありません</div>
        @endforelse
        </div>








        {{-- @foreach ($joinStreamData as $streamData)
        <div class="list">
            <a class="url_link" href="{{ $streamData['url'] }}">
            <div class="thumb">
                <img src="{{ $streamData['thumb_url'] }}">
            </div>
            <div class="site">
                <img src="{{ $streamData['stream_icon'] }}">
            </div>
            <div class="icon">
                <img src="{{ $streamData['profile_image_url'] }}">
            </div>
            <div class="streamer">{{ $streamData['name_id'] }}</div>
            </a>
            <div class="time">{{ $streamData['stream_time'] }}</div>
            <div class="viewers">視聴数：{{ number_format($streamData['viewer_count']) }}人</div>
            <div class="topic">{{ $streamData['title'] }}</div>
            @if($streamData['game_name'] !== null)
            <div class="category">{{ $streamData['game_name'] }}</div>
            @endif
            </a>
            @if($streamData['x_id'] !== null)
            <div class="x_id">
                <a href="https://x.com/{{ $streamData['x_id'] }}" target="_blank">
                <img src="images/xlogo-black.png">
                </a>
            </div>
            @endif
        </div>
        @endforeach --}}

        <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
