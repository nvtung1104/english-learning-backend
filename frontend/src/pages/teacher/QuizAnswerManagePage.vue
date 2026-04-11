<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/quiz-questions" class="text-sm theme-text-muted hover:theme-text">← Câu hỏi</RouterLink>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Đáp án câu hỏi</h1>
        </div>
        <button @click="openAdd" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Thêm đáp án
        </button>
      </div>

      <div class="theme-card rounded-[24px] p-4">
        <select v-model="questionFilter" class="search-input w-full rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả câu hỏi</option>
          <option v-for="q in questions" :key="q.id" :value="q.id">{{ q.question_text }}</option>
        </select>
      </div>

      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>
        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">✅</p>
          <p class="mt-4 font-semibold theme-text">Chưa có đáp án nào</p>
        </div>
        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Đáp án</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Câu hỏi</th>
              <th class="px-5 py-4 text-center font-semibold theme-text-muted">Đúng</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in filtered" :key="a.id" class="table-row border-t" style="border-color: var(--border)">
              <td class="px-5 py-4 theme-text">{{ a.answer_text }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell line-clamp-1">{{ a.question?.question_text ?? '—' }}</td>
              <td class="px-5 py-4 text-center">
                <span v-if="a.is_correct" class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">✓ Đúng</span>
                <span v-else class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold theme-text-muted dark:bg-slate-800">Sai</span>
              </td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="openEdit(a)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Sửa</button>
                  <button @click="deleteAnswer(a.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-md rounded-[28px] p-6 space-y-4" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editing ? 'Sửa đáp án' : 'Thêm đáp án' }}</h2>

          <div class="form-group">
            <label class="form-label">Câu hỏi</label>
            <select v-model="modalForm.question_id" class="form-input">
              <option value="">Chọn câu hỏi</option>
              <option v-for="q in questions" :key="q.id" :value="q.id">{{ q.question_text }}</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Nội dung đáp án</label>
            <input v-model="modalForm.answer_text" type="text" class="form-input" />
          </div>
          <div class="flex items-center gap-3">
            <input v-model="modalForm.is_correct" type="checkbox" id="is_correct" class="h-4 w-4 rounded" />
            <label for="is_correct" class="text-sm font-semibold theme-text">Đây là đáp án đúng</label>
          </div>

          <div v-if="modalError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">{{ modalError }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="saveAnswer" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
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
import { RouterLink, useRoute } from 'vue-router'
import { answerService, questionService } from '@/services/teacher.service'

const route = useRoute()
const answers = ref([])
const questions = ref([])
const loading = ref(true)
const questionFilter = ref(route.query.question_id ?? '')
const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const modalError = ref('')
const modalForm = ref({ question_id: '', answer_text: '', is_correct: false })

async function load() {
  loading.value = true
  try {
    const [aRes, qRes] = await Promise.all([answerService.list(), questionService.list()])
    answers.value = aRes.data?.data ?? aRes.data ?? []
    questions.value = qRes.data?.data ?? qRes.data ?? []
  } finally { loading.value = false }
}

onMounted(load)

const filtered = computed(() =>
  answers.value.filter(a => !questionFilter.value || String(a.question_id) === String(questionFilter.value))
)

function openAdd() {
  editing.value = null
  modalForm.value = { question_id: questionFilter.value, answer_text: '', is_correct: false }
  modalError.value = ''
  modal.value = true
}

function openEdit(a) {
  editing.value = a
  modalForm.value = { question_id: a.question_id, answer_text: a.answer_text, is_correct: !!a.is_correct }
  modalError.value = ''
  modal.value = true
}

async function saveAnswer() {
  saving.value = true; modalError.value = ''
  try {
    if (editing.value) {
      await answerService.update(editing.value.id, modalForm.value)
    } else {
      await answerService.create(modalForm.value)
    }
    modal.value = false
    await load()
  } catch (e) {
    modalError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteAnswer(id) {
  if (!confirm('Xóa đáp án này?')) return
  await answerService.destroy(id)
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
