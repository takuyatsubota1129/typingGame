<template>
  <div>
    <h1>ガチャ画面</h1>
    <InstantWin />
    <router-link to="/top" class="btn btn-secondary">ダッシュボードへ戻る</router-link>
  </div>
</template>

<script>
import InstantWin from './InstantWin.vue';

export default {
  name: 'Gacha',
  components: {
    InstantWin
  }
}
</script>