<template>
  <div class="min-h-screen bg-cream">
    <!-- Quiz header -->
    <div class="fixed inset-x-0 top-0 z-40 bg-white border-b border-[var(--border-soft)] shadow-card">
      <div class="container-app h-16 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
          <RouterLink to="/courses" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-cream-soft hover:text-navy transition-all border border-[var(--border)]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
          </RouterLink>
          <div class="min-w-0">
            <p class="font-display font-bold text-navy text-sm truncate">{{ quiz?.title || 'Quiz' }}</p>
            <p class="text-xs text-slate-400">{{ currentIndex + 1 }}/{{ questions.length }} câu hỏi</p>
          </div>
        </div>

        <!-- Progress bar -->
        <div class="flex-1 max-w-xs hidden sm:block">
          <div class="h-2 bg-cream-soft rounded-full overflow-hidden border border-[var(--border)]">
            <div
              class="h-full bg-navy rounded-full transition-all duration-500"
              :style="{ width: `${progress}%` }"
            />
          </div>
        </div>

        <!-- Timer -->
        <div :class="['flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-bold border', timeLeft < 60 ? 'bg-danger-50 text-danger-700 border-danger-200' : 'bg-cream-soft text-navy border-[var(--border)]']">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ formatTime(timeLeft) }}
        </div>
      </div>
    </div>

    <div class="pt-24 pb-16 container-app max-w-3xl">
      <!-- Result screen -->
      <div v-if="showResult" class="card p-8 text-center mt-8">
        <div class="w-20 h-20 rounded-3xl mx-auto mb-5 flex items-center justify-center text-4xl"
          :class="score >= 70 ? 'bg-success-50' : 'bg-warning-50'">
          {{ score >= 70 ? '🎉' : '📚' }}
        </div>
        <h2 class="font-display text-3xl font-bold text-navy mb-2">
          {{ score >= 70 ? 'Xuất sắc!' : 'Cố gắng hơn nhé!' }}
        </h2>
        <p class="text-slate-500 mb-6">Bạn đã hoàn thành bài quiz</p>
        <div class="grid grid-cols-3 gap-4 mb-8">
          <div class="card-cream p-4 rounded-2xl text-center">
            <div class="font-display text-3xl font-bold text-navy">{{ score }}%</div>
            <div class="text-xs text-slate-500 mt-1">Điểm số</div>
          </div>
          <div class="card-cream p-4 rounded-2xl text-center">
            <div class="font-display text-3xl font-bold text-success-600">{{ correctCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Đúng</div>
          </div>
          <div class="card-cream p-4 rounded-2xl text-center">
            <div class="font-display text-3xl font-bold text-danger-600">{{ questions.length - correctCount }}</div>
            <div class="text-xs text-slate-500 mt-1">Sai</div>
          </div>
        </div>
        <div class="flex gap-3 justify-center">
          <AppButton variant="secondary" @click="restart">Làm lại</AppButton>
          <AppButton variant="primary" to="/student/quiz-history">Xem lịch sử</AppButton>
        </div>
      </div>

      <!-- Question -->
      <div v-else-if="currentQuestion" class="mt-6 space-y-5">
        <div class="card p-6">
          <div class="flex items-start gap-3 mb-6">
            <span class="w-8 h-8 rounded-xl bg-navy text-white text-sm font-bold flex items-center justify-center shrink-0">
              {{ currentIndex + 1 }}
            </span>
            <p class="font-semibold text-navy text-base leading-relaxed">{{ currentQuestion.question }}</p>
          </div>

          <div class="space-y-3">
            <button
              v-for="(opt, i) in currentQuestion.options" :key="i"
              @click="selectAnswer(i)"
              :disabled="answered"
              :class="[
                'w-full text-left px-4 py-3.5 rounded-2xl border text-sm font-medium transition-all',
                getOptionClass(i)
              ]"
            >
              <span class="inline-flex w-6 h-6 rounded-lg items-center justify-center text-xs font-bold mr-3 shrink-0"
                :class="selectedAnswer === i ? 'bg-white/30' : 'bg-cream-soft'">
                {{ String.fromCharCode(65 + i) }}
              </span>
              {{ opt }}
            </button>
          </div>

          <div v-if="answered && currentQuestion.explanation" class="mt-4 p-4 bg-gold-pale rounded-2xl border border-gold/20">
            <p class="text-sm text-slate-700 leading-relaxed">
              <span class="font-bold text-navy">Giải thích: </span>{{ currentQuestion.explanation }}
            </p>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <button @click="prevQuestion" :disabled="currentIndex === 0" class="btn btn-md btn-secondary disabled:opacity-40">
            ← Câu trước
          </button>
          <div class="flex gap-1.5">
            <button
              v-for="(_, i) in questions" :key="i"
              @click="currentIndex = i"
              :class="[
                'w-7 h-7 rounded-lg text-xs font-bold transition-all',
                i === currentIndex ? 'bg-navy text-white' :
                answers[i] !== undefined ? 'bg-gold-pale text-navy border border-gold/30' :
                'bg-cream-soft text-slate-400 border border-[var(--border)]'
              ]"
            >{{ i + 1 }}</button>
          </div>
          <button v-if="currentIndex < questions.length - 1" @click="nextQuestion" class="btn btn-md btn-primary">
            Câu tiếp →
          </button>
          <button v-else @click="submitQuiz" class="btn btn-md btn-gold" style="background:var(--gold);color:#fff;border-color:var(--gold)">
            Nộp bài
          </button>
        </div>
      </div>

      <Spinner v-else />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import Spinner from '../components/Spinner.vue'
