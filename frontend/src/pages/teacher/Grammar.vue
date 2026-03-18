<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Ngữ pháp</h1>
        <p class="page-subtitle">Quản lý chủ đề ngữ pháp trong các bài học</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm chủ đề</AppButton>
    </div>

    <div class="space-y-3">
      <div v-for="g in grammar" :key="g.id" class="card p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <h3 class="font-display font-bold text-navy">{{ g.topic }}</h3>
              <AppBadge variant="gray">{{ g.level }}</AppBadge>
            </div>
            <p class="text-sm text-slate-500 mb-2">Bài học: {{ g.lesson }}</p>
            <p class="text-sm text-slate-600 leading-relaxed">{{ g.explanation }}</p>
          </div>
          <div class="flex gap-2 shrink-0">
            <button @click="openEdit(g)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="deleteGrammar(g)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </div>
      </div>
    </div>
    <EmptyState v-if="!grammar.length" icon="📐" title="Chưa có chủ đề ngữ pháp" description="Thêm chủ đề ngữ pháp cho bài học của bạn." />

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa ngữ pháp' : 'Thêm chủ đề ngữ pháp'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.topic" label="Chủ đề" id="topic" required />
        <AppInput v-model="form.lesson" label="Bài học" id="lesson" />
        <AppSelect v-model="form.level" label="Cấp độ" id="level" :options="levelOptions" />
        <div>
          <label class="block text-sm font-medium text-navy mb-1.5">Giải thích</label>
          <textarea v-model="form.explanation" id="explanation" rows="4"
            class="w-full px-3 py-2 border border-[var(--border)] rounded-xl text-sm text-navy bg-white focus:outline-none focus:border-navy/40 focus:ring-2 focus:ring-navy/8 resize-none"
          />
        </div>
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
const form      = ref({ topic: '', lesson: '', level: 'A1', explanation: '' })

const levelOptions = [
  { value: 'A1', label: 'A1 - Beginner' },
  { value: 'A2', label: 'A2 - Elementary' },
  { value: 'B1', label: 'B1 - Intermediate' },
  { value: 'B2', label: 'B2 - Upper Intermediate' },
  { value: 'C1', label: 'C1 - Advanced' },
]

const grammar = ref([
  { id: 1, topic: 'Present Simple Tense', lesson: 'Present Tense Review', level: 'A1', explanation: 'Used for habits, routines, and general truths. Form: Subject + V(s/es).' },
  { id: 2, topic: 'Present Continuous',   lesson: 'Present Tense Review', level: 'A2', explanation: 'Used for actions happening now. Form: Subject + am/is/are + V-ing.' },
])

function openCreate() { editItem.value = null; form.value = { topic: '', lesson: '', level: 'A1', explanation: '' }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { topic: item.topic, lesson: item.lesson, level: item.level, explanation: item.explanation }; showModal.value = true }
function deleteGrammar(item) { grammar.value = grammar.value.filter(g => g.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else grammar.value.push({ id: Date.now(), ...form.value })
  showModal.value = false
}
</script>
