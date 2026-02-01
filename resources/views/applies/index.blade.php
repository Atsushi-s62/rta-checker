<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('css/appliesStyle.css') }}">
    <title>登録申請フォーム</title>
</head>
<body>
    <h1>登録申請フォーム</h1>

    <form method="post" action="{{ route('applies.store') }}">
        @csrf
        <div id="name_form">
            <label>配信者名:[必須]</label>
            @error('name_id')
                <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="name_id" value="{{ old('name_id') }}">
            
        </div>
        <div id="twitch_form">
            <label>Twitch:<span>【https://www.twitch.tv/[この部分を入力]】</span></label>
            @error('twitch_id')
                    <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="twitch_id" value="{{ old('twitch_id') }}">
            
        </div>
        <div id="youtube_form">
            <label>Youtube:<span>【https://www.youtube.com/channel/[この部分を入力]】</span></label>
            @error('youtube_id')
                    <span class="error">{{ $message }}</span>
            @enderror
            <input type="text" name="youtube_id" value="{{ old('youtube_id') }}">
            
        </div>

        <div id="x_id">
            
            <label>X(旧ツイッター):<span>【https://x.com/[この部分を入力]】</span></label>
            <input type="text" name="x_id" value="{{ old('x_id') }}">
            @error('x_id')
                    <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" id="apply_button">申請</button>
    </form>
</body>
</html>