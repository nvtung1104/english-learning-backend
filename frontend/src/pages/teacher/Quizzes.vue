<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Quiz</h1>
        <p class="page-subtitle">Tạo và quản lý bài kiểm tra cho học viên</p>
      </div>
      <AppButton variant="primary" size="md" @click="openCreate">+ Tạo quiz</AppButton>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tên quiz</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bài học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Số câu hỏi</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Lượt làm</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Điểm TB</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="q in quizzes" :key="q.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4 font-semibold text-navy">{{ q.title }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ q.lesson }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ q.questions_count }}</td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ q.attempts }}</td>
              <td class="px-5 py-4">
                <span :class="['text-sm font-semibold', q.avg_score >= 70 ? 'text-success-600' : 'text-warning-600']">
                  {{ q.avg_score }}%
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <RouterLink :to="`/teacher/questions?quiz=${q.id}`" class="btn btn-sm btn-secondary">Câu hỏi</RouterLink>
                  <button @click="openEdit(q)" class="btn btn-sm btn-ghost text-slate-500 hover:text-navy">Sửa</button>
                  <button @click="deleteQuiz(q)" class="btn btn-sm btn-ghost text-danger-600 hover:bg-danger-50">Xóa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!quizzes.length" icon="🧩" title="Chưa có quiz" description="Tạo quiz đầu tiên cho học viên của bạn." />
    </div>

    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa quiz' : 'Tạo quiz mới'" size="md">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tên quiz" id="title" required />
        <AppInput v-model="form.lesson" label="Bài học liên quan" id="lesson" />
        <AppInput v-model="form.pass_score" label="Điểm đạt (%)" id="pass_score" type="number" />
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
import { RouterLink } from 'vue-router'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppModal from '../../components/ui/AppModal.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const showModal = ref(false)
const editItem  = ref(null)
const form      = ref({ title: '', lesson: '', pass_score: 70 })

const quizzes = ref([
  { id: 1, title: 'Vocabulary Quiz Unit 1', lesson: 'Listening Skills Intro', questions_count: 20, attempts: 85,  avg_score: 78 },
  { id: 2, title: 'Grammar Test Unit 2',    lesson: 'Present Tense Review',   questions_count: 15, attempts: 43,  avg_score: 65 },
  { id: 3, title: 'Business Terms Quiz',    lesson: 'Business Vocabulary 1',  questions_count: 25, attempts: 120, avg_score: 82 },
])

function openCreate() { editItem.value = null; form.value = { title: '', lesson: '', pass_score: 70 }; showModal.value = true }
function openEdit(item) { editItem.value = item; form.value = { title: item.title, lesson: item.lesson, pass_score: 70 }; showModal.value = true }
function deleteQuiz(item) { quizzes.value = quizzes.value.filter(q => q.id !== item.id) }
function handleSave() {
  if (editItem.value) Object.assign(editItem.value, form.value)
  else quizzes.value.push({ id: Date.now(), ...form.value, questions_count: 0, attempts: 0, avg_score: 0 })
  showModal.value = false
}
</script>
