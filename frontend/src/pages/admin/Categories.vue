<template>
  <AdminPageLayout title="Danh mục" subtitle="Quản lý danh mục khóa học">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm danh mục</AppButton>
    </template>
    <DataTable :columns="columns" :rows="items" :loading="loading" searchable :search="search" @update:search="search = $event" :total="items.length">
      <template #row="{ row }">
        <td class="px-5 py-4 font-semibold text-navy text-sm">{{ row.name }}</td>
        <td class="px-5 py-4 text-sm text-slate-500 line-clamp-1">{{ row.description }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.courses_count }}</td>
        <td class="px-5 py-4">
          <div class="flex gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>
    <AppModal v-model="showModal" :title="editItem ? 'Sửa danh mục' : 'Thêm danh mục'" size="sm">
      <form class="space-y-4">
        <AppInput v-model="form.name" label="Tên danh mục" id="name" required />
        <AppTextarea v-model="form.description" label="Mô tả" id="desc" :rows="2" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
    <ConfirmDialog v-model="showDelete" title="Xóa danh mục" message="Bạn có chắc muốn xóa danh mục này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppTextarea from '../../components/ui/AppTextarea.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'name',         label: 'Tên danh mục' },
  { key: 'description',  label: 'Mô tả' },
  { key: 'courses_count', label: 'Số khóa học' },
  { key: 'actions',      label: 'Hành động' },
]
const items      = ref([])
const loading    = ref(true)
const search     = ref('')
const showModal  = ref(false)
const showDelete = ref(false)
const editItem   = ref(null)
const deleteItem = ref(null)
const saving     = ref(false)
const deleting   = ref(false)
const form       = ref({ name: '', description: '' })

function openCreate() { editItem.value = null; form.value = { name: '', description: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { name: row.name, description: row.description }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = items.value.findIndex(i => i.id === editItem.value.id)
    if (idx !== -1) items.value[idx] = { ...items.value[idx], ...form.value }
  } else {
    items.value.unshift({ id: Date.now(), ...form.value, courses_count: 0 })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  items.value = items.value.filter(i => i.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 300))
  items.value = [
    { id: 1, name: 'IELTS',     description: 'Luyện thi IELTS',     courses_count: 8 },
    { id: 2, name: 'TOEIC',     description: 'Luyện thi TOEIC',     courses_count: 5 },
    { id: 3, name: 'Giao tiếp', description: 'Tiếng Anh giao tiếp', courses_count: 12 },
    { id: 4, name: 'Ngữ pháp',  description: 'Ngữ pháp tiếng Anh',  courses_count: 7 },
  ]
  loading.value = false
})
</script>
