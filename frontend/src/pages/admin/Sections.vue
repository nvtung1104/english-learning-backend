<template>
  <AdminPageLayout title="Chương học" subtitle="Quản lý chương học trong khóa học">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm chương</AppButton>
    </template>
    <DataTable :columns="columns" :rows="items" :loading="loading" searchable :search="search" @update:search="search = $event" :total="items.length">
      <template #row="{ row }">
        <td class="px-5 py-4 font-semibold text-navy text-sm">{{ row.title }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.course }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.order }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lessons_count }}</td>
        <td class="px-5 py-4">
          <div class="flex gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>
    <AppModal v-model="showModal" :title="editItem ? 'Sửa chương' : 'Thêm chương'" size="sm">
      <form class="space-y-4">
        <AppInput v-model="form.title" label="Tên chương" id="title" required />
        <AppInput v-model="form.order" label="Thứ tự" id="order" type="number" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
    <ConfirmDialog v-model="showDelete" title="Xóa chương" message="Xóa chương sẽ xóa toàn bộ bài học trong chương. Bạn có chắc?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'title',         label: 'Tên chương' },
  { key: 'course',        label: 'Khóa học' },
  { key: 'order',         label: 'Thứ tự' },
  { key: 'lessons_count', label: 'Số bài học' },
  { key: 'actions',       label: 'Hành động' },
]
const items = ref([])
const loading = ref(true)
const search = ref('')
const showModal = ref(false)
const showDelete = ref(false)
const editItem = ref(null)
const deleteItem = ref(null)
const saving = ref(false)
const deleting = ref(false)
const form = ref({ title: '', order: 1 })

function openCreate() { editItem.value = null; form.value = { title: '', order: 1 }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { title: row.title, order: row.order }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = items.value.findIndex(i => i.id === editItem.value.id)
    if (idx !== -1) items.value[idx] = { ...items.value[idx], ...form.value }
  } else {
    items.value.unshift({ id: Date.now(), ...form.value, course: 'IELTS Prep', lessons_count: 0 })
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
    { id: 1, title: 'Chương 1: Khởi đầu',    course: 'IELTS Prep',    order: 1, lessons_count: 5 },
    { id: 2, title: 'Chương 2: Từ vựng',      course: 'IELTS Prep',    order: 2, lessons_count: 8 },
    { id: 3, title: 'Chương 1: Ngữ pháp cơ bản', course: 'Grammar A1', order: 1, lessons_count: 6 },
  ]
  loading.value = false
})
</script>
