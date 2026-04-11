<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <!-- Hero -->
    <section class="relative overflow-hidden px-4 pb-14 pt-28 sm:px-6 lg:px-8 lg:pb-20 lg:pt-32">
      <div class="absolute inset-0">
        <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
        <div class="absolute inset-0 bg-slate-950/55 dark:bg-black/60"></div>
      </div>

      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.22),transparent_28%),radial-gradient(circle_at_80%_20%,rgba(16,185,129,0.14),transparent_22%)]"></div>
      <div class="absolute left-[-60px] top-20 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl"></div>
      <div class="absolute right-[-80px] top-24 h-80 w-80 rounded-full bg-cyan-300/15 blur-3xl"></div>
      <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-emerald-300/10 blur-3xl"></div>

      <div class="relative mx-auto max-w-7xl">
        <div class="grid items-center gap-10 lg:grid-cols-[1.08fr_0.92fr]">
          <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-white/85 backdrop-blur-md">
              Danh sách khóa học
            </div>

            <h1 class="mt-5 text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl xl:text-6xl">
              <span class="block">Chọn khóa học phù hợp với</span>
              <span class="block min-h-[1.35em]">
                {{ twDisplayed }}<span class="tw-cursor">|</span>
              </span>
            </h1>

            <p class="mt-5 max-w-2xl text-base leading-8 text-white/75 sm:text-lg">
              Từ nền tảng cơ bản đến luyện thi chuyên sâu, mỗi khóa học đều được thiết kế với lộ trình rõ ràng, giao diện trực quan và trải nghiệm học tập hiện đại.
            </p>

            <div class="mt-7 flex flex-wrap items-center gap-3 text-sm text-white/60">
              <RouterLink to="/" class="transition hover:text-white">Trang chủ</RouterLink>
              <span>/</span>
              <span class="font-medium text-white/90">Khóa học</span>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
              <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur">
                {{ meta.total ?? courses.length }} khóa học
              </div>
              <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur">
                Lộ trình rõ ràng
              </div>
              <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/80 backdrop-blur">
                Học mọi lúc
              </div>
            </div>
          </div>

          <!-- Hero side card -->
          <div class="hidden lg:block">
            <div class="rounded-[34px] border border-white/15 bg-white/10 p-5 shadow-[0_30px_80px_rgba(15,23,42,0.18)] backdrop-blur-2xl">
              <div class="rounded-[28px] border border-white/15 bg-white/85 p-5 dark:bg-slate-900/80">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">
                      Nổi bật
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">
                      Học theo mục tiêu
                    </h2>
                  </div>
                  <div class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-950 text-white dark:bg-white dark:text-slate-950">
                    ✨
                  </div>
                </div>

                <div class="mt-5 space-y-3">
                  <div
                    v-for="item in heroCards"
                    :key="item.label"
                    class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/80"
                  >
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-slate-950 text-lg text-white dark:bg-white dark:text-slate-950">
                      {{ item.icon }}
                    </div>
                    <div>
                      <p class="font-semibold text-slate-950 dark:text-white">{{ item.label }}</p>
                      <p class="text-xs text-slate-500 dark:text-slate-400">{{ item.sub }}</p>
                    </div>
                    <span class="ml-auto rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300">
                      {{ item.badge }}
                    </span>
                  </div>
                </div>

                <div class="mt-4 rounded-2xl bg-slate-950 px-4 py-4 text-center text-sm font-semibold text-white dark:bg-white dark:text-slate-950">
                  {{ courses.length > 0 ? courses.length + ' khóa học đang mở' : 'Đang tải...' }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Filters + content -->
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <!-- Filter panel -->
        <div class="relative z-10 -mt-14">
          <div class="rounded-[32px] border border-slate-200 bg-white/95 p-5 shadow-[0_24px_70px_rgba(15,23,42,0.08)] backdrop-blur-xl transition-colors duration-300 sm:p-6 dark:border-slate-700 dark:bg-slate-900/90 dark:shadow-[0_24px_70px_rgba(0,0,0,0.28)]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-sm font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">
                  Bộ lọc thông minh
                </p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">
                  Tìm khóa học phù hợp nhanh hơn
                </h2>
              </div>

              <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                {{ meta.total ?? courses.length }} kết quả
              </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-[1.5fr_0.7fr_0.7fr_0.7fr_auto]">
              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tìm kiếm</label>
                <input
                  v-model="search"
                  type="text"
                  placeholder="Tìm theo tên khóa học..."
                  class="filter-input"
                />
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Danh mục</label>
                <select v-model="categoryFilter" class="filter-input">
                  <option value="">Tất cả</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Cấp độ</label>
                <select v-model="levelFilter" class="filter-input">
                  <option value="">Tất cả</option>
                  <option v-for="lv in levels" :key="lv.id" :value="lv.id">{{ lv.name }}</option>
                </select>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Sắp xếp</label>
                <select v-model="sortBy" class="filter-input">
                  <option value="id">Mới nhất</option>
                  <option value="price">Giá tăng dần</option>
                  <option value="title">Tên A-Z</option>
                </select>
              </div>

              <div class="flex items-end">
                <button
                  @click="resetFilters"
                  class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                >
                  Xóa lọc
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
          <div
            v-for="n in 6"
            :key="n"
            class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/60"
          >
            <div class="h-72 animate-pulse bg-slate-100 dark:bg-slate-700"></div>
            <div class="space-y-4 p-6">
              <div class="h-4 animate-pulse rounded bg-slate-100 dark:bg-slate-700"></div>
              <div class="h-4 w-3/4 animate-pulse rounded bg-slate-100 dark:bg-slate-700"></div>
              <div class="h-20 animate-pulse rounded bg-slate-100 dark:bg-slate-700"></div>
            </div>
          </div>
        </div>

        <!-- Error -->
        <div
          v-else-if="error"
          class="mt-10 rounded-[32px] border border-red-200 bg-red-50 px-6 py-12 text-center text-red-600 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400"
        >
          {{ error }}
        </div>

        <!-- Results -->
        <template v-else>
          <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-500">
                Kết quả tìm thấy
              </p>
              <h3 class="mt-1 text-xl font-semibold text-slate-950 dark:text-white">
                {{ meta.total ?? courses.length }} khóa học phù hợp
              </h3>
            </div>

            <div class="flex flex-wrap gap-2">
              <span
                v-if="search"
                class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300"
              >
                Từ khóa: {{ search }}
              </span>
              <span
                v-if="categoryFilter"
                class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300"
              >
                Danh mục đã chọn
              </span>
              <span
                v-if="levelFilter"
                class="rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300"
              >
                Cấp độ đã chọn
              </span>
            </div>
          </div>

          <!-- Cards -->
          <div v-if="courses.length" class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <article
              v-for="course in courses"
              :key="course.id"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
              class="reveal tilt-card course-card shine-hover select-none overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_26px_70px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_26px_70px_rgba(0,0,0,0.3)]"
            >
              <RouterLink :to="`/courses/${course.id}`" class="block">
                <div class="relative h-72 overflow-hidden">
                  <img
                    :src="$storage(course.thumbnail) || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80'"
                    :alt="course.title"
                    class="course-image h-full w-full object-cover transition duration-700"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/10 to-transparent"></div>

                  <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                    <span class="rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 backdrop-blur">
                      {{ course.level?.name ?? 'All levels' }}
                    </span>
                    <span
                      class="rounded-full px-3 py-1 text-xs font-semibold backdrop-blur"
                      :class="Number(course.price) === 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-900/70 text-white'"
                    >
                      {{ Number(course.price) === 0 ? 'Free' : formatPrice(course.price) }}
                    </span>
                  </div>

                  <div class="absolute bottom-5 left-5 right-5">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/65">
                      {{ course.category?.name ?? 'English Course' }}
                    </p>
                    <h2 class="mt-2 text-2xl font-semibold leading-tight text-white">
                      {{ course.title }}
                    </h2>
                  </div>
                </div>
              </RouterLink>

              <div class="p-6">
                <p class="line-clamp-3 text-sm leading-7 text-slate-600 dark:text-slate-400">
                  {{ course.description }}
                </p>

                <div class="mt-5 flex flex-wrap gap-3 text-sm">
                  <span class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                    {{ course.duration ? course.duration + ' phút' : 'N/A' }}
                  </span>
                  <span class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                    {{ course.level?.name ?? 'Mọi cấp độ' }}
                  </span>
                </div>

                <div class="mt-6 flex items-center justify-between gap-4">
                  <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Học phí</p>
                    <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-white">
                      {{ Number(course.price) === 0 ? 'Miễn phí' : formatPrice(course.price) }}
                    </p>
                  </div>

                  <RouterLink
                    :to="`/courses/${course.id}`"
                    class="shine-hover rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-xl dark:bg-white dark:text-slate-950"
                  >
                    Xem chi tiết
                  </RouterLink>
                </div>
              </div>
            </article>
          </div>

          <!-- Empty -->
          <div
            v-else
            class="mt-12 rounded-[32px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm dark:border-slate-600 dark:bg-slate-800/40"
          >
            <div class="mx-auto max-w-xl">
              <div class="mx-auto grid h-16 w-16 place-items-center rounded-3xl bg-slate-100 text-2xl dark:bg-slate-700">
                📚
              </div>
              <h3 class="mt-6 text-2xl font-semibold text-slate-950 dark:text-white">
                Không tìm thấy khóa học phù hợp
              </h3>
              <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
                Hãy thử thay đổi từ khóa tìm kiếm hoặc xóa bộ lọc để xem nhiều khóa học hơn.
              </p>
              <button
                @click="resetFilters"
                class="mt-6 rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:shadow-xl dark:bg-white dark:text-slate-950"
              >
                Xóa bộ lọc
              </button>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="meta.last_page > 1" class="mt-12 flex flex-wrap justify-center gap-2">
            <button
              v-for="page in meta.last_page"
              :key="page"
              :class="[
                'rounded-full px-4 py-2 text-sm font-semibold transition duration-300',
                page === meta.current_page
                  ? 'bg-slate-950 text-white shadow-md dark:bg-white dark:text-slate-950'
                  : 'border border-slate-300 bg-white text-slate-700 hover:-translate-y-0.5 hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700'
              ]"
              @click="fetchCourses(page)"
            >
              {{ page }}
            </button>
          </div>
        </template>
      </div>
    </section>

    <!-- CTA -->
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#0f172a_0%,#111827_35%,#0369a1_100%)] px-6 py-12 text-white shadow-[0_24px_70px_rgba(15,23,42,0.18)] sm:px-8 lg:px-12">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/45">Sẵn sàng bắt đầu</p>
            <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
              Chọn khóa học yêu thích và bắt đầu học tiếng Anh theo lộ trình bài bản.
            </h2>
            <p class="mt-4 max-w-2xl text-base leading-8 text-white/75">
              Tạo tài khoản để lưu tiến độ, truy cập dashboard cá nhân và tiếp tục học mọi lúc trên mọi thiết bị.
            </p>
          </div>

          <div class="rounded-[32px] border border-white/10 bg-white/8 p-6 backdrop-blur">
            <div class="space-y-4">
              <RouterLink
                to="/register"
                class="shine-hover block w-full rounded-full bg-white px-5 py-3.5 text-center text-sm font-semibold text-slate-950 transition duration-300 hover:-translate-y-0.5 hover:shadow-2xl"
              >
                Tạo tài khoản miễn phí
              </RouterLink>
              <RouterLink
                to="/tests"
                class="shine-hover block w-full rounded-full border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white transition duration-300 hover:bg-white/15"
              >
                Xem đề thi thử
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, nextTick, ref, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { useTypewriter } from '@/composables/useTypewriter'
import heroBg from '@/assets/images/home/khoahoc.jpg'
import courseService from '@/services/course.service'
import categoryService from '@/services/category.service'
import levelService from '@/services/level.service'

