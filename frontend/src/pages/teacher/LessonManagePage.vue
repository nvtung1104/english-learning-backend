<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-5xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Quản lý bài học</h1>
        </div>
        <button @click="openAdd" class="self-start rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          + Thêm bài học
        </button>
      </div>

      <!-- Filter -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 sm:flex-row">
        <input v-model="search" type="text" placeholder="Tìm bài học..." class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none" />
      </div>

      <!-- Table -->
      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>
        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">📖</p>
          <p class="mt-4 font-semibold theme-text">Chưa có bài học nào</p>
        </div>
        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Tên bài học</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Loại</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Thứ tự</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="lesson in filtered" :key="lesson.id" class="table-row border-t" style="border-color: var(--border)">
              <td class="px-5 py-4 theme-text font-medium">{{ lesson.title }}</td>
              <td class="px-5 py-4 theme-text-soft hidden md:table-cell">{{ lesson.lessonType?.name ?? '—' }}</td>
              <td class="px-5 py-4 theme-text-soft hidden lg:table-cell">{{ lesson.order_number }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="openEdit(lesson)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Sửa</button>
                  <button @click="deleteLesson(lesson.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-2xl rounded-[28px] p-6 space-y-4 max-h-[90vh] overflow-y-auto" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editing ? 'Sửa bài học' : 'Thêm bài học' }}</h2>

          <div class="form-group">
            <label class="form-label">Tên bài học</label>
            <input v-model="modalForm.title" type="text" class="form-input" />
          </div>
          <div class="form-group">
            <label class="form-label">Section <span class="text-xs font-normal theme-text-muted">(chương của khóa học)</span></label>
            <select v-model="modalForm.section_id" class="form-input">
              <option value="">Chọn section</option>
              <template v-for="(group, courseName) in sectionsByCoure" :key="courseName">
                <optgroup :label="courseName">
                  <option v-for="s in group" :key="s.id" :value="s.id">{{ s.title }}</option>
                </optgroup>
              </template>
            </select>
          </div>
          <!-- Lesson Type Picker -->
          <div class="form-group">
            <label class="form-label">Loại bài học</label>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
              <button
                v-for="t in lessonTypes"
                :key="t.id"
                type="button"
                @click="selectType(t)"
                :class="['type-card rounded-2xl p-3 text-left transition', modalForm.lesson_type_id === t.id ? 'type-card--active' : '']"
              >
                <span class="text-xl">{{ t.icon }}</span>
                <p class="mt-1 text-xs font-semibold theme-text leading-tight">{{ t.name }}</p>
                <p v-if="t.description" class="text-[10px] theme-text-muted leading-tight mt-0.5">{{ t.description }}</p>
              </button>
            </div>
          </div>

          <!-- Sub-topics (shown when selected type has sub_topics) -->
          <div v-if="selectedType?.sub_topics?.length" class="form-group">
            <label class="form-label">Chủ đề con <span class="text-xs font-normal theme-text-muted">(tùy chọn)</span></label>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="sub in selectedType.sub_topics"
                :key="sub"
                type="button"
                @click="toggleSubTopic(sub)"
                :class="['sub-tag rounded-full px-3 py-1 text-xs font-medium transition', modalForm.sub_topics?.includes(sub) ? 'sub-tag--active' : '']"
              >
                {{ sub }}
              </button>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Thứ tự</label>
            <input v-model="modalForm.order_number" type="number" min="1" class="form-input" />
          </div>
          <div class="form-group">
            <label class="form-label">Video</label>
            <UploadInput v-model="modalForm.video_url" type="video" :show-url="true" placeholder="https://youtube.com/... hoặc link video" />
          </div>
          <div class="form-group">
            <label class="form-label">Nội dung</label>
            <textarea v-model="modalForm.content" class="form-input" rows="3"></textarea>
          </div>

          <div v-if="modalError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">{{ modalError }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="saveLesson" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
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
import { lessonService, sectionService, lessonTypeService, courseService } from '@/services/teacher.service'
import UploadInput from '@/components/upload/UploadInput.vue'

const lessons = ref([])
const sections = ref([])
const lessonTypes = ref([])
const loading = ref(true)
const search = ref('')
const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const modalError = ref('')
const modalForm = ref({ title: '', section_id: '', lesson_type_id: '', order_number: 1, video_url: '', content: '', sub_topics: [] })

const selectedType = computed(() =>
  lessonTypes.value.find(t => t.id === modalForm.value.lesson_type_id) ?? null
)

const sectionsByCoure = computed(() => {
  const groups = {}
  for (const s of sections.value) {
    const key = s.course_title || 'Khác'
    if (!groups[key]) groups[key] = []
    groups[key].push(s)
  }
  return groups
})

function selectType(t) {
  modalForm.value.lesson_type_id = t.id
  modalForm.value.sub_topics = []
}

function toggleSubTopic(sub) {
  const arr = modalForm.value.sub_topics ?? []
  const idx = arr.indexOf(sub)
  if (idx === -1) arr.push(sub)
  else arr.splice(idx, 1)
  modalForm.value.sub_topics = [...arr]
}

async function load() {
  loading.value = true
  try {
    const [lRes, tRes, cRes] = await Promise.all([
      lessonService.list(),
      lessonTypeService.list(),
      courseService.list(),
    ])
    lessons.value = lRes.data?.data ?? lRes.data ?? []
    lessonTypes.value = (tRes.data?.data ?? tRes.data ?? []).map(t => ({
      ...t,
      sub_topics: typeof t.sub_topics === 'string' ? JSON.parse(t.sub_topics) : (t.sub_topics ?? []),
    }))
    const courses = cRes.data?.data ?? cRes.data ?? []
    const sectionResults = await Promise.all(courses.map(c => sectionService.byCourse(c.id)))
    sections.value = sectionResults.flatMap((r, i) =>
      (r.data?.data ?? r.data ?? []).map(s => ({ ...s, course_title: courses[i]?.title ?? '' }))
    )
  } finally { loading.value = false }
}

onMounted(load)

const filtered = computed(() =>
  lessons.value.filter(l => l.title?.toLowerCase().includes(search.value.toLowerCase()))
)

function openAdd() {
  editing.value = null
  modalForm.value = { title: '', section_id: '', lesson_type_id: '', order_number: 1, video_url: '', content: '', sub_topics: [] }
  modalError.value = ''
  modal.value = true
}

function openEdit(lesson) {
  editing.value = lesson
  modalForm.value = {
    title: lesson.title,
    section_id: lesson.section_id,
    lesson_type_id: lesson.lesson_type_id,
    order_number: lesson.order_number,
    video_url: lesson.video_url ?? '',
    content: lesson.content ?? '',
    sub_topics: lesson.sub_topics ?? [],
  }
  modalError.value = ''
  modal.value = true
}

async function saveLesson() {
  saving.value = true; modalError.value = ''
  try {
    if (editing.value) {
      await lessonService.update(editing.value.id, modalForm.value)
    } else {
      await lessonService.create(modalForm.value)
    }
    modal.value = false
    await load()
  } catch (e) {
    modalError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteLesson(id) {
  if (!confirm('Xóa bài học này?')) return
  await lessonService.destroy(id)
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
.type-card { border: 1.5px solid var(--border); background: var(--surface-muted); cursor: pointer; }
.type-card:hover { border-color: var(--accent); background: var(--surface-strong); }
.type-card--active { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 10%, var(--surface-muted)); box-shadow: 0 0 0 2px var(--ring); }
.sub-tag { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
.sub-tag:hover { border-color: var(--accent); }
.sub-tag--active { border-color: var(--accent); background: color-mix(in srgb, var(--accent) 15%, var(--surface-muted)); color: var(--accent); font-weight: 600; }
.video-tab { background: var(--surface-muted); color: var(--text-muted, var(--text)); }
.video-tab--active { background: var(--accent); color: #fff; }
</style>
