<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-7xl space-y-8">

      <!-- Hero -->
      <section class="hero-card relative overflow-hidden rounded-[36px] px-6 py-8 text-white shadow-[var(--shadow-lg)] sm:px-8 lg:px-10">
        <div class="absolute -left-10 top-10 h-40 w-40 rounded-full bg-sky-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/50">
              Student Dashboard
            </p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">
              Xin chào, {{ user?.name ?? 'Học viên' }} 👋
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75 sm:text-base">
              Tiếp tục hành trình học tiếng Anh của bạn với khóa học, quiz, listening, speaking và toàn bộ tiến độ học tập được tập trung trong một nơi.
            </p>
            <div class="mt-6 flex flex-wrap gap-3 text-sm">
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Học mỗi ngày</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Theo dõi tiến độ</span>
              <span class="rounded-full bg-white/10 px-4 py-2 text-white/85">Nâng cao kỹ năng</span>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row">
            <RouterLink
              to="/courses"
              class="rounded-full bg-white px-5 py-3 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-xl"
            >
              Khám phá khóa học
            </RouterLink>
            <RouterLink
              to="/tests"
              class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white/15"
            >
              Làm bài luyện tập
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
            <div
              class="grid h-14 w-14 place-items-center rounded-2xl text-2xl"
              :class="stat.bg"
            >
              {{ stat.icon }}
            </div>
          </div>
          <p class="mt-4 text-xs font-medium uppercase tracking-[0.18em] theme-text-muted">
            {{ stat.note }}
          </p>
        </article>
      </section>

      <!-- Main -->
      <section class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <!-- Left -->
        <div class="space-y-6">

          <!-- Continue learning -->
          <div class="theme-card rounded-[30px] p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Tiếp tục học</p>
                <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Khóa học đang theo dõi</h2>
              </div>
              <p class="text-sm theme-text-soft">Trở lại bài học gần nhất</p>
            </div>

            <!-- No enrollment -->
            <div v-if="!latestEnrollment" class="mt-6 rounded-[28px] bg-slate-950 p-5 text-center text-white/60 dark:bg-slate-900">
              <p class="text-sm">Bạn chưa đăng ký khóa học nào.</p>
              <RouterLink to="/courses" class="mt-3 inline-block rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-950 transition hover:shadow-xl">
                Khám phá khóa học
              </RouterLink>
            </div>

            <!-- Has enrollment -->
            <div v-else class="mt-6 overflow-hidden rounded-[28px] bg-slate-950 p-5 text-white dark:bg-slate-900">
              <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                  <p class="text-xs uppercase tracking-[0.22em] text-white/45">{{ latestEnrollment.course?.category?.name ?? 'Khóa học' }}</p>
                  <h3 class="mt-2 text-2xl font-semibold">{{ latestEnrollment.course?.title }}</h3>
                  <p class="mt-3 max-w-xl text-sm leading-7 text-white/70">
                    Hãy tiếp tục bài học gần nhất để giữ nhịp độ học tập và hoàn thành mục tiêu.
                  </p>
                </div>
                <div class="rounded-[24px] bg-white/10 p-4 text-center shrink-0">
                  <p class="text-xs uppercase tracking-[0.2em] text-white/45">Tiến độ</p>
                  <p class="mt-2 text-3xl font-semibold">{{ progressMap[latestEnrollment.course_id] ?? 0 }}%</p>
                </div>
              </div>

              <div class="mt-5 h-3 rounded-full bg-white/10">
                <div class="h-3 rounded-full bg-gradient-to-r from-sky-400 to-emerald-400 transition-all" :style="{ width: (progressMap[latestEnrollment.course_id] ?? 0) + '%' }"></div>
              </div>

              <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <RouterLink
                  :to="`/student/courses/${latestEnrollment.course_id}/learn`"
                  class="rounded-full bg-white px-5 py-3 text-center text-sm font-semibold text-slate-950 transition hover:shadow-xl"
                >
                  Tiếp tục học
                </RouterLink>
                <RouterLink
                  to="/student/courses"
                  class="rounded-full border border-white/15 bg-white/10 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-white/15"
                >
                  Xem khóa học của tôi
                </RouterLink>
              </div>
            </div>
          </div>

          <!-- Quick actions -->
          <div class="theme-card rounded-[30px] p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Luyện tập nhanh</p>
                <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Truy cập nhanh các chuyên mục</h2>
              </div>
              <p class="text-sm theme-text-soft">Chọn phần bạn muốn luyện ngay</p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
              <RouterLink
                v-for="link in quickLinks"
                :key="link.to"
                :to="link.to"
                class="quick-link group flex items-center justify-between rounded-[24px] px-4 py-4 transition hover:-translate-y-0.5"
              >
                <div class="flex items-center gap-4">
                  <div class="grid h-12 w-12 place-items-center rounded-2xl text-xl quick-link-icon">
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

          <!-- Today plan -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Kế hoạch hôm nay</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Những việc nên hoàn thành</h2>

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
                <span
                  class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold"
                  :class="task.badgeClass"
                >
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
                  {{ user?.name?.charAt(0)?.toUpperCase() ?? 'S' }}
                </div>
                <div class="min-w-0">
                  <p class="text-lg font-semibold truncate">{{ user?.name ?? 'Học viên' }}</p>
                  <p class="text-sm text-white/65 truncate">{{ user?.email ?? 'student@example.com' }}</p>
                </div>
              </div>

              <div class="mt-5 grid gap-3">
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Vai trò</span>
                  <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300">Học viên</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                  <span class="text-sm text-white/65">Mục tiêu tuần</span>
                  <span class="rounded-full bg-sky-400/15 px-3 py-1 text-xs font-semibold text-sky-300">4 buổi học</span>
                </div>
              </div>
            </div>

            <div class="mt-5 grid gap-3">
              <RouterLink
                to="/student/profile"
                class="profile-btn block rounded-2xl px-4 py-3 text-center text-sm font-semibold transition hover:opacity-80"
              >
                Chỉnh sửa hồ sơ
              </RouterLink>
              <button
                @click="handleLogout"
                class="w-full rounded-2xl px-4 py-3 text-sm font-semibold transition logout-btn"
                type="button"
              >
                Đăng xuất
              </button>
            </div>
          </div>

          <!-- Learning insights -->
          <div class="theme-card rounded-[30px] p-6">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Gợi ý học tập</p>
            <h2 class="mt-3 text-2xl font-semibold tracking-tight theme-text">Mẹo để học hiệu quả hơn</h2>

            <div class="mt-6 space-y-4">
              <div
                v-for="tip in tips"
                :key="tip.title"
                class="tip-item rounded-[24px] p-4"
              >
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
            <h2 class="mt-3 text-2xl font-semibold tracking-tight">
              Làm một bài quiz ngắn để kiểm tra mức độ ghi nhớ
            </h2>
            <p class="mt-3 text-sm leading-7 text-white/75">
              Chỉ mất vài phút mỗi ngày để củng cố kiến thức và duy trì động lực học tập.
            </p>
            <RouterLink
              to="/tests"
              class="mt-6 inline-block rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-950 transition hover:shadow-xl hover:-translate-y-0.5"
            >
              Bắt đầu luyện tập
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
import api from '@/services/axios'

