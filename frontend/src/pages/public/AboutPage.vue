<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <main>
      <section class="relative overflow-hidden px-4 pb-14 pt-28 sm:px-6 lg:px-8 lg:pb-16 lg:pt-32">
        <div class="absolute inset-0">
          <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
          <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative mx-auto max-w-7xl">
          <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
              <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur-md">
                Giới thiệu nền tảng
              </div>
              <h1 class="mt-4 text-4xl font-semibold leading-snug tracking-tight text-white sm:text-5xl">
                <span class="block">Một hệ thống học và thi tiếng Anh online</span>
                <span class="block min-h-[1.4em]">{{ twDisplayed }}<span class="tw-cursor">|</span></span>
              </h1>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                Chúng tôi xây dựng nền tảng này để kết nối khóa học, từ vựng, ngữ pháp, listening, speaking, quiz và theo dõi tiến độ trong cùng một trải nghiệm học tập đồng bộ.
              </p>
              <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-white/60">
                <RouterLink to="/" class="transition hover:text-white">Trang chủ</RouterLink>
                <span>/</span>
                <span class="font-medium text-white/90">Giới thiệu</span>
              </div>
            </div>

            <!-- Right: platform highlights -->
            <div class="hidden lg:block">
              <div class="overflow-hidden rounded-[32px] border border-white/80 bg-white/80 p-6 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">Nền tảng của chúng tôi</p>
                <div class="mt-4 grid grid-cols-2 gap-3">
                  <div v-for="item in aboutHighlights" :key="item.label"
                    class="rounded-2xl bg-slate-50 dark:bg-slate-800 p-4">
                    <span class="text-2xl">{{ item.icon }}</span>
                    <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ item.value }}</p>
                    <p class="text-xs text-slate-400">{{ item.label }}</p>
                  </div>
                </div>
                <div class="mt-4 flex items-center gap-3 rounded-2xl bg-slate-950 px-4 py-3">
                  <div class="grid h-9 w-9 place-items-center rounded-xl bg-white/10 text-lg">🚀</div>
                  <div>
                    <p class="text-sm font-semibold text-white">Đang phát triển liên tục</p>
                    <p class="text-xs text-white/50">Cập nhật tính năng mỗi tuần</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Sứ mệnh</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">              Giúp việc học tiếng Anh trở nên dễ tiếp cận, có lộ trình và dễ duy trì hơn.
            </h2>
            <p class="mt-5 text-base leading-8 text-slate-600 dark:text-slate-400">
              Thay vì phải học rời rạc qua nhiều công cụ khác nhau, người học có thể tiếp cận toàn bộ quá trình học trong một hệ thống thống nhất: học lý thuyết, làm bài tập, luyện nghe, luyện nói, làm quiz và theo dõi tiến bộ theo thời gian.
            </p>
            <p class="mt-4 text-base leading-8 text-slate-600 dark:text-slate-400">
              Mục tiêu của chúng tôi không chỉ là làm một website đẹp, mà là xây dựng một trải nghiệm học tập thực sự rõ ràng, tạo động lực và có thể mở rộng cho học sinh, giáo viên và quản trị viên.
            </p>
          </div>

          <div class="rounded-[36px] border border-slate-200 bg-white p-6 shadow-[0_25px_70px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_25px_70px_rgba(0,0,0,0.2)]">
            <div class="grid gap-4 sm:grid-cols-2">
              <div
                v-for="stat in stats"
                :key="stat.label"
                class="rounded-[28px] bg-slate-50 p-5 dark:bg-slate-800"
              >
                <p class="text-3xl font-semibold text-slate-950 dark:text-white">{{ stat.value }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ stat.label }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-slate-50 px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl">
          <div class="max-w-2xl reveal">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Giá trị cốt lõi</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
              Thiết kế để học tốt hơn, không chỉ để nhìn đẹp hơn.
            </h2>
          </div>

          <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <article
              v-for="value in values"
              :key="value.title"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
              class="reveal tilt-card shine-hover rounded-[30px] border border-slate-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_24px_60px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_16px_40px_rgba(0,0,0,0.2)]"
            >
              <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-xl dark:bg-slate-700">
                {{ value.icon }}
              </div>
              <h3 class="mt-6 text-xl font-semibold text-slate-950 dark:text-white">{{ value.title }}</h3>
              <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">{{ value.description }}</p>
            </article>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl">
          <div class="max-w-3xl reveal">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Hệ thống dành cho ai</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
              Một nền tảng, ba vai trò, cùng hướng tới kết quả học tập tốt hơn.
            </h2>
          </div>

          <div class="mt-10 grid gap-5 lg:grid-cols-3">
            <article
              v-for="role in roles"
              :key="role.title"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
              class="reveal tilt-card shine-hover rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_24px_60px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_16px_40px_rgba(0,0,0,0.2)]"
            >
              <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-xl dark:bg-slate-700">
                {{ role.icon }}
              </div>
              <h3 class="mt-5 text-xl font-semibold text-slate-950 dark:text-white">{{ role.title }}</h3>
              <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">{{ role.description }}</p>
            </article>
          </div>
        </div>
      </section>

      <section class="bg-slate-50 px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#0f172a_0%,#111827_35%,#0369a1_100%)] px-6 py-12 text-white sm:px-8 lg:px-12">
          <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/45">Khám phá ngay</p>
              <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
                Sẵn sàng bắt đầu hành trình học tiếng Anh của bạn?
              </h2>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/75">
                Khám phá khóa học, làm bài luyện tập và theo dõi tiến bộ trên một hệ thống duy nhất.
              </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <RouterLink
                to="/courses"
                class="shine-hover rounded-full bg-white px-5 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:shadow-2xl"
              >
                Xem khóa học
              </RouterLink>
              <RouterLink
                to="/register"
                class="shine-hover rounded-full border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:bg-white/15"
              >
                Tạo tài khoản
              </RouterLink>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { RouterLink } from 'vue-router'
