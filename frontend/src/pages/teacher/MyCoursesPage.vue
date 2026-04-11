<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-7xl space-y-6">

      <!-- Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Khóa học của tôi</h1>
        </div>
        <RouterLink
          to="/teacher/courses/create"
          class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:shadow-lg"
          style="background: var(--accent)"
        >
          + Tạo khóa học mới
        </RouterLink>
      </div>

      <!-- Search & filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row sm:items-center">
        <input
          v-model="search"
          type="text"
          placeholder="Tìm kiếm khóa học..."
          class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none"
        />
        <select v-model="statusFilter" class="search-input rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả trạng thái</option>
          <option value="1">Đã xuất bản</option>
          <option value="0">Bản nháp</option>
        </select>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <div v-for="i in 6" :key="i" class="theme-card rounded-[28px] overflow-hidden animate-pulse">
          <div class="h-44 skeleton"></div>
          <div class="p-5 space-y-3">
            <div class="h-4 rounded skeleton w-3/4"></div>
            <div class="h-3 rounded skeleton w-full"></div>
            <div class="h-3 rounded skeleton w-1/2"></div>
            <div class="mt-4 flex gap-2">
              <div class="h-9 flex-1 rounded-2xl skeleton"></div>
              <div class="h-9 flex-1 rounded-2xl skeleton"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-16 text-center">
        <p class="text-5xl">📚</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có khóa học nào</p>
        <p class="mt-2 text-sm theme-text-soft">Tạo khóa học đầu tiên để bắt đầu giảng dạy.</p>
        <RouterLink
          to="/teacher/courses/create"
          class="mt-6 inline-block rounded-full px-5 py-3 text-sm font-semibold text-white"
          style="background: var(--accent)"
        >
          Tạo ngay
        </RouterLink>
      </div>

      <!-- Grid -->
      <div v-else class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <article
          v-for="course in filtered"
          :key="course.id"
          class="theme-card group rounded-[28px] overflow-hidden transition hover:-translate-y-1 hover:shadow-[var(--shadow-md)]"
        >
          <!-- Thumbnail -->
          <div class="relative h-44 overflow-hidden bg-slate-100 dark:bg-slate-800">
            <img
              v-if="course.thumbnail"
              :src="$storage(course.thumbnail)"
              :alt="course.title"
              class="h-full w-full object-cover transition group-hover:scale-105"
            />
            <div v-else class="flex h-full items-center justify-center text-5xl">📚</div>
            <span
              class="absolute right-3 top-3 rounded-full px-3 py-1 text-xs font-semibold"
              :class="course.status ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300'"
            >
              {{ course.status ? 'Đã xuất bản' : 'Bản nháp' }}
            </span>
          </div>

          <div class="p-5">
            <h3 class="font-semibold theme-text line-clamp-2">{{ course.title }}</h3>
            <p class="mt-1 text-sm theme-text-soft line-clamp-2">{{ course.description }}</p>

            <div class="mt-4 flex items-center gap-3 text-xs theme-text-muted">
              <span>{{ course.category?.name ?? '—' }}</span>
              <span>·</span>
              <span>{{ course.level?.name ?? '—' }}</span>
              <span v-if="course.creator?.name">·</span>
              <span v-if="course.creator?.name">👨‍🏫 {{ course.creator.name }}</span>
            </div>

            <div class="mt-4 flex gap-2">
              <RouterLink
                :to="`/teacher/courses/${course.id}/edit`"
                class="action-btn flex-1 rounded-2xl py-2 text-center text-sm font-semibold"
              >
                Chỉnh sửa
              </RouterLink>
              <RouterLink
                :to="`/teacher/courses/${course.id}/sections`"
                class="action-btn-accent flex-1 rounded-2xl py-2 text-center text-sm font-semibold text-white"
              >
                Sections
              </RouterLink>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { courseService } from '@/services/teacher.service'

const courses = ref([])
const loading = ref(true)
const search = ref('')
const statusFilter = ref('')

onMounted(async () => {
  try {
    const res = await courseService.list()
    courses.value = res.data?.data ?? res.data ?? []
  } catch { /* ignore */ }
  finally { loading.value = false }
})

const filtered = computed(() => {
  return courses.value.filter(c => {
    const matchSearch = c.title?.toLowerCase().includes(search.value.toLowerCase())
    const matchStatus = statusFilter.value === '' || String(c.status) === statusFilter.value
    return matchSearch && matchStatus
  })
})
</script>

<style scoped>
.search-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.search-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
}
.skeleton {
  background: var(--border);
}
.action-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.action-btn:hover { background: var(--surface-strong); }
.action-btn-accent {
  background: var(--accent);
}
.action-btn-accent:hover { opacity: 0.88; }
</style>
