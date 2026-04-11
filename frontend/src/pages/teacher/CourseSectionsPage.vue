<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-4xl space-y-6">

      <!-- Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/courses" class="text-sm theme-text-muted hover:theme-text">← Khóa học của tôi</RouterLink>
          <h1 class="mt-2 text-2xl font-semibold tracking-tight theme-text">
            {{ course?.title ?? 'Nội dung khóa học' }}
          </h1>
          <p v-if="course?.creator?.name" class="mt-1 text-sm theme-text-muted">
            👨‍🏫 Giáo viên: {{ course.creator.name }}
          </p>
        </div>
        <div class="flex gap-2 self-start flex-wrap">
          <RouterLink
            :to="`/teacher/courses/${courseId}/edit`"
            class="rounded-full px-4 py-2.5 text-sm font-semibold"
            style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
          >
            ✏️ Chỉnh sửa
          </RouterLink>
          <button
            @click="openAddSection"
            class="rounded-full px-4 py-2.5 text-sm font-semibold text-white"
            style="background: var(--accent)"
          >
            + Thêm section
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="space-y-4">
        <div v-for="i in 3" :key="i" class="theme-card rounded-[24px] p-5 animate-pulse h-24" style="background: var(--border)"></div>
      </div>

      <!-- Empty -->
      <div v-else-if="!sections.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">📂</p>
        <p class="mt-4 text-lg font-semibold theme-text">Chưa có section nào</p>
        <p class="mt-2 text-sm theme-text-soft">Thêm section để tổ chức nội dung khóa học.</p>
        <button
          @click="openAddSection"
          class="mt-5 rounded-full px-5 py-2.5 text-sm font-semibold text-white"
          style="background: var(--accent)"
        >
          + Thêm section đầu tiên
        </button>
      </div>

      <!-- Sections -->
      <div v-else class="space-y-5">
        <div
          v-for="section in sections"
          :key="section.id"
          class="theme-card rounded-[24px] overflow-hidden"
        >
          <!-- Section header -->
          <div class="flex items-center justify-between gap-4 px-5 py-4" style="border-bottom: 1px solid var(--border)">
            <div class="flex items-center gap-3">
              <button
                @click="toggleSection(section.id)"
                class="text-sm theme-text-muted transition hover:theme-text"
              >
                {{ expanded[section.id] ? '▼' : '▶' }}
              </button>
              <div>
                <p class="font-semibold theme-text">{{ section.title }}</p>
                <p class="text-xs theme-text-muted mt-0.5">
                  {{ (sectionLessons[section.id] ?? []).length }} bài học · Thứ tự {{ section.order_number }}
                </p>
              </div>
            </div>
            <div class="flex gap-2 shrink-0">
              <button @click="openAddLesson(section)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">
                + Bài học
              </button>
              <button @click="openEditSection(section)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">
                Sửa
              </button>
              <button @click="deleteSection(section.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">
                Xóa
              </button>
            </div>
          </div>

          <!-- Lessons list -->
          <div v-if="expanded[section.id]">
            <div v-if="!sectionLessons[section.id]?.length" class="px-5 py-6 text-center text-sm theme-text-muted">
              Chưa có bài học nào trong section này.
            </div>
            <div v-else>
              <div
                v-for="lesson in sectionLessons[section.id]"
                :key="lesson.id"
                class="flex items-center justify-between gap-4 px-5 py-3.5 border-t"
                style="border-color: var(--border)"
              >
                <div class="flex items-center gap-3 min-w-0">
                  <span class="text-lg shrink-0">{{ lessonIcon(lesson) }}</span>
                  <div class="min-w-0">
                    <p class="text-sm font-medium theme-text truncate">{{ lesson.title }}</p>
                    <p class="text-xs theme-text-muted mt-0.5">
                      {{ lesson.lessonType?.name ?? lesson.lesson_type?.name ?? '—' }}
                      <span v-if="lesson.duration"> · {{ lesson.duration }} phút</span>
                    </p>
                  </div>
                </div>
                <div class="flex gap-2 shrink-0">
                  <RouterLink
                    :to="`/teacher/lesson-materials?lesson_id=${lesson.id}`"
                    class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold"
                  >
                    Tài liệu
                  </RouterLink>
                  <button @click="openEditLesson(lesson, section)" class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold">
                    Sửa
                  </button>
                  <button @click="deleteLesson(lesson.id, section.id)" class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold">
                    Xóa
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Section modal -->
      <div v-if="sectionModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-md rounded-[28px] p-6 space-y-4" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editingSection ? 'Sửa section' : 'Thêm section' }}</h2>

          <div class="form-group">
            <label class="form-label">Tên section</label>
            <input v-model="sectionForm.title" type="text" class="form-input" placeholder="VD: Unit 1 - Introduction" />
          </div>
          <div class="form-group">
            <label class="form-label">Thứ tự</label>
            <input v-model.number="sectionForm.order_number" type="number" min="1" class="form-input" />
          </div>

          <div v-if="sectionError" class="rounded-2xl px-4 py-3 text-sm" style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)">{{ sectionError }}</div>

          <div class="flex gap-3 pt-1">
            <button @click="saveSection" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
              {{ saving ? 'Đang lưu...' : 'Lưu' }}
            </button>
            <button @click="sectionModal = false" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold">Hủy</button>
          </div>
        </div>
      </div>

      <!-- Lesson modal -->
      <div v-if="lessonModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)">
        <div class="w-full max-w-lg rounded-[28px] p-6 space-y-4 overflow-y-auto max-h-[92vh]" style="background: var(--surface-strong); border: 1px solid var(--border)">
          <h2 class="text-xl font-semibold theme-text">{{ editingLesson ? 'Sửa bài học' : 'Thêm bài học' }}</h2>

          <!-- Tabs (only when adding new) -->
          <div v-if="!editingLesson" class="flex rounded-2xl overflow-hidden" style="border: 1px solid var(--border)">
            <button
              @click="lessonTab = 'new'"
              class="flex-1 py-2.5 text-sm font-semibold transition"
              :style="lessonTab === 'new' ? 'background: var(--accent); color: white' : 'background: var(--surface-muted); color: var(--text)'"
            >
              ✏️ Tạo mới
            </button>
            <button
              @click="lessonTab = 'pick'; loadAllLessons()"
              class="flex-1 py-2.5 text-sm font-semibold transition"
              :style="lessonTab === 'pick' ? 'background: var(--accent); color: white' : 'background: var(--surface-muted); color: var(--text)'"
            >
              📋 Chọn từ danh sách
            </button>
          </div>

          <!-- Tab: Pick existing lesson -->
          <template v-if="lessonTab === 'pick' && !editingLesson">
            <input
              v-model="pickSearch"
              type="text"
              class="form-input"
              placeholder="Tìm bài học..."
            />
            <div class="max-h-72 overflow-y-auto space-y-2 rounded-2xl" style="border: 1px solid var(--border); padding: 0.5rem">
              <div v-if="loadingAllLessons" class="py-6 text-center text-sm theme-text-muted">Đang tải...</div>
              <div v-else-if="!filteredPickLessons.length" class="py-6 text-center text-sm theme-text-muted">Không tìm thấy bài học nào.</div>
              <div
                v-for="l in filteredPickLessons"
                :key="l.id"
                @click="pickLesson(l)"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 cursor-pointer transition"
                :style="pickedLesson?.id === l.id ? 'background: var(--accent); color: white' : 'background: var(--surface-muted); color: var(--text)'"
              >
                <span>{{ lessonIcon(l) }}</span>
                <div class="min-w-0">
                  <p class="text-sm font-medium truncate">{{ l.title }}</p>
                  <p class="text-xs opacity-70">{{ l.lessonType?.name ?? l.lesson_type?.name ?? '—' }}</p>
                </div>
              </div>
            </div>
            <div v-if="lessonError" class="rounded-2xl px-4 py-3 text-sm" style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)">{{ lessonError }}</div>
            <div class="flex gap-3 pt-1">
              <button @click="confirmPickLesson" :disabled="!pickedLesson || saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
                {{ saving ? 'Đang lưu...' : 'Thêm vào section' }}
              </button>
              <button @click="lessonModal = false" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold">Hủy</button>
            </div>
          </template>

          <!-- Tab: Create new lesson -->
          <template v-else>
            <div class="form-group">
              <label class="form-label">Tên bài học</label>
              <input v-model="lessonForm.title" type="text" class="form-input" placeholder="VD: Lesson 1 - Greetings" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="form-group">
                <label class="form-label">Loại bài học</label>
                <select v-model="lessonForm.lesson_type_id" class="form-input">
                  <option value="">Chọn loại</option>
                  <option v-for="t in lessonTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Thứ tự</label>
                <input v-model.number="lessonForm.order_number" type="number" min="1" class="form-input" />
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">URL Video</label>
              <input v-model="lessonForm.video_url" type="url" class="form-input" placeholder="https://youtube.com/..." />
            </div>

            <div class="form-group">
              <label class="form-label">Nội dung / mô tả</label>
              <textarea v-model="lessonForm.content" class="form-input" rows="3" placeholder="Mô tả nội dung bài học..."></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Thời lượng (phút)</label>
              <input v-model.number="lessonForm.duration" type="number" min="0" class="form-input" placeholder="VD: 15" />
            </div>

            <div v-if="lessonError" class="rounded-2xl px-4 py-3 text-sm" style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)">{{ lessonError }}</div>

            <div class="flex gap-3 pt-1">
              <button @click="saveLesson" :disabled="saving" class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60">
                {{ saving ? 'Đang lưu...' : 'Lưu' }}
              </button>
              <button @click="lessonModal = false" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold">Hủy</button>
            </div>
          </template>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { courseService, sectionService, lessonService, lessonTypeService } from '@/services/teacher.service'