import { useTypewriter } from '@/composables/useTypewriter'
import heroBg from '@/assets/images/home/gioithieu.jpg'
import courseService from '@/services/course.service'
import quizService from '@/services/quiz.service'
import statsService from '@/services/stats.service'

const { displayed: twDisplayed } = useTypewriter('hiện đại, rõ ràng và tạo động lực học mỗi ngày.')

const counts = ref({ courses: 0, quizzes: 0 })
const totalUsers = ref(0)
const avgRating = ref(null)

const aboutHighlights = computed(() => [
  { icon: '🎓', value: totalUsers.value ? `${totalUsers.value.toLocaleString('vi-VN')}+` : '—', label: 'Học viên' },
  { icon: '📚', value: counts.value.courses ? `${counts.value.courses}+` : '—', label: 'Khóa học' },
  { icon: '📝', value: counts.value.quizzes ? `${counts.value.quizzes}` : '—', label: 'Đề luyện' },
  { icon: '⭐', value: avgRating.value !== null ? `${avgRating.value}` : '—', label: 'Đánh giá' },
])

const stats = computed(() => [
  { value: counts.value.courses ? `${counts.value.courses}+` : '—', label: 'Khóa học đang sẵn sàng' },
  { value: counts.value.quizzes ? `${counts.value.quizzes}` : '—', label: 'Quiz và đề luyện tập' },
  { value: totalUsers.value ? `${totalUsers.value.toLocaleString('vi-VN')}+` : '—', label: 'Học viên sử dụng mỗi tháng' },
  { value: '3', label: 'Vai trò hoạt động trong hệ thống' },
])

const values = [
  {
    icon: '🎯',
    title: 'Rõ mục tiêu',
    description: 'Mỗi phần học đều có định hướng rõ ràng để người học biết mình đang học gì và tiến tới đâu.',
  },
  {
    icon: '🧩',
    title: 'Đồng bộ trải nghiệm',
    description: 'Khóa học, quiz, listening, speaking và dashboard được kết nối trong cùng một luồng học tập.',
  },
  {
    icon: '📈',
    title: 'Theo dõi tiến bộ',
    description: 'Người học có thể xem lịch sử, kết quả và tiến độ để học tập có định hướng hơn.',
  },
  {
    icon: '✨',
    title: 'Hiện đại và dễ dùng',
    description: 'Giao diện được thiết kế theo hướng trực quan, cao cấp nhưng vẫn dễ tiếp cận.',
  },
]

