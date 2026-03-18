<template>
  <div class="space-y-5">
    <div>
      <h1 class="page-title">Học viên</h1>
      <p class="page-subtitle">Theo dõi học viên trong các khóa học của bạn</p>
    </div>

    <!-- Filter by course -->
    <div class="card p-4 flex flex-wrap items-center gap-3">
      <AppSelect v-model="filterCourse" :options="courseOptions" placeholder="Tất cả khóa học" class="w-56" />
      <div class="relative flex-1 max-w-xs">
        <input v-model="search" type="text" placeholder="Tìm học viên..." class="input pl-9 w-full" />
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
    </div>

    <div class="card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Học viên</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tiến độ</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Điểm Quiz TB</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Đăng ký</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="s in filteredStudents" :key="s.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-navy/10 flex items-center justify-center text-xs font-bold text-navy shrink-0">
                    {{ s.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="font-semibold text-navy text-sm">{{ s.name }}</p>
                    <p class="text-xs text-slate-400">{{ s.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ s.course }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-24 h-1.5 bg-cream-soft rounded-full overflow-hidden border border-[var(--border)]">
                    <div class="h-full bg-navy rounded-full" :style="`width:${s.progress}%`" />
                  </div>
                  <span class="text-xs font-semibold text-navy">{{ s.progress }}%</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <span :class="['text-sm font-semibold', s.avg_score >= 70 ? 'text-success-600' : 'text-warning-600']">
                  {{ s.avg_score }}%
                </span>
              </td>
              <td class="px-5 py-4 text-xs text-slate-400">{{ s.enrolled_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!filteredStudents.length" icon="👥" title="Chưa có học viên" description="Chưa có học viên đăng ký khóa học của bạn." />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const search       = ref('')
const filterCourse = ref('')

const courseOptions = [
  { value: '1', label: 'IELTS Preparation Course' },
  { value: '2', label: 'Business English' },
  { value: '3', label: 'Grammar Fundamentals' },
]

const students = ref([
  { id: 1, name: 'Nguyễn Minh Anh', email: 'minhanh@email.com', course: 'IELTS Preparation', progress: 65, avg_score: 82, enrolled_at: '01/01/2025', course_id: '1' },
  { id: 2, name: 'Trần Bảo Long',   email: 'baolong@email.com', course: 'Business English',  progress: 40, avg_score: 75, enrolled_at: '05/01/2025', course_id: '2' },
  { id: 3, name: 'Lê Thị Hương',    email: 'lehuong@email.com', course: 'IELTS Preparation', progress: 90, avg_score: 91, enrolled_at: '10/12/2024', course_id: '1' },
  { id: 4, name: 'Phạm Văn Đức',    email: 'vanduc@email.com',  course: 'Grammar Fundamentals', progress: 20, avg_score: 60, enrolled_at: '15/01/2025', course_id: '3' },
])

const filteredStudents = computed(() => {
  let result = students.value
  if (filterCourse.value) result = result.filter(s => s.course_id === filterCourse.value)
  if (search.value) result = result.filter(s =>
    s.name.toLowerCase().includes(search.value.toLowerCase()) ||
    s.email.toLowerCase().includes(search.value.toLowerCase())
  )
  return result
})
</script>
