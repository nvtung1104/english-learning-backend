<template>
  <AdminPageLayout title="Đăng ký khóa học" subtitle="Quản lý học viên đăng ký">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm đăng ký</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="enrollments" :loading="loading"
      searchable :search="search" search-placeholder="Tìm học viên..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="🎓" empty-title="Chưa có đăng ký" empty-description="Chưa có học viên nào đăng ký."
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
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.user }}</p>
              <p class="text-xs text-slate-400">{{ row.email }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-700">{{ row.course }}</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <div class="flex-1 bg-slate-100 rounded-full h-1.5 w-20">
              <div class="bg-gold h-1.5 rounded-full" :style="{ width: row.progress + '%' }"></div>
            </div>
            <span class="text-xs text-slate-500">{{ row.progress }}%</span>
          </div>
        </td>
        <td class="px-5 py-4">
          <AppBadge :variant="statusVariant(row.status)">{{ statusLabel(row.status) }}</AppBadge>
        </td>
        <td class="px-5 py-4 text-sm text-slate-400">{{ row.enrolled_at }}</td>
        <td class="px-5 py-4">
          <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" title="Thêm đăng ký" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.user"   label="Học viên" id="user"   required />
        <AppInput v-model="form.course" label="Khóa học" id="course" required />
        <AppSelect v-model="form.status" label="Trạng thái" id="status" :options="statusOptions" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa đăng ký" message="Bạn có chắc muốn xóa đăng ký này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'user',        label: 'Học viên' },
  { key: 'course',      label: 'Khóa học' },
  { key: 'progress',    label: 'Tiến độ' },
  { key: 'status',      label: 'Trạng thái' },
  { key: 'enrolled_at', label: 'Ngày đăng ký' },
  { key: 'actions',     label: 'Hành động' },
]
const statusOptions = [
  { value: 'active',    label: 'Đang học' },
  { value: 'completed', label: 'Hoàn thành' },
  { value: 'paused',    label: 'Tạm dừng' },
]
const statusVariant = (s) => ({ active: 'success', completed: 'primary', paused: 'gray' }[s] || 'gray')
const statusLabel   = (s) => ({ active: 'Đang học', completed: 'Hoàn thành', paused: 'Tạm dừng' }[s] || s)

const enrollments  = ref([])
const loading      = ref(true)
const search       = ref('')
const filterStatus = ref('')
const page         = ref(1)
const lastPage     = ref(1)
const total        = ref(0)
const showModal    = ref(false)
const showDelete   = ref(false)
const deleteItem   = ref(null)
const saving       = ref(false)
const deleting     = ref(false)
const form         = ref({ user: '', course: '', status: 'active' })

function openCreate() { form.value = { user: '', course: '', status: 'active' }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  enrollments.value.unshift({ id: Date.now(), ...form.value, email: '', progress: 0, enrolled_at: 'Vừa xong' })
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  enrollments.value = enrollments.value.filter(e => e.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  enrollments.value = [
    { id: 1, user: 'Nguyễn Minh Anh', email: 'anh@example.com', course: 'IELTS Foundation', progress: 65, status: 'active',    enrolled_at: '01/01/2026' },
    { id: 2, user: 'Lê Thị Hương',    email: 'huong@example.com', course: 'TOEIC 600+',     progress: 100, status: 'completed', enrolled_at: '15/11/2025' },
    { id: 3, user: 'Phạm Văn Đức',    email: 'duc@example.com',  course: 'English Basics',  progress: 30, status: 'paused',    enrolled_at: '20/02/2026' },
  ]
  total.value = enrollments.value.length
  loading.value = false
})
</script>
