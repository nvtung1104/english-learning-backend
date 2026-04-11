<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Tiến độ học viên</h1>
      </div>

      <!-- Stats -->
      <div class="grid gap-4 sm:grid-cols-3">
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-sky-100 dark:bg-sky-900/40">📊</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ progress.length }}</p>
            <p class="text-sm theme-text-muted">Tổng bản ghi</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-emerald-100 dark:bg-emerald-900/40">✅</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ completedCount }}</p>
            <p class="text-sm theme-text-muted">Đã hoàn thành</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-amber-100 dark:bg-amber-900/40">⏳</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ inProgressCount }}</p>
            <p class="text-sm theme-text-muted">Đang học</p>
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
            placeholder="Tìm học viên hoặc bài học..."
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
            <div class="h-3 rounded skeleton w-1/2 hidden md:block"></div>
          </div>
          <div class="h-6 w-20 rounded-full skeleton"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-16 text-center">
        <p class="text-5xl">📊</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có dữ liệu tiến độ</p>
        <p class="mt-2 text-sm theme-text-soft">Học viên chưa bắt đầu học bài nào.</p>
      </div>

      <!-- Table -->
      <div v-else class="theme-card rounded-[30px] overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Học viên</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Bài học</th>
              <th class="px-5 py-4 text-center font-semibold theme-text-muted">Trạng thái</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Hoàn thành lúc</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="p in filtered"
              :key="p.id"
              class="table-row border-t"
              style="border-color: var(--border)"
            >
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="grid h-9 w-9 place-items-center rounded-full text-sm font-semibold text-white shrink-0"
                    style="background: var(--accent)"
                  >
                    {{ p.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                  </div>
                  <span class="font-medium theme-text">{{ p.user?.name ?? '—' }}</span>
                </div>
              </td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ p.lesson?.title ?? '—' }}</td>
              <td class="px-5 py-4 text-center">
                <span
                  class="rounded-full px-3 py-1 text-xs font-semibold"
                  :class="p.status
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                    : 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300'"
                >
                  {{ p.status ? '✓ Hoàn thành' : '⏳ Đang học' }}
                </span>
              </td>
              <td class="px-5 py-4 theme-text-muted hidden lg:table-cell">{{ formatDate(p.completed_at) }}</td>
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
import { progressService } from '@/services/teacher.service'

const progress = ref([])
const loading = ref(true)
const search = ref('')

onMounted(async () => {
  try {
    const res = await progressService.list()
    progress.value = res.data?.data ?? res.data ?? []
  } finally { loading.value = false }
})

const filtered = computed(() => {
  const q = search.value.toLowerCase()
  return progress.value.filter(p =>
    p.user?.name?.toLowerCase().includes(q) || p.lesson?.title?.toLowerCase().includes(q)
  )
})

const completedCount = computed(() => progress.value.filter(p => p.status).length)
const inProgressCount = computed(() => progress.value.filter(p => !p.status).length)

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