const { displayed: twDisplayed } = useTypewriter('mục tiêu học tiếng Anh của bạn.')

const courses = ref([])
const categories = ref([])
const levels = ref([])
const meta = ref({})
const loading = ref(false)
const error = ref(null)

const heroCards = [
  { icon: '📚', label: 'Lộ trình rõ ràng', sub: 'Từng bước từ cơ bản đến nâng cao', badge: 'Structured' },
  { icon: '🎯', label: 'Học theo mục tiêu', sub: 'IELTS, TOEIC, giao tiếp, cơ bản', badge: 'Goal-based' },
  { icon: '⚡', label: 'Học mọi lúc mọi nơi', sub: 'Truy cập trên mọi thiết bị', badge: 'Flexible' },
]

const search = ref('')
const categoryFilter = ref('')
const levelFilter = ref('')
const sortBy = ref('id')

const formatPrice = (price) => {
  return Number(price).toLocaleString('vi-VN') + 'đ'
}

const fetchCourses = async (page = 1) => {
  loading.value = true
  error.value = null

  try {
    const params = { page, sort_by: sortBy.value, sort_order: 'desc' }
    if (search.value) params.search = search.value
    if (categoryFilter.value) params.category_id = categoryFilter.value
    if (levelFilter.value) params.level_id = levelFilter.value

    const res = await courseService.list(params)
    courses.value = res.data.data
    meta.value = res.data.meta ?? {}
  } catch {
    error.value = 'Không thể tải danh sách khóa học. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

const resetFilters = () => {
  search.value = ''
  categoryFilter.value = ''
  levelFilter.value = ''
  sortBy.value = 'id'
}

let searchTimer = null
let observer = null

watch(search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchCourses(), 400)
})

