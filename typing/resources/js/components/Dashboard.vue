<template>
  <div class="dashboard">
    <h1>ダッシュボード</h1>
    <div class="buttons">
      <router-link to="/game" class="btn btn-primary" :class="{ disabled: isLoggingOut }">ゲームへ</router-link>
      <router-link to="/gacha" class="btn btn-success" :class="{ disabled: isLoggingOut }">ガチャへ</router-link>
      <button @click="logout" class="btn btn-danger" :disabled="isLoggingOut">
        {{ isLoggingOut ? 'ログアウト中...' : 'ログアウト' }}
      </button>
    </div>
    <div v-if="isLoggingOut" class="overlay">
      <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">ログアウト中...</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Dashboard',
  data() {
    return {
      isLoggingOut: false
    }
  },
  methods: {
    async logout() {
      this.isLoggingOut = true;
      try {
        await axios.post('/logout');
        window.location.href = '/';
      } catch (error) {
        console.error('ログアウトエラー:', error);
        this.isLoggingOut = false;
        alert('ログアウトに失敗しました。もう一度お試しください。');
      }
    }
  }
}
</script>

<style scoped>
.dashboard {
  text-align: center;
  padding: 20px;
  position: relative;
}
.buttons {
  margin-top: 20px;
}
.btn {
  margin: 0 10px;
}
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.disabled {
  pointer-events: none;
  opacity: 0.6;
}
</style>