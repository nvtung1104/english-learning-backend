<template>
  <AdminPageLayout title="Quyền hạn" subtitle="Quản lý quyền truy cập hệ thống">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm quyền</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="permissions" :loading="loading"
      searchable :search="search" search-placeholder="Tìm quyền..."
      @update:search="search = $event"
      empty-icon="🔐" empty-title="Chưa có quyền hạn" empty-description="Tạo quyền hạn đầu tiên."
    >
      <template #toolbar>
        <AppSelect v-model="filterGroup" :options="groupOptions" placeholder="Nhóm" class="w-40" />
      </template>
      <template #row="{ row }">
        <td class="px-5 py-4">
          <p class="font-semibold text-navy text-sm font-mono">{{ row.name }}</p>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.description }}</td>
        <td class="px-5 py-4"><AppBadge variant="primary">{{ row.group }}</AppBadge></td>
        <td class="px-5 py-4">
          <div class="flex flex-wrap gap-1">
            <AppBadge v-for="role in row.roles" :key="role" variant="gray" class="text-xs">{{ role }}</AppBadge>
          </div>
        </td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa quyền' : 'Thêm quyền'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.name"        label="Tên quyền (slug)" id="name"        required />
        <AppInput v-model="form.description" label="Mô tả"            id="description" />
        <AppSelect v-model="form.group"      label="Nhóm"             id="group"       :options="groupOptions" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa quyền" message="Bạn có chắc muốn xóa quyền này?" :loading="deleting" @confirm="handleDelete" />
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
  { key: 'name',        label: 'Quyền' },
  { key: 'description', label: 'Mô tả' },
  { key: 'group',       label: 'Nhóm' },
  { key: 'roles',       label: 'Vai trò' },
  { key: 'actions',     label: 'Hành động' },
]
const groupOptions = [
  { value: 'users',    label: 'Người dùng' },
  { value: 'courses',  label: 'Khóa học' },
  { value: 'content',  label: 'Nội dung' },
  { value: 'system',   label: 'Hệ thống' },
]

const permissions  = ref([])
const loading      = ref(true)
const search       = ref('')
const filterGroup  = ref('')
const showModal    = ref(false)
const showDelete   = ref(false)
const editItem     = ref(null)
const deleteItem   = ref(null)
const saving       = ref(false)
const deleting     = ref(false)
const form         = ref({ name: '', description: '', group: 'system' })

function openCreate() { editItem.value = null; form.value = { name: '', description: '', group: 'system' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { name: row.name, description: row.description, group: row.group }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = permissions.value.findIndex(p => p.id === editItem.value.id)
    if (idx !== -1) permissions.value[idx] = { ...permissions.value[idx], ...form.value }
  } else {
    permissions.value.unshift({ id: Date.now(), ...form.value, roles: [] })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  permissions.value = permissions.value.filter(p => p.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  permissions.value = [
    { id: 1, name: 'users.view',    description: 'Xem danh sách người dùng', group: 'users',   roles: ['admin'] },
    { id: 2, name: 'users.create',  description: 'Tạo người dùng mới',       group: 'users',   roles: ['admin'] },
    { id: 3, name: 'courses.view',  description: 'Xem khóa học',             group: 'courses', roles: ['admin', 'teacher'] },
    { id: 4, name: 'courses.edit',  description: 'Chỉnh sửa khóa học',       group: 'courses', roles: ['admin', 'teacher'] },
    { id: 5, name: 'content.manage',description: 'Quản lý nội dung',         group: 'content', roles: ['admin'] },
  ]
  loading.value = false
})
</script>
