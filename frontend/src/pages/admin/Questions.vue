<template>
  <AdminPageLayout title="Câu hỏi" subtitle="Quản lý ngân hàng câu hỏi">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm câu hỏi</AppButton>
    </template>
    <DataTable :columns="columns" :rows="items" :loading="loading" searchable :search="search" @update:search="search = $event" :total="items.length">
      <template #toolbar>
        <AppSelect v-model="filterType" :options="typeOptions" placeholder="Loại câu hỏi" class="w-44" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4 text-sm text-navy font-medium max-w-xs">
          <p class="line-clamp-2">{{ row.question }}</p>
        </td>
        <td class="px-5 py-4"><AppBadge variant="gray">{{ row.type }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.quiz }}</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.difficulty === 'easy' ? 'success' : row.difficulty === 'hard' ? 'danger' : 'warning'">
            {{ row.difficulty }}
          </AppBadge>
        </td>
        <td class="px-5 py-4">
          <div class="flex gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>
    <AppModal v-model="showModal" :title="editItem ? 'Sửa câu hỏi' : 'Thêm câu hỏi'" size="lg">
      <form class="space-y-4">
        <AppTextarea v-model="form.question" label="Nội dung câu hỏi" id="question" required :rows="3" />
        <div class="grid sm:grid-cols-2 gap-4">
          <AppSelect v-model="form.type" label="Loại" id="type" :options="typeOptions" />
          <AppSelect v-model="form.difficulty" label="Độ khó" id="difficulty" :options="diffOptions" />
        </div>
        <div class="space-y-2">
          <label class="label">Các đáp án</label>
          <AppInput v-for="(_, i) in form.options" :key="i" v-model="form.options[i]" :placeholder="`Đáp án ${String.fromCharCode(65+i)}`" :id="`opt${i}`" />
        </div>
        <AppSelect v-model="form.correct" label="Đáp án đúng" id="correct" :options="correctOptions" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
    <ConfirmDialog v-model="showDelete" title="Xóa câu hỏi" message="Bạn có chắc muốn xóa câu hỏi này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppTextarea from '../../components/ui/AppTextarea.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'question',   label: 'Câu hỏi' },
  { key: 'type',       label: 'Loại' },
  { key: 'quiz',       label: 'Quiz' },
  { key: 'difficulty', label: 'Độ khó' },
  { key: 'actions',    label: 'Hành động' },
]
const typeOptions = [
  { value: 'multiple_choice', label: 'Trắc nghiệm' },
  { value: 'true_false',      label: 'Đúng/Sai' },
  { value: 'fill_blank',      label: 'Điền vào chỗ trống' },
]
const diffOptions = [
  { value: 'easy',   label: 'Dễ' },
  { value: 'medium', label: 'Trung bình' },
  { value: 'hard',   label: 'Khó' },
]
const correctOptions = computed(() => [0,1,2,3].map(i => ({ value: i, label: `Đáp án ${String.fromCharCode(65+i)}` })))

const items = ref([])
const loading = ref(true)
const search = ref('')
const filterType = ref('')
const showModal = ref(false)
const showDelete = ref(false)
const editItem = ref(null)
const deleteItem = ref(null)
const saving = ref(false)
const deleting = ref(false)
const form = ref({ question: '', type: 'multiple_choice', difficulty: 'medium', options: ['','','',''], correct: 0 })

function openCreate() { editItem.value = null; form.value = { question: '', type: 'multiple_choice', difficulty: 'medium', options: ['','','',''], correct: 0 }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { question: row.question, type: row.type, difficulty: row.difficulty, options: ['','','',''], correct: 0 }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = items.value.findIndex(i => i.id === editItem.value.id)
    if (idx !== -1) items.value[idx] = { ...items.value[idx], question: form.value.question, type: form.value.type, difficulty: form.value.difficulty }
  } else {
    items.value.unshift({ id: Date.now(), question: form.value.question, type: form.value.type, difficulty: form.value.difficulty, quiz: 'Quiz 1' })
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
    { id: 1, question: 'What is the meaning of "eloquent"?', type: 'multiple_choice', difficulty: 'medium', quiz: 'Vocabulary Quiz 1' },
    { id: 2, question: 'She ___ to school every day.', type: 'fill_blank', difficulty: 'easy', quiz: 'Grammar Test 1' },
    { id: 3, question: 'The sun rises in the west. True or False?', type: 'true_false', difficulty: 'easy', quiz: 'General Knowledge' },
  ]
  loading.value = false
})
</script>
