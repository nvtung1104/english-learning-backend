<template>
  <AdminPageLayout title="Vai trò" subtitle="Quản lý vai trò người dùng">
    <template #actions>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm vai trò</AppButton>
    </template>

    <DataTable
      :columns="columns" :rows="roles" :loading="loading"
      searchable :search="search" search-placeholder="Tìm vai trò..."
      @update:search="search = $event"
      empty-icon="🛡️" empty-title="Chưa có vai trò" empty-description="Tạo vai trò đầu tiên."
    >
      <template #row="{ row }">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-navy/10 flex items-center justify-center text-lg">{{ row.icon }}</div>
            <div>
              <p class="font-semibold text-navy text-sm">{{ row.name }}</p>
              <p class="text-xs text-slate-400">{{ row.slug }}</p>
            </div>
          </div>
        </td>
        <td class="px-5 py-4 text-sm text-slate-600 max-w-xs">{{ row.description }}</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.users_count }} người dùng</td>
        <td class="px-5 py-4 text-sm text-slate-600">{{ row.permissions_count }} quyền</td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-2">
            <button @click="openEdit(row)" class="btn btn-sm btn-secondary">Sửa</button>
            <button v-if="!row.system" @click="confirmDelete(row)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </td>
      </template>
    </DataTable>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa vai trò' : 'Thêm vai trò'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.name"        label="Tên vai trò"  id="name"        required />
        <AppInput v-model="form.slug"        label="Slug"         id="slug"        required />
        <AppInput v-model="form.description" label="Mô tả"        id="description" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" :loading="saving" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>

    <ConfirmDialog v-model="showDelete" title="Xóa vai trò" message="Bạn có chắc muốn xóa vai trò này?" :loading="deleting" @confirm="handleDelete" />
  </AdminPageLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import DataTable from '../../components/admin/DataTable.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppModal from '../../components/ui/AppModal.vue'
import ConfirmDialog from '../../components/ui/ConfirmDialog.vue'

const columns = [
  { key: 'name',              label: 'Vai trò' },
  { key: 'description',       label: 'Mô tả' },
  { key: 'users_count',       label: 'Người dùng' },
  { key: 'permissions_count', label: 'Quyền hạn' },
  { key: 'actions',           label: 'Hành động' },
]

const roles      = ref([])
const loading    = ref(true)
const search     = ref('')
const showModal  = ref(false)
const showDelete = ref(false)
const editItem   = ref(null)
const deleteItem = ref(null)
const saving     = ref(false)
const deleting   = ref(false)
const form       = ref({ name: '', slug: '', description: '' })

function openCreate() { editItem.value = null; form.value = { name: '', slug: '', description: '' }; showModal.value = true }
function openEdit(row) { editItem.value = row; form.value = { name: row.name, slug: row.slug, description: row.description }; showModal.value = true }
function confirmDelete(row) { deleteItem.value = row; showDelete.value = true }

async function handleSave() {
  saving.value = true
  await new Promise(r => setTimeout(r, 500))
  if (editItem.value) {
    const idx = roles.value.findIndex(r => r.id === editItem.value.id)
    if (idx !== -1) roles.value[idx] = { ...roles.value[idx], ...form.value }
  } else {
    roles.value.unshift({ id: Date.now(), ...form.value, icon: '👤', users_count: 0, permissions_count: 0, system: false })
  }
  saving.value = false; showModal.value = false
}

async function handleDelete() {
  deleting.value = true
  await new Promise(r => setTimeout(r, 400))
  roles.value = roles.value.filter(r => r.id !== deleteItem.value.id)
  deleting.value = false; showDelete.value = false
}

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  roles.value = [
    { id: 1, name: 'Quản trị viên', slug: 'admin',   icon: '👑', description: 'Toàn quyền hệ thống',    users_count: 2,  permissions_count: 50, system: true },
    { id: 2, name: 'Giáo viên',     slug: 'teacher', icon: '👨‍🏫', description: 'Quản lý khóa học',       users_count: 8,  permissions_count: 20, system: true },
    { id: 3, name: 'Học viên',      slug: 'student', icon: '🎓', description: 'Học và làm bài kiểm tra', users_count: 150, permissions_count: 10, system: true },
  ]
  loading.value = false
})
</script>
