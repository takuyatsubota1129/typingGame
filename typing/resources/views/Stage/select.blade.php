<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            @foreach($stages as $stage)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="" class="card-img-top" alt="Stage Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $stage->name }}</h5>
                        <p class="card-text">難易度: {{ $stage->difficulty }}</p>
                        <p class="card-text">取得できるアイテム:</p>
                        <ul>
                            {{-- @foreach($stage->items as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach --}}
                        </ul>
                        <a href="{{ route('play', ['stage_id' => $stage->id]) }}" class="btn btn-primary">ステージを選択</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
<form id="userInfoForm" style="display:none;">
    <input type="text" id="userName" name="name" placeholder="ユーザ名">
    <input type="password" id="userPassword" name="password" placeholder="パスワード">
</form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
