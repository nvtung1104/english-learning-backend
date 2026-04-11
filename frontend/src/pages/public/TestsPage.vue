<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <main>
      <section class="relative overflow-hidden px-4 pb-12 pt-28 sm:px-6 lg:px-8 lg:pb-16 lg:pt-32">
        <div class="absolute inset-0">
          <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
          <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative mx-auto max-w-7xl">
          <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
              <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur-md">
                Quiz và đề thi
              </div>
              <h1 class="mt-4 text-4xl font-semibold leading-snug tracking-tight text-white sm:text-5xl">
                <span class="block">Luyện tập với các bài quiz và đề thi thử</span>
                <span class="block min-h-[1.4em]">{{ twDisplayed }}<span class="tw-cursor">|</span></span>
              </h1>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                Từ quiz nhanh đến mock test chuyên sâu, mỗi bài đều có cấu trúc gọn gàng, trải nghiệm làm bài mượt và dễ theo dõi kết quả.
              </p>
              <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-white/60">
                <RouterLink to="/" class="transition hover:text-white">Trang chủ</RouterLink>
                <span>/</span>
                <span class="font-medium text-white/90">Đề thi</span>
              </div>
            </div>

            <!-- Right: mock test preview -->
            <div class="hidden lg:block">
              <div class="overflow-hidden rounded-[32px] border border-white/80 bg-white/80 p-5 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
                <div class="flex items-center justify-between">
                  <p class="text-xs font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">Mock Test Preview</p>
                  <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Live</span>
                </div>
                <div class="mt-4 rounded-2xl bg-slate-950 p-4 text-white">
                  <p class="text-xs text-white/50">Câu hỏi trong ngày</p>
                  <template v-if="dailyQuestion">
                    <p class="mt-2 text-sm font-medium leading-6">{{ dailyQuestion.question }}</p>
                    <div class="mt-3 space-y-2">
                      <button
                        v-for="(ans, i) in dailyQuestion.answers"
                        :key="ans.id"
                        @click="selectedAnswer = ans.id"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm transition text-left"
                        :class="
                          selectedAnswer === ans.id
                            ? ans.is_correct ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300'
                            : selectedAnswer !== null && ans.is_correct ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/8 text-white/70'
                        "
                      >
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-xs font-bold"
                          :class="
                            selectedAnswer === ans.id
                              ? ans.is_correct ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'
                              : selectedAnswer !== null && ans.is_correct ? 'bg-emerald-500 text-white' : 'bg-white/10'
                          ">{{ ['A','B','C','D'][i] }}</span>
                        {{ ans.answer_text }}
                      </button>
                    </div>
                  </template>
                  <template v-else>
                    <p class="mt-2 text-sm font-medium leading-6">Choose the correct answer: "She ___ to school every day."</p>
                    <div class="mt-3 space-y-2">
                      <div v-for="(opt, i) in ['go', 'goes', 'going', 'gone']" :key="i"
                        class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm transition"
                        :class="i === 1 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/8 text-white/70'">
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-xs font-bold"
                          :class="i === 1 ? 'bg-emerald-500 text-white' : 'bg-white/10'">{{ ['A','B','C','D'][i] }}</span>
                        {{ opt }}
                      </div>
                    </div>
                  </template>
                </div>
                <div class="mt-3 grid grid-cols-3 gap-2">
                  <div v-for="s in testStats" :key="s.label" class="rounded-2xl bg-slate-50 dark:bg-slate-800 px-3 py-3 text-center">
                    <p class="text-lg font-semibold text-slate-950 dark:text-white">{{ s.value }}</p>
                    <p class="text-xs text-slate-400">{{ s.label }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl">
          <!-- Filter bar -->
          <div class="rounded-[32px] border border-slate-200 bg-white p-5 shadow-[0_20px_60px_rgba(15,23,42,0.06)] transition-colors duration-300 sm:p-6 dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_20px_60px_rgba(0,0,0,0.2)]">
            <div class="grid gap-4 lg:grid-cols-[1.5fr_1fr_auto]">
              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tìm kiếm</label>
                <input
                  v-model="search"
                  type="text"
                  placeholder="Tìm theo tên bài thi..."
                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  @input="onSearchInput"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Sắp xếp</label>
                <select
                  v-model="sortBy"
                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  @change="fetchTests"
                >
                  <option value="id_desc">Mới nhất</option>
                  <option value="id_asc">Cũ nhất</option>
                  <option value="title_asc">Tên A-Z</option>
                  <option value="time_asc">Thời gian ngắn hơn</option>
                </select>
              </div>

              <div class="flex items-end">
                <button
                  @click="resetFilters"
                  class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700"
                >
                  Xóa lọc
                </button>
              </div>
            </div>
          </div>

          <!-- Result info -->
          <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p class="text-sm font-semibold text-slate-700 dark:text-slate-100">{{ total }} bài phù hợp</p>
              <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Chọn bài phù hợp để luyện tập nhanh hoặc làm thử như thi thật.</p>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="mt-10 flex justify-center py-16">
            <div class="h-10 w-10 animate-spin rounded-full border-4 border-slate-200 border-t-slate-950 dark:border-slate-700 dark:border-t-white"></div>
          </div>

          <!-- Grid -->
          <div v-else-if="tests.length" class="mt-10 grid gap-6 lg:grid-cols-3">
            <article
              v-for="test in tests"
              :key="test.id"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
              class="reveal tilt-card shine-hover overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_26px_70px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_26px_70px_rgba(0,0,0,0.3)]"
            >
              <div class="relative overflow-hidden rounded-t-[32px] bg-slate-950 p-6 text-white">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.28),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(16,185,129,0.18),_transparent_25%)]"></div>
                <div class="relative">
                  <h2 class="text-2xl font-semibold">{{ test.title }}</h2>
                  <p v-if="test.time_limit" class="mt-2 text-sm text-white/60">{{ test.time_limit }} phút</p>
                </div>
              </div>

              <div class="p-6">
                <div class="grid grid-cols-2 gap-3 text-sm">
                  <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-700/50">
                    <p class="text-slate-500 dark:text-slate-400">Thời gian</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100">{{ test.time_limit ? `${test.time_limit} phút` : '—' }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-700/50">
                    <p class="text-slate-500 dark:text-slate-400">Câu hỏi</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100">{{ test.questions_count ?? '—' }} câu</p>
                  </div>
                </div>

                <div class="mt-6 flex items-center justify-between gap-4">
                  <RouterLink
                    :to="`/tests/${test.id}`"
                    class="shine-hover rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:shadow-xl dark:bg-white dark:text-slate-950"
                  >
                    Làm thử ngay
                  </RouterLink>
                </div>
              </div>
            </article>
          </div>

          <!-- Empty -->
          <div
            v-else
            class="mt-10 rounded-[32px] border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center dark:border-slate-600 dark:bg-slate-800/40"
          >
            <div class="mx-auto max-w-xl">
              <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-2xl dark:bg-slate-700">📝</div>
              <h3 class="mt-6 text-2xl font-semibold text-slate-950 dark:text-white">Không tìm thấy bài phù hợp</h3>
              <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">Hãy thử đổi từ khóa hoặc xóa bộ lọc để xem thêm.</p>
              <button
                @click="resetFilters"
                class="mt-6 rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:shadow-xl dark:bg-white dark:text-slate-950"
              >
                Xóa bộ lọc
              </button>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="lastPage > 1" class="mt-10 flex justify-center gap-2">
            <button
              v-for="page in lastPage"
              :key="page"
              @click="goToPage(page)"
              :class="[
                'rounded-full px-4 py-2 text-sm font-semibold transition',
                currentPage === page
                  ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950'
                  : 'border border-slate-300 text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700',
              ]"
            >
              {{ page }}
            </button>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#0f172a_0%,#111827_35%,#0369a1_100%)] px-6 py-12 text-white sm:px-8 lg:px-12">
          <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/45">Sẵn sàng bắt đầu</p>
              <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
                Chọn một bài phù hợp và bắt đầu luyện tập ngay hôm nay.
              </h2>
            </div>
            <div class="rounded-[32px] border border-white/10 bg-white/8 p-6 backdrop-blur">
              <div class="space-y-4">
                <RouterLink
                  to="/register"
                  class="shine-hover block w-full rounded-full bg-white px-5 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:shadow-2xl"
                >
                  Tạo tài khoản miễn phí
                </RouterLink>
                <RouterLink
                  to="/courses"
                  class="shine-hover block w-full rounded-full border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:bg-white/15"
                >
                  Xem khóa học
                </RouterLink>
              </div>
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
import heroBg from '@/assets/images/home/dethi.jpg'
import quizService from '@/services/quiz.service'
import statsService from '@/services/stats.service'
import api from '@/services/axios'

