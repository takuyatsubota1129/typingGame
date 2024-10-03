<template>
  <div class="container mt-5">
    <h1 class="mb-4">Instant Win Result</h1>
    <div v-if="isLoading" class="loader-overlay">
      <div class="loader"></div>
    </div>
    <div v-else-if="results.length > 0" class="cards">
      <div 
        v-for="(result, index) in results" 
        :key="index" 
        class="card-wrapper anim-box slidein" 
        :class="[getCardClass(result.prize.probability), { 'is-animated': isAnimated }]"
        :style="{ animationDelay: `${index * 0.1}s` }"
        @mousemove="updateCard($event, index)"
        @mouseleave="resetCard(index)"
        @click="handleCardClick(result.image_path)"
      >
      <div class="card" :style="[cardStyles[index], { backgroundImage: `url(${cardBackgroundImage})` }]">
          <img :src="result.image_path" alt="Character Image" class="character-image">
          <div class="card-body">
            <p class="card-text">{{ result.prize.name }} ({{ result.prize.probability }}%)</p>
            <p class="card-text">{{ result.winner_name.last_name }} {{ result.winner_name.first_name }} ({{ result.winner_name.gender }})</p>
          </div>
        </div>
      </div>
    </div>
    <button @click="tryLuck" class="btn btn-primary mr-2" :disabled="isLoading">Try Again</button>
    <button @click="tryTenTimes" class="btn btn-secondary" :disabled="isLoading">Try 10 Times</button>
    <div v-if="showBackground" class="background-image" :style="{ backgroundImage: `url(${cardBackgroundImage})` }"></div>
    <!-- モーダル -->
    <div v-if="showModal" class="modal" @click="closeModal">
      <img :src="currentImage" class="modal-image" />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, reactive } from 'vue';

export default {
  name: 'InstantWin',
  setup() {
    const results = ref([]);
    const isLoading = ref(false);
    const isAnimated = ref(false);
    const cardStyles = reactive([]);
    const cardBackgroundImage = `${import.meta.env.VITE_APP_URL}/storage/card/background01.png`;
    
    // モーダル表示の状態を管理
    const showModal = ref(false);
    const currentImage = ref('');

    const initializeCardStyles = () => {
      cardStyles.splice(0, cardStyles.length, ...results.value.map(() => ({
        transform: 'rotateX(0deg) rotateY(0deg)',
        '--mx': '50%',
        '--my': '50%'
      })));
    };

    const updateCard = (event, index) => {
      const card = cardStyles[index];
      card.transform = `rotateX(${(event.clientY - event.target.getBoundingClientRect().top) / 10 - 5}deg) rotateY(${(event.clientX - event.target.getBoundingClientRect().left) / 10 - 5}deg)`;
    };

    const resetCard = (index) => {
      cardStyles[index] = {
        transform: 'rotateX(0deg) rotateY(0deg)',
        '--mx': '50%',
        '--my': '50%'
      };
    };

    const getCardClass = (probability) => {
      if (probability >= 75) return 'high-probability';
      if (probability >= 50) return 'medium-probability';
      if (probability >= 25) return 'low-probability';
      if (probability > 0.1) return 'rare-probability';
      return 'ultra-rare-probability';
    };

    const handleCardClick = (imagePath) => {
      console.log("カードがクリックされました:", imagePath);
      currentImage.value = imagePath; // クリックされた画像のパスを設定
      showModal.value = true; // モーダルを表示
    };

    const closeModal = () => {
      showModal.value = false; // モーダルを非表示
    };

    const checkAuth = () => {
      // 認証チェックのロジックを実装
      return true; // 仮の実装
    };

    const handleError = (error) => {
      console.error('Error:', error);
      alert('エラーが発生しました。もう一度お試しください。');
      document.body.classList.remove('loader-active'); // エラー時にローダーを解除
    };

    const tryLuck = async () => {
      if (!checkAuth()) return;
      isLoading.value = true; // ローディング開始
      document.body.classList.add('loader-active'); // ローダーを全画面に適用
      isAnimated.value = false;
      try {
        const response = await axios.post('/api/instantwin/select');
        results.value = response.data.results;
        initializeCardStyles();
        isAnimated.value = true;
      } catch (error) {
        handleError(error);
      } finally {
        isLoading.value = false; // ローディング終了
        document.body.classList.remove('loader-active'); // ローダーを解除
      }
    };

    const tryTenTimes = async () => {
      if (!checkAuth()) return;
      isLoading.value = true; // ローディング開始
      document.body.classList.add('loader-active'); // ローダーを全画面に適用
      isAnimated.value = false;
      try {
        const response = await axios.post('/api/instantwin/selectTen');
        results.value = response.data.results;
        initializeCardStyles();
        isAnimated.value = true;
      } catch (error) {
        handleError(error);
      } finally {
        isLoading.value = false; // ローディング終了
        document.body.classList.remove('loader-active'); // ローダーを解除
      }
    };

    const showBackground = ref(false); // 背景表示の状態を管理

    const toggleBackground = () => {
      showBackground.value = !showBackground.value; // 背景の表示/非表示を切り替え
    };

    return {
      results,
      isLoading,
      isAnimated,
      cardStyles,
      updateCard,
      resetCard,
      getCardClass,
      cardBackgroundImage,
      tryLuck,
      tryTenTimes,
      showBackground,
      toggleBackground,
      handleCardClick,
      showModal,
      currentImage,
      closeModal
    };
  }
}
</script>

