<template>
  <AdminPageLayout title="Lượt làm bài" subtitle="Lịch sử làm quiz của học viên">
    <DataTable
      :columns="columns" :rows="attempts" :loading="loading"
      searchable :search="search" search-placeholder="Tìm học viên..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📊" empty-title="Chưa có lượt làm bài" empty-description="Học viên chưa làm quiz nào."
    >
      <template #toolbar>
        <AppSelect v-model="filterStatus" :options="statusOptions" placeholder="Kết quả" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-navy flex items-center justify-center text-white text-xs font-bold shrink-0">
              {{ row.user?.charAt(0)?.toUpperCase() }}
            </div>
            <p class="font-semibold text-navy text-sm">{{ row.user }}</p>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.quiz }}</td>
        <td class="px-5 py-4">
          <span class="font-bold text-sm" :class="row.score >= row.pass_score ? 'text-success-600' : 'text-danger-600'">
            {{ row.score }}%
          </span>
        </td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.score >= row.pass_score ? 'success' : 'danger'" dot>
            {{ row.score >= row.pass_score ? 'Đạt' : 'Không đạt' }}
          </AppBadge>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.duration }} phút</td>
        <td class="px-5 py-4 text-sm text-slate-400">{{ row.created_at }}</td>
        <td class="px-5 py-4">
          <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
        </td>
      </template>
    </DataTable>

    <ConfirmDialog v-model="showDelete" title="Xóa lượt làm bài" message="Bạn có chắc muốn xóa lượt làm bài này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'user',       label: 'Học viên' },
  { key: 'quiz',       label: 'Quiz' },
  { key: 'score',      label: 'Điểm' },
  { key: 'status',     label: 'Kết quả' },
  { key: 'duration',   label: 'Thời gian' },
  { key: 'created_at', label: 'Ngày làm' },
  { key: 'actions',    label: 'Hành động' },
]
const statusOptions = [
  { value: 'pass', label: 'Đạt' },
  { value: 'fail', label: 'Không đạt' },
]

const attempts     = ref([])
const loading      = ref(true)
const search       = ref('')
const filterStatus = ref('')
const page         = ref(1)
const lastPage     = ref(1)
const total        = ref(0)
const showDelete   = ref(false)
const deleteItem   = ref(null)
const deleting     = ref(false)

function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  attempts.value = attempts.value.filter(a => a.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  attempts.value = [
    { id: 1, user: 'Nguyễn Minh Anh', quiz: 'Quiz Unit 1', score: 85, pass_score: 70, duration: 12, created_at: '10/03/2026' },
    { id: 2, user: 'Lê Thị Hương',    quiz: 'Mid-term Exam', score: 60, pass_score: 75, duration: 55, created_at: '08/03/2026' },
    { id: 3, user: 'Trần Bảo Long',   quiz: 'Quiz Unit 1', score: 90, pass_score: 70, duration: 10, created_at: '07/03/2026' },
  ]
  total.value = attempts.value.length
  loading.value = false
})
</script>
