<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Bài học</h1>
        <p class="page-subtitle">Quản lý bài học trong các khóa học của bạn</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài học</AppButton>
    </div>

    <div class="card p-4 flex flex-wrap items-center gap-3">
      <AppSelect v-model="filterCourse" :options="courseOptions" placeholder="Tất cả khóa học" class="w-56" />
      <div class="relative flex-1 max-w-xs">
        <input v-model="search" type="text" placeholder="Tìm bài học..." class="input pl-9 w-full" />
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Chương</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Loại</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Thời lượng</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="l in filteredLessons" :key="l.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4 font-semibold text-navy">{{ l.title }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ l.section }}</td>
              <td class="px-5 py-4">
                <AppBadge variant="gray">{{ l.type }}</AppBadge>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ l.duration }}</td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEdit(l)" class="btn btn-sm btn-secondary">Sửa</button>
                  <button @click="deleteLesson(l)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!filteredLessons.length" icon="📖" title="Chưa có bài học" description="Thêm bài học cho khóa học của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa bài học' : 'Thêm bài học'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tên bài học" id="title" required />
        <div class="grid sm:grid-cols-2 gap-4">
          <AppSelect v-model="form.course_id" label="Khóa học" id="course" :options="courseOptions" />
          <AppSelect v-model="form.type" label="Loại bài học" id="type" :options="typeOptions" />
        </div>
        <AppInput v-model="form.duration" label="Thời lượng (vd: 15p)" id="duration" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppModal from '../../components/ui/AppModal.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const search       = ref('')
const filterCourse = ref('')
const showModal    = ref(false)
const editItem     = ref(null)
const form         = ref({ title: '', course_id: '', type: 'video', duration: '' })

const courseOptions = [
  { value: '1', label: 'IELTS Preparation Course' },
  { value: '2', label: 'Business English' },
  { value: '3', label: 'Grammar Fundamentals' },
]
const typeOptions = [
  { value: 'video',    label: 'Video' },
  { value: 'text',     label: 'Bài đọc' },
  { value: 'quiz',     label: 'Quiz' },
  { value: 'speaking', label: 'Speaking' },
]

const lessons = ref([
  { id: 1, title: 'Giới thiệu khóa học',    section: 'Chương 1', type: 'video', duration: '5p',  course_id: '1' },
  { id: 2, title: 'Listening Skills Intro',  section: 'Chương 2', type: 'video', duration: '12p', course_id: '1' },
  { id: 3, title: 'Business Vocabulary 1',   section: 'Chương 1', type: 'text',  duration: '10p', course_id: '2' },
  { id: 4, title: 'Present Tense Review',    section: 'Chương 1', type: 'video', duration: '8p',  course_id: '3' },
])

const filteredLessons = computed(() => {
  let result = lessons.value
  if (filterCourse.value) result = result.filter(l => l.course_id === filterCourse.value)
  if (search.value) result = result.filter(l => l.title.toLowerCase().includes(search.value.toLowerCase()))
  return result
})

function openCreate() { editItem.value = null; form.value = { title: '', course_id: '', type: 'video', duration: '' }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { title: item.title, course_id: item.course_id, type: item.type, duration: item.duration }; showModal.value = true }
function deleteLesson(item) { lessons.value = lessons.value.filter(l => l.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else lessons.value.push({ id: Date.now(), ...form.value, section: 'Chương mới' })
  showModal.value = false
}
</script>
