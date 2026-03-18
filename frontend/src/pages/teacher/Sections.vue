<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Chương học</h1>
        <p class="page-subtitle">Quản lý các chương trong khóa học của bạn</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm chương</AppButton>
    </div>

    <div class="card p-4 flex flex-wrap items-center gap-3">
      <AppSelect v-model="filterCourse" :options="courseOptions" placeholder="Chọn khóa học" class="w-56" />
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Chương</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Số bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Thứ tự</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="s in filteredSections" :key="s.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4 font-semibold text-navy">{{ s.title }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ s.course }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ s.lessons_count }} bài</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ s.order }}</td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEdit(s)" class="btn btn-sm btn-secondary">Sửa</button>
                  <button @click="deleteSection(s)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!filteredSections.length" icon="📂" title="Chưa có chương học" description="Thêm chương học cho khóa học của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa chương' : 'Thêm chương học'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tên chương" id="title" required />
        <AppSelect v-model="form.course_id" label="Khóa học" id="course" :options="courseOptions" required />
        <AppInput v-model="form.order" label="Thứ tự" id="order" type="number" />
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
import EmptyState from '../../components/ui/EmptyState.vue'

const filterCourse = ref('')
const showModal    = ref(false)
const editItem     = ref(null)
const form         = ref({ title: '', course_id: '', order: 1 })

const courseOptions = [
  { value: '1', label: 'IELTS Preparation Course' },
  { value: '2', label: 'Business English' },
  { value: '3', label: 'Grammar Fundamentals' },
]

const sections = ref([
  { id: 1, title: 'Chương 1: Giới thiệu',    course: 'IELTS Preparation', lessons_count: 5, order: 1, course_id: '1' },
  { id: 2, title: 'Chương 2: Listening',      course: 'IELTS Preparation', lessons_count: 8, order: 2, course_id: '1' },
  { id: 3, title: 'Chương 1: Business Vocab', course: 'Business English',  lessons_count: 6, order: 1, course_id: '2' },
])

const filteredSections = computed(() =>
  filterCourse.value ? sections.value.filter(s => s.course_id === filterCourse.value) : sections.value
)

function openCreate() { editItem.value = null; form.value = { title: '', course_id: '', order: 1 }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { title: item.title, course_id: item.course_id, order: item.order }; showModal.value = true }
function deleteSection(item) { sections.value = sections.value.filter(s => s.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else sections.value.push({ id: Date.now(), ...form.value, lessons_count: 0, course: courseOptions.find(c => c.value === form.value.course_id)?.label || '' })
  showModal.value = false
}
</script>
