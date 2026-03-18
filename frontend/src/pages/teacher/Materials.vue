<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Tài liệu</h1>
        <p class="page-subtitle">Quản lý tài liệu đính kèm cho các bài học</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm tài liệu</AppButton>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tài liệu</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Loại</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="m in materials" :key="m.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <span class="text-lg">{{ m.icon }}</span>
                  <span class="font-semibold text-navy">{{ m.title }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ m.lesson }}</td>
              <td class="px-5 py-4"><AppBadge variant="gray">{{ m.type }}</AppBadge></td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEdit(m)" class="btn btn-sm btn-secondary">Sửa</button>
                  <button @click="deleteMaterial(m)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!materials.length" icon="📎" title="Chưa có tài liệu" description="Thêm tài liệu cho bài học của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa tài liệu' : 'Thêm tài liệu'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tên tài liệu" id="title" required />
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
        <AppSelect v-model="form.type" label="Loại" id="type" :options="typeOptions" />
        <AppInput v-model="form.url" label="URL / Đường dẫn" id="url" />
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppModal from '../../components/ui/AppModal.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const showModal = ref(false)
const editItem  = ref(null)
const form      = ref({ title: '', lesson: '', type: 'pdf', url: '' })

const typeOptions = [
  { value: 'pdf',   label: 'PDF' },
  { value: 'doc',   label: 'Word' },
  { value: 'audio', label: 'Audio' },
  { value: 'video', label: 'Video' },
  { value: 'link',  label: 'Link' },
]

const iconMap = { pdf: '📄', doc: '📝', audio: '🎵', video: '🎬', link: '🔗' }

const materials = ref([
  { id: 1, title: 'IELTS Listening Practice',  lesson: 'Listening Skills Intro', type: 'pdf',   icon: '📄' },
  { id: 2, title: 'Business Email Templates',  lesson: 'Business Vocabulary 1',  type: 'doc',   icon: '📝' },
  { id: 3, title: 'Grammar Reference Sheet',   lesson: 'Present Tense Review',   type: 'pdf',   icon: '📄' },
])

function openCreate() { editItem.value = null; form.value = { title: '', lesson: '', type: 'pdf', url: '' }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { title: item.title, lesson: item.lesson, type: item.type, url: '' }; showModal.value = true }
function deleteMaterial(item) { materials.value = materials.value.filter(m => m.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, { ...form.value, icon: iconMap[form.value.type] || '📎' })
  else materials.value.push({ id: Date.now(), ...form.value, icon: iconMap[form.value.type] || '📎' })
  showModal.value = false
}
</script>