const router = useRouter()
const authStore = useAuthStore()
const user = computed(() => authStore.user)

const apiStats = ref({ enrollments: null, lessons_completed: null, quiz_attempts: null, avg_score: null })
const enrollments = ref([])
const progressMap = ref({})
const latestEnrollment = computed(() => enrollments.value[0] ?? null)

onMounted(async () => {
  try {
    const [statsRes, enrollRes] = await Promise.allSettled([
      statsService.student(),
      api.get('/student/my-courses'),
    ])
    if (statsRes.status === 'fulfilled') apiStats.value = statsRes.value.data
    if (enrollRes.status === 'fulfilled') {
      enrollments.value = enrollRes.value.data?.data ?? enrollRes.value.data ?? []
      await Promise.all(enrollments.value.map(async (e) => {
        try {
          const p = await api.get(`/student/courses/${e.course_id}/progress`)
          progressMap.value[e.course_id] = p.data?.progress_percent ?? 0
        } catch { progressMap.value[e.course_id] = 0 }
      }))
    }
  } catch { /* ignore */ }
})

const stats = computed(() => [
  { label: 'Khóa học đang học', value: apiStats.value.enrollments ?? '—', icon: '📚', note: 'Đang theo dõi', bg: 'bg-sky-100 dark:bg-sky-900/40' },
  { label: 'Bài học hoàn thành', value: apiStats.value.lessons_completed ?? '—', icon: '✅', note: 'Tổng cộng', bg: 'bg-emerald-100 dark:bg-emerald-900/40' },
  { label: 'Quiz đã làm', value: apiStats.value.quiz_attempts ?? '—', icon: '📝', note: 'Lượt làm bài', bg: 'bg-amber-100 dark:bg-amber-900/40' },
  { label: 'Điểm trung bình', value: apiStats.value.avg_score ?? '—', icon: '📈', note: 'Kết quả hiện tại', bg: 'bg-violet-100 dark:bg-violet-900/40' },
])

