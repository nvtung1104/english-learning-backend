<template>
  <div class="min-h-screen bg-cream flex flex-col">
    <!-- Top bar -->
    <div class="fixed inset-x-0 top-0 z-40 bg-white border-b border-[var(--border-soft)] shadow-card h-14 flex items-center px-4 gap-4">
      <RouterLink to="/student/my-courses" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-cream-soft hover:text-navy transition-all border border-[var(--border)] shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </RouterLink>
      <div class="flex-1 min-w-0">
        <p class="font-display font-bold text-navy text-sm truncate">{{ lesson?.title || 'Bài học' }}</p>
      </div>
      <!-- Progress -->
      <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
        <div class="w-32 h-1.5 bg-cream-soft rounded-full overflow-hidden border border-[var(--border)]">
          <div class="h-full bg-navy rounded-full transition-all" :style="{ width: `${lessonProgress}%` }" />
        </div>
        <span>{{ lessonProgress }}%</span>
      </div>
      <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:bg-cream-soft border border-[var(--border)]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    <div class="flex flex-1 pt-14">
      <!-- Lesson sidebar -->
      <aside :class="[
        'fixed inset-y-0 left-0 z-30 w-72 bg-white border-r border-[var(--border-soft)] flex flex-col transition-transform duration-300 pt-14',
        'lg:static lg:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
      ]">
        <div class="px-4 py-3 border-b border-[var(--border-soft)]">
          <p class="font-display font-bold text-navy text-sm">Nội dung khóa học</p>
        </div>
        <div class="flex-1 overflow-y-auto scrollbar-thin py-2">
          <div v-for="section in sections" :key="section.id" class="mb-1">
            <div class="px-4 py-2.5 flex items-center gap-2">
              <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ section.title }}</span>
            </div>
            <button
              v-for="l in section.lessons" :key="l.id"
              @click="selectLesson(l)"
              :class="[
                'w-full text-left px-4 py-2.5 flex items-center gap-3 text-sm transition-colors',
                l.id === lesson?.id ? 'bg-navy/5 text-navy font-semibold' : 'text-slate-600 hover:bg-cream-soft'
              ]"
            >
              <div :class="['w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-[10px]', l.completed ? 'bg-success-500 text-white' : 'border-2 border-[var(--border)] text-slate-400']">
                <svg v-if="l.completed" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <span class="truncate flex-1">{{ l.title }}</span>
              <span class="text-[10px] text-slate-400 shrink-0">{{ l.duration || '5p' }}</span>
            </button>
          </div>
        </div>
      </aside>

      <!-- Main content -->
      <main class="flex-1 min-w-0 overflow-y-auto">
        <div class="max-w-3xl mx-auto px-4 py-8">
          <Spinner v-if="loading" />
          <div v-else-if="lesson">
            <!-- Content area -->
            <div class="card overflow-hidden mb-5">
              <!-- Video placeholder -->
              <div v-if="lesson.type === 'video'" class="aspect-video bg-navy flex items-center justify-center">
                <div class="text-center text-white/60">
                  <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"/>
                    </svg>
                  </div>
                  <p class="text-sm">Video bài học</p>
                </div>
              </div>
              <div class="p-6">
                <h1 class="font-display text-2xl font-bold text-navy mb-4">{{ lesson.title }}</h1>
                <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed" v-html="lesson.content || '<p>Nội dung bài học sẽ hiển thị ở đây.</p>'" />
              </div>
            </div>

            <!-- Tabs: vocab, grammar, materials -->
            <AppTabs v-model="activeTab" :tabs="tabs" variant="line">
              <div v-if="activeTab === 'vocab'" class="space-y-3">
                <div v-for="v in lesson.vocabularies || []" :key="v.id" class="card-cream p-4 rounded-2xl flex items-start gap-4">
                  <div class="w-10 h-10 rounded-xl bg-navy/8 flex items-center justify-center text-lg shrink-0">📝</div>
                  <div>
                    <p class="font-bold text-navy text-sm">{{ v.word }}</p>
                    <p class="text-xs text-slate-500">{{ v.pronunciation }}</p>
                    <p class="text-sm text-slate-700 mt-1">{{ v.meaning }}</p>
                  </div>
                </div>
                <EmptyState v-if="!lesson.vocabularies?.length" icon="📝" title="Chưa có từ vựng" description="Bài học này chưa có từ vựng." />
              </div>
              <div v-if="activeTab === 'grammar'" class="space-y-3">
                <EmptyState icon="✏️" title="Chưa có ngữ pháp" description="Bài học này chưa có nội dung ngữ pháp." />
              </div>
              <div v-if="activeTab === 'materials'" class="space-y-3">
                <EmptyState icon="📎" title="Chưa có tài liệu" description="Bài học này chưa có tài liệu đính kèm." />
              </div>
            </AppTabs>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-6 pt-6 border-t border-[var(--border-soft)]">
              <AppButton variant="secondary" size="md" :disabled="!prevLesson" @click="selectLesson(prevLesson)">
                ← Bài trước
              </AppButton>
              <AppButton variant="primary" size="md" @click="markComplete">
                {{ lesson.completed ? '✓ Đã hoàn thành' : 'Đánh dấu hoàn thành' }}
              </AppButton>
              <AppButton variant="secondary" size="md" :disabled="!nextLesson" @click="selectLesson(nextLesson)">
                Bài tiếp →
              </AppButton>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div v-if="sidebarOpen" class="fixed inset-0 z-20 bg-navy/30 lg:hidden" @click="sidebarOpen = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Spinner from '../components/Spinner.vue'
