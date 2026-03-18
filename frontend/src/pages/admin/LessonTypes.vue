<template>
  <AdminPageLayout title="Loại bài học" subtitle="Quản lý các loại bài học trong hệ thống">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm loại bài học</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="lessonTypes" :loading="loading"
      searchable :search="search" search-placeholder="Tìm loại bài học..."
      @update:search="search = $event"
      empty-icon="🗂️" empty-title="Chưa có loại bài học" empty-description="Tạo loại bài học đầu tiên."
    >
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg" :style="{ background: row.color + '20' }">
              {{ row.icon }}
            </div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.name }}</p>
              <p class="text-xs text-slate-400">{{ row.slug }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600 max-w-xs">{{ row.description }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lessons_count }} bài học</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.active ? 'success' : 'gray'" dot>{{ row.active ? 'Hoạt động' : 'Ẩn' }}</AppBadge>
        </td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa loại bài học' : 'Thêm loại bài học'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <AppInput v-model="form.name" label="Tên loại" id="name" required />
          <AppInput v-model="form.slug" label="Slug"     id="slug" required />
        </div>
        <AppInput    v-model="form.description" label="Mô tả"  id="description" />
        <div class="grid grid-cols-2 gap-4">
          <AppInput v-model="form.icon"  label="Icon (emoji)" id="icon"  />
          <AppInput v-model="form.color" label="Màu (hex)"    id="color" type="color" />
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa loại bài học" message="Bạn có chắc muốn xóa loại bài học này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'name',          label: 'Loại bài học' },
  { key: 'description',   label: 'Mô tả' },
  { key: 'lessons_count', label: 'Số bài học' },
  { key: 'active',        label: 'Trạng thái' },
  { key: 'actions',       label: 'Hành động' },
]

const lessonTypes = ref([])
const loading     = ref(true)
const search      = ref('')
const showModal   = ref(false)
const showDelete  = ref(false)
const editItem    = ref(null)
const deleteItem  = ref(null)
const saving      = ref(false)
const deleting    = ref(false)
const form        = ref({ name: '', slug: '', description: '', icon: '📖', color: '#1e3a5f' })

function openCreate() { editItem.value = null; form.value = { name: '', slug: '', description: '', icon: '📖', color: '#1e3a5f' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = lessonTypes.value.findIndex(t => t.id === editItem.value.id)
    if (idx !== -1) lessonTypes.value[idx] = { ...lessonTypes.value[idx], ...form.value }
  } else {
    lessonTypes.value.unshift({ id: Date.now(), ...form.value, lessons_count: 0, active: true })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  lessonTypes.value = lessonTypes.value.filter(t => t.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  lessonTypes.value = [
    { id: 1, name: 'Video',      slug: 'video',      icon: '🎬', color: '#ef4444', description: 'Bài học dạng video',          lessons_count: 45, active: true },
    { id: 2, name: 'Đọc hiểu',   slug: 'reading',    icon: '📖', color: '#3b82f6', description: 'Bài tập đọc hiểu',            lessons_count: 32, active: true },
    { id: 3, name: 'Nghe',       slug: 'listening',  icon: '🎧', color: '#10b981', description: 'Bài tập nghe',                lessons_count: 28, active: true },
    { id: 4, name: 'Nói',        slug: 'speaking',   icon: '🎤', color: '#8b5cf6', description: 'Bài tập luyện nói',           lessons_count: 20, active: true },
    { id: 5, name: 'Từ vựng',    slug: 'vocabulary', icon: '📚', color: '#f59e0b', description: 'Học từ vựng theo chủ đề',     lessons_count: 60, active: true },
    { id: 6, name: 'Ngữ pháp',   slug: 'grammar',    icon: '✏️', color: '#6366f1', description: 'Bài học ngữ pháp',            lessons_count: 38, active: true },
    { id: 7, name: 'Quiz',       slug: 'quiz',       icon: '📝', color: '#ec4899', description: 'Bài kiểm tra trắc nghiệm',    lessons_count: 55, active: true },
  ]
  loading.value = false
})
</script>
