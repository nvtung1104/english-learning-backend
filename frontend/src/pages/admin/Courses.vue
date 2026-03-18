<template>
  <AdminPageLayout title="Quản lý khóa học" subtitle="Tạo và quản lý toàn bộ khóa học trong hệ thống">
    <template #actions>
      <AppButton variant="primary" size="sm" @click="openCreate">
        <template #icon-left>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
        </template>
        Thêm khóa học
      </AppButton>
    </template>

    <template #filters>
      <AppSelect v-model="filterStatus" :options="statusOptions" placeholder="Trạng thái" class="w-40" />
      <AppSelect v-model="filterLevel" :options="levelOptions" placeholder="Cấp độ" class="w-40" />
    </template>

    <DataTable
      :columns="columns" :data="filteredCourses" :loading="loading"
      v-model:search="search" search-placeholder="Tìm khóa học..."
      :total="filteredCourses.length" empty-icon="📚" empty-title="Chưa có khóa học"
      empty-desc="Tạo khóa học đầu tiên để bắt đầu."
    >
      <template #cell-title="{ row }">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-100 to-purple-100 flex items-center justify-center text-lg shrink-0 overflow-hidden">
            <img v-if="row.thumbnail" :src="row.thumbnail" class="w-full h-full object-cover" />
            <span v-else>📚</span>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm">{{ row.title }}</p>
            <p class="text-xs text-gray-400">{{ row.category?.name || '—' }}</p>
          </div>
        </div>
      </template>
      <template #cell-level="{ row }">
        <AppBadge :variant="levelVariant(row.level)">{{ levelLabel(row.level) }}</AppBadge>
      </template>
      <template #cell-status="{ row }">
        <AppBadge :variant="row.status === 'published' ? 'success' : 'gray'" :dot="true">
          {{ row.status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
        </AppBadge>
      </template>
      <template #cell-price="{ row }">
        <span class="font-semibold text-gray-700">
          {{ row.is_free || !row.price ? 'Miễn phí' : Number(row.price).toLocaleString('vi-VN') + 'đ' }}
        </span>
      </template>
      <template #cell-students_count="{ row }">
        <span class="text-gray-600">{{ row.students_count || 0 }}</span>
      </template>
      <template #actions="{ row }">
        <RouterLink :to="`/admin/courses/${row.id}/sections`"
          class="p-1.5 rounded-lg text-gray-400 hover:text-purple-600 hover:bg-purple-50 transition-colors" title="Quản lý chương">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
          </svg>
        </RouterLink>
        <button @click="openEdit(row)" class="p-1.5 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-colors" title="Chỉnh sửa">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </button>
        <button @click="confirmDelete(row)" class="p-1.5 rounded-lg text-gray-400 hover:text-danger-600 hover:bg-danger-50 transition-colors" title="Xóa">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
        </button>
      </template>
    </DataTable>

    <!-- Modal -->
    <AppModal v-model="showModal" :title="editCourse ? 'Chỉnh sửa khóa học' : 'Thêm khóa học mới'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <AppInput v-model="form.title" label="Tên khóa học" placeholder="VD: IELTS 7.0 Toàn diện" required class="col-span-2" />
          <AppSelect v-model="form.level" label="Cấp độ" :options="levelOptions.slice(1)" placeholder="Chọn cấp độ" />
          <AppSelect v-model="form.status" label="Trạng thái" :options="[{value:'draft',label:'Nháp'},{value:'published',label:'Xuất bản'}]" />
          <AppInput v-model="form.price" label="Giá (VNĐ)" type="number" placeholder="0 = Miễn phí" />
          <AppInput v-model="form.thumbnail" label="Ảnh thumbnail (URL)" placeholder="https://..." />
        </div>
        <AppTextarea v-model="form.description" label="Mô tả khóa học" placeholder="Mô tả ngắn về khóa học..." :rows="3" />
        <div class="flex items-center gap-2">
          <input type="checkbox" id="is_free" v-model="form.is_free" class="accent-primary-600 w-4 h-4" />
          <label for="is_free" class="text-sm font-medium text-gray-700">Khóa học miễn phí</label>
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">
          {{ editCourse ? 'Lưu thay đổi' : 'Tạo khóa học' }}
        </AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa khóa học"
      :message="`Xóa khóa học &quot;${deleteTarget?.title}&quot;? Tất cả dữ liệu liên quan sẽ bị xóa.`"
      :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppModal from '../../components/ui/AppModal.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppTextarea from '../../components/ui/AppTextarea.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'
import { useToastStore } from '../../stores/toast'
import api from '../../api/axios'

const toast = useToastStore()
const courses = ref([])
const loading = ref(true)
const search = ref('')
const filterStatus = ref('')
const filterLevel = ref('')
const showModal = ref(false)
const showDelete = ref(false)
const saving = ref(false)
const deleting = ref(false)
const editCourse = ref(null)
const deleteTarget = ref(null)
const form = reactive({ title: '', description: '', level: '', status: 'draft', price: '', thumbnail: '', is_free: false })

const columns = [
  { key: 'title', label: 'Khóa học' },
  { key: 'level', label: 'Cấp độ', width: '120px' },
  { key: 'status', label: 'Trạng thái', width: '140px' },
  { key: 'price', label: 'Giá', width: '120px' },
  { key: 'students_count', label: 'Học viên', width: '100px' },
]

const statusOptions = [{ value: '', label: 'Tất cả' }, { value: 'published', label: 'Đã xuất bản' }, { value: 'draft', label: 'Nháp' }]
const levelOptions = [{ value: '', label: 'Tất cả' }, { value: 'beginner', label: 'Cơ bản' }, { value: 'intermediate', label: 'Trung cấp' }, { value: 'advanced', label: 'Nâng cao' }]

const levelLabel = (l) => ({ beginner: 'Cơ bản', intermediate: 'Trung cấp', advanced: 'Nâng cao' }[l] || l || '—')
const levelVariant = (l) => ({ beginner: 'success', intermediate: 'warning', advanced: 'danger' }[l] || 'gray')

const filteredCourses = computed(() => courses.value.filter(c => {
  const q = search.value.toLowerCase()
  return (!q || c.title?.toLowerCase().includes(q)) &&
    (!filterStatus.value || c.status === filterStatus.value) &&
    (!filterLevel.value || c.level === filterLevel.value)
}))

onMounted(async () => {
  try {
    const res = await api.get('/admin/courses')
    courses.value = res.data?.data || res.data || []
  } catch { courses.value = mockCourses } finally { loading.value = false }
})

function openCreate() {
  editCourse.value = null
  Object.assign(form, { title: '', description: '', level: '', status: 'draft', price: '', thumbnail: '', is_free: false })
  showModal.value = true
}
function openEdit(c) {
  editCourse.value = c
  Object.assign(form, { title: c.title, description: c.description || '', level: c.level || '', status: c.status || 'draft', price: c.price || '', thumbnail: c.thumbnail || '', is_free: c.is_free || false })
  showModal.value = true
}
function confirmDelete(c) { deleteTarget.value = c; showDelete.value = true }

async function handleSave() {
  saving.value = true
  try {
    if (editCourse.value) {
      await api.put(`/admin/courses/${editCourse.value.id}`, form)
      const idx = courses.value.findIndex(c => c.id === editCourse.value.id)
      if (idx !== -1) courses.value[idx] = { ...courses.value[idx], ...form }
      toast.success('Cập nhật khóa học thành công')
    } else {
      const res = await api.post('/admin/courses', form)
      courses.value.unshift(res.data?.data || res.data)
      toast.success('Tạo khóa học thành công')
    }
    showModal.value = false
  } catch (e) { toast.error(e.response?.data?.message || 'Có lỗi xảy ra') } finally { saving.value = false }
}

async function handleDelete() {
  deleting.value = true
  try {
    await api.delete(`/admin/courses/${deleteTarget.value.id}`)
    courses.value = courses.value.filter(c => c.id !== deleteTarget.value.id)
    toast.success('Xóa khóa học thành công')
    showDelete.value = false
  } catch { toast.error('Xóa thất bại') } finally { deleting.value = false }
}

const mockCourses = [
  { id: 1, title: 'IELTS 7.0 Toàn diện', level: 'advanced', status: 'published', price: 1200000, is_free: false, students_count: 2841, category: { name: 'IELTS' } },
  { id: 2, title: 'Tiếng Anh giao tiếp A1-B2', level: 'beginner', status: 'published', price: 0, is_free: true, students_count: 2210, category: { name: 'Giao tiếp' } },
  { id: 3, title: 'TOEIC 900+ Chiến lược', level: 'intermediate', status: 'published', price: 990000, is_free: false, students_count: 1890, category: { name: 'TOEIC' } },
  { id: 4, title: 'Ngữ pháp tiếng Anh cơ bản', level: 'beginner', status: 'draft', price: 0, is_free: true, students_count: 0, category: { name: 'Ngữ pháp' } },
]
</script>