const props = defineProps({ courseId: { type: String, required: true } })

const course = ref(null)
const sections = ref([])
const sectionLessons = reactive({}) // { sectionId: [lessons] }
const lessonTypes = ref([])
const loading = ref(true)
const saving = ref(false)
const expanded = reactive({})

// Section modal
const sectionModal = ref(false)
const editingSection = ref(null)
const sectionError = ref('')
const sectionForm = ref({ title: '', order_number: 1 })

// Lesson modal
const lessonModal = ref(false)
const editingLesson = ref(null)
const lessonError = ref('')
const currentSectionId = ref(null)
const lessonForm = ref({ title: '', section_id: '', lesson_type_id: '', order_number: 1, video_url: '', content: '', duration: 0 })

// Pick from existing lessons
const lessonTab = ref('new') // 'new' | 'pick'
const allLessons = ref([])
const loadingAllLessons = ref(false)
const pickSearch = ref('')
const pickedLesson = ref(null)

const filteredPickLessons = computed(() =>
  allLessons.value.filter(l =>
    l.title?.toLowerCase().includes(pickSearch.value.toLowerCase())
  )
)

async function loadAllLessons() {
  if (allLessons.value.length) return
  loadingAllLessons.value = true
  try {
    const res = await lessonService.list()
    allLessons.value = res.data?.data ?? res.data ?? []
  } finally {
    loadingAllLessons.value = false
  }
}

