<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-7xl space-y-8">

      <!-- Hero -->
      <section class="hero-card relative overflow-hidden rounded-[36px] px-6 py-8 text-white shadow-[var(--shadow-lg)] sm:px-8 lg:px-10">
        <div class="absolute -left-10 top-10 h-40 w-40 rounded-full bg-blue-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-indigo-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/50">Teacher Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">
              Xin chào, {{ user?.name ?? 'Giáo viên' }} 👋
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75 sm:text-base">
              Quản lý khóa học, bài học, quiz và theo dõi quá trình học tập của học viên trong một giao diện tập trung, rõ ràng và hiện đại.
            </p>
            <div class="mt-6 flex flex-wrap gap-3 text-sm">
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Giảng dạy hiệu quả</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Theo dõi học viên</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Tối ưu nội dung</span>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row">
            <RouterLink
              to="/teacher/courses/create"
              class="rounded-full bg-white px-5 py-3 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-xl"
            >
              + Tạo khóa học mới
            </RouterLink>
            <RouterLink
              to="/teacher/profile"
              class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white/15"
            >
              Hồ sơ giáo viên
            </RouterLink>
          </div>
        </div>
      </section>

      <!-- Stats -->
      <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <article
          v-for="stat in stats"
          :key="stat.label"
          class="theme-card group rounded-[30px] p-6 transition hover:-translate-y-1 hover:shadow-[var(--shadow-md)]"
        >
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <p class="text-sm theme-text-muted">{{ stat.label }}</p>
              <p class="mt-3 text-3xl font-semibold theme-text">{{ stat.value }}</p>
            </div>
            <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl text-xl" :class="stat.bg">
              {{ stat.icon }}
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2">
            <div class="h-1 flex-1 rounded-full" style="background: var(--border)">
              <div class="h-1 rounded-full w-2/3" :class="stat.barClass"></div>
            </div>
            <p class="text-xs font-medium theme-text-muted shrink-0">{{ stat.note }}</p>
          </div>
        </article>
      </section>

      <!-- Main -->
      <section class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <!-- Left -->
        <div class="space-y-6">

          <!-- Quick links -->
          <div class="theme-card rounded-[30px] p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Quản lý nhanh</p>
                <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Các khu vực dùng thường xuyên</h2>
              </div>
              <p class="text-sm theme-text-soft">Truy cập nhanh các module chính</p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
              <RouterLink
                v-for="link in quickLinks"
                :key="link.to"
                :to="link.to"
                class="quick-link group flex items-center justify-between rounded-[24px] px-4 py-4 transition hover:-translate-y-0.5"
              >
                <div class="flex items-center gap-4">
                  <div class="quick-link-icon grid h-12 w-12 place-items-center rounded-2xl text-xl">
                    {{ link.icon }}
                  </div>
                  <div>
                    <p class="font-semibold theme-text">{{ link.label }}</p>
                    <p class="mt-1 text-sm theme-text-soft">{{ link.desc }}</p>
                  </div>
                </div>
                <span class="theme-text-muted transition group-hover:translate-x-1">→</span>
              </RouterLink>
            </div>
          </div>

          <!-- Tasks -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Hoạt động hôm nay</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Gợi ý công việc nên làm</h2>

            <div class="mt-6 space-y-4">
              <div
                v-for="task in tasks"
                :key="task.title"
                class="task-item flex items-start justify-between gap-4 rounded-[24px] px-4 py-4"
              >
                <div class="flex items-start gap-4">
                  <div class="task-icon grid h-11 w-11 place-items-center rounded-2xl text-lg shrink-0">
                    {{ task.icon }}
                  </div>
                  <div>
                    <h3 class="font-semibold theme-text">{{ task.title }}</h3>
                    <p class="mt-1 text-sm leading-7 theme-text-soft">{{ task.desc }}</p>
                  </div>
                </div>
                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold" :class="task.badgeClass">
                  {{ task.badge }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right -->
        <div class="space-y-6">

          <!-- Account -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Tài khoản</p>

            <div class="mt-5 rounded-[26px] bg-slate-950 p-5 text-white dark:bg-slate-900">
              <div class="flex items-center gap-4">
                <img
                  v-if="user?.avatar"
                  :src="$storage(user.avatar)"
                  :alt="user?.name"
                  class="h-14 w-14 rounded-full object-cover shrink-0"
                />
                <div v-else class="grid h-14 w-14 place-items-center rounded-full bg-white text-lg font-semibold text-slate-950 shrink-0">
                  {{ user?.name?.charAt(0)?.toUpperCase() ?? 'T' }}
                </div>
                <div class="min-w-0">
                  <p class="text-lg font-semibold truncate">{{ user?.name ?? 'Giáo viên' }}</p>
                  <p class="text-sm text-white/65 truncate">{{ user?.email ?? 'teacher@example.com' }}</p>
                </div>
              </div>

              <div class="mt-5 grid gap-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Vai trò</span>
                  <span class="rounded-full bg-sky-400/15 px-3 py-1 text-xs font-semibold text-sky-300">Giáo viên</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Trạng thái</span>
                  <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300">Hoạt động</span>
                </div>
              </div>
            </div>

            <div class="mt-5 grid gap-3">
              <RouterLink
                to="/teacher/profile"
                class="profile-btn block rounded-2xl px-4 py-3 text-center text-sm font-semibold transition"
              >
                Chỉnh sửa hồ sơ
              </RouterLink>
              <button
                @click="handleLogout"
                class="logout-btn w-full rounded-2xl px-4 py-3 text-sm font-semibold transition"
                type="button"
              >
                Đăng xuất
              </button>
            </div>
          </div>

          <!-- Tips -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Gợi ý cho giáo viên</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Mẹo vận hành nội dung hiệu quả</h2>

            <div class="mt-6 space-y-4">
              <div v-for="tip in tips" :key="tip.title" class="tip-item rounded-[24px] p-4">
                <div class="flex items-start gap-4">
                  <div class="tip-icon grid h-11 w-11 place-items-center rounded-2xl text-lg shrink-0">
                    {{ tip.icon }}
                  </div>
                  <div>
                    <h3 class="font-semibold theme-text">{{ tip.title }}</h3>
                    <p class="mt-1 text-sm leading-7 theme-text-soft">{{ tip.desc }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- CTA -->
          <div class="hero-card overflow-hidden rounded-[32px] p-6 text-white shadow-[var(--shadow-md)]">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/45">Action</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight">Cập nhật nội dung mới cho khóa học của bạn</h2>
            <p class="mt-3 text-sm leading-7 text-white/75">
              Thêm lesson, material hoặc quiz mới để tăng trải nghiệm học tập cho học viên.
            </p>
            <RouterLink
              to="/teacher/courses/create"
              class="mt-6 inline-block rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:shadow-xl hover:-translate-y-0.5"
            >
              Tạo nội dung mới
            </RouterLink>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import statsService from '@/services/stats.service'

const router = useRouter()
const authStore = useAuthStore()
const user = computed(() => authStore.user)

const apiStats = ref({ courses: null, students: null, quizzes: null, avg_rating: null })

onMounted(async () => {
  try {
    const res = await statsService.teacher()
    apiStats.value = res.data
  } catch { /* ignore */ }
})

const stats = computed(() => [
  { label: 'Khóa học', value: apiStats.value.courses ?? '—', icon: '📚', note: 'Đang quản lý', bg: 'bg-sky-100 dark:bg-sky-900/40', barClass: 'bg-sky-400' },
  { label: 'Học viên', value: apiStats.value.students ?? '—', icon: '👥', note: 'Đã ghi danh', bg: 'bg-emerald-100 dark:bg-emerald-900/40', barClass: 'bg-emerald-400' },
  { label: 'Quiz', value: apiStats.value.quizzes ?? '—', icon: '📝', note: 'Đang hoạt động', bg: 'bg-amber-100 dark:bg-amber-900/40', barClass: 'bg-amber-400' },
  { label: 'Đánh giá TB', value: apiStats.value.avg_rating ?? '—', icon: '⭐', note: 'Điểm trung bình', bg: 'bg-violet-100 dark:bg-violet-900/40', barClass: 'bg-violet-400' },
])

const quickLinks = [
  { label: 'Khóa học của tôi', icon: '📚', to: '/teacher/courses', desc: 'Xem và quản lý các khóa học đã tạo' },
  { label: 'Quản lý bài học', icon: '📖', to: '/teacher/lessons', desc: 'Cập nhật lesson, tài liệu và cấu trúc nội dung' },
  { label: 'Quản lý quiz', icon: '📝', to: '/teacher/quizzes', desc: 'Tạo bài kiểm tra và hệ thống câu hỏi' },
  { label: 'Học viên đăng ký', icon: '👥', to: '/teacher/enrollments', desc: 'Theo dõi học viên tham gia khóa học' },
  { label: 'Đánh giá khóa học', icon: '⭐', to: '/teacher/reviews', desc: 'Xem phản hồi và cải thiện nội dung' },
  { label: 'Thông báo', icon: '🔔', to: '/teacher/notifications', desc: 'Quản lý thông báo liên quan tới lớp học' },
]

const tasks = [
  {
    icon: '🧩',
    title: 'Rà soát lại lesson mới thêm',
    desc: 'Kiểm tra nội dung bài học, material và luồng section trước khi xuất bản.',
    badge: 'Ưu tiên',
    badgeClass: 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
  },
  {
    icon: '📊',
    title: 'Xem tiến độ học viên',
    desc: 'Phân tích tỷ lệ hoàn thành và xác định nơi học viên gặp khó khăn.',
    badge: 'Phân tích',
    badgeClass: 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300',
  },
  {
    icon: '✅',
    title: 'Cập nhật quiz cho khóa học',
    desc: 'Bổ sung thêm câu hỏi để tăng chất lượng luyện tập cuối bài.',
    badge: 'Khuyến nghị',
    badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
  },
]

const tips = [
  { icon: '🎯', title: 'Giữ lesson ngắn và rõ mục tiêu', desc: 'Bài học ngắn, tập trung và đi thẳng vào trọng tâm sẽ giúp học viên duy trì động lực tốt hơn.' },
  { icon: '📌', title: 'Đặt quiz sau từng phần học', desc: 'Quiz ngắn sau mỗi lesson giúp củng cố kiến thức và tăng tỷ lệ ghi nhớ.' },
  { icon: '💬', title: 'Theo dõi phản hồi thường xuyên', desc: 'Review từ học viên là tín hiệu quan trọng để cải tiến nội dung theo nhu cầu thực tế.' },
]

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.hero-card {
  background: linear-gradient(135deg, #0f172a 0%, #111827 35%, #1d4ed8 100%);
}

.quick-link {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.quick-link:hover {
  border-color: var(--border-strong);
  background: var(--surface-strong);
  box-shadow: var(--shadow-sm);
}
.quick-link-icon {
  background: var(--surface-strong);
  box-shadow: var(--shadow-sm);
}

.task-item {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.task-icon {
  background: var(--surface-strong);
  box-shadow: var(--shadow-sm);
}

.tip-item {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.tip-icon {
  background: var(--surface-strong);
  box-shadow: var(--shadow-sm);
}

.profile-btn {
  border: 1px solid var(--border);
  color: var(--text);
  background: var(--surface-muted);
}
.profile-btn:hover {
  background: var(--surface-strong);
}

.logout-btn {
  border: 1px solid color-mix(in srgb, var(--danger) 25%, var(--border));
  color: var(--danger);
  background: color-mix(in srgb, var(--danger) 6%, var(--surface-muted));
}
.logout-btn:hover {
  background: color-mix(in srgb, var(--danger) 10%, var(--surface-muted));
}
</style>
