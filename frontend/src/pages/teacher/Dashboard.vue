<template>
  <div class="space-y-6">
    <!-- Welcome -->
    <div class="relative rounded-2xl overflow-hidden bg-navy px-8 py-7">
      <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-gold/6 -translate-y-1/2 translate-x-1/4 pointer-events-none" />
      <div class="relative flex flex-wrap items-center justify-between gap-4">
        <div>
          <p class="text-white/45 text-xs mb-1">Xin chào 👋</p>
          <h1 class="font-display text-2xl font-bold text-white mb-1">{{ auth.user?.name || 'Giảng viên' }}</h1>
          <p class="text-white/50 text-sm">Quản lý khóa học và theo dõi học viên của bạn</p>
        </div>
        <RouterLink to="/teacher/courses"
          class="px-5 py-2.5 bg-gold text-white text-sm font-semibold rounded-xl hover:opacity-90 transition-opacity"
        >+ Tạo khóa học</RouterLink>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard v-for="s in stats" :key="s.label" v-bind="s" />
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
      <!-- My courses -->
      <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-display font-bold text-navy text-base">Khóa học của tôi</h2>
          <RouterLink to="/teacher/courses" class="text-xs font-semibold text-gold hover:underline">Xem tất cả →</RouterLink>
        </div>
        <div class="space-y-3">
          <div v-for="c in myCourses" :key="c.id"
            class="flex items-center gap-4 p-3 rounded-xl border border-[var(--border-soft)] hover:border-navy/20 transition-colors"
          >
            <div class="w-10 h-10 rounded-xl bg-navy/8 flex items-center justify-center text-xl shrink-0">📚</div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy text-sm truncate">{{ c.title }}</p>
              <p class="text-xs text-slate-400">{{ c.students }} học viên · {{ c.lessons }} bài học</p>
            </div>
            <AppBadge :variant="c.status === 'published' ? 'success' : 'warning'">
              {{ c.status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
            </AppBadge>
          </div>
        </div>
        <EmptyState v-if="!myCourses.length" icon="📚" title="Chưa có khóa học" description="Tạo khóa học đầu tiên của bạn." />
      </div>

      <!-- Recent reviews -->
      <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-display font-bold text-navy text-base">Đánh giá gần đây</h2>
          <RouterLink to="/teacher/reviews" class="text-xs font-semibold text-gold hover:underline">Xem tất cả →</RouterLink>
        </div>
        <div class="space-y-4">
          <div v-for="r in recentReviews" :key="r.id" class="flex gap-3 pb-4 border-b border-[var(--border-soft)] last:border-0 last:pb-0">
            <div class="w-8 h-8 rounded-full bg-navy/10 flex items-center justify-center text-xs font-bold text-navy shrink-0">
              {{ r.name.charAt(0) }}
            </div>
            <div>
              <div class="flex items-center gap-2 mb-0.5">
                <span class="font-semibold text-navy text-sm">{{ r.name }}</span>
                <span class="text-gold text-xs">{{ '★'.repeat(r.rating) }}</span>
              </div>
              <p class="text-xs text-slate-500">{{ r.course }}</p>
              <p class="text-sm text-slate-600 mt-1">{{ r.comment }}</p>
            </div>
          </div>
        </div>
        <EmptyState v-if="!recentReviews.length" icon="⭐" title="Chưa có đánh giá" description="Học viên chưa đánh giá khóa học của bạn." />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import StatCard from '../../components/ui/StatCard.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const auth = useAuthStore()

const stats = [
  { icon: '📚', label: 'Khóa học',    value: '4',    color: 'navy' },
  { icon: '👥', label: 'Học viên',    value: '248',  color: 'emerald' },
  { icon: '⭐', label: 'Đánh giá TB', value: '4.7',  color: 'amber' },
  { icon: '🧩', label: 'Quiz đã tạo', value: '18',   color: 'gold' },
]

const myCourses = ref([
  { id: 1, title: 'IELTS Preparation Course', students: 120, lessons: 30, status: 'published' },
  { id: 2, title: 'Business English',         students: 85,  lessons: 20, status: 'published' },
  { id: 3, title: 'Grammar Fundamentals',     students: 43,  lessons: 25, status: 'published' },
  { id: 4, title: 'Advanced Writing Skills',  students: 0,   lessons: 5,  status: 'draft' },
])

const recentReviews = ref([
  { id: 1, name: 'Minh Anh',  course: 'IELTS Preparation', rating: 5, comment: 'Khóa học rất hay, giảng viên nhiệt tình!' },
  { id: 2, name: 'Bảo Long',  course: 'Business English',  rating: 4, comment: 'Nội dung thực tế, dễ áp dụng.' },
  { id: 3, name: 'Lê Hương',  course: 'Grammar Fundamentals', rating: 5, comment: 'Giải thích rõ ràng, dễ hiểu.' },
])
</script>
