<template>
  <AdminPageLayout title="Bài học" subtitle="Quản lý bài học trong các chương">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài học</AppButton>
    </template>
    <DataTable :columns="columns" :rows="items" :loading="loading" searchable :search="search" @update:search="search = $event" :total="items.length">
      <template #row="{ row }">
        <td class="px-5 py-4 font-semibold text-navy text-sm">{{ row.title }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.section }}</td>
        <td class="px-5 py-4"><AppBadge variant="gray">{{ row.type }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.duration }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.order }}</td>
        <td class="px-5 py-4">
          <div class="flex gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>
    <AppModal v-model="showModal" :title="editItem ? 'Sửa bài học' : 'Thêm bài học'" size="lg">
      <form class="space-y-4">
        <AppInput v-model="form.title" label="Tên bài học" id="title" required />
        <div class="grid sm:grid-cols-2 gap-4">
          <AppSelect v-model="form.type" label="Loại bài học" id="type" :options="typeOptions" />
          <AppInput v-model="form.duration" label="Thời lượng" id="duration" placeholder="10 phút" />
        </div>
        <AppTextarea v-model="form.content" label="Nội dung" id="content" :rows="4" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
    <ConfirmDialog v-model="showDelete" title="Xóa bài học" message="Bạn có chắc muốn xóa bài học này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppTextarea from '../../components/ui/AppTextarea.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'title',    label: 'Tên bài học' },
  { key: 'section',  label: 'Chương' },
  { key: 'type',     label: 'Loại' },
  { key: 'duration', label: 'Thời lượng' },
  { key: 'order',    label: 'Thứ tự' },
  { key: 'actions',  label: 'Hành động' },
]
const typeOptions = [
  { value: 'video',    label: 'Video' },
  { value: 'text',     label: 'Văn bản' },
  { value: 'quiz',     label: 'Quiz' },
  { value: 'audio',    label: 'Audio' },
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
const form = ref({ title: '', type: 'text', duration: '', content: '' })

function openCreate() { editItem.value = null; form.value = { title: '', type: 'text', duration: '', content: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { title: row.title, type: row.type, duration: row.duration, content: '' }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = items.value.findIndex(i => i.id === editItem.value.id)
    if (idx !== -1) items.value[idx] = { ...items.value[idx], ...form.value }
  } else {
    items.value.unshift({ id: Date.now(), ...form.value, section: 'Chương 1', order: items.value.length + 1 })
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
    { id: 1, title: 'Giới thiệu khóa học',    section: 'Chương 1', type: 'video', duration: '5p',  order: 1 },
    { id: 2, title: 'Bảng chữ cái tiếng Anh', section: 'Chương 1', type: 'text',  duration: '10p', order: 2 },
    { id: 3, title: 'Phát âm cơ bản',         section: 'Chương 1', type: 'audio', duration: '15p', order: 3 },
  ]
  loading.value = false
})
</script>
