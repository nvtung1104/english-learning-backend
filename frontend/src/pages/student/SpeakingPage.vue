<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Speaking</h1>
        <p class="mt-1 text-sm theme-text-soft">Luyện phát âm và phản xạ nói</p>
      </div>

      <!-- Intro -->
      <div class="rounded-[30px] p-6 text-white space-y-3" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%)">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/50">Luyện tập hôm nay</p>
        <h2 class="text-2xl font-semibold">Luyện nói theo chủ đề</h2>
        <p class="text-sm leading-7 text-white/75">
          Chọn chủ đề, đọc to câu mẫu và ghi âm lại để tự đánh giá phát âm của bạn.
        </p>
      </div>

      <!-- Topic selector -->
      <div class="theme-card rounded-[30px] p-6 space-y-4">
        <h2 class="text-lg font-semibold theme-text">Chọn chủ đề</h2>
        <div class="grid gap-3 sm:grid-cols-2">
          <button
            v-for="topic in topics"
            :key="topic.id"
            type="button"
            class="topic-btn rounded-[20px] p-4 text-left transition"
            :class="{ 'topic-btn--active': activeTopic?.id === topic.id }"
            @click="selectTopic(topic)"
          >
            <p class="text-xl mb-1">{{ topic.icon }}</p>
            <p class="font-semibold theme-text text-sm">{{ topic.title }}</p>
            <p class="text-xs theme-text-muted mt-0.5">{{ topic.desc }}</p>
          </button>
        </div>
      </div>

      <!-- Practice area -->
      <div v-if="activeTopic" class="theme-card rounded-[30px] p-6 space-y-5">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold theme-text">{{ activeTopic.title }}</h2>
          <button type="button" class="text-xs font-semibold" style="color: var(--accent)" @click="nextSentence">
            Câu tiếp theo →
          </button>
        </div>

        <!-- Sentence -->
        <div class="rounded-[24px] p-5 text-center space-y-2" style="background: var(--surface-strong)">
          <p class="text-xl font-semibold theme-text leading-8">{{ currentSentence }}</p>
          <p class="text-sm theme-text-muted italic">{{ currentTranslation }}</p>
        </div>

        <!-- Record -->
        <div class="flex flex-col items-center gap-4">
          <button
            type="button"
            class="record-btn grid h-20 w-20 place-items-center rounded-full text-3xl transition"
            :class="{ 'record-btn--active': isRecording }"
            @click="toggleRecord"
          >
            {{ isRecording ? '⏹️' : '🎙️' }}
          </button>
          <p class="text-sm theme-text-muted">
            {{ isRecording ? 'Đang ghi âm... Nhấn để dừng' : 'Nhấn để bắt đầu ghi âm' }}
          </p>
          <audio v-if="recordedUrl" :src="recordedUrl" controls class="w-full h-9 rounded-xl"></audio>
        </div>

        <!-- Tip -->
        <div class="rounded-[20px] p-4 space-y-1" style="background: var(--surface-muted); border: 1px solid var(--border)">
          <p class="text-xs font-semibold uppercase tracking-wider theme-text-muted">Mẹo phát âm</p>
          <p class="text-sm theme-text-soft">{{ activeTopic.tip }}</p>
        </div>
      </div>

      <div v-else class="theme-card rounded-[30px] p-10 text-center">
        <p class="text-4xl">🎙️</p>
        <p class="mt-3 text-sm theme-text-soft">Chọn một chủ đề để bắt đầu luyện nói</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useToast } from '@/composables/useToast'

const toast = useToast()

