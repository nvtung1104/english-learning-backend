<template>
  <AdminPageLayout title="Ngữ pháp" subtitle="Quản lý nội dung ngữ pháp">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài ngữ pháp</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="grammars" :loading="loading"
      searchable :search="search" search-placeholder="Tìm bài ngữ pháp..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📖" empty-title="Chưa có bài ngữ pháp" empty-description="Thêm bài ngữ pháp đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterLevel" :options="levelOptions" placeholder="Cấp độ" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <p class="font-semibold text-navy text-sm">{{ row.title }}</p>
          <p class="text-xs text-slate-400 line-clamp-1">{{ row.description }}</p>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.lesson }}</td>
        <td class="px-5 py-4"><AppBadge :variant="levelVariant(row.level)">{{ row.level }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.examples }} ví dụ</td>
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

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa ngữ pháp' : 'Thêm ngữ pháp'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title"       label="Tiêu đề"    id="title"       required />
        <AppTextarea v-model="form.description" label="Mô tả"   id="description" rows="2" />
        <AppTextarea v-model="form.content"  label="Nội dung"   id="content"     rows="5" />
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

    <ConfirmDialog v-model="showDelete" title="Xóa bài ngữ pháp" message="Bạn có chắc muốn xóa bài ngữ pháp này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'title',     label: 'Tiêu đề' },
  { key: 'lesson',    label: 'Bài học' },
  { key: 'level',     label: 'Cấp độ' },
  { key: 'examples',  label: 'Ví dụ' },
  { key: 'published', label: 'Trạng thái' },
  { key: 'actions',   label: 'Hành động' },
]
const levelOptions = [
  { value: 'A1', label: 'A1 - Beginner' },
  { value: 'A2', label: 'A2 - Elementary' },
  { value: 'B1', label: 'B1 - Intermediate' },
  { value: 'B2', label: 'B2 - Upper Intermediate' },
  { value: 'C1', label: 'C1 - Advanced' },
]
const levelVariant = (l) => ({ A1: 'success', A2: 'primary', B1: 'warning', B2: 'danger', C1: 'gray' }[l] || 'gray')

const grammars   = ref([])
const loading    = ref(true)
const search     = ref('')
const filterLevel = ref('')
const page       = ref(1)
const lastPage   = ref(1)
const total      = ref(0)
const showModal  = ref(false)
const showDelete = ref(false)
const editItem   = ref(null)
const deleteItem = ref(null)
const saving     = ref(false)
const deleting   = ref(false)
const form       = ref({ title: '', description: '', content: '', level: 'A1', lesson: '' })

function openCreate() { editItem.value = null; form.value = { title: '', description: '', content: '', level: 'A1', lesson: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = grammars.value.findIndex(g => g.id === editItem.value.id)
    if (idx !== -1) grammars.value[idx] = { ...grammars.value[idx], ...form.value }
  } else {
    grammars.value.unshift({ id: Date.now(), ...form.value, examples: 0, published: false })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  grammars.value = grammars.value.filter(g => g.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  grammars.value = [
    { id: 1, title: 'Present Simple Tense',    description: 'Thì hiện tại đơn', lesson: 'Unit 1', level: 'A1', examples: 10, published: true },
    { id: 2, title: 'Present Continuous',      description: 'Thì hiện tại tiếp diễn', lesson: 'Unit 2', level: 'A2', examples: 8, published: true },
    { id: 3, title: 'Past Perfect Tense',      description: 'Thì quá khứ hoàn thành', lesson: 'Unit 5', level: 'B1', examples: 12, published: false },
    { id: 4, title: 'Conditional Sentences',   description: 'Câu điều kiện', lesson: 'Unit 8', level: 'B2', examples: 15, published: true },
  ]
  total.value = grammars.value.length
  loading.value = false
})
</script>
