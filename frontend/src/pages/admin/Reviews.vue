<template>
  <AdminPageLayout title="Đánh giá" subtitle="Quản lý đánh giá từ học viên">
    <DataTable :columns="columns" :rows="items" :loading="loading" searchable :search="search" @update:search="search = $event" :total="items.length">
      <template #toolbar>
        <AppSelect v-model="filterRating" :options="ratingOptions" placeholder="Số sao" class="w-32" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-navy flex items-center justify-center text-white text-xs font-bold shrink-0">
              {{ row.user?.charAt(0) }}
            </div>
            <span class="font-medium text-navy text-sm">{{ row.user }}</span>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.course }}</td>
        <td class="px-5 py-4">
          <div class="flex gap-0.5">
            <span v-for="i in 5" :key="i" :class="i <= row.rating ? 'text-gold' : 'text-slate-200'" class="text-sm">★</span>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600 max-w-xs">
          <p class="line-clamp-2">{{ row.comment }}</p>
        </td>
        <td class="px-5 py-4 text-xs text-slate-400">{{ row.date }}</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.approved ? 'success' : 'warning'" dot>
            {{ row.approved ? 'Đã duyệt' : 'Chờ duyệt' }}
          </AppBadge>
        </td>
        <td class="px-5 py-4">
          <div class="flex gap-2">
            <button v-if="!row.approved" @click="approve(row)" class="btn btn-sm btn-success">Duyệt</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>
    <ConfirmDialog v-model="showDelete" title="Xóa đánh giá" message="Bạn có chắc muốn xóa đánh giá này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'user',     label: 'Học viên' },
  { key: 'course',   label: 'Khóa học' },
  { key: 'rating',   label: 'Đánh giá' },
  { key: 'comment',  label: 'Nhận xét' },
  { key: 'date',     label: 'Ngày' },
  { key: 'approved', label: 'Trạng thái' },
  { key: 'actions',  label: 'Hành động' },
]
const ratingOptions = [1,2,3,4,5].map(i => ({ value: i, label: `${i} sao` }))

const items = ref([])
const loading = ref(true)
const search = ref('')
const filterRating = ref('')
const showDelete = ref(false)
const deleteItem = ref(null)
const deleting = ref(false)

function approve(row) { row.approved = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  items.value = items.value.filter(i => i.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 300))
  items.value = [
    { id: 1, user: 'Nguyễn Minh Anh', course: 'IELTS Prep', rating: 5, comment: 'Khóa học rất hay, giáo viên nhiệt tình!', date: '01/03/2026', approved: true },
    { id: 2, user: 'Trần Bảo Long',   course: 'Business EN', rating: 4, comment: 'Nội dung tốt, cần thêm bài tập thực hành.', date: '28/02/2026', approved: false },
    { id: 3, user: 'Lê Thị Hương',    course: 'Grammar A1',  rating: 5, comment: 'Giải thích rõ ràng, dễ hiểu.', date: '25/02/2026', approved: true },
  ]
  loading.value = false
})
</script>
