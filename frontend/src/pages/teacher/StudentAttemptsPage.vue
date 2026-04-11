<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Lượt làm bài học viên</h1>
      </div>

      <!-- Stats -->
      <div class="grid gap-4 sm:grid-cols-3">
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-sky-100 dark:bg-sky-900/40">📝</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ attempts.length }}</p>
            <p class="text-sm theme-text-muted">Tổng lượt làm</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-amber-100 dark:bg-amber-900/40">📈</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ avgScore }}</p>
            <p class="text-sm theme-text-muted">Điểm trung bình</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-emerald-100 dark:bg-emerald-900/40">🏆</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ passRate }}%</p>
            <p class="text-sm theme-text-muted">Tỷ lệ đạt</p>
          </div>
        </div>
      </div>

      <!-- Search -->
      <div class="theme-card rounded-[24px] p-4">
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-base opacity-50">🔍</span>
          <input
            v-model="search"
            type="text"
            placeholder="Tìm học viên hoặc quiz..."
            class="search-input w-full rounded-2xl pl-10 pr-4 py-2.5 text-sm outline-none"
          />
        </div>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="theme-card rounded-[30px] overflow-hidden">
        <div class="table-head px-5 py-4 flex gap-5">
          <div class="h-4 rounded skeleton w-1/4"></div>
          <div class="h-4 rounded skeleton w-1/3 hidden md:block"></div>
          <div class="h-4 rounded skeleton w-1/6 ml-auto"></div>
        </div>
        <div v-for="i in 6" :key="i" class="border-t px-5 py-4 flex items-center gap-4 animate-pulse" style="border-color: var(--border)">
          <div class="h-9 w-9 rounded-full skeleton shrink-0"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 rounded skeleton w-1/3"></div>
          </div>
          <div class="h-4 rounded skeleton w-16 hidden md:block"></div>
          <div class="h-6 w-16 rounded-full skeleton"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-16 text-center">
        <p class="text-5xl">📝</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có lượt làm bài nào</p>
        <p class="mt-2 text-sm theme-text-soft">Học viên chưa hoàn thành quiz nào.</p>
      </div>

      <!-- Table -->
      <div v-else class="theme-card rounded-[30px] overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Học viên</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Quiz</th>
              <th class="px-5 py-4 text-center font-semibold theme-text-muted">Điểm</th>
              <th class="px-5 py-4 text-center font-semibold theme-text-muted hidden md:table-cell">Kết quả</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Thời gian</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="a in filtered"
              :key="a.id"
              class="table-row border-t"
              style="border-color: var(--border)"
            >
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="grid h-9 w-9 place-items-center rounded-full text-sm font-semibold text-white shrink-0"
                    style="background: var(--accent)"
                  >
                    {{ a.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                  </div>
                  <span class="font-medium theme-text">{{ a.user?.name ?? '—' }}</span>
                </div>
              </td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ a.quiz?.title ?? '—' }}</td>
              <td class="px-5 py-4 text-center">
                <span class="font-semibold theme-text">{{ a.score ?? '—' }}</span>
              </td>
              <td class="px-5 py-4 text-center hidden md:table-cell">
                <span
                  class="rounded-full px-3 py-1 text-xs font-semibold"
                  :class="a.score >= 50
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                    : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300'"
                >
                  {{ a.score >= 50 ? '✓ Đạt' : '✗ Chưa đạt' }}
                </span>
              </td>
              <td class="px-5 py-4 theme-text-muted hidden lg:table-cell">{{ formatDate(a.finished_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { attemptService } from '@/services/teacher.service'

const attempts = ref([])
const loading = ref(true)
const search = ref('')

onMounted(async () => {
  try {
    const res = await attemptService.list()
    attempts.value = res.data?.data ?? res.data ?? []
  } finally { loading.value = false }
})

const filtered = computed(() => {
  const q = search.value.toLowerCase()
  return attempts.value.filter(a =>
    (a.user?.name?.toLowerCase() ?? '').includes(q) ||
    (a.quiz?.title?.toLowerCase() ?? '').includes(q)
  )
})

const avgScore = computed(() => {
  const valid = attempts.value.filter(a => a.score != null)
  if (!valid.length) return '—'
  return (valid.reduce((s, a) => s + Number(a.score), 0) / valid.length).toFixed(1)
})

const passRate = computed(() => {
  if (!attempts.value.length) return 0
  return Math.round((attempts.value.filter(a => a.score >= 50).length / attempts.value.length) * 100)
})

function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('vi-VN')
}
</script>

<style scoped>
.search-input { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); transition: border-color 0.2s, box-shadow 0.2s; }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.table-head { background: var(--surface-muted); }
.table-row:hover { background: var(--surface-muted); }
.skeleton { background: var(--border); }
</style>
