<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Học viên đăng ký</h1>
      </div>

      <!-- Stats -->
      <div class="grid gap-4 sm:grid-cols-3">
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-sky-100 dark:bg-sky-900/40">📋</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ enrollments.length }}</p>
            <p class="text-sm theme-text-muted">Tổng lượt đăng ký</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-emerald-100 dark:bg-emerald-900/40">👥</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ uniqueStudents }}</p>
            <p class="text-sm theme-text-muted">Học viên duy nhất</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-violet-100 dark:bg-violet-900/40">📚</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ uniqueCourses }}</p>
            <p class="text-sm theme-text-muted">Khóa học có học viên</p>
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
            placeholder="Tìm học viên hoặc khóa học..."
            class="search-input w-full rounded-2xl pl-10 pr-4 py-2.5 text-sm outline-none"
          />
        </div>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="theme-card rounded-[30px] overflow-hidden">
        <div class="table-head px-5 py-4 flex gap-5">
          <div class="h-4 rounded skeleton w-1/4"></div>
          <div class="h-4 rounded skeleton w-1/3 hidden md:block"></div>
          <div class="h-4 rounded skeleton w-1/6 hidden lg:block ml-auto"></div>
        </div>
        <div v-for="i in 6" :key="i" class="border-t px-5 py-4 flex items-center gap-4 animate-pulse" style="border-color: var(--border)">
          <div class="h-9 w-9 rounded-full skeleton shrink-0"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 rounded skeleton w-1/3"></div>
            <div class="h-3 rounded skeleton w-1/2"></div>
          </div>
          <div class="h-4 rounded skeleton w-1/4 hidden md:block"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-16 text-center">
        <p class="text-5xl">👥</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có học viên nào đăng ký</p>
        <p class="mt-2 text-sm theme-text-soft">Chia sẻ khóa học để thu hút học viên.</p>
      </div>

      <!-- Table -->
      <div v-else class="theme-card rounded-[30px] overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Học viên</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Khóa học</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Ngày đăng ký</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="e in filtered"
              :key="e.id"
              class="table-row border-t"
              style="border-color: var(--border)"
            >
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="grid h-9 w-9 place-items-center rounded-full text-sm font-semibold text-white shrink-0"
                    style="background: var(--accent)"
                  >
                    {{ e.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                  </div>
                  <div>
                    <p class="font-semibold theme-text">{{ e.user?.name ?? '—' }}</p>
                    <p class="text-xs theme-text-muted">{{ e.user?.email ?? '' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 hidden md:table-cell">
                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/50 dark:text-sky-300">
                  {{ e.course?.title ?? '—' }}
                </span>
              </td>
              <td class="px-5 py-4 theme-text-muted hidden lg:table-cell">{{ formatDate(e.enrolled_at) }}</td>
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
import { enrollmentService } from '@/services/teacher.service'

const enrollments = ref([])
const loading = ref(true)
const search = ref('')

onMounted(async () => {
  try {
    const res = await enrollmentService.list()
    enrollments.value = res.data?.data ?? res.data ?? []
  } finally { loading.value = false }
})

const filtered = computed(() => {
  const q = search.value.toLowerCase()
  return enrollments.value.filter(e =>
    (e.user?.name?.toLowerCase() ?? '').includes(q) ||
    (e.course?.title?.toLowerCase() ?? '').includes(q)
  )
})

const uniqueStudents = computed(() => new Set(enrollments.value.map(e => e.user_id)).size)
const uniqueCourses = computed(() => new Set(enrollments.value.map(e => e.course_id)).size)

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
