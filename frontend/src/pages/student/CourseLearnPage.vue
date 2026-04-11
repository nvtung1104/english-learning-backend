<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/student/courses" class="text-sm theme-text-muted hover:theme-text">← Khóa học của tôi</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">{{ course?.title ?? 'Đang tải...' }}</h1>
        <p v-if="course?.description" class="mt-1 text-sm theme-text-soft">{{ course.description }}</p>
      </div>

      <!-- Progress bar -->
      <div v-if="progress !== null" class="theme-card rounded-[24px] p-4 flex items-center gap-4">
        <div class="flex-1 space-y-1">
          <div class="flex justify-between text-xs theme-text-muted">
            <span>Tiến độ khóa học</span>
            <span>{{ progress.completed_lessons }}/{{ progress.total_lessons }} bài · {{ progress.progress_percent }}%</span>
          </div>
          <div class="h-2.5 rounded-full overflow-hidden" style="background: var(--border)">
            <div class="h-full rounded-full transition-all" style="background: var(--accent)" :style="{ width: progress.progress_percent + '%' }"></div>
          </div>
        </div>
      </div>

      <div v-if="loading" class="space-y-3">
        <div v-for="i in 4" :key="i" class="theme-card rounded-[24px] p-4 animate-pulse flex gap-4">
          <div class="h-10 w-10 rounded-xl skeleton shrink-0"></div>
          <div class="flex-1 space-y-2">
            <div class="h-4 rounded skeleton w-2/3"></div>
            <div class="h-3 rounded skeleton w-1/3"></div>
          </div>
        </div>
      </div>

      <div v-else-if="!sections.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-4xl">📭</p>
        <p class="mt-4 font-semibold theme-text">Khóa học chưa có nội dung</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="section in sections" :key="section.id" class="theme-card rounded-[28px] overflow-hidden">
          <div class="px-5 py-4 flex items-center justify-between" style="background: var(--surface-muted); border-bottom: 1px solid var(--border)">
            <h2 class="font-semibold theme-text">{{ section.title }}</h2>
            <span class="text-xs theme-text-muted">{{ section.lessons?.length ?? 0 }} bài</span>
          </div>
          <div class="divide-y" style="border-color: var(--border)">
            <RouterLink
              v-for="lesson in section.lessons"
              :key="lesson.id"
              :to="`/student/lessons/${lesson.id}`"
              class="lesson-row flex items-center gap-4 px-5 py-4 transition"
            >
              <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-lg" style="background: var(--surface-strong)">
                {{ completedIds.has(lesson.id) ? '✅' : lessonIcon(lesson) }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-medium theme-text truncate">{{ lesson.title }}</p>
                <p class="text-xs theme-text-muted">{{ lesson.lessonType?.name ?? 'Bài học' }}</p>
              </div>
              <span v-if="completedIds.has(lesson.id)" class="text-xs font-semibold text-emerald-500">Hoàn thành</span>
              <span v-else class="text-xs theme-text-muted">→</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'

const props = defineProps({ courseId: { type: String, required: true } })

const course = ref(null)
const sections = ref([])
const progress = ref(null)
const completedLessons = ref([])
const loading = ref(true)

const completedIds = computed(() => new Set(completedLessons.value.map(p => p.lesson_id)))

function lessonIcon(lesson) {
  const type = lesson.lessonType?.name?.toLowerCase() ?? ''
  if (type.includes('listen')) return '🎧'
  if (type.includes('speak')) return '🎙️'
  if (type.includes('vocab')) return '📖'
  if (type.includes('grammar')) return '✍️'
  if (type.includes('quiz')) return '📝'
  return '📄'
}

onMounted(async () => {
  try {
    const [cRes, sRes, pRes, lpRes] = await Promise.all([
      api.get(`/courses/${props.courseId}`),
      api.get(`/courses/${props.courseId}/sections`),
      api.get(`/student/courses/${props.courseId}/progress`),
      api.get('/student/lesson-progress'),
    ])
    course.value = cRes.data?.data ?? cRes.data
    const rawSections = sRes.data?.data ?? sRes.data ?? []
    // Load lessons for each section
    const withLessons = await Promise.all(rawSections.map(async s => {
      try {
        const lr = await api.get(`/lessons`, { params: { section_id: s.id } })
        return { ...s, lessons: lr.data?.data ?? lr.data ?? [] }
      } catch { return { ...s, lessons: [] } }
    }))
    sections.value = withLessons
    progress.value = pRes.data
    completedLessons.value = lpRes.data?.data ?? lpRes.data ?? []
  } finally { loading.value = false }
})
</script>

<style scoped>
.skeleton { background: var(--border); }
.lesson-row:hover { background: var(--surface-muted); }
</style>
