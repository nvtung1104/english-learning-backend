<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-6xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Khóa học của tôi</h1>
        </div>
        <RouterLink to="/courses" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Khám phá thêm
        </RouterLink>
      </div>

      <div v-if="loading" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <div v-for="i in 3" :key="i" class="theme-card rounded-[28px] p-5 animate-pulse space-y-3">
          <div class="h-40 rounded-2xl skeleton"></div>
          <div class="h-4 rounded skeleton w-3/4"></div>
          <div class="h-3 rounded skeleton w-1/2"></div>
        </div>
      </div>

      <div v-else-if="!enrollments.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-4xl">📚</p>
        <p class="mt-4 text-lg font-semibold theme-text">Bạn chưa đăng ký khóa học nào</p>
        <RouterLink to="/courses" class="mt-6 inline-block rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          Khám phá khóa học
        </RouterLink>
      </div>

      <div v-else class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <article
          v-for="e in enrollments"
          :key="e.id"
          class="theme-card group rounded-[28px] overflow-hidden transition hover:-translate-y-1"
        >
          <div class="relative h-44 overflow-hidden bg-slate-100 dark:bg-slate-800">
            <img v-if="e.course?.thumbnail" :src="$storage(e.course.thumbnail)" :alt="e.course.title" class="h-full w-full object-cover transition group-hover:scale-105" />
            <div v-else class="flex h-full items-center justify-center text-5xl">📚</div>
          </div>
          <div class="p-5 space-y-3">
            <h3 class="font-semibold theme-text line-clamp-2">{{ e.course?.title }}</h3>
            <div class="space-y-1">
              <div class="flex justify-between text-xs theme-text-muted">
                <span>Tiến độ</span>
                <span>{{ progressMap[e.course_id] ?? 0 }}%</span>
              </div>
              <div class="h-2 rounded-full overflow-hidden" style="background: var(--border)">
                <div class="h-full rounded-full transition-all" style="background: var(--accent)" :style="{ width: (progressMap[e.course_id] ?? 0) + '%' }"></div>
              </div>
            </div>
            <RouterLink :to="`/student/courses/${e.course_id}/learn`" class="block rounded-2xl py-2.5 text-center text-sm font-semibold text-white transition hover:opacity-90" style="background: var(--accent)">
              Tiếp tục học
            </RouterLink>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'

const enrollments = ref([])
const progressMap = ref({})
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/student/my-courses')
    enrollments.value = res.data?.data ?? res.data ?? []
    await Promise.all(enrollments.value.map(async e => {
      try {
        const p = await api.get(`/student/courses/${e.course_id}/progress`)
        progressMap.value[e.course_id] = p.data?.progress_percent ?? 0
      } catch { progressMap.value[e.course_id] = 0 }
    }))
  } finally { loading.value = false }
})
</script>

<style scoped>
.skeleton { background: var(--border); }
</style>
