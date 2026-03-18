<template>
  <AdminPageLayout title="Người dùng" subtitle="Quản lý tài khoản người dùng">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm người dùng</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="users" :loading="loading"
      searchable :search="search" search-placeholder="Tìm người dùng..."
      @update:search="search = $event"
      :current-page="page" :last-page="lastPage" :total="total"
      @page-change="page = $event"
    >
      <template #toolbar>
        <AppSelect v-model="filterRole" :options="roleOptions" placeholder="Vai trò" class="w-36" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center text-white text-sm font-bold shrink-0">
              {{ row.name?.charAt(0)?.toUpperCase() }}
            </div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.name }}</p>
              <p class="text-xs text-slate-400">{{ row.email }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4">
          <AppBadge :variant="roleVariant(row.role)">{{ row.role }}</AppBadge>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.created_at }}</td>
        <td class="px-5 py-4">
          <AppBadge :variant="row.active ? 'success' : 'gray'" dot>{{ row.active ? 'Hoạt động' : 'Vô hiệu' }}</AppBadge>
        </td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <!-- Create/Edit Modal -->
    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa người dùng' : 'Thêm người dùng'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.name"  label="Họ và tên" id="name"  required :error="errors.name" />
        <AppInput v-model="form.email" label="Email"     id="email" type="email" required :error="errors.email" />
        <AppSelect v-model="form.role" label="Vai trò" id="role" :options="roleOptions" required />
        <AppInput v-if="!editItem" v-model="form.password" label="Mật khẩu" id="password" type="password" required :error="errors.password" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa người dùng" message="Bạn có chắc muốn xóa người dùng này? Hành động không thể hoàn tác." :loading="deleting" @confirm="handleDelete" />
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
  { key: 'name',       label: 'Người dùng' },
  { key: 'role',       label: 'Vai trò' },
  { key: 'created_at', label: 'Ngày tạo' },
  { key: 'active',     label: 'Trạng thái' },
  { key: 'actions',    label: 'Hành động' },
]
const roleOptions = [
  { value: 'student', label: 'Học viên' },
  { value: 'teacher', label: 'Giáo viên' },
  { value: 'admin',   label: 'Quản trị' },
]
const roleVariant = (r) => ({ admin: 'danger', teacher: 'warning', student: 'primary' }[r] || 'gray')

const users      = ref([])
const loading    = ref(true)
const search     = ref('')
const filterRole = ref('')
const page       = ref(1)
const lastPage   = ref(1)
const total      = ref(0)
const showModal  = ref(false)
const showDelete = ref(false)
const editItem   = ref(null)
const deleteItem = ref(null)
const saving     = ref(false)
const deleting   = ref(false)
const errors     = ref({})
const form       = ref({ name: '', email: '', role: 'student', password: '' })

function openCreate() { editItem.value = null; form.value = { name: '', email: '', role: 'student', password: '' }; errors.value = {}; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { name: row.name, email: row.email, role: row.role, password: '' }; errors.value = {}; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 600))
  if (editItem.value) {
    const idx = users.value.findIndex(u => u.id === editItem.value.id)
    if (idx !== -1) users.value[idx] = { ...users.value[idx], ...form.value }
  } else {
    users.value.unshift({ id: Date.now(), ...form.value, active: true, created_at: 'Vừa xong' })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 500))
  users.value = users.value.filter(u => u.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  users.value = [
    { id: 1, name: 'Nguyễn Minh Anh', email: 'anh@example.com', role: 'student', active: true,  created_at: '01/01/2026' },
    { id: 2, name: 'Trần Bảo Long',   email: 'long@example.com', role: 'teacher', active: true,  created_at: '15/12/2025' },
    { id: 3, name: 'Admin System',     email: 'admin@example.com', role: 'admin',  active: true,  created_at: '01/01/2025' },
    { id: 4, name: 'Lê Thị Hương',    email: 'huong@example.com', role: 'student', active: false, created_at: '20/11/2025' },
  ]
  total.value = users.value.length
  loading.value = false
})
</script>
