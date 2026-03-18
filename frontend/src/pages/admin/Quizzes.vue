<template>
  <AdminPageLayout title="Bài kiểm tra" subtitle="Quản lý quiz và bài kiểm tra">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm quiz</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="quizzes" :loading="loading"
      searchable :search="search" search-placeholder="Tìm quiz..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📝" empty-title="Chưa có quiz" empty-description="Tạo quiz đầu tiên cho khóa học."
    >
      <template #toolbar>
        <AppSelect v-model="filterType" :options="typeOptions" placeholder="Loại" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <p class="font-semibold text-navy text-sm">{{ row.title }}</p>
          <p class="text-xs text-slate-400">{{ row.lesson }}</p>
        </td>
        <td class="px-5 py-4"><AppBadge :variant="typeVariant(row.type)">{{ row.type }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.questions }} câu</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.duration }} phút</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.pass_score }}%</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa quiz' : 'Thêm quiz'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tiêu đề" id="title" required />
        <AppSelect v-model="form.type" label="Loại" id="type" :options="typeOptions" required />
        <div class="grid grid-cols-2 gap-4">
          <AppInput v-model="form.duration" label="Thời gian (phút)" id="duration" type="number" />
          <AppInput v-model="form.pass_score" label="Điểm đạt (%)" id="pass_score" type="number" />
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa quiz" message="Bạn có chắc muốn xóa quiz này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'title',      label: 'Tiêu đề' },
  { key: 'type',       label: 'Loại' },
  { key: 'questions',  label: 'Số câu' },
  { key: 'duration',   label: 'Thời gian' },
  { key: 'pass_score', label: 'Điểm đạt' },
  { key: 'actions',    label: 'Hành động' },
]
const typeOptions = [
  { value: 'practice', label: 'Luyện tập' },
  { value: 'exam',     label: 'Kiểm tra' },
  { value: 'final',    label: 'Cuối khóa' },
]
const typeVariant = (t) => ({ practice: 'primary', exam: 'warning', final: 'danger' }[t] || 'gray')

const quizzes    = ref([])
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
const form       = ref({ title: '', type: 'practice', duration: 30, pass_score: 70 })

function openCreate() { editItem.value = null; form.value = { title: '', type: 'practice', duration: 30, pass_score: 70 }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { ...row }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = quizzes.value.findIndex(q => q.id === editItem.value.id)
    if (idx !== -1) quizzes.value[idx] = { ...quizzes.value[idx], ...form.value }
  } else {
    quizzes.value.unshift({ id: Date.now(), ...form.value, questions: 0, lesson: 'Chưa gán' })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  quizzes.value = quizzes.value.filter(q => q.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  quizzes.value = [
    { id: 1, title: 'Quiz Unit 1 - Vocabulary', type: 'practice', questions: 20, duration: 15, pass_score: 70, lesson: 'Unit 1' },
    { id: 2, title: 'Mid-term Exam',            type: 'exam',     questions: 50, duration: 60, pass_score: 75, lesson: 'Giữa kỳ' },
    { id: 3, title: 'Final Test IELTS',          type: 'final',    questions: 80, duration: 120, pass_score: 80, lesson: 'Cuối khóa' },
  ]
  total.value = quizzes.value.length
  loading.value = false
})
</script>
