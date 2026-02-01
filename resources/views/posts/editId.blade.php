<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('css/editStyle.css') }}">
    <title>更新画面 | {{ $post->id }}</title>
</head>
<body>
    <h1>更新画面 | ID:{{ $post->id }}</h1>

    <form method="post" action="{{ route('posts.update', $post) }}">
        @method('PATCH')
        @csrf
        <div id="name_form">
            <label>配信者名:</label>
            @error('name_id')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="name_id" value="{{ old('$post->name_id', $post->name_id) }}">
        </div>
        <div id="twitch_form">
            <label>Twitch:</label>
            @error('twitch_id')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="twitch_id" value="{{ old('$post->twitch_id', $post->twitch_id) }}">
        </div>
        <div id="youtube_form">
            <label>Youtube:</label>
            @error('youtube_id')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="youtube_id" value="{{ old('$post->youtube_id', $post->youtube_id) }}">
        </div>
        <div id="x_id">
            <label>X(旧ツイッター):</label>
            @error('x_id')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="x_id" value="{{ old('$post->x_id', $post->x_id) }}">
        </div>
        <div>
        <button id="update">更新</button>
        </div>
    </form>
    
</body>
</html>