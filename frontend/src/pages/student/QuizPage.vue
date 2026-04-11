<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <!-- Loading -->
      <div v-if="loading" class="space-y-4 animate-pulse">
        <div class="h-8 rounded skeleton w-1/2"></div>
        <div v-for="i in 3" :key="i" class="theme-card rounded-[28px] p-5 space-y-3">
          <div class="h-5 rounded skeleton w-3/4"></div>
          <div v-for="j in 4" :key="j" class="h-10 rounded-xl skeleton"></div>
        </div>
      </div>

      <!-- Result -->
      <template v-else-if="result">
        <div class="theme-card rounded-[36px] p-8 text-center space-y-4">
          <p class="text-5xl">{{ result.score >= (quiz?.pass_score ?? 50) ? '🎉' : '📝' }}</p>
          <h1 class="text-3xl font-semibold theme-text">
            {{ result.score >= (quiz?.pass_score ?? 50) ? 'Xuất sắc!' : 'Cố gắng hơn nhé!' }}
          </h1>
          <div
            class="inline-flex items-center gap-2 rounded-full px-5 py-2 text-lg font-bold"
            :class="result.score >= (quiz?.pass_score ?? 50)
              ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
              : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'"
          >
            {{ result.score }}%
          </div>
          <p class="text-sm theme-text-muted">
            {{ result.correct_count }}/{{ result.total_questions }} câu đúng
            · Điểm đạt: {{ quiz?.pass_score ?? 50 }}%
          </p>
          <div class="flex justify-center gap-3 pt-2">
            <RouterLink
              to="/tests"
              class="rounded-full px-5 py-3 text-sm font-semibold"
              style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
            >Về danh sách đề thi</RouterLink>
            <button
              @click="restart"
              class="rounded-full px-5 py-3 text-sm font-semibold text-white"
              style="background: var(--accent)"
            >Làm lại</button>
          </div>
        </div>
      </template>

      <!-- Quiz form -->
      <template v-else-if="quiz">
        <!-- Header -->
        <div>
          <RouterLink to="/tests" class="text-sm theme-text-muted hover:theme-text">← Đề thi</RouterLink>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">{{ quiz.title }}</h1>
          <div class="mt-2 flex flex-wrap gap-3 text-sm theme-text-muted">
            <span v-if="quiz.time_limit">⏱ {{ quiz.time_limit }} phút</span>
            <span>📝 {{ questions.length }} câu hỏi</span>
            <span>🎯 Điểm đạt: {{ quiz.pass_score }}%</span>
          </div>
        </div>

        <!-- Timer -->
        <div v-if="quiz.time_limit && timeLeft !== null" class="theme-card rounded-[20px] px-5 py-3 flex items-center justify-between">
          <span class="text-sm theme-text-muted">Thời gian còn lại</span>
          <span
            class="text-lg font-bold font-mono"
            :class="timeLeft < 60 ? 'text-red-500' : 'theme-text'"
          >{{ formatTime(timeLeft) }}</span>
        </div>

        <!-- Progress -->
        <div class="flex items-center gap-3">
          <div class="flex-1 h-2 rounded-full overflow-hidden" style="background: var(--border)">
            <div
              class="h-full rounded-full transition-all"
              style="background: var(--accent)"
              :style="{ width: (answeredCount / questions.length * 100) + '%' }"
            ></div>
          </div>
          <span class="text-xs theme-text-muted shrink-0">{{ answeredCount }}/{{ questions.length }}</span>
        </div>

        <!-- Questions -->
        <div class="space-y-5">
          <div
            v-for="(q, qi) in questions"
            :key="q.id"
            class="theme-card rounded-[28px] p-5 space-y-4"
          >
            <!-- Question header -->
            <div class="flex items-start gap-3">
              <span
                class="shrink-0 grid h-7 w-7 place-items-center rounded-full text-xs font-bold text-white"
                style="background: var(--accent)"
              >{{ qi + 1 }}</span>
              <div class="flex-1 min-w-0">
                <!-- passage nếu có (từ extended fields) -->
                <p v-if="q.passage" class="mb-2 text-sm theme-text-soft italic border-l-2 pl-3" style="border-color: var(--accent)">
                  {{ q.passage }}
                </p>
                <!-- field `question` theo DB (không phải question_text) -->
                <p class="font-semibold theme-text leading-7">{{ q.question }}</p>
              </div>
            </div>

            <!-- Image nếu có -->
            <img
              v-if="q.image_url"
              :src="$storage(q.image_url)"
              alt="Question image"
              class="w-full max-h-48 object-contain rounded-2xl"
            />

            <!-- Audio nếu có -->
            <audio
              v-if="q.audio_url"
              :src="$storage(q.audio_url)"
              controls
              class="w-full h-9 rounded-xl"
            ></audio>

            <!-- Answers — field `answer_text` và `is_correct` theo DB -->
            <div class="space-y-2">
              <label
                v-for="ans in q.answers"
                :key="ans.id"
                class="answer-opt flex items-center gap-3 rounded-2xl px-4 py-3 cursor-pointer transition"
                :class="{ 'answer-opt--selected': userAnswers[q.id] === ans.id }"
              >
                <input
                  type="radio"
                  :name="`q${q.id}`"
                  :value="ans.id"
                  v-model="userAnswers[q.id]"
                  class="hidden"
                />
                <span class="answer-dot shrink-0"></span>
                <span class="text-sm theme-text">{{ ans.answer_text }}</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Error -->
        <div
          v-if="submitError"
          class="rounded-2xl px-4 py-3 text-sm text-red-600 dark:text-red-400"
          style="background: color-mix(in srgb, #ef4444 8%, var(--surface-muted))"
        >{{ submitError }}</div>

        <!-- Submit -->
        <button
          @click="submitQuiz"
          :disabled="submitting"
          class="w-full rounded-full py-3.5 text-sm font-semibold text-white disabled:opacity-60 transition hover:opacity-90"
          style="background: var(--accent)"
        >
          {{ submitting ? 'Đang nộp...' : `Nộp bài (${answeredCount}/${questions.length} câu)` }}
        </button>
      </template>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useToast } from '@/composables/useToast'
