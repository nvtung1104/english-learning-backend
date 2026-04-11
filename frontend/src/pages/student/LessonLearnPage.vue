<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-4xl space-y-6">

      <div v-if="loading" class="space-y-4 animate-pulse">
        <div class="h-6 rounded skeleton w-1/3"></div>
        <div class="h-10 rounded skeleton w-2/3"></div>
        <div class="h-64 rounded-[28px] skeleton"></div>
      </div>

      <template v-else-if="lesson">
        <div>
          <RouterLink :to="backLink" class="text-sm theme-text-muted hover:theme-text">← Quay lại khóa học</RouterLink>
          <div class="mt-2 flex items-center gap-2 flex-wrap">
            <span class="rounded-full px-3 py-1 text-xs font-semibold" style="background: color-mix(in srgb, var(--accent) 12%, var(--surface-muted)); color: var(--accent)">
              {{ lesson.lessonType?.name ?? 'Bài học' }}
            </span>
            <span v-if="completed" class="rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">✅ Đã hoàn thành</span>
          </div>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">{{ lesson.title }}</h1>
        </div>

        <!-- Video -->
        <div v-if="lesson.video_url" class="theme-card rounded-[28px] overflow-hidden">
          <div v-if="isYoutube(lesson.video_url)" class="aspect-video">
            <iframe :src="youtubeEmbed(lesson.video_url)" class="h-full w-full" allowfullscreen frameborder="0"></iframe>
          </div>
          <video v-else :src="$storage(lesson.video_url)" controls class="w-full rounded-[28px]"></video>
        </div>

        <!-- Content -->
        <div v-if="lesson.content" class="theme-card rounded-[28px] p-6">
          <h2 class="text-lg font-semibold theme-text mb-4">Nội dung bài học</h2>
          <div class="prose-content text-sm leading-8 theme-text-soft whitespace-pre-wrap">{{ lesson.content }}</div>
        </div>

        <!-- Materials -->
        <div v-if="materials.length" class="theme-card rounded-[28px] p-6 space-y-3">
          <h2 class="text-lg font-semibold theme-text">Tài liệu đính kèm</h2>
          <a
            v-for="m in materials"
            :key="m.id"
            :href="$storage(m.file_url)"
            target="_blank"
            class="flex items-center gap-3 rounded-2xl px-4 py-3 transition hover:opacity-80"
            style="background: var(--surface-muted); border: 1px solid var(--border)"
          >
            <span class="text-xl">📎</span>
            <span class="text-sm font-medium theme-text">{{ m.title ?? m.file_url }}</span>
          </a>
        </div>

        <!-- Complete button -->
        <div class="flex gap-3">
          <button
            v-if="!completed"
            @click="markComplete"
            :disabled="completing"
            class="rounded-full px-6 py-3 text-sm font-semibold text-white disabled:opacity-60 transition hover:opacity-90"
            style="background: var(--accent)"
          >
            {{ completing ? 'Đang lưu...' : '✅ Đánh dấu hoàn thành' }}
          </button>
          <RouterLink :to="backLink" class="rounded-full px-6 py-3 text-sm font-semibold transition" style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)">
            Quay lại
          </RouterLink>
        </div>
      </template>

      <div v-else class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-4xl">❌</p>
        <p class="mt-4 font-semibold theme-text">Không tìm thấy bài học</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'
import { useToast } from '@/composables/useToast'

const props = defineProps({ lessonId: { type: String, required: true } })
const toast = useToast()

const lesson = ref(null)
const materials = ref([])
const completed = ref(false)
const completing = ref(false)
const loading = ref(true)

const backLink = computed(() => {
  const sectionId = lesson.value?.section_id
  return sectionId ? `/student/courses/${lesson.value?.section?.course_id ?? ''}/learn` : '/student/courses'
})

function isYoutube(url) {
  return url?.includes('youtube.com') || url?.includes('youtu.be')
}

function youtubeEmbed(url) {
  const match = url.match(/(?:v=|youtu\.be\/)([^&?/]+)/)
  return match ? `https://www.youtube.com/embed/${match[1]}` : url
}

async function markComplete() {
  completing.value = true
  try {
    await api.post(`/student/lessons/${props.lessonId}/complete`)
    completed.value = true
    toast.success('Đã hoàn thành bài học!')
  } catch (e) {
    toast.error(e.response?.data?.message ?? 'Có lỗi xảy ra.')
  } finally { completing.value = false }
}

onMounted(async () => {
  try {
    const [lRes, mRes, lpRes] = await Promise.all([
      api.get(`/lessons/${props.lessonId}`),
      api.get(`/teacher/lesson-materials`, { params: { lesson_id: props.lessonId } }).catch(() => ({ data: [] })),
      api.get('/student/lesson-progress'),
    ])
    lesson.value = lRes.data?.data ?? lRes.data
    materials.value = mRes.data?.data ?? mRes.data ?? []
    const progress = lpRes.data?.data ?? lpRes.data ?? []
    completed.value = progress.some(p => p.lesson_id == props.lessonId && p.status)
  } finally { loading.value = false }
})
</script>

<style scoped>
.skeleton { background: var(--border); }
.prose-content { line-height: 1.8; }
</style>
