<template>
  <AdminPageLayout title="Nói" subtitle="Quản lý bài tập nói">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài nói</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="speakings" :loading="loading"
      searchable :search="search" search-placeholder="Tìm bài nói..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="🎤" empty-title="Chưa có bài nói" empty-description="Thêm bài tập nói đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterType" :options="typeOptions" placeholder="Loại" class="w-40" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center text-lg">🎤</div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.title }}</p>
              <p class="text-xs text-slate-400">{{ row.topic }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lesson }}</td>
        <td class="px-5 py-4"><AppBadge :variant="typeVariant(row.type)">{{ typeLabel(row.type) }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.duration }} giây</td>
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

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa bài nói' : 'Thêm bài nói'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title"    label="Tiêu đề"    id="title"    required />
        <AppInput v-model="form.topic"    label="Chủ đề"     id="topic"    />
        <AppTextarea v-model="form.prompt" label="Câu hỏi / Gợi ý" id="prompt" rows="3" />
        <div class="grid grid-cols-2 gap-4">
          <AppSelect v-model="form.type"     label="Loại"       id="type"     :options="typeOptions" />
          <AppInput  v-model="form.duration" label="Thời gian (giây)" id="duration" type="number" />
        </div>
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa bài nói" message="Bạn có chắc muốn xóa bài nói này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'title',     label: 'Bài nói' },
  { key: 'lesson',    label: 'Bài học' },
  { key: 'type',      label: 'Loại' },
  { key: 'duration',  label: 'Thời gian' },
  { key: 'published', label: 'Trạng thái' },
  { key: 'actions',   label: 'Hành động' },
]
const typeOptions = [
  { value: 'repeat',    label: 'Nhắc lại' },
  { value: 'describe',  label: 'Mô tả' },
  { value: 'roleplay',  label: 'Nhập vai' },
  { value: 'freestyle', label: 'Tự do' },
]
const typeVariant = (t) => ({ repeat: 'primary', describe: 'success', roleplay: 'warning', freestyle: 'gray' }[t] || 'gray')
const typeLabel   = (t) => ({ repeat: 'Nhắc lại', describe: 'Mô tả', roleplay: 'Nhập vai', freestyle: 'Tự do' }[t] || t)

const speakings  = ref([])
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
const form       = ref({ title: '', topic: '', prompt: '', type: 'repeat', duration: 60, lesson: '' })

function openCreate() { editItem.value = null; form.value = { title: '', topic: '', prompt: '', type: 'repeat', duration: 60, lesson: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = speakings.value.findIndex(s => s.id === editItem.value.id)
    if (idx !== -1) speakings.value[idx] = { ...speakings.value[idx], ...form.value }
  } else {
    speakings.value.unshift({ id: Date.now(), ...form.value, published: false })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  speakings.value = speakings.value.filter(s => s.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  speakings.value = [
    { id: 1, title: 'Introduce Yourself',    topic: 'Self Introduction', lesson: 'Unit 1', type: 'freestyle', duration: 120, published: true },
    { id: 2, title: 'Describe Your Hometown', topic: 'Places',           lesson: 'Unit 3', type: 'describe',  duration: 90,  published: true },
    { id: 3, title: 'IELTS Speaking Part 2',  topic: 'Cue Card',         lesson: 'Unit 7', type: 'roleplay',  duration: 120, published: false },
  ]
  total.value = speakings.value.length
  loading.value = false
})
</script>