watch([categoryFilter, levelFilter, sortBy], () => fetchCourses())

// Re-run reveal after courses update
watch(courses, async () => {
  await nextTick()
  initRevealAnimations()
})

onMounted(async () => {
  fetchCourses()

  try {
    const [catRes, lvRes] = await Promise.all([
      categoryService.list(),
      levelService.list(),
    ])
    categories.value = catRes.data.data ?? catRes.data
    levels.value = lvRes.data.data ?? lvRes.data
  } catch {}
})

onBeforeUnmount(() => {
  if (observer) observer.disconnect()
  clearTimeout(searchTimer)
})

function initRevealAnimations() {
  if (observer) observer.disconnect()

  const els = document.querySelectorAll('.reveal:not(.is-visible)')

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) entry.target.classList.add('is-visible')
      })
    },
    { threshold: 0.05 }
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

.filter-input {
  width: 100%;
  border-radius: 1rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(248 250 252);
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: rgb(15 23 42);
  outline: none;
  transition: all 0.2s ease;
}

.filter-input:focus {
  border-color: rgb(148 163 184);
  background: white;
}

:global(.dark) .filter-input {
  border-color: rgb(51 65 85);
  background: rgb(30 41 59);
  color: rgb(241 245 249);
}

:global(.dark) .filter-input:focus {
  border-color: rgb(100 116 139);
}

