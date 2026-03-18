<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Câu hỏi</h1>
        <p class="page-subtitle">Quản lý câu hỏi và đáp án cho các quiz</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Thêm câu hỏi</AppButton>
    </div>

    <div class="card p-4 flex flex-wrap items-center gap-3">
      <AppSelect v-model="filterQuiz" :options="quizOptions" placeholder="Tất cả quiz" class="w-56" />
    </div>

    <div class="space-y-3">
      <div v-for="q in filteredQuestions" :key="q.id" class="card p-5">
        <div class="flex items-start justify-between gap-4 mb-3">
          <p class="font-semibold text-navy text-sm flex-1">{{ q.question }}</p>
          <div class="flex gap-2 shrink-0">
            <button @click="openEdit(q)" class="btn btn-sm btn-secondary">Sửa</button>
            <button @click="deleteQuestion(q)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
          </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-2">
          <div v-for="a in q.answers" :key="a.id"
            :class="['flex items-center gap-2 px-3 py-2 rounded-xl text-sm border', a.is_correct ? 'border-success-300 bg-success-50 text-success-700' : 'border-[var(--border-soft)] text-slate-600']"
          >
            <svg v-if="a.is_correct" class="w-4 h-4 text-success-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="w-4 h-4 shrink-0" v-else />
            {{ a.text }}
          </div>
        </div>
        <p class="text-xs text-slate-400 mt-2">Quiz: {{ q.quiz }}</p>
      </div>
    </div>
    <EmptyState v-if="!filteredQuestions.length" icon="❓" title="Chưa có câu hỏi" description="Thêm câu hỏi cho quiz của bạn." />

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa câu hỏi' : 'Thêm câu hỏi'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.question" label="Câu hỏi" id="question" required />
        <AppSelect v-model="form.quiz_id" label="Quiz" id="quiz" :options="quizOptions" required />
        <div class="space-y-2">
          <label class="text-sm font-medium text-navy">Đáp án (chọn đáp án đúng)</label>
          <div v-for="(a, i) in form.answers" :key="i" class="flex items-center gap-2">
            <input type="radio" :name="'correct'" :value="i" v-model="form.correct_index" class="accent-navy" />
            <AppInput v-model="form.answers[i]" :id="`ans-${i}`" :placeholder="`Đáp án ${i+1}`" class="flex-1" />
          </div>
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
import { ref, computed } from 'vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppModal from '../../components/ui/AppModal.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const filterQuiz = ref('')
const showModal  = ref(false)
const editItem   = ref(null)
const form       = ref({ question: '', quiz_id: '', answers: ['', '', '', ''], correct_index: 0 })

const quizOptions = [
  { value: '1', label: 'Vocabulary Quiz Unit 1' },
  { value: '2', label: 'Grammar Test Unit 2' },
  { value: '3', label: 'Business Terms Quiz' },
]

const questions = ref([
  {
    id: 1, question: 'What does "eloquent" mean?', quiz: 'Vocabulary Quiz Unit 1', quiz_id: '1',
    answers: [
      { id: 1, text: 'Fluent and persuasive', is_correct: true },
      { id: 2, text: 'Shy and quiet',         is_correct: false },
      { id: 3, text: 'Loud and aggressive',   is_correct: false },
      { id: 4, text: 'Confused and unclear',  is_correct: false },
    ],
  },
  {
    id: 2, question: 'Choose the correct form: She ___ to school every day.', quiz: 'Grammar Test Unit 2', quiz_id: '2',
    answers: [
      { id: 1, text: 'go',   is_correct: false },
      { id: 2, text: 'goes', is_correct: true },
      { id: 3, text: 'gone', is_correct: false },
      { id: 4, text: 'going',is_correct: false },
    ],
  },
])

const filteredQuestions = computed(() =>
  filterQuiz.value ? questions.value.filter(q => q.quiz_id === filterQuiz.value) : questions.value
)

function openCreate() { editItem.value = null; form.value = { question: '', quiz_id: '', answers: ['', '', '', ''], correct_index: 0 }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { question: item.question, quiz_id: item.quiz_id, answers: item.answers.map(a => a.text), correct_index: item.answers.findIndex(a => a.is_correct) }; showModal.value = true }
function deleteQuestion(item) { questions.value = questions.value.filter(q => q.id !== item.id) }
function handleSave() {
  if (!editItem.value) {
    questions.value.push({
      id: Date.now(),
      question: form.value.question,
      quiz: quizOptions.find(q => q.value === form.value.quiz_id)?.label || '',
      quiz_id: form.value.quiz_id,
      answers: form.value.answers.map((t, i) => ({ id: i + 1, text: t, is_correct: i === Number(form.value.correct_index) })),
    })
  }
  showModal.value = false
}
</script>