import EmptyState from '../components/ui/EmptyState.vue'
import AppTabs from '../components/ui/AppTabs.vue'
import AppButton from '../components/ui/AppButton.vue'

const lesson       = ref(null)
const loading      = ref(true)
const sidebarOpen  = ref(false)
const activeTab    = ref('vocab')
const lessonProgress = ref(35)

const tabs = [
  { value: 'vocab',     label: 'Từ vựng',  icon: '📝' },
  { value: 'grammar',   label: 'Ngữ pháp', icon: '✏️' },
  { value: 'materials', label: 'Tài liệu', icon: '📎' },
]

const sections = ref([
  {
    id: 1, title: 'Chương 1: Khởi đầu',
    lessons: [
      { id: 1, title: 'Giới thiệu khóa học', duration: '5p', completed: true },
      { id: 2, title: 'Bảng chữ cái tiếng Anh', duration: '10p', completed: true },
      { id: 3, title: 'Phát âm cơ bản', duration: '15p', completed: false },
    ],
  },
  {
    id: 2, title: 'Chương 2: Từ vựng',
    lessons: [
      { id: 4, title: 'Từ vựng chủ đề gia đình', duration: '12p', completed: false },
      { id: 5, title: 'Từ vựng chủ đề công việc', duration: '14p', completed: false },
    ],
  },
])

const allLessons = computed(() => sections.value.flatMap(s => s.lessons))
const currentIdx = computed(() => allLessons.value.findIndex(l => l.id === lesson.value?.id))
const prevLesson = computed(() => currentIdx.value > 0 ? allLessons.value[currentIdx.value - 1] : null)
const nextLesson = computed(() => currentIdx.value < allLessons.value.length - 1 ? allLessons.value[currentIdx.value + 1] : null)

function selectLesson(l) {
  if (!l) return
  lesson.value = { ...l, content: '<p>Nội dung bài học đang được tải...</p>', type: 'text', vocabularies: [] }
  sidebarOpen.value = false
}

function markComplete() {
  if (lesson.value) lesson.value.completed = true
}

onMounted(() => {
  setTimeout(() => {
    lesson.value = { id: 3, title: 'Phát âm cơ bản', content: '<p>Trong bài học này, chúng ta sẽ học về các âm cơ bản trong tiếng Anh...</p>', type: 'text', vocabularies: [] }
    loading.value = false
  }, 500)
})
</script>
