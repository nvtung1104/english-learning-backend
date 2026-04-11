<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Ngữ pháp</h1>
        <p class="mt-1 text-sm theme-text-soft">{{ topics.length }} chủ đề ngữ pháp</p>
      </div>

      <!-- Search + filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <input
          v-model="search"
          type="search"
          class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none"
          placeholder="Tìm chủ đề ngữ pháp..."
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
          <div class="h-4 rounded skeleton w-1/2"></div>
          <div class="h-16 rounded-xl skeleton"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">✍️</p>
        <p class="mt-4 text-lg font-semibold theme-text">Không tìm thấy chủ đề</p>
      </div>

      <!-- Grid -->
      <div v-else class="grid gap-5 sm:grid-cols-2">
        <div
          v-for="topic in filtered"
          :key="topic.id"
          class="grammar-card theme-card rounded-[28px] p-5 space-y-3 cursor-pointer transition hover:-translate-y-0.5"
          :class="{ 'grammar-card--open': selected?.id === topic.id }"
          @click="selected = selected?.id === topic.id ? null : topic"
        >
          <div class="flex items-start justify-between gap-2">
            <p class="font-semibold theme-text">{{ topic.title }}</p>
            <span
              v-if="topic.lesson?.title"
              class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold leading-none"
              style="background: color-mix(in srgb, var(--accent) 12%, var(--surface-muted)); color: var(--accent)"
            >{{ topic.lesson.title }}</span>
          </div>

          <!-- explanation là field thực trong DB (không phải content/description) -->
          <p
            v-if="topic.explanation && selected?.id !== topic.id"
            class="text-sm theme-text-soft line-clamp-2"
          >{{ topic.explanation }}</p>

          <!-- Expanded -->
          <template v-if="selected?.id === topic.id">
            <div
              v-if="topic.explanation"
              class="rounded-2xl p-4 text-sm leading-7 theme-text-soft whitespace-pre-wrap border-t pt-3"
              style="border-color: var(--border); background: var(--surface-strong)"
            >{{ topic.explanation }}</div>
            <div v-else class="text-xs theme-text-muted">Chưa có nội dung giải thích</div>
          </template>

          <p class="text-xs theme-text-muted">
            {{ selected?.id === topic.id ? '▲ Thu gọn' : '▼ Xem giải thích' }}
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/axios'

const topics = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const lessonFilter = ref('')
const selected = ref(null)

const filtered = computed(() =>
  topics.value.filter(t => {
    const matchSearch = !search.value ||
      t.title?.toLowerCase().includes(search.value.toLowerCase()) ||
      t.explanation?.toLowerCase().includes(search.value.toLowerCase())
    const matchLesson = !lessonFilter.value || String(t.lesson_id) === String(lessonFilter.value)
    return matchSearch && matchLesson
  })
)

onMounted(async () => {
  try {
    const res = await api.get('/grammar-topics')
    topics.value = res.data?.data ?? res.data ?? []
    const lessonMap = new Map()
    topics.value.forEach(t => {
      if (t.lesson_id && t.lesson?.title) {
        lessonMap.set(t.lesson_id, { id: t.lesson_id, title: t.lesson.title })
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
.grammar-card { border: 1.5px solid var(--border); }
.grammar-card--open { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 4%, var(--surface-muted)); }
.skeleton { background: var(--border); }
</style>
