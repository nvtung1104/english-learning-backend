<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Đánh giá khóa học</h1>
      </div>

      <!-- Stats -->
      <div class="grid gap-4 sm:grid-cols-3">
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-amber-100 dark:bg-amber-900/40">⭐</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ avgRating }}</p>
            <p class="text-sm theme-text-muted">Điểm trung bình</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-sky-100 dark:bg-sky-900/40">💬</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ reviews.length }}</p>
            <p class="text-sm theme-text-muted">Tổng đánh giá</p>
          </div>
        </div>
        <div class="theme-card rounded-[24px] p-5 flex items-center gap-4">
          <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl shrink-0 bg-emerald-100 dark:bg-emerald-900/40">🏅</div>
          <div>
            <p class="text-2xl font-semibold theme-text">{{ fiveStars }}</p>
            <p class="text-sm theme-text-muted">Đánh giá 5 sao</p>
          </div>
        </div>
      </div>

      <!-- Filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <select v-model="courseFilter" class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả khóa học</option>
          <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.title }}</option>
        </select>
        <select v-model="ratingFilter" class="search-input rounded-2xl px-4 py-2.5 text-sm outline-none sm:w-40">
          <option value="">Tất cả sao</option>
          <option v-for="r in [5,4,3,2,1]" :key="r" :value="r">{{ '★'.repeat(r) }} {{ r }} sao</option>
        </select>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="space-y-4">
        <div
          v-for="i in 4"
          :key="i"
          class="theme-card rounded-[24px] p-5 animate-pulse flex gap-4"
        >
          <div class="h-10 w-10 rounded-full skeleton shrink-0"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 rounded skeleton w-1/4"></div>
            <div class="h-3 rounded skeleton w-1/3"></div>
            <div class="h-4 rounded skeleton w-3/4 mt-2"></div>
          </div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-16 text-center">
        <p class="text-5xl">⭐</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có đánh giá nào</p>
        <p class="mt-2 text-sm theme-text-soft">Học viên chưa để lại nhận xét.</p>
      </div>

      <!-- Reviews list -->
      <div v-else class="space-y-4">
        <div
          v-for="r in filtered"
          :key="r.id"
          class="theme-card rounded-[24px] p-5"
        >
          <div class="flex items-start gap-4">
            <div
              class="grid h-10 w-10 place-items-center rounded-full text-sm font-semibold text-white shrink-0"
              style="background: var(--accent)"
            >
              {{ r.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                <p class="font-semibold theme-text">{{ r.user?.name ?? 'Ẩn danh' }}</p>
                <span class="text-xs theme-text-muted">· {{ formatDate(r.created_at) }}</span>
                <span class="rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-semibold text-sky-700 dark:bg-sky-900/50 dark:text-sky-300 ml-auto">
                  {{ r.course?.title ?? '—' }}
                </span>
              </div>
              <div class="mt-1.5 flex gap-0.5">
                <span
                  v-for="s in 5"
                  :key="s"
                  class="text-base"
                  :class="s <= r.rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'"
                >★</span>
              </div>
              <p v-if="r.comment" class="mt-2 text-sm theme-text-soft leading-relaxed">{{ r.comment }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { courseService } from '@/services/teacher.service'
import api from '@/services/axios'

const reviews = ref([])
const courses = ref([])
const loading = ref(true)
const courseFilter = ref('')
const ratingFilter = ref('')

onMounted(async () => {
  try {
    const cRes = await courseService.list()
    courses.value = cRes.data?.data ?? cRes.data ?? []
    const allReviews = []
    for (const course of courses.value) {
      const rRes = await api.get(`/courses/${course.id}/reviews`)
      const items = rRes.data?.data ?? rRes.data ?? []
      allReviews.push(...items.map(r => ({ ...r, course })))
    }
    reviews.value = allReviews
  } finally { loading.value = false }
})

const filtered = computed(() =>
  reviews.value.filter(r => {
    const matchCourse = !courseFilter.value || String(r.course?.id) === String(courseFilter.value)
    const matchRating = !ratingFilter.value || r.rating === Number(ratingFilter.value)
    return matchCourse && matchRating
  })
)

const avgRating = computed(() => {
  if (!reviews.value.length) return '—'
  return (reviews.value.reduce((s, r) => s + r.rating, 0) / reviews.value.length).toFixed(1)
})

const fiveStars = computed(() => reviews.value.filter(r => r.rating === 5).length)

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('vi-VN')
}
</script>

<style scoped>
.search-input { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); transition: border-color 0.2s, box-shadow 0.2s; }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.skeleton { background: var(--border); }
</style>
