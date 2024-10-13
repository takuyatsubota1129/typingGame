<template>
  <div>
    <h1>ゲーム画面</h1>

    <div v-if="!selectedStage">
      <h2>ステージ選択</h2>
      <div v-for="stage in 10" :key="stage" class="mb-2">
        <button @click="selectStage(stage)" class="btn btn-primary">ステージ {{ stage }}</button>
      </div>
    </div>
    <div v-else>
      <h2>ステージ {{ selectedStage }}</h2>
      <p>現在のプレイヤー数: {{ currentPlayers[selectedStage] || 0 }}</p>
      <div v-if="currentWord">
        <p>タイプしてください: {{ currentWord.japanese }}</p>
        <p class="typing-text">
          <span :class="{ 'typed': isMatching, 'mistyped': !isMatching }">{{ userInput }}</span>
          <span class="untyped">{{ remainingText }}</span>
        </p>
        <input v-model="userInput" @input="checkInput" class="form-control mb-2" :disabled="gameCompleted">
      </div>
      <button @click="backToSelection" class="btn btn-secondary">ステージ選択に戻る</button>
    </div>
    <router-link to="/top" class="btn btn-info mt-3">ダッシュボードへ戻る</router-link>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue'
import io from 'socket.io-client'
import axios from 'axios'

export default {
  name: 'Game',
  setup() {
    const selectedStage = ref(null)
    const currentPlayers = ref({})
    const words = ref([])
    const currentWord = ref(null)
    const userInput = ref('')
    const gameCompleted = ref(false)
    const isMatching = ref(true)
    let socket

    const remainingText = computed(() => {
      if (!currentWord.value || !currentWord.value.romaji) return ''
      return currentWord.value.romaji.slice(userInput.value.length)
    })

    const selectStage = (stage) => {
      if (confirm(`ステージ${stage}を選択しますか？`)) {
        selectedStage.value = stage
        socket.emit('joinStage', stage)
        selectRandomWord()
      }
    }

    const backToSelection = () => {
      socket.emit('leaveStage')
      selectedStage.value = null
      currentWord.value = null
      userInput.value = ''
      gameCompleted.value = false
      isMatching.value = true
    }

    const selectRandomWord = () => {
      if (words.value.length > 0) {
        const randomIndex = Math.floor(Math.random() * words.value.length)
        currentWord.value = words.value[randomIndex]
        userInput.value = ''
        isMatching.value = true
      }
    }

    const checkInput = () => {
      if (currentWord.value) {
        isMatching.value = currentWord.value.romaji.toLowerCase().startsWith(userInput.value.toLowerCase())
        if (userInput.value.toLowerCase() === currentWord.value.romaji.toLowerCase()) {
          gameCompleted.value = true
          setTimeout(() => {
            backToSelection()
          }, 1500) // 1.5秒後にステージ選択に戻る
        }
      }
    }

    onMounted(async () => {
      try {
        const response = await axios.get('/api/words', {
          headers: {
            'Accept': 'application/json'
          }
        });
        console.log('Raw response:', response)
        if (response.data && Array.isArray(response.data)) {
          words.value = response.data
          console.log('Loaded words:', words.value)
        } else {
          console.error('Invalid response format:', response.data)
          console.error('Response headers:', response.headers)
        }
      } catch (error) {
        console.error('Error loading words:', error)
        if (error.response) {
          console.error('Error response:', error.response.data)
          console.error('Error status:', error.response.status)
          console.error('Error headers:', error.response.headers)
        }
      }

      // socket = io('http://localhost:3000', {
      socket = io('wss://localhost:3000', {
        withCredentials: true,
        transports: ['websocket']
      })
      socket.on('playerCount', ({ stageId, count }) => {
        currentPlayers.value[stageId] = count
      })
    })

    onUnmounted(() => {
      if (socket) {
        socket.disconnect()
      }
    })

    watch(selectedStage, (newStage) => {
      if (newStage === null) {
        currentWord.value = null
        userInput.value = ''
        gameCompleted.value = false
        isMatching.value = true
      }
    })

    return {
      selectedStage,
      currentPlayers,
      selectStage,
      backToSelection,
      words,
      currentWord,
      userInput,
      checkInput,
      gameCompleted,
      remainingText,
      isMatching
    }
  }
}
</script>

<style scoped>
.typing-text {
  font-size: 1.5em;
  margin-bottom: 10px;
}
.typed {
  color: green;
}
.mistyped {
  color: red;
}
.untyped {
  color: gray;
}
</style>
