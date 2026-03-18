<template>
  <AdminPageLayout title="Từ vựng" subtitle="Quản lý ngân hàng từ vựng">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm từ vựng</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="vocabularies" :loading="loading"
      searchable :search="search" search-placeholder="Tìm từ vựng..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📚" empty-title="Chưa có từ vựng" empty-description="Thêm từ vựng đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterLevel" :options="levelOptions" placeholder="Cấp độ" class="w-36" />
        <AppSelect v-model="filterType"  :options="typeOptions"  placeholder="Loại từ" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <p class="font-bold text-navy text-sm">{{ row.word }}</p>
          <p class="text-xs text-slate-400 italic">{{ row.phonetic }}</p>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.meaning }}</td>
        <td class="px-5 py-4 text-xs text-slate-500 italic max-w-xs line-clamp-2">{{ row.example }}</td>
        <td class="px-5 py-4"><AppBadge variant="primary">{{ row.type }}</AppBadge></td>
        <td class="px-5 py-4"><AppBadge :variant="levelVariant(row.level)">{{ row.level }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lesson }}</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa từ vựng' : 'Thêm từ vựng'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <AppInput v-model="form.word"     label="Từ vựng"  id="word"     required />
          <AppInput v-model="form.phonetic" label="Phiên âm" id="phonetic" />
        </div>
        <AppInput    v-model="form.meaning" label="Nghĩa"    id="meaning"  required />
        <AppTextarea v-model="form.example" label="Ví dụ"   id="example"  rows="2" />
        <div class="grid grid-cols-2 gap-4">
          <AppSelect v-model="form.type"   label="Loại từ" id="type"   :options="typeOptions" />
          <AppSelect v-model="form.level"  label="Cấp độ"  id="level"  :options="levelOptions" />
        </div>
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa từ vựng" message="Bạn có chắc muốn xóa từ vựng này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'word',     label: 'Từ vựng' },
  { key: 'meaning',  label: 'Nghĩa' },
  { key: 'example',  label: 'Ví dụ' },
  { key: 'type',     label: 'Loại từ' },
  { key: 'level',    label: 'Cấp độ' },
  { key: 'lesson',   label: 'Bài học' },
  { key: 'actions',  label: 'Hành động' },
]
const typeOptions = [
  { value: 'noun',      label: 'Danh từ' },
  { value: 'verb',      label: 'Động từ' },
  { value: 'adjective', label: 'Tính từ' },
  { value: 'adverb',    label: 'Trạng từ' },
  { value: 'phrase',    label: 'Cụm từ' },
]
const levelOptions = [
  { value: 'A1', label: 'A1' }, { value: 'A2', label: 'A2' },
  { value: 'B1', label: 'B1' }, { value: 'B2', label: 'B2' }, { value: 'C1', label: 'C1' },
]
const levelVariant = (l) => ({ A1: 'success', A2: 'primary', B1: 'warning', B2: 'danger', C1: 'gray' }[l] || 'gray')

const vocabularies = ref([])
const loading      = ref(true)
const search       = ref('')
const filterLevel  = ref('')
const filterType   = ref('')
const page         = ref(1)
const lastPage     = ref(1)
const total        = ref(0)
const showModal    = ref(false)
const showDelete   = ref(false)
const editItem     = ref(null)
const deleteItem   = ref(null)
const saving       = ref(false)
const deleting     = ref(false)
const form         = ref({ word: '', phonetic: '', meaning: '', example: '', type: 'noun', level: 'A1', lesson: '' })

function openCreate() { editItem.value = null; form.value = { word: '', phonetic: '', meaning: '', example: '', type: 'noun', level: 'A1', lesson: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = vocabularies.value.findIndex(v => v.id === editItem.value.id)
    if (idx !== -1) vocabularies.value[idx] = { ...vocabularies.value[idx], ...form.value }
  } else {
    vocabularies.value.unshift({ id: Date.now(), ...form.value })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  vocabularies.value = vocabularies.value.filter(v => v.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  vocabularies.value = [
    { id: 1, word: 'accomplish', phonetic: '/əˈkʌmplɪʃ/', meaning: 'hoàn thành, đạt được', example: 'She accomplished her goal.', type: 'verb',      level: 'B1', lesson: 'Unit 4' },
    { id: 2, word: 'ambiguous',  phonetic: '/æmˈbɪɡjuəs/', meaning: 'mơ hồ, không rõ ràng', example: 'The message was ambiguous.', type: 'adjective', level: 'B2', lesson: 'Unit 7' },
    { id: 3, word: 'brevity',    phonetic: '/ˈbrevɪti/',   meaning: 'sự ngắn gọn',          example: 'Brevity is the soul of wit.', type: 'noun',      level: 'C1', lesson: 'Unit 9' },
    { id: 4, word: 'diligent',   phonetic: '/ˈdɪlɪdʒənt/', meaning: 'chăm chỉ, cần cù',    example: 'He is a diligent student.',  type: 'adjective', level: 'A2', lesson: 'Unit 2' },
  ]
  total.value = vocabularies.value.length
  loading.value = false
})
</script>