const quickLinks = [
  { label: 'Khóa học của tôi', icon: '📚', to: '/student/courses', desc: 'Quay lại các khóa học đang theo dõi' },
  { label: 'Từ vựng', icon: '📖', to: '/student/vocabulary', desc: 'Ôn và mở rộng vốn từ theo chủ đề' },
  { label: 'Ngữ pháp', icon: '✍️', to: '/student/grammar', desc: 'Luyện cấu trúc và điểm ngữ pháp quan trọng' },
  { label: 'Listening', icon: '🎧', to: '/student/listening', desc: 'Rèn nghe với audio, transcript và bài tập' },
  { label: 'Speaking', icon: '🎙️', to: '/student/speaking', desc: 'Luyện phản xạ và cải thiện khả năng nói' },
  { label: 'Thông báo', icon: '🔔', to: '/student/notifications', desc: 'Xem cập nhật mới từ hệ thống và khóa học' },
]

const tasks = [
  {
    icon: '🎧',
    title: 'Hoàn thành bài Listening Unit 12',
    desc: 'Nghe audio, xem transcript và trả lời câu hỏi để hoàn thiện bài học hôm nay.',
    badge: '12 phút',
    badgeClass: 'bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-300',
  },
  {
    icon: '📝',
    title: 'Làm Vocabulary Speed Quiz',
    desc: 'Củng cố nhanh từ vựng đã học bằng một bài quiz ngắn.',
    badge: '20 câu',
    badgeClass: 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
  },
  {
    icon: '✅',
    title: 'Rà soát lại Grammar checkpoint',
    desc: 'Ôn lại các điểm ngữ pháp quan trọng trước khi chuyển sang chuyên đề mới.',
    badge: 'Khuyến nghị',
    badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
  },
]

const tips = [
  { icon: '🎯', title: 'Học đều mỗi ngày', desc: 'Duy trì thời lượng học ngắn nhưng đều đặn sẽ hiệu quả hơn học dồn trong một lần.' },
  { icon: '🔁', title: 'Lặp lại bằng quiz', desc: 'Sau khi học lesson, hãy làm quiz ngắn để tăng khả năng ghi nhớ.' },
  { icon: '🗣️', title: 'Kết hợp nghe và nói', desc: 'Listening và Speaking nên đi cùng nhau để tăng phản xạ sử dụng ngôn ngữ.' },
]

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
/* Hero gradient card — dùng chung cho hero section và CTA */
.hero-card {
  background: linear-gradient(135deg, #0f172a 0%, #111827 35%, #0369a1 100%);
}

/* Stat / quick-link / task / tip items dùng CSS vars để tự adapt dark/light */
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
