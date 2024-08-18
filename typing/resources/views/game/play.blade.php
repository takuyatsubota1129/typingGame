<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>タイピングゲーム</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    #word1, #word2, #word3 { font-size: 20px; }
    #romaji1,#romaji2,#romaji3 { font-size: 20px; }
    body { font-family: Arial, sans-serif; text-align: center; }
    #fullRomaji { font-size: 16px; color: #888; margin-top: 5px; min-height: 20px; }
    #score { margin-top: 20px; }
    #timer { margin-top: 20px; font-size: 20px; color: red; }
    input[type="text"] {
        font-size: 16px;
        padding: 10px;
        margin-top: 5px;
        height: 40px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        width: 100%;
    }
    .highlight { background-color: yellow; }
    .typing-game .row > div {
        display: flex;
        justify-content: center;
        flex: 0 0 50%; /* flex-basisを50%に設定して、基本の幅を半分にする */
        max-width: 50%; /* 最大幅を50%に設定 */
    }
    .typing-game .row > div > div {
        text-align: center;
        margin: auto;
    }
    .typing-game .row {
        justify-content: center; /* 中央揃え */
    }
    /* コンテナの最大幅を調整 */
    .container {
        max-width: 600px; /* または必要に応じてさらに狭く設定 */
    }
    .current-word { background-color: orange; }
</style>

</head>
<body>
<div class="typing-game">
    <div class="container mt-5">
        <div class="row">
            <div class="col d-flex align-items-stretch">
                <div>
                    <div id="word1">ここに単語が表示されます</div>
                    <div id="romaji1">ここにローマ字が表示されます</div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div>
                    <div id="word2">ここに単語が表示されます</div>
                    <div id="romaji2">ここにローマ字が表示されます</div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div>
                    <div id="word3">ここに単語が表示されます</div>
                    <div id="romaji3">ここにローマ字が表示されます</div>
                </div>
            </div>
        </div>
        <div id="fullRomaji">ここに現在のローマ字入力が表示されます</div>
        <input type="text" id="inputField" autofocus>
        <p id="typing-speed">タイピングスピード: 0 文字/秒</p>
        <div id="score">スコア: 0</div>
        <div id="timer">残り時間: 60秒</div>
    </div>
</div>
<form id="userInfoForm" style="display:none;">
    <input type="text" id="userName" name="name" placeholder="ユーザ名">
    <input type="password" id="userPassword" name="password" placeholder="パスワード">
</form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let words = @json($words); // Laravelから単語リストを取得

    let score = 0;
    let currentWords = [];
    let timeLeft = 60;
    let gameStarted = false;
    let typedChars = 0; // 追加: タイピングした文字数を記録する変数
    let timeElapsed=0;
    document.getElementById('inputField').disabled = false;
    pickWords();

    // 追加: タイピングスピードを更新する関数
    function updateTypingSpeed() {
        let speed = typedChars / timeElapsed;
        speed = Math.round(speed * 100) / 100;
        document.getElementById('typing-speed').textContent = 'タイピングスピード: ' + speed + ' 文字/秒';
    }

    // ゲーム開始時に10秒ごとにタイピングスピードを更新するタイマーを設定
    setInterval(function() {
        if (gameStarted) {
            timeElapsed += 10;
            updateTypingSpeed();
        }
    }, 10000);
    
    function pickWords() {
        currentWords = words.sort(() => 0.5 - Math.random()).slice(0, 3);
        currentWords.forEach((word, index) => {
            document.getElementById(`word${index + 1}`).textContent = word.japanese;
            document.getElementById(`romaji${index + 1}`).textContent = '(' + word.romaji + ')';
        });
        document.getElementById('inputField').value = '';
        updateInputDisplay();
    }

    function checkInput() {
        if (!gameStarted) return;
        const input = document.getElementById('inputField').value.toLowerCase();
        if (currentWords.some(word => word.romaji.startsWith(input))) {
            updateInputDisplay();
            if (currentWords.some(word => word.romaji === input)) {
                score++;
                typedChars =typedChars+ input.length; // 追加: 入力が行われるたびに、タイピングした文字数を更新
                document.getElementById('score').textContent = 'スコア: ' + score;
                pickWords();
            }
        } else {
            updateInputDisplay(true);
        }
    }

    function updateInputDisplay() {
        const input = document.getElementById('inputField').value.toLowerCase();
        if (input.length === 0) {
            document.getElementById('word1').classList.remove('current-word');
            document.getElementById('word2').classList.remove('current-word');
            document.getElementById('word3').classList.remove('current-word');
            document.getElementById('fullRomaji').innerHTML = '';
            return;
        }

        let isMatch = false;
        currentWords.forEach((word, index) => {
            if (word.romaji.startsWith(input)) {
                document.getElementById(`word${index + 1}`).classList.add('current-word');
                isMatch = true;
            } else {
                document.getElementById(`word${index + 1}`).classList.remove('current-word');
            }
        });

        let displayText = '';
        if (isMatch) {
            displayText = input;
        } else {
            displayText = `<span class="highlight">${input}</span>`;
        }
        document.getElementById('fullRomaji').innerHTML = displayText;
    }

    function resetGame() {
        score = 0;
        timeLeft = 60;
        typedChars = 0; // 追加: ゲームがリセットされたときに、タイピングした文字数をリセット
        timeElapsed=0;
        document.getElementById('score').textContent = 'スコア: 0';
        document.getElementById('timer').textContent = '残り時間: 60秒';
        document.getElementById('inputField').disabled = false;
        startGame();
    }

    function updateTimer() {
        if (!gameStarted) return;
        timeLeft--;
        document.getElementById('timer').textContent = '残り時間: ' + timeLeft + '秒';
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            document.getElementById('inputField').disabled = true;
            alert('時間切れ！ あなたのスコアは ' + score + ' です。');
            let typingSpeed = typedChars / 60; // 追加: タイピング速度を計算
            alert('あなたのタイピング速度は ' + typingSpeed + ' 文字/秒です。'); // 追加: タイピング速度を表示
            gameStarted = false;
            if (confirm('もう一度プレイしますか？')) {
                resetGame();
            }
        }
    }

    function startGame() {
        if (gameStarted) return;
        gameStarted = true;
        document.getElementById('inputField').disabled = false;
        document.getElementById('inputField').focus();
        pickWords();
        timerInterval = setInterval(updateTimer, 1000);
    }

    document.getElementById('inputField').addEventListener('input', () => {
        checkInput();
        updateInputDisplay();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !gameStarted) {
            startGame();
        }
    });
});

// function sendUserData(name, password) {
//     fetch('./', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         body: `name=${encodeURIComponent(name)}&password=${encodeURIComponent(password)}`
//     })
//     .then(response => response.text())
//     .then(result => {
//         console.log('Success:', result);
//         alert('ユーザデータを保存しました。');
//     })
//     .catch(error => {
//         console.error('Error:', error);
//     });
// }
function sendUserData(userId, speed, accuracy) {
    // POSTリクエストのボディを作成します。
    let body = new FormData();
    body.append('user_id', userId);
    body.append('speed', speed);
    body.append('accuracy', accuracy);

    // POSTリクエストを送信します。
    fetch('/measurements', {
        method: 'POST',
        body: body
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        alert('ユーザデータを保存しました。');
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
function gameOver() {
    const name = document.getElementById('userName').value;
    const password = document.getElementById('userPassword').value;
    sendUserData(name, password);
}
</script>
</body>
</html>