<style>
.cards {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.card-wrapper {
  width: 200px;
  height: 280px;
  position: relative;
  margin: 15px;
  perspective: 1000px;
  /* overflow: hidden; を削除 */
}

.card {
  width: 100%;
  height: 100%;
  position: absolute;
  border-radius: 5% / 3.5%;
  box-shadow: 
    -5px -5px 5px -5px var(--color1), 
    5px 5px 5px -5px var(--color2), 
    0 0 5px 0px rgba(255,255,255,0),
    0 55px 35px -20px rgba(0, 0, 0, 0.5);
  transition: transform 0.5s ease, box-shadow 0.2s ease;
  will-change: transform, filter;
  background: var(--card-bg);
  border: 2px solid var(--card-border);
  transform-origin: center;
  transform-style: preserve-3d;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.card:hover {
  box-shadow: 
    -20px -20px 30px -25px var(--color1), 
    20px 20px 30px -25px var(--color2), 
    -7px -7px 10px -5px var(--color1), 
    7px 7px 10px -5px var(--color2), 
    0 0 13px 4px rgba(255,255,255,0.3),
    0 55px 35px -20px rgba(0, 0, 0, 0.5);
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--card-inner-bg);
  z-index: 1;
  border-radius: 5% / 3.5%;
}

.card-body {
  position: relative;
  z-index: 2;
  /* background-color: rgba(255, 255, 255, 0.7); */
  border-radius: 5px;
  margin: 10px;
  padding: 10px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end; /* 下に配置 */
}

.card-text {
  color: white; /* フォントの色を白に変更 */
  font-family: 'DotGothic16', sans-serif; /* デジタルな日本語フォントを適用 */
  font-size: 1rem;
}

.card-wrapper.high-probability {
  --color1: #fac;
  --color2: #ddccaa;
  --card-bg: linear-gradient(135deg, #fac, #ddccaa);
  --card-border: #fac;
  --card-inner-bg: linear-gradient(135deg, rgba(255,170,204,0.3), rgba(221,204,170,0.3));
}

.card-wrapper.medium-probability {
  --color1: #54a29e;
  --color2: #a79d66;
  --card-bg: linear-gradient(135deg, #54a29e, #a79d66);
  --card-border: #54a29e;
  --card-inner-bg: linear-gradient(135deg, rgba(84,162,158,0.3), rgba(167,157,102,0.3));
}

.card-wrapper.low-probability {
  --color1: #9795f0;
  --color2: #fbc8d4;
  --card-bg: linear-gradient(135deg, #9795f0, #fbc8d4);
  --card-border: #9795f0;
  --card-inner-bg: linear-gradient(135deg, rgba(151,149,240,0.3), rgba(251,200,212,0.3));
}

.card-wrapper.rare-probability {
  --color1: #f6d365;
  --color2: #fda085;
  --card-bg: linear-gradient(135deg, #f6d365, #fda085);
  --card-border: #f6d365;
  --card-inner-bg: linear-gradient(135deg, rgba(246,211,101,0.3), rgba(253,160,133,0.3));
}

.card-wrapper.ultra-rare-probability {
  --color1: #ff00ff;
  --color2: #00ffff;
  --card-bg: linear-gradient(135deg, #ff00ff, #00ffff);
  --card-border: #ff00ff;
  --card-inner-bg: linear-gradient(135deg, rgba(255,0,255,0.3), rgba(0,255,255,0.3));
}

.card-title {
  font-size: 1rem;
}

.card-text {
  font-size: 0.8rem;
}

.btn {
  margin-right: 10px;
}

.anim-box.slidein {
  opacity: 0;
  transform: translateX(180px);
}

.anim-box.slidein.is-animated {
  animation: slideIn 1s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}

@keyframes slideIn {
  0% {
    transform: translateX(180px);
    opacity: 0;
  }
  100% {
    transform: translateX(0);
  }
  40%,100% {
    opacity: 1;
  }
}

.character-image {
    height: auto;
    position: absolute;
    top: 10%;
    left: 10%;
    z-index: 1;
    border-radius: 5% / 3.5%;
    object-fit: cover;
    width: 80%;
    height: 45%;
}

.background-image {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(1.5); /* 中央に配置し、拡大 */
  width: auto; /* 幅を自動に設定 */
  height: auto; /* 高さを自動に設定 */
  max-width: 90%; /* 最大幅を設定 */
  max-height: 90%; /* 最大高さを設定 */
  z-index: 999; /* 背景を最前面に配置 */
  background-size: cover;
  background-position: center;
  opacity: 1; /* 不透明に設定 */
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.8); /* 半透明の黒 */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* 最前面に表示 */
}

.modal-image {
  max-width: 90%;
  max-height: 90%;
  border: solid 5px #fff; /* 白い枠線 */
}

.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 1); /* 半透明の背景 */
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999; /* 他の要素の上に表示 */
}

.loader {
  z-index: 10000; /* ローダーをさらに前面に表示 */
}
</style>