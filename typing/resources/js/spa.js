// SPAのルーティングを管理するオブジェクト
const routes = {
    '/top': { title: 'ダッシュボード', render: renderDashboard },
    '/game': { title: 'ゲーム', render: renderGame },
    '/gacha': { title: 'ガチャ', render: renderGacha }
};

// ページ遷移を処理する関数
function navigate(path) {
    const route = routes[path];
    if (route) {
        document.title = route.title;
        route.render();
        history.pushState(null, route.title, path);
    }
}

// 各ページのレンダリング関数
function renderDashboard() {
    document.getElementById('app').innerHTML = `
        <h1>ダッシュボード</h1>
        <a href="#" onclick="navigate('/game'); return false;">ゲーム画面へ</a>
        <a href="#" onclick="navigate('/gacha'); return false;">ガチャ画面へ</a>
    `;
}

function renderGame() {
    document.getElementById('app').innerHTML = `
        <h1>ゲーム画面</h1>
        <a href="#" onclick="navigate('/top'); return false;">ダッシュボードへ戻る</a>
    `;
}

function renderGacha() {
    document.getElementById('app').innerHTML = `
        <h1>ガチャ画面</h1>
        <a href="#" onclick="navigate('/top'); return false;">ダッシュボードへ戻る</a>
    `;
}

// 初期化関数
function initSPA() {
    window.addEventListener('popstate', () => {
        const path = window.location.pathname;
        const route = routes[path];
        if (route) {
            route.render();
        }
    });

    // 初期ページのレンダリング
    const path = window.location.pathname;
    const route = routes[path] || routes['/top'];
    route.render();
}

// SPAの初期化
document.addEventListener('DOMContentLoaded', initSPA);