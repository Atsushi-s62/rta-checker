<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('css/appliesStyle.css') }}">
    <title>申請判定ページ</title>
</head>
<body>
    <h1>申請判定ページ</h1>

    @if (session('permission'))
        <p>{{ session('permission') }}</p>
    @elseif (session('not-allowed'))
        <p>{{ session('not-allowed') }}</p>
    @endif



    <table id="judge_table">
        <thead>
            <tr>
                <th class="">配信者名</th>
                <th>Twitch</th>
                <th>Youtube</th>
                <th>X</th>
                <th>許可</th>
                <th>不許可</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($applies as $apply)
            <tr>
                <td>{{ $apply->name_id }}</td>
                <td><a href="https://www.twitch.tv/{{ $apply->twitch_id }}">{{ $apply->twitch_id }}</a></td>
                <td><a href="https://www.youtube.com/channel/{{ $apply->youtube_id }}">{{ $apply->youtube_id }}</td>
                <td><a href="https://x.com/{{ $apply->x_id }}">{{ $apply->x_id }}</td>
                
                <form method="post" action="{{ route('posts.move', $apply) }}">
                    @csrf
                    <td><button id="ok">OK!</button></td>
                </form>

                <form method="post" action="{{ route('applies.destroy', $apply ) }}">
                    @method('DELETE')
                    @csrf
                    <td><button id="ng">NG!</button></td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>