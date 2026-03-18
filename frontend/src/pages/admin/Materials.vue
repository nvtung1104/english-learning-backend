<template>
  <AdminPageLayout title="Tài liệu học" subtitle="Quản lý tài liệu đính kèm bài học">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm tài liệu</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="materials" :loading="loading"
      searchable :search="search" search-placeholder="Tìm tài liệu..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📎" empty-title="Chưa có tài liệu" empty-description="Thêm tài liệu cho bài học."
    >
      <template #toolbar>
        <AppSelect v-model="filterType" :options="typeOptions" placeholder="Loại file" class="w-40" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-lg" :class="fileIconBg(row.type)">
              {{ fileIcon(row.type) }}
            </div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.name }}</p>
              <p class="text-xs text-slate-400">{{ row.size }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lesson }}</td>
        <td class="px-5 py-4"><AppBadge :variant="typeVariant(row.type)">{{ row.type?.toUpperCase() }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.downloads }}</td>
        <td class="px-5 py-4 text-sm text-slate-400">{{ row.created_at }}</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa tài liệu' : 'Thêm tài liệu'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.name"   label="Tên tài liệu" id="name"   required />
        <AppInput v-model="form.url"    label="URL / Link"   id="url"    required />
        <AppSelect v-model="form.type"  label="Loại file"    id="type"   :options="typeOptions" />
        <AppInput v-model="form.lesson" label="Bài học"      id="lesson" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa tài liệu" message="Bạn có chắc muốn xóa tài liệu này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'name',       label: 'Tài liệu' },
  { key: 'lesson',     label: 'Bài học' },
  { key: 'type',       label: 'Loại' },
  { key: 'downloads',  label: 'Lượt tải' },
  { key: 'created_at', label: 'Ngày thêm' },
  { key: 'actions',    label: 'Hành động' },
]
const typeOptions = [
  { value: 'pdf',   label: 'PDF' },
  { value: 'doc',   label: 'Word' },
  { value: 'mp3',   label: 'Audio' },
  { value: 'mp4',   label: 'Video' },
  { value: 'other', label: 'Khác' },
]
const typeVariant  = (t) => ({ pdf: 'danger', doc: 'primary', mp3: 'success', mp4: 'warning', other: 'gray' }[t] || 'gray')
const fileIcon     = (t) => ({ pdf: '📄', doc: '📝', mp3: '🎵', mp4: '🎬', other: '📎' }[t] || '📎')
const fileIconBg   = (t) => ({ pdf: 'bg-red-50', doc: 'bg-blue-50', mp3: 'bg-green-50', mp4: 'bg-yellow-50', other: 'bg-slate-50' }[t] || 'bg-slate-50')

const materials  = ref([])
const loading    = ref(true)
const search     = ref('')
const filterType = ref('')
const page       = ref(1)
const lastPage   = ref(1)
const total      = ref(0)
const showModal  = ref(false)
const showDelete = ref(false)
const editItem   = ref(null)
const deleteItem = ref(null)
const saving     = ref(false)
const deleting   = ref(false)
const form       = ref({ name: '', url: '', type: 'pdf', lesson: '' })

function openCreate() { editItem.value = null; form.value = { name: '', url: '', type: 'pdf', lesson: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { name: row.name, url: row.url, type: row.type, lesson: row.lesson }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = materials.value.findIndex(m => m.id === editItem.value.id)
    if (idx !== -1) materials.value[idx] = { ...materials.value[idx], ...form.value }
  } else {
    materials.value.unshift({ id: Date.now(), ...form.value, size: 'N/A', downloads: 0, created_at: 'Vừa xong' })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  materials.value = materials.value.filter(m => m.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  materials.value = [
    { id: 1, name: 'IELTS Vocabulary List',  lesson: 'Unit 1', type: 'pdf',   size: '2.4 MB', downloads: 145, created_at: '01/01/2026' },
    { id: 2, name: 'Listening Practice MP3', lesson: 'Unit 2', type: 'mp3',   size: '8.1 MB', downloads: 89,  created_at: '05/01/2026' },
    { id: 3, name: 'Grammar Worksheet',      lesson: 'Unit 3', type: 'doc',   size: '512 KB', downloads: 67,  created_at: '10/01/2026' },
    { id: 4, name: 'Speaking Sample Video',  lesson: 'Unit 4', type: 'mp4',   size: '45 MB',  downloads: 34,  created_at: '15/01/2026' },
  ]
  total.value = materials.value.length
  loading.value = false
})
</script>