import AppButton from '../components/ui/AppButton.vue'

const quiz           = ref({ title: 'Bài kiểm tra từ vựng' })
const questions      = ref([])
const currentIndex   = ref(0)
const answers        = ref({})
const selectedAnswer = ref(null)
const answered       = ref(false)
const showResult     = ref(false)
const timeLeft       = ref(1800)
let timer            = null

const currentQuestion = computed(() => questions.value[currentIndex.value])
const progress        = computed(() => ((currentIndex.value + 1) / questions.value.length) * 100)
const correctCount    = computed(() => Object.entries(answers.value).filter(([i, a]) => a === questions.value[i]?.correct).length)
const score           = computed(() => Math.round((correctCount.value / questions.value.length) * 100))

function formatTime(s) {
  const m = Math.floor(s / 60)
  const sec = s % 60
  return `${m}:${sec.toString().padStart(2, '0')}`
}

function selectAnswer(i) {
  if (answered.value) return
  selectedAnswer.value = i
  answered.value = true
  answers.value[currentIndex.value] = i
}

function getOptionClass(i) {
  if (!answered.value) {
    return selectedAnswer.value === i
      ? 'border-navy bg-navy/5 text-navy'
      : 'border-[var(--border)] bg-white hover:border-navy/30 hover:bg-cream-soft text-slate-700'
  }
  if (i === currentQuestion.value?.correct) return 'border-success-400 bg-success-50 text-success-800'
  if (i === selectedAnswer.value)           return 'border-danger-400 bg-danger-50 text-danger-800'
  return 'border-[var(--border)] bg-white text-slate-400'
}

function nextQuestion() {
  if (currentIndex.value < questions.value.length - 1) {
    currentIndex.value++
    selectedAnswer.value = answers.value[currentIndex.value] ?? null
    answered.value = selectedAnswer.value !== null
  }
}

function prevQuestion() {
  if (currentIndex.value > 0) {
    currentIndex.value--
    selectedAnswer.value = answers.value[currentIndex.value] ?? null
    answered.value = selectedAnswer.value !== null
  }
}

function submitQuiz() { clearInterval(timer); showResult.value = true }

function restart() {
  answers.value = {}; currentIndex.value = 0; selectedAnswer.value = null
  answered.value = false; showResult.value = false; timeLeft.value = 1800
  startTimer()
}

function startTimer() {
  timer = setInterval(() => {
    if (timeLeft.value > 0) timeLeft.value--
    else submitQuiz()
  }, 1000)
}

onMounted(() => {
  questions.value = [
    { question: 'What is the meaning of "eloquent"?', options: ['Nói lưu loát', 'Im lặng', 'Tức giận', 'Vui vẻ'], correct: 0, explanation: '"Eloquent" có nghĩa là nói lưu loát, hùng hồn.' },
    { question: 'Choose the correct form: "She ___ to school every day."', options: ['go', 'goes', 'going', 'gone'], correct: 1, explanation: 'Chủ ngữ "She" (ngôi 3 số ít) dùng "goes" ở thì hiện tại đơn.' },
    { question: 'What does "perseverance" mean?', options: ['Sự lười biếng', 'Sự kiên trì', 'Sự tức giận', 'Sự vui vẻ'], correct: 1, explanation: '"Perseverance" có nghĩa là sự kiên trì, bền bỉ.' },
  ]
  startTimer()
})

onBeforeUnmount(() => clearInterval(timer))
</script>
