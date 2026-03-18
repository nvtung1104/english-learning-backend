<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Bài nghe</h1>
        <p class="page-subtitle">Quản lý bài nghe trong các khóa học của bạn</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm bài nghe</AppButton>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài nghe</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Thời lượng</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Cấp độ</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="l in listenings" :key="l.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <span class="text-lg">🎧</span>
                  <span class="font-semibold text-navy">{{ l.title }}</span>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ l.lesson }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ l.duration }}</td>
              <td class="px-5 py-4"><AppBadge variant="gray">{{ l.level }}</AppBadge></td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEdit(l)" class="btn btn-sm btn-secondary">Sửa</button>
                  <button @click="deleteListening(l)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!listenings.length" icon="🎧" title="Chưa có bài nghe" description="Thêm bài nghe cho khóa học của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa bài nghe' : 'Thêm bài nghe'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tiêu đề" id="title" required />
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
        <div class="grid sm:grid-cols-2 gap-4">
          <AppInput v-model="form.duration" label="Thời lượng" id="duration" placeholder="vd: 3:45" />
          <AppSelect v-model="form.level" label="Cấp độ" id="level" :options="levelOptions" />
        </div>
        <AppInput v-model="form.audio_url" label="URL Audio" id="audio_url" />
        <AppInput v-model="form.transcript" label="Transcript" id="transcript" />
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
const form      = ref({ title: '', lesson: '', duration: '', level: 'B1', audio_url: '', transcript: '' })

const levelOptions = [
  { value: 'A1', label: 'A1' }, { value: 'A2', label: 'A2' },
  { value: 'B1', label: 'B1' }, { value: 'B2', label: 'B2' },
  { value: 'C1', label: 'C1' },
]

const listenings = ref([
  { id: 1, title: 'Airport Conversation',    lesson: 'Listening Skills Intro', duration: '3:20', level: 'A2' },
  { id: 2, title: 'Business Meeting Dialog', lesson: 'Business Vocabulary 1',  duration: '5:45', level: 'B2' },
  { id: 3, title: 'IELTS Practice Test 1',   lesson: 'IELTS Listening',        duration: '30:00', level: 'B2' },
])

function openCreate() { editItem.value = null; form.value = { title: '', lesson: '', duration: '', level: 'B1', audio_url: '', transcript: '' }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { title: item.title, lesson: item.lesson, duration: item.duration, level: item.level, audio_url: '', transcript: '' }; showModal.value = true }
function deleteListening(item) { listenings.value = listenings.value.filter(l => l.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else listenings.value.push({ id: Date.now(), ...form.value })
  showModal.value = false
}
</script>
