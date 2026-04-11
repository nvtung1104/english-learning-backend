<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Quản lý Quiz</h1>
        </div>
        <button @click="openAdd" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Thêm quiz
        </button>
      </div>

      <div class="theme-card rounded-[24px] p-4">
        <input v-model="search" type="text" placeholder="Tìm quiz..." class="search-input w-full rounded-2xl px-4 py-2.5 text-sm outline-none" />
      </div>

      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>
        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">📝</p>
          <p class="mt-4 font-semibold theme-text">Chưa có quiz nào</p>
        </div>
        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Tên quiz</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Thời gian (phút)</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Điểm đạt</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="quiz in filtered" :key="quiz.id" class="table-row border-t" style="border-color: var(--border)">
              <td class="px-5 py-4 theme-text font-medium">{{ quiz.title }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ quiz.time_limit ?? '—' }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ quiz.pass_score ?? '—' }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <RouterLink :to="`/teacher/quiz-questions?quiz_id=${quiz.id}`" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Câu hỏi</RouterLink>
                  <button @click="openEdit(quiz)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Sửa</button>
                  <button @click="deleteQuiz(quiz.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-md rounded-[28px] p-6 space-y-4" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editing ? 'Sửa quiz' : 'Thêm quiz' }}</h2>

          <div class="form-group">
            <label class="form-label">Tên quiz</label>
            <input v-model="modalForm.title" type="text" class="form-input" />
          </div>
          <div class="form-group">
            <label class="form-label">Bài học</label>
            <select v-model="modalForm.lesson_id" class="form-input">
              <option value="">Chọn bài học</option>
              <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
            </select>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="form-group">
              <label class="form-label">Thời gian (phút)</label>
              <input v-model="modalForm.time_limit" type="number" min="1" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Điểm đạt (%)</label>
              <input v-model="modalForm.pass_score" type="number" min="0" max="100" class="form-input" />
            </div>
          </div>

          <div v-if="modalError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">{{ modalError }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="saveQuiz" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
              {{ saving ? 'Đang lưu...' : 'Lưu' }}
            </button>
            <button @click="modal = false" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold">Hủy</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { quizService, lessonService } from '@/services/teacher.service'

const quizzes = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const modalError = ref('')
const modalForm = ref({ title: '', lesson_id: '', time_limit: 30, pass_score: 50 })

async function load() {
  loading.value = true
  try {
    const [qRes, lRes] = await Promise.all([quizService.list(), lessonService.list()])
    quizzes.value = qRes.data?.data ?? qRes.data ?? []
    lessons.value = lRes.data?.data ?? lRes.data ?? []
  } finally { loading.value = false }
}

onMounted(load)

const filtered = computed(() =>
  quizzes.value.filter(q => q.title?.toLowerCase().includes(search.value.toLowerCase()))
)

function openAdd() {
  editing.value = null
  modalForm.value = { title: '', lesson_id: '', time_limit: 30, pass_score: 50 }
  modalError.value = ''
  modal.value = true
}

function openEdit(quiz) {
  editing.value = quiz
  modalForm.value = { title: quiz.title, lesson_id: quiz.lesson_id, time_limit: quiz.time_limit, pass_score: quiz.pass_score }
  modalError.value = ''
  modal.value = true
}

async function saveQuiz() {
  saving.value = true; modalError.value = ''
  try {
    if (editing.value) {
      await quizService.update(editing.value.id, modalForm.value)
    } else {
      await quizService.create(modalForm.value)
    }
    modal.value = false
    await load()
  } catch (e) {
    modalError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteQuiz(id) {
  if (!confirm('Xóa quiz này?')) return
  await quizService.destroy(id)
  await load()
}
</script>

<style scoped>
.search-input { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
.search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.table-head { background: var(--surface-muted); }
.table-row:hover { background: var(--surface-muted); }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-label { font-size: 0.875rem; font-weight: 600; color: var(--text); }
.form-input { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); border-radius: 1rem; padding: 0.625rem 1rem; font-size: 0.875rem; outline: none; width: 100%; }
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.action-btn { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
.action-btn:hover { background: var(--surface-strong); }
.danger-btn { border: 1px solid color-mix(in srgb, var(--danger) 25%, var(--border)); color: var(--danger); background: color-mix(in srgb, var(--danger) 6%, var(--surface-muted)); }
.danger-btn:hover { background: color-mix(in srgb, var(--danger) 10%, var(--surface-muted)); }
.submit-btn { background: var(--accent); }
.submit-btn:hover { opacity: 0.88; }
.cancel-btn { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
</style>
