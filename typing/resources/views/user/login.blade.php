<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- Bootstrap CSSのリンク -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>
<body class="bg-light">
    <div class="bg-image">
        <div id="tv-noise" style="position: absolute; width: 100%; height: 100%;"></div>
        <canvas></canvas>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card mt-5">
                        <h5 class="card-header">User Login</h5>
                        <div class="card-body">
                            <form id="loginForm" method="POST" action="{{ route('user.login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <button type="button" onclick="location.href='/register'" class="btn btn-link">新規登録</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JSのリンク -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // フォームのデフォルト送信を防ぐ

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('{{ route('user.login') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('authToken', data.token); // トークンを保存
                    window.location.href = '/top'; // ログイン成功後にリダイレクト
                } else {
                    alert(data.error || 'ログインに失敗しました。');
                }
            } catch (error) {
                console.error('ログインエラー:', error);
                alert('ログイン中にエラーが発生しました。');
            }
        });
    </script>
</body>
</html>