.reveal {
  opacity: 0;
  transform: translateY(32px) scale(0.98);
  transition:
    opacity 0.8s ease,
    transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.reveal.is-visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}

.tilt-card {
  position: relative;
  transform-style: preserve-3d;
  will-change: transform;
}

.tilt-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  pointer-events: none;
  background:
    radial-gradient(
      220px circle at var(--mx, 50%) var(--my, 50%),
      rgba(255,255,255,0.18),
      transparent 45%
    );
  opacity: 0;
  transition: opacity .25s ease;
}

.tilt-card:hover::before {
  opacity: 1;
}

.course-image {
  will-change: transform;
}

.course-card:hover .course-image {
  transform: scale(1.06);
}

.shine-hover {
  position: relative;
  overflow: hidden;
}

.shine-hover::after {
  content: '';
  position: absolute;
  top: -120%;
  left: -140%;
  width: 60%;
  height: 300%;
  transform: rotate(24deg);
  background: linear-gradient(
    to right,
    transparent 0%,
    rgba(255,255,255,0.08) 40%,
    rgba(255,255,255,0.34) 50%,
    transparent 60%
  );
  transition: left .85s ease;
  pointer-events: none;
}

.shine-hover:hover::after {
  left: 180%;
}

@media (prefers-reduced-motion: reduce) {
  .reveal,
  .shine-hover::after,
  .tilt-card,
  .course-image {
    animation: none !important;
    transition: none !important;
    transform: none !important;
  }
}

@media (max-width: 1023px) {
  .tilt-card:hover::before {
    opacity: 0;
  }
}
</style>