import api from '@/services/axios'

const props = defineProps({ quizId: { type: String, required: true } })
const toast = useToast()

const quiz = ref(null)
const questions = ref([])
const userAnswers = reactive({})
const loading = ref(true)
const submitting = ref(false)
const submitError = ref('')
const result = ref(null)
const timeLeft = ref(null)
let timer = null

const answeredCount = computed(() =>
  questions.value.filter(q => userAnswers[q.id] != null).length
)

function formatTime(secs) {
  const m = Math.floor(secs / 60).toString().padStart(2, '0')
  const s = (secs % 60).toString().padStart(2, '0')
  return `${m}:${s}`
}

function startTimer(minutes) {
  timeLeft.value = minutes * 60
  timer = setInterval(() => {
    if (timeLeft.value <= 0) {
      clearInterval(timer)
      toast.info('Hết giờ! Bài đang được nộp tự động.')
      submitQuiz()
    } else {
      timeLeft.value--
    }
  }, 1000)
}

async function loadQuiz() {
  loading.value = true
  result.value = null
  Object.keys(userAnswers).forEach(k => delete userAnswers[k])
  try {
    const res = await api.get(`/quizzes/${props.quizId}`)
    quiz.value = res.data?.data ?? res.data
    // questions có thể nằm trong quiz.questions
    questions.value = quiz.value?.questions ?? []
    if (quiz.value?.time_limit) startTimer(quiz.value.time_limit)
  } finally {
    loading.value = false
  }
}

async function submitQuiz() {
  if (timer) clearInterval(timer)
  submitError.value = ''
  // Gửi answers theo format API: [{ question_id, answer_id }]
  const answers = questions.value.map(q => ({
    question_id: q.id,
    answer_id: userAnswers[q.id] ?? null,
  }))
  submitting.value = true
  try {
    const res = await api.post(`/quizzes/${props.quizId}/submit`, { answers })
    result.value = res.data
  } catch (e) {
    submitError.value = e.response?.data?.message ?? 'Có lỗi xảy ra khi nộp bài.'
  } finally {
    submitting.value = false
  }
}

function restart() {
  if (timer) clearInterval(timer)
  loadQuiz()
}

onMounted(loadQuiz)
onUnmounted(() => { if (timer) clearInterval(timer) })
</script>

<style scoped>
.skeleton { background: var(--border); }
.answer-opt {
  border: 1.5px solid var(--border);
  background: var(--surface-muted);
}
.answer-opt:hover { border-color: var(--accent); }
.answer-opt--selected {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 8%, var(--surface-muted));
}
.answer-dot {
  width: 16px; height: 16px;
  border-radius: 50%;
  border: 2px solid var(--border);
  transition: all 0.2s;
  flex-shrink: 0;
}
.answer-opt--selected .answer-dot {
  border-color: var(--accent);
  background: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
}
</style>