const topics = [
  {
    id: 1, icon: '👋', title: 'Giới thiệu bản thân', desc: 'Hello, my name is...',
    tip: 'Chú ý âm cuối của từ, đặc biệt là /s/ và /z/ trong tiếng Anh.',
    sentences: [
      { en: 'Hello, my name is Alex and I am a student.', vi: 'Xin chào, tên tôi là Alex và tôi là học sinh.' },
      { en: 'I am from Vietnam and I love learning English.', vi: 'Tôi đến từ Việt Nam và tôi thích học tiếng Anh.' },
      { en: 'Nice to meet you! How are you today?', vi: 'Rất vui được gặp bạn! Hôm nay bạn thế nào?' },
    ],
  },
  {
    id: 2, icon: '🌤️', title: 'Thời tiết', desc: "What's the weather like?",
    tip: 'Luyện phát âm "th" trong "the", "weather" — đặt lưỡi giữa hai hàng răng.',
    sentences: [
      { en: 'The weather today is sunny and warm.', vi: 'Thời tiết hôm nay nắng và ấm áp.' },
      { en: 'It looks like it might rain this afternoon.', vi: 'Có vẻ như chiều nay trời sẽ mưa.' },
      { en: 'I love the cool breeze in the morning.', vi: 'Tôi thích làn gió mát vào buổi sáng.' },
    ],
  },
  {
    id: 3, icon: '🍜', title: 'Đặt món ăn', desc: 'I would like to order...',
    tip: "Dùng \"I'd like\" thay vì \"I want\" để lịch sự hơn khi đặt món.",
    sentences: [
      { en: "I'd like to order a bowl of pho, please.", vi: 'Tôi muốn gọi một tô phở, làm ơn.' },
      { en: 'Could I have the menu, please?', vi: 'Cho tôi xem thực đơn được không?' },
      { en: 'The food here is absolutely delicious!', vi: 'Đồ ăn ở đây thực sự rất ngon!' },
    ],
  },
  {
    id: 4, icon: '🏫', title: 'Trường học', desc: 'My favorite subject is...',
    tip: 'Nhấn trọng âm đúng: "SUBject" (danh từ) vs "subJECT" (động từ).',
    sentences: [
      { en: 'My favorite subject is English literature.', vi: 'Môn học yêu thích của tôi là văn học Anh.' },
      { en: 'I study at a university in Ho Chi Minh City.', vi: 'Tôi học tại một trường đại học ở TP.HCM.' },
      { en: 'The library is a great place to study quietly.', vi: 'Thư viện là nơi tuyệt vời để học yên tĩnh.' },
    ],
  },
]

const activeTopic = ref(null)
const sentenceIndex = ref(0)
const currentSentence = ref('')
const currentTranslation = ref('')
const isRecording = ref(false)
const recordedUrl = ref('')
let mediaRecorder = null
let chunks = []

function selectTopic(topic) {
  activeTopic.value = topic
  sentenceIndex.value = 0
  currentSentence.value = topic.sentences[0].en
  currentTranslation.value = topic.sentences[0].vi
  isRecording.value = false
  recordedUrl.value = ''
}

function nextSentence() {
  if (!activeTopic.value) return
  sentenceIndex.value = (sentenceIndex.value + 1) % activeTopic.value.sentences.length
  currentSentence.value = activeTopic.value.sentences[sentenceIndex.value].en
  currentTranslation.value = activeTopic.value.sentences[sentenceIndex.value].vi
  recordedUrl.value = ''
}

async function toggleRecord() {
  if (isRecording.value) {
    mediaRecorder?.stop()
    isRecording.value = false
    return
  }
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
    chunks = []
    mediaRecorder = new MediaRecorder(stream)
    mediaRecorder.ondataavailable = e => chunks.push(e.data)
    mediaRecorder.onstop = () => {
      const blob = new Blob(chunks, { type: 'audio/webm' })
      recordedUrl.value = URL.createObjectURL(blob)
      stream.getTracks().forEach(t => t.stop())
    }
    mediaRecorder.start()
    isRecording.value = true
  } catch {
    toast.error('Không thể truy cập microphone. Vui lòng cấp quyền.')
  }
}
</script>

<style scoped>
.topic-btn {
  border: 1.5px solid var(--border);
  background: var(--surface-muted);
}
.topic-btn:hover { border-color: var(--accent); }
.topic-btn--active {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 8%, var(--surface-muted));
}
.record-btn {
  border: 3px solid var(--border);
  background: var(--surface-muted);
}
.record-btn:hover { border-color: var(--accent); transform: scale(1.05); }
.record-btn--active {
  border-color: #ef4444;
  background: color-mix(in srgb, #ef4444 10%, var(--surface-muted));
  animation: pulse-ring 1.2s ease-in-out infinite;
}
@keyframes pulse-ring {
  0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.3); }
  50%       { box-shadow: 0 0 0 12px rgba(239,68,68,0); }
}
</style>
