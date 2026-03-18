<template>
  <div class="space-y-5">
    <div>
      <h1 class="page-title">Đánh giá</h1>
      <p class="page-subtitle">Xem đánh giá của học viên về khóa học của bạn</p>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard v-for="s in stats" :key="s.label" v-bind="s" />
    </div>

    <!-- Filter -->
    <div class="card p-4 flex flex-wrap items-center gap-3">
      <AppSelect v-model="filterCourse" :options="courseOptions" placeholder="Tất cả khóa học" class="w-56" />
      <AppSelect v-model="filterRating" :options="ratingOptions" placeholder="Tất cả sao" class="w-36" />
    </div>

    <!-- Reviews list -->
    <div class="card divide-y divide-[var(--border-soft)]">
      <div v-for="r in filteredReviews" :key="r.id" class="p-5 flex gap-4">
        <div class="w-9 h-9 rounded-full bg-navy/10 flex items-center justify-center text-sm font-bold text-navy shrink-0">
          {{ r.name.charAt(0) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2 mb-1">
            <span class="font-semibold text-navy text-sm">{{ r.name }}</span>
            <span class="text-gold text-sm">{{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}</span>
            <AppBadge variant="gray">{{ r.course }}</AppBadge>
          </div>
          <p class="text-sm text-slate-600 leading-relaxed">{{ r.comment }}</p>
          <p class="text-xs text-slate-400 mt-1.5">{{ r.date }}</p>
        </div>
      </div>
      <EmptyState v-if="!filteredReviews.length" icon="⭐" title="Chưa có đánh giá" description="Học viên chưa đánh giá khóa học của bạn." />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import StatCard from '../../components/ui/StatCard.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const filterCourse = ref('')
const filterRating = ref('')

const stats = [
  { icon: '⭐', label: 'Đánh giá TB',  value: '4.7',  color: 'amber' },
  { icon: '📝', label: 'Tổng đánh giá', value: '156',  color: 'navy' },
  { icon: '😊', label: '5 sao',         value: '98',   color: 'emerald' },
  { icon: '😐', label: '3 sao trở xuống', value: '12', color: 'red' },
]

const courseOptions = [
  { value: '1', label: 'IELTS Preparation Course' },
  { value: '2', label: 'Business English' },
  { value: '3', label: 'Grammar Fundamentals' },
]

const ratingOptions = [
  { value: '5', label: '5 sao' },
  { value: '4', label: '4 sao' },
  { value: '3', label: '3 sao' },
  { value: '2', label: '2 sao' },
  { value: '1', label: '1 sao' },
]

const reviews = ref([
  { id: 1, name: 'Minh Anh',  course: 'IELTS Preparation', rating: 5, comment: 'Khóa học rất hay, giảng viên nhiệt tình và dễ hiểu!', date: '15/01/2025', course_id: '1' },
  { id: 2, name: 'Bảo Long',  course: 'Business English',  rating: 4, comment: 'Nội dung thực tế, áp dụng được ngay vào công việc.', date: '12/01/2025', course_id: '2' },
  { id: 3, name: 'Lê Hương',  course: 'IELTS Preparation', rating: 5, comment: 'Tuyệt vời! Điểm IELTS của tôi tăng từ 5.5 lên 7.0.', date: '10/01/2025', course_id: '1' },
  { id: 4, name: 'Văn Đức',   course: 'Grammar Fundamentals', rating: 3, comment: 'Nội dung ổn nhưng cần thêm bài tập thực hành.', date: '08/01/2025', course_id: '3' },
])

const filteredReviews = computed(() => {
  let result = reviews.value
  if (filterCourse.value) result = result.filter(r => r.course_id === filterCourse.value)
  if (filterRating.value) result = result.filter(r => r.rating === Number(filterRating.value))
  return result
})
</script>
