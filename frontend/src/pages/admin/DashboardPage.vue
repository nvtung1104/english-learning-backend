<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-7xl space-y-8">

      <!-- Hero -->
      <section class="hero-card relative overflow-hidden rounded-[36px] px-6 py-8 text-white shadow-[var(--shadow-lg)] sm:px-8 lg:px-10">
        <div class="absolute -left-10 top-10 h-40 w-40 rounded-full bg-violet-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-rose-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/50">Admin Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">
              Xin chào, {{ user?.name ?? 'Admin' }} 👋
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75 sm:text-base">
              Toàn quyền quản trị hệ thống — người dùng, khóa học, danh mục và cấu hình nền tảng học tiếng Anh.
            </p>
            <div class="mt-6 flex flex-wrap gap-3 text-sm">
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Quản trị toàn hệ thống</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Theo dõi số liệu</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Kiểm soát nội dung</span>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row">
            <RouterLink
              to="/admin/users"
              class="rounded-full bg-white px-5 py-3 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-xl"
            >
              Quản lý người dùng
            </RouterLink>
            <RouterLink
              to="/admin/courses"
              class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white/15"
            >
              Quản lý khóa học
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
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm theme-text-muted">{{ stat.label }}</p>
              <p class="mt-4 text-3xl font-semibold theme-text">{{ stat.value }}</p>
            </div>
            <div class="grid h-14 w-14 place-items-center rounded-2xl text-2xl" :class="stat.bg">
              {{ stat.icon }}
            </div>
          </div>
          <p class="mt-4 text-xs font-medium uppercase tracking-[0.18em] theme-text-muted">{{ stat.note }}</p>
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
                <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Quản lý hệ thống</p>
                <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Truy cập nhanh các module</h2>
              </div>
              <p class="text-sm theme-text-soft">Điều hướng đến khu vực cần quản lý</p>
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

          <!-- Recent activity -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Hoạt động hệ thống</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Các việc cần xử lý</h2>

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
                  {{ user?.name?.charAt(0)?.toUpperCase() ?? 'A' }}
                </div>
                <div class="min-w-0">
                  <p class="text-lg font-semibold truncate">{{ user?.name ?? 'Admin' }}</p>
                  <p class="text-sm text-white/65 truncate">{{ user?.email ?? 'admin@example.com' }}</p>
                </div>
              </div>

              <div class="mt-5 grid gap-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Vai trò</span>
                  <span class="rounded-full bg-violet-400/15 px-3 py-1 text-xs font-semibold text-violet-300">Quản trị viên</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Quyền hạn</span>
                  <span class="rounded-full bg-rose-400/15 px-3 py-1 text-xs font-semibold text-rose-300">Toàn quyền</span>
                </div>
              </div>
            </div>

            <div class="mt-5 grid gap-3">
              <RouterLink
                to="/admin/profile"
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

          <!-- System stats detail -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Tổng quan nền tảng</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Số liệu hệ thống</h2>

            <div class="mt-6 space-y-3">
              <div v-for="item in systemInfo" :key="item.label" class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
                <div class="flex items-center gap-3">
                  <span class="text-lg">{{ item.icon }}</span>
                  <span class="text-sm theme-text-soft">{{ item.label }}</span>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="item.badgeClass">
                  {{ item.value }}
                </span>
              </div>
            </div>
          </div>

          <!-- CTA -->
          <div class="hero-card overflow-hidden rounded-[32px] p-6 text-white shadow-[var(--shadow-md)]">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/45">Action</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight">Kiểm tra và duyệt nội dung mới</h2>
            <p class="mt-3 text-sm leading-7 text-white/75">
              Xem xét các khóa học, bài học và tài khoản mới đang chờ phê duyệt từ hệ thống.
            </p>
            <RouterLink
              to="/admin/courses"
              class="mt-6 inline-block rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:shadow-xl hover:-translate-y-0.5"
            >
              Xem nội dung chờ duyệt
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

const apiStats = ref({ users: null, courses: null, quizzes: null, avg_rating: null })

onMounted(async () => {
  try {
    const res = await statsService.public()
    apiStats.value = res.data
  } catch { /* ignore */ }
})

const stats = computed(() => [
  { label: 'Người dùng', value: apiStats.value.users ?? '—', icon: '👥', note: 'Tổng tài khoản', bg: 'bg-sky-100 dark:bg-sky-900/40' },
  { label: 'Khóa học', value: apiStats.value.courses ?? '—', icon: '📚', note: 'Đang hoạt động', bg: 'bg-emerald-100 dark:bg-emerald-900/40' },
  { label: 'Quiz', value: apiStats.value.quizzes ?? '—', icon: '📝', note: 'Tổng bài kiểm tra', bg: 'bg-amber-100 dark:bg-amber-900/40' },
  { label: 'Đánh giá TB', value: apiStats.value.avg_rating ?? '—', icon: '⭐', note: 'Điểm trung bình', bg: 'bg-violet-100 dark:bg-violet-900/40' },
])

const quickLinks = [
  { label: 'Người dùng', icon: '👥', to: '/admin/users', desc: 'Quản lý tài khoản học viên và giáo viên' },
  { label: 'Khóa học', icon: '📚', to: '/admin/courses', desc: 'Duyệt và quản lý toàn bộ khóa học' },
  { label: 'Danh mục', icon: '🏷️', to: '/admin/categories', desc: 'Phân loại và tổ chức nội dung học' },
  { label: 'Cấp độ', icon: '📊', to: '/admin/levels', desc: 'Thiết lập cấp độ học từ cơ bản đến nâng cao' },
  { label: 'Thông báo', icon: '🔔', to: '/admin/notifications', desc: 'Gửi thông báo đến người dùng hệ thống' },
]

const tasks = [
  {
    icon: '🔍',
    title: 'Duyệt khóa học mới',
    desc: 'Kiểm tra và phê duyệt các khóa học vừa được giáo viên tạo mới.',
    badge: 'Cần xử lý',
    badgeClass: 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
  },
  {
    icon: '👤',
    title: 'Xác minh tài khoản giáo viên',
    desc: 'Xem xét hồ sơ và cấp quyền giảng dạy cho các tài khoản đang chờ.',
    badge: 'Ưu tiên',
    badgeClass: 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300',
  },
  {
    icon: '📊',
    title: 'Xem báo cáo hệ thống',
    desc: 'Theo dõi số liệu tăng trưởng người dùng và hoạt động học tập.',
    badge: 'Hàng tuần',
    badgeClass: 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300',
  },
]

const systemInfo = [
  { icon: '🟢', label: 'Trạng thái hệ thống', value: 'Hoạt động', badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' },
  { icon: '🔐', label: 'Bảo mật', value: 'Bình thường', badgeClass: 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300' },
  { icon: '💾', label: 'Database', value: 'Ổn định', badgeClass: 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300' },
  { icon: '📧', label: 'Mail service', value: 'Đang chạy', badgeClass: 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' },
]

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.hero-card {
  background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #4c1d95 100%);
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

.info-row {
  border: 1px solid var(--border);
  background: var(--surface-muted);
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
