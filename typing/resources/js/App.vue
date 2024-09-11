<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">{{ appName }}</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link to="/top" class="nav-link">ダッシュボード</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/game" class="nav-link">ゲーム</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/gacha" class="nav-link">ガチャ</router-link>
            </li>
          </ul>
          <button @click="logout" class="btn btn-outline-danger">ログアウト</button>
        </div>
      </div>
    </nav>

    <main class="container mt-4">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  data() {
    return {
      appName: 'タイピングゲーム2024'
    }
  },
  methods: {
    async logout() {
      try {
        await axios.post('/logout');
        // ログアウト後、ログインページにリダイレクト
        window.location.href = '/';
      } catch (error) {
        console.error('ログアウトエラー:', error);
      }
    }
  }
}
</script>