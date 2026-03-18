<template>
  <AdminPageLayout title="Dashboard" subtitle="Tổng quan hệ thống">
    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard v-for="s in stats" :key="s.label" v-bind="s" />
    </div>

    <div class="grid lg:grid-cols-3 gap-5">
      <!-- Recent users -->
      <div class="lg:col-span-2 card overflow-hidden">
        <div class="px-5 py-4 border-b border-[var(--border-soft)] flex items-center justify-between">
          <h3 class="font-display font-bold text-navy text-base">Người dùng mới</h3>
          <RouterLink to="/admin/users" class="text-xs text-gold font-semibold hover:underline">Xem tất cả →</RouterLink>
        </div>
        <div class="divide-y divide-[var(--border-soft)]">
          <div v-for="u in recentUsers" :key="u.id" class="px-5 py-3.5 flex items-center gap-3 hover:bg-cream-soft/50 transition-colors">
            <div class="w-9 h-9 rounded-full bg-navy flex items-center justify-center text-white text-sm font-bold shrink-0">
              {{ u.name.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy text-sm truncate">{{ u.name }}</p>
              <p class="text-xs text-slate-400 truncate">{{ u.email }}</p>
            </div>
            <AppBadge :variant="u.role === 'teacher' ? 'warning' : 'primary'">{{ u.role }}</AppBadge>
            <span class="text-xs text-slate-400 shrink-0">{{ u.joined }}</span>
          </div>
        </div>
      </div>

      <!-- Quick actions + recent activity -->
      <div class="space-y-5">
        <div class="card p-5">
          <h3 class="font-display font-bold text-navy text-sm mb-4">Thao tác nhanh</h3>
          <div class="grid grid-cols-2 gap-2">
            <RouterLink v-for="a in quickActions" :key="a.to" :to="a.to"
              class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-cream-soft hover:bg-parchment border border-[var(--border)] transition-all text-center">
              <span class="text-2xl">{{ a.icon }}</span>
              <span class="text-xs font-semibold text-navy">{{ a.label }}</span>
            </RouterLink>
          </div>
        </div>

        <div class="card p-5">
          <h3 class="font-display font-bold text-navy text-sm mb-4">Hoạt động gần đây</h3>
          <div class="space-y-3">
            <div v-for="a in recentActivity" :key="a.id" class="flex items-start gap-3">
              <div class="w-7 h-7 rounded-xl bg-cream-soft flex items-center justify-center text-sm shrink-0">{{ a.icon }}</div>
              <div>
                <p class="text-xs font-medium text-navy leading-snug">{{ a.text }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ a.time }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent enrollments -->
    <div class="card overflow-hidden">
      <div class="px-5 py-4 border-b border-[var(--border-soft)] flex items-center justify-between">
        <h3 class="font-display font-bold text-navy text-base">Đăng ký học gần đây</h3>
        <RouterLink to="/admin/enrollments" class="text-xs text-gold font-semibold hover:underline">Xem tất cả →</RouterLink>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Học viên</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa học</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ngày đăng ký</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="e in recentEnrollments" :key="e.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-3.5 font-medium text-navy">{{ e.student }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ e.course }}</td>
              <td class="px-5 py-3.5 text-slate-500 text-xs">{{ e.date }}</td>
              <td class="px-5 py-3.5"><AppBadge variant="success">Đã đăng ký</AppBadge></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminPageLayout>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import AdminPageLayout from '../../components/admin/AdminPageLayout.vue'
import StatCard from '../../components/ui/StatCard.vue'
import AppBadge from '../../components/ui/AppBadge.vue'

const stats = [
  { icon: '👥', label: 'Tổng người dùng', value: '1,248', trend: 12,  color: 'navy' },
  { icon: '📚', label: 'Khóa học',        value: '48',    trend: 5,   color: 'gold' },
  { icon: '🎓', label: 'Đăng ký học',     value: '3,892', trend: 18,  color: 'emerald' },
  { icon: '🧩', label: 'Lượt làm quiz',   value: '12,450', trend: -3, color: 'amber' },
]

const recentUsers = ref([
  { id: 1, name: 'Nguyễn Minh Anh', email: 'anh@example.com', role: 'student', joined: '2 giờ trước' },
  { id: 2, name: 'Trần Bảo Long',   email: 'long@example.com', role: 'student', joined: '5 giờ trước' },
  { id: 3, name: 'Lê Thị Hương',    email: 'huong@example.com', role: 'teacher', joined: 'Hôm qua' },
  { id: 4, name: 'Phạm Văn Đức',    email: 'duc@example.com', role: 'student', joined: 'Hôm qua' },
])

const quickActions = [
  { icon: '👤', label: 'Thêm user',    to: '/admin/users' },
  { icon: '📚', label: 'Thêm khóa học', to: '/admin/courses' },
  { icon: '🧩', label: 'Tạo quiz',     to: '/admin/quizzes' },
  { icon: '🔔', label: 'Thông báo',    to: '/admin/notifications' },
]

const recentActivity = ref([
  { id: 1, icon: '👤', text: 'Người dùng mới đăng ký: Nguyễn Minh Anh', time: '2 giờ trước' },
  { id: 2, icon: '📚', text: 'Khóa học mới được tạo: IELTS Advanced', time: '4 giờ trước' },
  { id: 3, icon: '⭐', text: 'Đánh giá mới cho Business English', time: '6 giờ trước' },
])

const recentEnrollments = ref([
  { id: 1, student: 'Nguyễn Minh Anh', course: 'IELTS Preparation', date: 'Hôm nay, 10:30' },
  { id: 2, student: 'Trần Bảo Long',   course: 'Business English',  date: 'Hôm nay, 09:15' },
  { id: 3, student: 'Lê Thị Hương',    course: 'Grammar A1-B2',     date: 'Hôm qua, 16:00' },
])
</script>
