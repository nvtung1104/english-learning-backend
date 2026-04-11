<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Listening</h1>
        <p class="mt-1 text-sm theme-text-soft">Luyện nghe với audio và transcript</p>
      </div>

      <!-- Search + filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <input
          v-model="search"
          type="search"
          class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none"
          placeholder="Tìm bài nghe theo tên bài học..."
        />
        <select v-model="lessonFilter" class="search-input rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả bài học</option>
          <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
        </select>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid gap-5 sm:grid-cols-2">
        <div v-for="i in 4" :key="i" class="theme-card rounded-[28px] p-5 animate-pulse space-y-3">
          <div class="h-5 rounded skeleton w-3/4"></div>
          <div class="h-10 rounded-xl skeleton"></div>
          <div class="h-4 rounded skeleton w-1/2"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">🎧</p>
        <p class="mt-4 text-lg font-semibold theme-text">Không tìm thấy bài nghe</p>
      </div>

      <!-- Grid -->
      <div v-else class="grid gap-5 sm:grid-cols-2">
        <div
          v-for="item in filtered"
          :key="item.id"
          class="listen-card theme-card rounded-[28px] p-5 space-y-4"
        >
          <!-- Lesson title (từ relation lesson_id → lesson) -->
          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="font-semibold theme-text">
                {{ item.lesson?.title ?? `Bài nghe #${item.id}` }}
              </p>
              <p v-if="item.lesson?.section?.course?.title" class="mt-0.5 text-xs theme-text-muted">
                {{ item.lesson.section.course.title }}
              </p>
            </div>
            <span
              class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold"
              style="background: color-mix(in srgb, #3b82f6 12%, var(--surface-muted)); color: #3b82f6"
            >🎧 Listening</span>
          </div>

          <!-- Audio player — field `audio_url` theo DB -->
          <audio
            v-if="item.audio_url"
            :src="$storage(item.audio_url)"
            controls
            class="w-full h-9 rounded-xl"
          ></audio>
          <div
            v-else
            class="flex h-9 items-center justify-center rounded-xl text-xs theme-text-muted"
            style="background: var(--surface-strong)"
          >Chưa có audio</div>

          <!-- Transcript toggle — field `transcript` theo DB -->
          <div v-if="item.transcript">
            <button
              type="button"
              class="text-xs font-semibold"
              style="color: var(--accent)"
              @click="toggleTranscript(item.id)"
            >
              {{ openTranscripts.has(item.id) ? '▲ Ẩn transcript' : '▼ Xem transcript' }}
            </button>
            <div
              v-if="openTranscripts.has(item.id)"
              class="mt-3 rounded-2xl p-4 text-sm leading-7 theme-text-soft whitespace-pre-wrap"
              style="background: var(--surface-strong)"
            >{{ item.transcript }}</div>
          </div>
          <p v-else class="text-xs theme-text-muted">Chưa có transcript</p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'

const items = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const lessonFilter = ref('')
const openTranscripts = ref(new Set())

const filtered = computed(() =>
  items.value.filter(item => {
    const title = item.lesson?.title?.toLowerCase() ?? ''
    const matchSearch = !search.value || title.includes(search.value.toLowerCase())
    const matchLesson = !lessonFilter.value || String(item.lesson_id) === String(lessonFilter.value)
    return matchSearch && matchLesson
  })
)

function toggleTranscript(id) {
  const s = new Set(openTranscripts.value)
  s.has(id) ? s.delete(id) : s.add(id)
  openTranscripts.value = s
}

onMounted(async () => {
  try {
    const res = await api.get('/listening-lessons')
    items.value = res.data?.data ?? res.data ?? []
    const lessonMap = new Map()
    items.value.forEach(item => {
      if (item.lesson_id && item.lesson?.title) {
        lessonMap.set(item.lesson_id, { id: item.lesson_id, title: item.lesson.title })
      }
    })
    lessons.value = [...lessonMap.values()]
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.search-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); outline: none; }
.listen-card { border: 1.5px solid var(--border); }
.skeleton { background: var(--border); }
</style>
