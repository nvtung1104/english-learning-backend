<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Từ vựng</h1>
        <p class="page-subtitle">Quản lý từ vựng trong các bài học của bạn</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm từ vựng</AppButton>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Từ</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Phiên âm</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nghĩa</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="v in vocabularies" :key="v.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4 font-bold text-navy font-display">{{ v.word }}</td>
              <td class="px-5 py-4 text-sm text-slate-500 font-mono">{{ v.pronunciation }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ v.meaning }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ v.lesson }}</td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEdit(v)" class="btn btn-sm btn-secondary">Sửa</button>
                  <button @click="deleteVocab(v)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!vocabularies.length" icon="📝" title="Chưa có từ vựng" description="Thêm từ vựng cho bài học của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa từ vựng' : 'Thêm từ vựng'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.word" label="Từ" id="word" required />
        <AppInput v-model="form.pronunciation" label="Phiên âm" id="pronunciation" />
        <AppInput v-model="form.meaning" label="Nghĩa" id="meaning" required />
        <AppInput v-model="form.example" label="Ví dụ" id="example" />
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
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
import AppModal from '../../components/ui/AppModal.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const showModal = ref(false)
const editItem  = ref(null)
const form      = ref({ word: '', pronunciation: '', meaning: '', example: '', lesson: '' })

const vocabularies = ref([
  { id: 1, word: 'Eloquent',     pronunciation: '/ˈɛl.ə.kwənt/', meaning: 'Fluent and persuasive', lesson: 'Vocabulary Unit 1' },
  { id: 2, word: 'Perseverance', pronunciation: '/ˌpɜː.sɪˈvɪər.əns/', meaning: 'Persistence despite difficulty', lesson: 'Vocabulary Unit 2' },
  { id: 3, word: 'Diligent',     pronunciation: '/ˈdɪl.ɪ.dʒənt/', meaning: 'Hardworking and careful', lesson: 'Vocabulary Unit 1' },
])

function openCreate() { editItem.value = null; form.value = { word: '', pronunciation: '', meaning: '', example: '', lesson: '' }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { word: item.word, pronunciation: item.pronunciation, meaning: item.meaning, example: '', lesson: item.lesson }; showModal.value = true }
function deleteVocab(item) { vocabularies.value = vocabularies.value.filter(v => v.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else vocabularies.value.push({ id: Date.now(), ...form.value })
  showModal.value = false
}
</script>
