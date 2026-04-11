<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <RouterLink to="/tests" class="text-sm theme-text-muted hover:theme-text">← Đề thi</RouterLink>

      <!-- Loading -->
      <div v-if="loading" class="theme-card rounded-[36px] p-8 animate-pulse space-y-4">
        <div class="h-8 rounded skeleton w-1/2 mx-auto"></div>
        <div class="h-20 rounded-2xl skeleton"></div>
      </div>

      <template v-else-if="attempt">
        <!-- Score card -->
        <div class="theme-card rounded-[36px] p-8 text-center space-y-3">
          <p class="text-5xl">{{ attempt.score >= 80 ? '🏆' : attempt.score >= 50 ? '👍' : '📝' }}</p>
          <h1 class="text-3xl font-semibold theme-text">{{ attempt.quiz?.title }}</h1>
          <div
            class="inline-flex items-center gap-2 rounded-full px-5 py-2 text-lg font-bold"
            :class="attempt.score >= (attempt.quiz?.pass_score ?? 50)
              ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
              : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'"
          >
            {{ attempt.score }}%
          </div>
          <p class="text-sm theme-text-muted">
            Hoàn thành lúc {{ formatDate(attempt.finished_at) }}
          </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
          <div class="theme-card rounded-[24px] p-4 text-center">
            <p class="text-2xl font-semibold theme-text">{{ attempt.score }}%</p>
            <p class="mt-1 text-xs theme-text-muted">Điểm số</p>
          </div>
          <div class="theme-card rounded-[24px] p-4 text-center">
            <p class="text-2xl font-semibold text-emerald-500">{{ correctCount }}</p>
            <p class="mt-1 text-xs theme-text-muted">Câu đúng</p>
          </div>
          <div class="theme-card rounded-[24px] p-4 text-center">
            <p class="text-2xl font-semibold text-red-500">{{ wrongCount }}</p>
            <p class="mt-1 text-xs theme-text-muted">Câu sai</p>
          </div>
        </div>

        <!-- Quiz info -->
        <div class="theme-card rounded-[24px] p-5 space-y-3">
          <h2 class="text-sm font-semibold theme-text">Thông tin bài thi</h2>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Tên quiz</span>
            <span class="text-sm theme-text">{{ attempt.quiz?.title ?? '—' }}</span>
          </div>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Điểm đạt yêu cầu</span>
            <span class="text-sm theme-text">{{ attempt.quiz?.pass_score ?? 50 }}%</span>
          </div>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Kết quả</span>
            <span
              class="rounded-full px-3 py-1 text-xs font-semibold"
              :class="attempt.score >= (attempt.quiz?.pass_score ?? 50)
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
                : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300'"
            >{{ attempt.score >= (attempt.quiz?.pass_score ?? 50) ? 'Đạt' : 'Chưa đạt' }}</span>
          </div>
          <div v-if="attempt.started_at" class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Thời gian làm bài</span>
            <span class="text-sm theme-text">{{ calcDuration(attempt.started_at, attempt.finished_at) }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
          <RouterLink
            to="/tests"
            class="flex-1 rounded-full py-3 text-center text-sm font-semibold"
            style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
          >Về danh sách</RouterLink>
          <RouterLink
            :to="`/student/quizzes/${attempt.quiz_id}`"
            class="flex-1 rounded-full py-3 text-center text-sm font-semibold text-white"
            style="background: var(--accent)"
          >Làm lại</RouterLink>
        </div>
      </template>

      <!-- Not found -->
      <div v-else class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-4xl">❌</p>
        <p class="mt-4 font-semibold theme-text">Không tìm thấy kết quả</p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'

const props = defineProps({ attemptId: { type: String, required: true } })

const attempt = ref(null)
const loading = ref(true)

// user_answers có is_correct theo DB (quiz_answers.is_correct)
const correctCount = computed(() =>
  attempt.value?.user_answers?.filter(a => a.is_correct)?.length ?? 0
)
const wrongCount = computed(() =>
  (attempt.value?.user_answers?.length ?? 0) - correctCount.value
)

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleString('vi-VN')
}

function calcDuration(start, end) {
  if (!start || !end) return '—'
  const diff = Math.round((new Date(end) - new Date(start)) / 1000)
  if (diff < 60) return `${diff} giây`
  return `${Math.floor(diff / 60)} phút ${diff % 60} giây`
}

onMounted(async () => {
  try {
    const res = await api.get('/quiz-attempts', { params: { attempt_id: props.attemptId } })
    const list = res.data?.data ?? res.data ?? []
    attempt.value = list.find(a => String(a.id) === String(props.attemptId)) ?? list[0] ?? null
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.skeleton { background: var(--border); }
.info-row { border: 1px solid var(--border); background: var(--surface-muted); }
</style>