function pickLesson(l) {
  pickedLesson.value = pickedLesson.value?.id === l.id ? null : l
}

async function confirmPickLesson() {
  if (!pickedLesson.value) return
  saving.value = true; lessonError.value = ''
  try {
    await lessonService.update(pickedLesson.value.id, {
      ...pickedLesson.value,
      section_id: currentSectionId.value,
      lesson_type_id: pickedLesson.value.lesson_type_id ?? '',
    })
    lessonModal.value = false
    pickedLesson.value = null
    allLessons.value = [] // reset cache
    await loadSectionLessons(currentSectionId.value)
  } catch (e) {
    lessonError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function load() {
  loading.value = true
  try {
    const [courseRes, sectionsRes, typesRes] = await Promise.all([
      courseService.get(props.courseId),
      sectionService.byCourse(props.courseId),
      lessonTypeService.list(),
    ])

    course.value = courseRes.data?.data ?? courseRes.data
    sections.value = sectionsRes.data?.data ?? sectionsRes.data ?? []
    lessonTypes.value = typesRes.data?.data ?? typesRes.data ?? []

    // Load lessons for each section
    await Promise.all(sections.value.map(s => loadSectionLessons(s.id)))

    // Auto-expand all sections
    sections.value.forEach(s => { expanded[s.id] = true })
  } finally {
    loading.value = false
  }
}

async function loadSectionLessons(sectionId) {
  try {
    const res = await lessonService.list({ section_id: sectionId })
    const lessons = res.data?.data ?? res.data ?? []
    sectionLessons[sectionId] = lessons.sort((a, b) => a.order_number - b.order_number)
  } catch {
    sectionLessons[sectionId] = []
  }
}

onMounted(load)

function toggleSection(id) {
  expanded[id] = !expanded[id]
}

function lessonIcon(lesson) {
  const name = (lesson.lessonType?.name ?? lesson.lesson_type?.name ?? '').toLowerCase()
  if (name.includes('video')) return '🎬'
  if (name.includes('quiz')) return '📝'
  if (name.includes('read')) return '📖'
  if (name.includes('listen')) return '🎧'
  return '📄'
}

// ── Section CRUD ──────────────────────────────────────────────
function openAddSection() {
  editingSection.value = null
  sectionForm.value = { title: '', order_number: sections.value.length + 1 }
  sectionError.value = ''
  sectionModal.value = true
}

function openEditSection(section) {
  editingSection.value = section
  sectionForm.value = { title: section.title, order_number: section.order_number }
  sectionError.value = ''
  sectionModal.value = true
}

async function saveSection() {
  saving.value = true; sectionError.value = ''
  try {
    if (editingSection.value) {
      await sectionService.update(editingSection.value.id, sectionForm.value)
    } else {
      await sectionService.create({ ...sectionForm.value, course_id: props.courseId })
    }
    sectionModal.value = false
    await load()
  } catch (e) {
    sectionError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteSection(id) {
  if (!confirm('Xóa section này và toàn bộ bài học bên trong?')) return
  await sectionService.destroy(id)
  await load()
}

// ── Lesson CRUD ───────────────────────────────────────────────
function openAddLesson(section) {
  editingLesson.value = null
  currentSectionId.value = section.id
  const count = (sectionLessons[section.id] ?? []).length
  lessonForm.value = {
    title: '', section_id: section.id, lesson_type_id: '',
    order_number: count + 1, video_url: '', content: '', duration: 0,
  }
  lessonError.value = ''
  lessonTab.value = 'new'
  pickedLesson.value = null
  pickSearch.value = ''
  lessonModal.value = true
}

function openEditLesson(lesson, section) {
  editingLesson.value = lesson
  currentSectionId.value = section.id
  lessonForm.value = {
    title: lesson.title,
    section_id: lesson.section_id,
    lesson_type_id: lesson.lesson_type_id ?? '',
    order_number: lesson.order_number,
    video_url: lesson.video_url ?? '',
    content: lesson.content ?? '',
    duration: lesson.duration ?? 0,
  }
  lessonError.value = ''
  lessonModal.value = true
}

async function saveLesson() {
  saving.value = true; lessonError.value = ''
  try {
    if (editingLesson.value) {
      await lessonService.update(editingLesson.value.id, lessonForm.value)
    } else {
      await lessonService.create(lessonForm.value)
    }
    lessonModal.value = false
    await loadSectionLessons(currentSectionId.value)
  } catch (e) {
    lessonError.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally { saving.value = false }
}

async function deleteLesson(id, sectionId) {
  if (!confirm('Xóa bài học này?')) return
  await lessonService.destroy(id)
  await loadSectionLessons(sectionId)
}
</script>

<style scoped>
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-label { font-size: 0.875rem; font-weight: 600; color: var(--text); }
.form-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 1rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
}
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.action-btn { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
.action-btn:hover { background: var(--surface-strong); }
.danger-btn {
  border: 1px solid color-mix(in srgb, var(--danger) 25%, var(--border));
  color: var(--danger);
  background: color-mix(in srgb, var(--danger) 6%, var(--surface-muted));
}
.danger-btn:hover { background: color-mix(in srgb, var(--danger) 10%, var(--surface-muted)); }
.submit-btn { background: var(--accent); }
.submit-btn:hover { opacity: 0.88; }
.cancel-btn { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
</style>
