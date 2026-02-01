<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('css/editStyle.css') }}">
    <title>Document</title>
</head>
<body>
    <h1>管理画面｜編集</h1>

    <div id="pending">
        <a href="{{ route('applies.judge') }}">申請中リスト</a>
    </div>

    <table>
        <thead>
            <tr>
                <th class="caption">ID</th>
                <th class="caption">配信者名</th>
                <th class="caption">Twitch</th>
                <th class="caption">Youtube</th>
                <th class="caption">X</th>
                <th class="caption">編集</th>
                <th class="caption">削除</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->name_id }}</td>
                <td><a href="https://www.twitch.tv/{{ $post->twitch_id }}" target="_blank">{{ $post->twitch_id }}</a></td>
                <td><a href="https://www.youtube.com/channel/{{ $post->youtube_id }}" target="_blank">{{ $post->youtube_id }}</td>
                <td><a href="https://x.com/{{ $post->x_id }}" target="_blank">{{ $post->x_id }}</td>
                
                <td><a href="{{ route('posts.editId', $post) }}"><button id="edit">編集</button></a></td>

                <form method="post" action="{{ route('posts.destroy', $post) }}">
                    @method('DELETE')
                    @csrf
                    <td><button id="delete">削除</button></td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ログアウト</button>
</form>    
</body>
</html>