<template>
  <AdminPageLayout title="Tiến độ bài học" subtitle="Theo dõi tiến độ học viên theo từng bài">
    <DataTable
      :columns="columns" :rows="progress" :loading="loading"
      searchable :search="search" search-placeholder="Tìm học viên..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="📈" empty-title="Chưa có dữ liệu" empty-description="Chưa có tiến độ nào được ghi nhận."
    >
      <template #toolbar>
        <AppSelect v-model="filterStatus" :options="statusOptions" placeholder="Trạng thái" class="w-40" />
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
        <td class="px-5 py-4 text-sm text-slate-700">{{ row.lesson }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.course }}</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.completed ? 'success' : 'warning'" dot>
            {{ row.completed ? 'Hoàn thành' : 'Đang học' }}
          </AppBadge>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.time_spent }} phút</td>
        <td class="px-5 py-4 text-sm text-slate-400">{{ row.updated_at }}</td>
      </template>
    </DataTable>
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppBadge from '../../components/ui/AppBadge.vue'

const columns = [
  { key: 'user',       label: 'Học viên' },
  { key: 'lesson',     label: 'Bài học' },
  { key: 'course',     label: 'Khóa học' },
  { key: 'status',     label: 'Trạng thái' },
  { key: 'time_spent', label: 'Thời gian' },
  { key: 'updated_at', label: 'Cập nhật' },
]
const statusOptions = [
  { value: 'completed', label: 'Hoàn thành' },
  { value: 'in_progress', label: 'Đang học' },
]

const progress     = ref([])
const loading      = ref(true)
const search       = ref('')
const filterStatus = ref('')
const page         = ref(1)
const lastPage     = ref(1)
const total        = ref(0)

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  progress.value = [
    { id: 1, user: 'Nguyễn Minh Anh', lesson: 'Lesson 1: Greetings',    course: 'English Basics', completed: true,  time_spent: 25, updated_at: '10/03/2026' },
    { id: 2, user: 'Nguyễn Minh Anh', lesson: 'Lesson 2: Numbers',      course: 'English Basics', completed: false, time_spent: 10, updated_at: '11/03/2026' },
    { id: 3, user: 'Lê Thị Hương',    lesson: 'IELTS Reading Task 1',   course: 'IELTS Foundation', completed: true, time_spent: 45, updated_at: '09/03/2026' },
    { id: 4, user: 'Phạm Văn Đức',    lesson: 'Vocabulary Unit 1',      course: 'TOEIC 600+',     completed: false, time_spent: 8,  updated_at: '12/03/2026' },
  ]
  total.value = progress.value.length
  loading.value = false
})
</script>