const { displayed: twDisplayed } = useTypewriter('được thiết kế rõ ràng, hiện đại và dễ tập trung.')

const totalTests = ref(0)
const completionRate = ref(null)
const avgTime = ref(null)
const dailyQuestion = ref(null)
const selectedAnswer = ref(null)

const testStats = computed(() => [
  { value: totalTests.value || '—', label: 'Đề thi' },
  { value: avgTime.value ? `${avgTime.value} phút` : '—', label: 'Phút TB' },
  { value: completionRate.value !== null ? `${completionRate.value}%` : '—', label: 'Hoàn thành' },
])

const tests = ref([])
const loading = ref(true)
const total = ref(0)
const currentPage = ref(1)
const lastPage = ref(1)

const search = ref('')
const sortBy = ref('id_desc')

let searchTimer = null

function onSearchInput() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchTests(), 400)
}

function buildParams() {
  const [sort_by, sort_order] = sortBy.value.split('_')
  const params = { page: currentPage.value, sort_by, sort_order }
  if (search.value.trim()) params.search = search.value.trim()
  return params
}

async function fetchTests() {
  loading.value = true
  try {
    const res = await quizService.list(buildParams())
    const payload = res.data
    tests.value = payload.data ?? []
    total.value = payload.meta?.total ?? tests.value.length
    currentPage.value = payload.meta?.current_page ?? 1
    lastPage.value = payload.meta?.last_page ?? 1
  } catch {
    tests.value = []
  } finally {
    loading.value = false
  }
}

function goToPage(page) {
  currentPage.value = page
  fetchTests()
}

function resetFilters() {
  search.value = ''
  sortBy.value = 'id_desc'
  currentPage.value = 1
  fetchTests()
}

let observer = null

onMounted(async () => {
  try {
    const [statsRes, dailyRes] = await Promise.allSettled([
      statsService.public(),
      api.get('/quiz-questions/daily'),
    ])
    if (statsRes.status === 'fulfilled') {
      totalTests.value = statsRes.value.data?.quizzes ?? 0
      completionRate.value = statsRes.value.data?.completion_rate ?? null
      avgTime.value = statsRes.value.data?.avg_time ?? null
    }
    if (dailyRes.status === 'fulfilled') {
      dailyQuestion.value = dailyRes.value.data
    }
  } catch { /* ignore */ }
  await fetchTests()
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
.tw-cursor {
  display: inline-block;
  color: #0ea5e9;
  font-weight: 300;
  animation: blink 0.7s step-end infinite;
}
@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

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
