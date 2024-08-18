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
                            <form method="POST" action="{{ route('user.login') }}">
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
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
        <script>

            // ------------------------------------------------------------
            const canvas = document.querySelector("canvas");
            const ctx = canvas.getContext("2d");
            const colors = [
            "#b4b2b5",
            "#dfd73f",
            "#6ed2dc",
            "#66cf5d",
            "#c542cb",
            "#d0535e",
            "#3733c9"
            ];
        
            canvas.width = innerWidth;
            canvas.height = innerHeight;
        
            function texts(color) {
            ctx.font = "20vh Bungee Outline";
            ctx.shadowBlur = 30;
            ctx.shadowColor = color;
            ctx.fillStyle = color;
            ctx.setTransform(1, -0.15, 0, 1, 0, -10);
            ctx.fillText("Typing", innerWidth / 2, innerHeight / 2 - 5);
        
            ctx.fillStyle = "white";
            ctx.shadowBlur = 30;
            ctx.shadowColor = color;
            ctx.fillText("Typing", innerWidth / 2, innerHeight / 2);
        
            ctx.font = "18vh Bungee Inline";
            ctx.shadowBlur = 30;
            ctx.shadowColor = color;
            ctx.fillStyle = "#fff";
            ctx.setTransform(1, -0.15, 0, 1, 0, -10);
            ctx.fillText(
                "Game",
                innerWidth / 2,
                innerHeight / 2 + innerHeight / 10
            );
        
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            }
        
            function glitch() {
            // ctx.clearRect(0, 0, canvas.width, canvas.height); // キャンバスを透明にクリア
            texts(colors[Math.floor(Math.random() * colors.length)]);
            ctx.shadowBlur = 0;
            ctx.shadowColor = "none";
            ctx.setTransform(1, 0, 0, 1, 0, 0);
        
            // TVノイズのような効果を追加するためのランダムな点と四角形を描画
            for (let i = 0; i < 1000; i++) {
                ctx.fillStyle = `rgba(255, 255, 255, ${Math.random() * 0.01})`;
                ctx.fillRect(
                Math.floor(Math.random() * innerWidth),
                Math.floor(Math.random() * innerHeight),
                Math.floor(Math.random() * 30) + 1,
                Math.floor(Math.random() * 30) + 1
                );
        
                ctx.fillStyle = `rgba(0,0,0,${Math.random() * 0.1})`;
                ctx.fillRect(
                Math.floor(Math.random() * innerWidth),
                Math.floor(Math.random() * innerHeight),
                Math.floor(Math.random() * 25) + 1,
                Math.floor(Math.random() * 25) + 1
                );
            }
            }
        
            glitch();
        
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    canvas.width = innerWidth;
                    canvas.height = innerHeight;
                    glitch(); // サイズ変更が完了してからグリッチエフェクトを再描画
                }, 250); // 250ミリ秒のデバウンスタイマー
            });
        </script>
</body>
</html>
