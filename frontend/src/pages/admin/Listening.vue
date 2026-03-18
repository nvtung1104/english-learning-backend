<template>
  <AdminPageLayout title="Nghe" subtitle="Quản lý bài tập nghe">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài nghe</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="listenings" :loading="loading"
      searchable :search="search" search-placeholder="Tìm bài nghe..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="🎧" empty-title="Chưa có bài nghe" empty-description="Thêm bài tập nghe đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterLevel" :options="levelOptions" placeholder="Cấp độ" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center text-lg">🎧</div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.title }}</p>
              <p class="text-xs text-slate-400">{{ row.duration }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lesson }}</td>
        <td class="px-5 py-4"><AppBadge :variant="levelVariant(row.level)">{{ row.level }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.questions }} câu hỏi</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.published ? 'success' : 'gray'" dot>{{ row.published ? 'Đã xuất bản' : 'Nháp' }}</AppBadge>
        </td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa bài nghe' : 'Thêm bài nghe'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title"    label="Tiêu đề"       id="title"    required />
        <AppInput v-model="form.audio_url" label="URL Audio"    id="audio_url" required />
        <AppTextarea v-model="form.transcript" label="Transcript" id="transcript" rows="3" />
        <div class="grid grid-cols-2 gap-4">
          <AppSelect v-model="form.level"  label="Cấp độ"  id="level"  :options="levelOptions" />
          <AppInput  v-model="form.lesson" label="Bài học" id="lesson" />
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa bài nghe" message="Bạn có chắc muốn xóa bài nghe này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'title',     label: 'Bài nghe' },
  { key: 'lesson',    label: 'Bài học' },
  { key: 'level',     label: 'Cấp độ' },
  { key: 'questions', label: 'Câu hỏi' },
  { key: 'published', label: 'Trạng thái' },
  { key: 'actions',   label: 'Hành động' },
]
const levelOptions = [
  { value: 'A1', label: 'A1' }, { value: 'A2', label: 'A2' },
  { value: 'B1', label: 'B1' }, { value: 'B2', label: 'B2' }, { value: 'C1', label: 'C1' },
]
const levelVariant = (l) => ({ A1: 'success', A2: 'primary', B1: 'warning', B2: 'danger', C1: 'gray' }[l] || 'gray')

const listenings  = ref([])
const loading     = ref(true)
const search      = ref('')
const filterLevel = ref('')
const page        = ref(1)
const lastPage    = ref(1)
const total       = ref(0)
const showModal   = ref(false)
const showDelete  = ref(false)
const editItem    = ref(null)
const deleteItem  = ref(null)
const saving      = ref(false)
const deleting    = ref(false)
const form        = ref({ title: '', audio_url: '', transcript: '', level: 'A1', lesson: '' })

function openCreate() { editItem.value = null; form.value = { title: '', audio_url: '', transcript: '', level: 'A1', lesson: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = listenings.value.findIndex(l => l.id === editItem.value.id)
    if (idx !== -1) listenings.value[idx] = { ...listenings.value[idx], ...form.value }
  } else {
    listenings.value.unshift({ id: Date.now(), ...form.value, duration: 'N/A', questions: 0, published: false })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  listenings.value = listenings.value.filter(l => l.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  listenings.value = [
    { id: 1, title: 'Daily Conversations',    lesson: 'Unit 1', level: 'A1', duration: '3:45', questions: 5,  published: true },
    { id: 2, title: 'IELTS Listening Part 1', lesson: 'Unit 3', level: 'B1', duration: '8:20', questions: 10, published: true },
    { id: 3, title: 'News Broadcast',         lesson: 'Unit 6', level: 'B2', duration: '5:10', questions: 8,  published: false },
  ]
  total.value = listenings.value.length
  loading.value = false
})
</script>
