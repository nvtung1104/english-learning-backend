<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Quản lý Listening</h1>
        </div>
        <button @click="openAdd" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Thêm bài nghe
        </button>
      </div>

      <div class="theme-card rounded-[24px] p-4">
        <input v-model="search" type="text" placeholder="Tìm theo bài học..." class="search-input w-full rounded-2xl px-4 py-2.5 text-sm outline-none" />
      </div>

      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>
        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">🎧</p>
          <p class="mt-4 font-semibold theme-text">Chưa có bài nghe nào</p>
        </div>
        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Bài học</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Audio URL</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Transcript</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="l in filtered" :key="l.id" class="table-row border-t" style="border-color: var(--border)">
              <td class="px-5 py-4 theme-text font-medium">{{ l.lesson?.title ?? `Bài #${l.lesson_id}` }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">
                <a v-if="l.audio_url" :href="l.audio_url" target="_blank" class="underline" style="color: var(--accent)">Nghe thử</a>
                <span v-else>—</span>
              </td>
              <td class="px-5 py-4 theme-text-muted hidden lg:table-cell truncate max-w-[200px]">{{ l.transcript ? l.transcript.slice(0, 60) + '…' : '—' }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="openEdit(l)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Sửa</button>
                  <button @click="deleteItem(l.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-lg rounded-[28px] p-6 space-y-4" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editing ? 'Sửa bài nghe' : 'Thêm bài nghe' }}</h2>

          <div class="form-group">
            <label class="form-label">Bài học</label>
            <select v-model="form.lesson_id" class="form-input">
              <option value="">Chọn bài học</option>
              <option v-for="l in lessons" :key="l.id" :value="l.id">{{ l.title }}</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Audio</label>
            <AudioUploadField v-model="form.audio_url" placeholder="https://... hoặc tải file MP3" />
          </div>
          <div class="form-group">
            <label class="form-label">Transcript</label>
            <textarea v-model="form.transcript" class="form-input" rows="5" placeholder="Nội dung transcript..."></textarea>
          </div>

          <div v-if="error" class="rounded-2xl px-4 py-3 text-sm" style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)">{{ error }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="save" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
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
import { listeningService, lessonService } from '@/services/teacher.service'
import AudioUploadField from '@/components/upload/AudioUploadField.vue'

const items = ref([])
const lessons = ref([])
const loading = ref(true)
const search = ref('')
const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const error = ref('')
const form = ref({ lesson_id: '', audio_url: '', transcript: '' })

async function load() {
  loading.value = true
  try {
    const [lRes, lesRes] = await Promise.all([listeningService.list(), lessonService.list()])
    items.value = lRes.data?.data ?? lRes.data ?? []
    lessons.value = lesRes.data?.data ?? lesRes.data ?? []
  } finally { loading.value = false }
}

onMounted(load)

const filtered = computed(() =>
  items.value.filter(l => {
    const lessonTitle = l.lesson?.title?.toLowerCase() ?? ''
    return lessonTitle.includes(search.value.toLowerCase())
  })
)

function openAdd() {
  editing.value = null
  form.value = { lesson_id: '', audio_url: '', transcript: '' }
  error.value = ''
  modal.value = true
}

function openEdit(l) {
  editing.value = l
  form.value = { lesson_id: l.lesson_id ?? '', audio_url: l.audio_url ?? '', transcript: l.transcript ?? '' }
  error.value = ''
  modal.value = true
}

async function save() {
  saving.value = true; error.value = ''
  try {
    if (editing.value) {
      await listeningService.update(editing.value.id, form.value)
    } else {
      await listeningService.create(form.value)
    }
    modal.value = false
    await load()
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteItem(id) {
  if (!confirm('Xóa bài nghe này?')) return
  await listeningService.destroy(id)
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