const roles = [
  {
    icon: '🎓',
    title: 'Học sinh',
    description: 'Học theo lộ trình, làm quiz, theo dõi tiến độ, luyện nghe và luyện nói trong dashboard cá nhân.',
  },
  {
    icon: '👨‍🏫',
    title: 'Giáo viên',
    description: 'Quản lý khóa học, bài học, câu hỏi quiz và theo dõi hiệu quả học tập của học viên.',
  },
  {
    icon: '🛠️',
    title: 'Quản trị viên',
    description: 'Quản lý toàn bộ người dùng, phân quyền, nội dung hệ thống và các hoạt động vận hành.',
  },
]

let observer = null

onMounted(async () => {
  try {
    const [lessonsRes, quizzesRes, statsRes] = await Promise.allSettled([
      courseService.list({ per_page: 1 }),
      quizService.list({ per_page: 1 }),
      statsService.public(),
    ])
    const getTotal = (r) => r.status === 'fulfilled' ? (r.value.data?.meta?.total ?? r.value.data?.total ?? 0) : 0
    counts.value = { courses: getTotal(lessonsRes), quizzes: getTotal(quizzesRes) }
    if (statsRes.status === 'fulfilled') {
      totalUsers.value = statsRes.value.data?.users ?? 0
      avgRating.value = statsRes.value.data?.avg_rating ?? null
    }
  } catch { /* ignore */ }
  await nextTick()
  initRevealAnimations()
})

onBeforeUnmount(() => {
  if (observer) observer.disconnect()
})

function initRevealAnimations() {
  const els = document.querySelectorAll('.reveal')
  observer = new IntersectionObserver(
    (entries) => { entries.forEach((entry) => { if (entry.isIntersecting) entry.target.classList.add('is-visible') }) },
    { threshold: 0.12 }
  )
  els.forEach((el, index) => {
    el.style.transitionDelay = `${Math.min(index % 4, 3) * 90}ms`
    observer.observe(el)
  })
}

function handleTilt(event) {
  if (window.innerWidth < 1024) return
  const card = event.currentTarget
  const rect = card.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top
  const rotateY = ((x / rect.width) - 0.5) * 10
  const rotateX = ((y / rect.height) - 0.5) * -10
  card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px)`
  card.style.setProperty('--mx', `${x}px`)
  card.style.setProperty('--my', `${y}px`)
}

function resetTilt(event) {
  const card = event.currentTarget
  card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)'
}
</script>

<style scoped>
.tw-cursor { display: inline-block; color: #0ea5e9; font-weight: 300; animation: blink 0.7s step-end infinite; }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.reveal {
  opacity: 0;
  transform: translateY(32px) scale(0.98);
  transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}
.reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }

.tilt-card { position: relative; transform-style: preserve-3d; will-change: transform; }
.tilt-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  pointer-events: none;
  background: radial-gradient(220px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,0.18), transparent 45%);
  opacity: 0;
  transition: opacity .25s ease;
}
.tilt-card:hover::before { opacity: 1; }

.shine-hover { position: relative; overflow: hidden; }
.shine-hover::after {
  content: '';
  position: absolute;
  top: -120%;
  left: -140%;
  width: 60%;
  height: 300%;
  transform: rotate(24deg);
  background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.08) 40%, rgba(255,255,255,0.34) 50%, transparent 60%);
  transition: left .85s ease;
  pointer-events: none;
}
.shine-hover:hover::after { left: 180%; }

@media (prefers-reduced-motion: reduce) {
  .reveal, .shine-hover::after, .tilt-card { animation: none !important; transition: none !important; transform: none !important; }
}
@media (max-width: 1023px) { .tilt-card:hover::before { opacity: 0; } }
</style>