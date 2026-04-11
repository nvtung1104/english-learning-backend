<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Quản lý từ vựng</h1>
        </div>
        <button @click="openAdd" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Thêm từ vựng
        </button>
      </div>

      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <input v-model="search" type="text" placeholder="Tìm từ vựng..." class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none" />
        <select v-model="lessonFilter" class="search-input rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả bài học</option>
          <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
        </select>
      </div>

      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>
        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">📖</p>
          <p class="mt-4 font-semibold theme-text">Chưa có từ vựng nào</p>
        </div>
        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Từ</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Nghĩa</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Bài học</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="v in filtered" :key="v.id" class="table-row border-t" style="border-color: var(--border)">
              <td class="px-5 py-4 theme-text font-semibold">{{ v.word }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ v.meaning }}</td>
              <td class="px-5 py-4 theme-text-muted hidden lg:table-cell">{{ v.lesson?.title ?? '—' }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="openEdit(v)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Sửa</button>
                  <button @click="deleteVocab(v.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-lg rounded-[28px] p-6 space-y-4" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editing ? 'Sửa từ vựng' : 'Thêm từ vựng' }}</h2>

          <div class="form-group">
            <label class="form-label">Bài học <span class="text-red-500">*</span></label>
            <select v-model="modalForm.lesson_id" class="form-input" required>
              <option value="">Chọn bài học</option>
              <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
            </select>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="form-group">
              <label class="form-label">Từ <span class="text-red-500">*</span></label>
              <input v-model="modalForm.word" type="text" class="form-input" required />
            </div>
            <div class="form-group">
              <label class="form-label">Nghĩa <span class="text-red-500">*</span></label>
              <input v-model="modalForm.meaning" type="text" class="form-input" required />
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Ví dụ</label>
            <textarea v-model="modalForm.example" class="form-input" rows="2" placeholder="VD: This is an example sentence."></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Audio phát âm</label>
            <AudioUploadField v-model="modalForm.audio" placeholder="https://... hoặc tải file MP3" />
          </div>

          <div v-if="modalError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">{{ modalError }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="saveVocab" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
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
import { vocabularyService, lessonService } from '@/services/teacher.service'
import AudioUploadField from '@/components/upload/AudioUploadField.vue'

const vocabs = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const lessonFilter = ref('')
const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const modalError = ref('')
// DB fields: lesson_id, word, meaning, example, audio
const modalForm = ref({ lesson_id: '', word: '', meaning: '', example: '', audio: '' })

async function load() {
  loading.value = true
  try {
    const [vRes, lRes] = await Promise.all([vocabularyService.list(), lessonService.list()])
    vocabs.value = vRes.data?.data ?? vRes.data ?? []
    lessons.value = lRes.data?.data ?? lRes.data ?? []
  } finally { loading.value = false }
}

onMounted(load)

const filtered = computed(() =>
  vocabs.value.filter(v => {
    const matchSearch = v.word?.toLowerCase().includes(search.value.toLowerCase()) || v.meaning?.toLowerCase().includes(search.value.toLowerCase())
    const matchLesson = !lessonFilter.value || String(v.lesson_id) === String(lessonFilter.value)
    return matchSearch && matchLesson
  })
)

function openAdd() {
  editing.value = null
  modalForm.value = { lesson_id: '', word: '', meaning: '', example: '', audio: '' }
  modalError.value = ''
  modal.value = true
}

function openEdit(v) {
  editing.value = v
  modalForm.value = { lesson_id: v.lesson_id, word: v.word, meaning: v.meaning, example: v.example ?? '', audio: v.audio ?? '' }
  modalError.value = ''
  modal.value = true
}

async function saveVocab() {
  saving.value = true; modalError.value = ''
  try {
    const payload = {
      lesson_id: modalForm.value.lesson_id,
      word:      modalForm.value.word,
      meaning:   modalForm.value.meaning,
      example:   modalForm.value.example || null,
      audio:     modalForm.value.audio   || null,
    }
    if (editing.value) {
      await vocabularyService.update(editing.value.id, payload)
    } else {
      await vocabularyService.create(payload)
    }
    modal.value = false
    await load()
  } catch (e) {
    const errors = e.response?.data?.errors
    modalError.value = errors ? Object.values(errors).flat().join(' ') : (e.response?.data?.message ?? 'Có lỗi xảy ra.')
  } finally { saving.value = false }
}

async function deleteVocab(id) {
  if (!confirm('Xóa từ vựng này?')) return
  await vocabularyService.destroy(id)
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
