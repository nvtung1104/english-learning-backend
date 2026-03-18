<template>
  <AdminPageLayout title="Thông báo" subtitle="Quản lý thông báo hệ thống">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Tạo thông báo</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="notifications" :loading="loading"
      searchable :search="search" search-placeholder="Tìm thông báo..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
      empty-icon="🔔" empty-title="Chưa có thông báo" empty-description="Tạo thông báo đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterType" :options="typeOptions" placeholder="Loại" class="w-40" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <p class="font-semibold text-navy text-sm">{{ row.title }}</p>
          <p class="text-xs text-slate-400 line-clamp-1">{{ row.message }}</p>
        </td>
        <td class="px-5 py-4"><AppBadge :variant="typeVariant(row.type)">{{ typeLabel(row.type) }}</AppBadge></td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.recipient }}</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.read ? 'gray' : 'primary'" dot>{{ row.read ? 'Đã đọc' : 'Chưa đọc' }}</AppBadge>
        </td>
        <td class="px-5 py-4 text-sm text-slate-400">{{ row.created_at }}</td>
        <td class="px-5 py-4">
          <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" title="Tạo thông báo" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title"     label="Tiêu đề"  id="title"     required />
        <AppTextarea v-model="form.message" label="Nội dung" id="message"   rows="3" required />
        <AppSelect v-model="form.type"     label="Loại"     id="type"      :options="typeOptions" />
        <AppInput v-model="form.recipient" label="Người nhận (email hoặc 'all')" id="recipient" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Gửi</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa thông báo" message="Bạn có chắc muốn xóa thông báo này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'title',      label: 'Thông báo' },
  { key: 'type',       label: 'Loại' },
  { key: 'recipient',  label: 'Người nhận' },
  { key: 'read',       label: 'Trạng thái' },
  { key: 'created_at', label: 'Ngày tạo' },
  { key: 'actions',    label: 'Hành động' },
]
const typeOptions = [
  { value: 'system',   label: 'Hệ thống' },
  { value: 'course',   label: 'Khóa học' },
  { value: 'reminder', label: 'Nhắc nhở' },
]
const typeVariant = (t) => ({ system: 'danger', course: 'primary', reminder: 'warning' }[t] || 'gray')
const typeLabel   = (t) => ({ system: 'Hệ thống', course: 'Khóa học', reminder: 'Nhắc nhở' }[t] || t)

const notifications = ref([])
const loading       = ref(true)
const search        = ref('')
const filterType    = ref('')
const page          = ref(1)
const lastPage      = ref(1)
const total         = ref(0)
const showModal     = ref(false)
const showDelete    = ref(false)
const deleteItem    = ref(null)
const saving        = ref(false)
const deleting      = ref(false)
const form          = ref({ title: '', message: '', type: 'system', recipient: 'all' })

function openCreate() { form.value = { title: '', message: '', type: 'system', recipient: 'all' }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  notifications.value.unshift({ id: Date.now(), ...form.value, read: false, created_at: 'Vừa xong' })
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  notifications.value = notifications.value.filter(n => n.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  notifications.value = [
    { id: 1, title: 'Chào mừng học viên mới', message: 'Chào mừng bạn đến với nền tảng học tiếng Anh.', type: 'system',   recipient: 'all',              read: false, created_at: '10/03/2026' },
    { id: 2, title: 'Khóa học mới ra mắt',    message: 'IELTS Advanced đã có mặt trên hệ thống.',       type: 'course',   recipient: 'all',              read: true,  created_at: '08/03/2026' },
    { id: 3, title: 'Nhắc ôn tập',            message: 'Bạn chưa học trong 3 ngày, hãy tiếp tục nhé.', type: 'reminder', recipient: 'anh@example.com',  read: false, created_at: '07/03/2026' },
  ]
  total.value = notifications.value.length
  loading.value = false
})
</script>
