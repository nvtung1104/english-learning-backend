<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Từ vựng</h1>
        <p class="mt-1 text-sm theme-text-soft">{{ vocabs.length }} từ vựng trong hệ thống</p>
      </div>

      <!-- Search + filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <input
          v-model="search"
          type="search"
          class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none"
          placeholder="Tìm từ vựng hoặc nghĩa..."
        />
        <select v-model="lessonFilter" class="search-input rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả bài học</option>
          <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
        </select>
      </div>

      <!-- Stats bar -->
      <div v-if="!loading && vocabs.length" class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="theme-card rounded-[20px] p-4 text-center">
          <p class="text-2xl font-semibold theme-text">{{ vocabs.length }}</p>
          <p class="mt-0.5 text-xs theme-text-muted">Tổng từ vựng</p>
        </div>
        <div class="theme-card rounded-[20px] p-4 text-center">
          <p class="text-2xl font-semibold theme-text">{{ lessons.length }}</p>
          <p class="mt-0.5 text-xs theme-text-muted">Bài học</p>
        </div>
        <div class="theme-card rounded-[20px] p-4 text-center">
          <p class="text-2xl font-semibold theme-text">{{ withAudio }}</p>
          <p class="mt-0.5 text-xs theme-text-muted">Có audio</p>
        </div>
        <div class="theme-card rounded-[20px] p-4 text-center">
          <p class="text-2xl font-semibold theme-text">{{ filtered.length }}</p>
          <p class="mt-0.5 text-xs theme-text-muted">Kết quả lọc</p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="i in 6" :key="i" class="theme-card rounded-[24px] p-5 animate-pulse space-y-3">
          <div class="h-5 rounded skeleton w-2/3"></div>
          <div class="h-4 rounded skeleton w-1/2"></div>
          <div class="h-3 rounded skeleton w-3/4"></div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="!filtered.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">📖</p>
        <p class="mt-4 text-lg font-semibold theme-text">Không tìm thấy từ vựng</p>
        <p class="mt-2 text-sm theme-text-soft">Thử thay đổi từ khóa hoặc bộ lọc</p>
      </div>

      <!-- Grid -->
      <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="v in filtered"
          :key="v.id"
          class="vocab-card theme-card rounded-[24px] p-5 space-y-3 cursor-pointer transition hover:-translate-y-0.5"
          :class="{ 'vocab-card--open': selected?.id === v.id }"
          @click="selected = selected?.id === v.id ? null : v"
        >
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
              <p class="text-lg font-bold theme-text">{{ v.word }}</p>
              <p class="mt-0.5 text-sm theme-text-soft">{{ v.meaning }}</p>
            </div>
            <span
              v-if="v.lesson?.title"
              class="shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold leading-none"
              style="background: color-mix(in srgb, var(--accent) 12%, var(--surface-muted)); color: var(--accent)"
            >{{ v.lesson.title }}</span>
          </div>

          <!-- Expanded -->
          <template v-if="selected?.id === v.id">
            <div class="border-t pt-3 space-y-3" style="border-color: var(--border)">
              <p v-if="v.example" class="text-sm theme-text-muted italic">"{{ v.example }}"</p>
              <!-- audio field từ DB là `audio` không phải `audio_url` -->
              <audio
                v-if="v.audio"
                :src="$storage(v.audio)"
                controls
                class="w-full h-8 rounded-xl"
              ></audio>
              <div v-if="!v.example && !v.audio" class="text-xs theme-text-muted">Chưa có thêm thông tin</div>
            </div>
          </template>

          <p class="text-xs theme-text-muted">
            {{ selected?.id === v.id ? '▲ Thu gọn' : '▼ Xem chi tiết' }}
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

const vocabs = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const lessonFilter = ref('')
const selected = ref(null)

// Tổng số từ có audio (field `audio` theo DB)
const withAudio = computed(() => vocabs.value.filter(v => v.audio).length)

const filtered = computed(() =>
  vocabs.value.filter(v => {
    const matchSearch = !search.value ||
      v.word?.toLowerCase().includes(search.value.toLowerCase()) ||
      v.meaning?.toLowerCase().includes(search.value.toLowerCase()) ||
      v.example?.toLowerCase().includes(search.value.toLowerCase())
    const matchLesson = !lessonFilter.value || String(v.lesson_id) === String(lessonFilter.value)
    return matchSearch && matchLesson
  })
)

onMounted(async () => {
  try {
    const res = await api.get('/vocabularies')
    vocabs.value = res.data?.data ?? res.data ?? []
    // Lấy danh sách bài học unique từ dữ liệu trả về
    const lessonMap = new Map()
    vocabs.value.forEach(v => {
      if (v.lesson_id && v.lesson?.title) {
        lessonMap.set(v.lesson_id, { id: v.lesson_id, title: v.lesson.title })
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
.vocab-card { border: 1.5px solid var(--border); }
.vocab-card--open { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 4%, var(--surface-muted)); }
.skeleton { background: var(--border); }
</style>
