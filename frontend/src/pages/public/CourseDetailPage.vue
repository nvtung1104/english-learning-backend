<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">

    <!-- Loading -->
    <div v-if="loading" class="flex min-h-screen items-center justify-center">
      <div class="h-10 w-10 animate-spin rounded-full border-4 border-slate-200 dark:border-slate-700 border-t-sky-500"></div>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="flex min-h-screen flex-col items-center justify-center gap-4 px-4 text-center">
      <p class="text-5xl">😕</p>
      <p class="text-xl font-semibold">Không tìm thấy khóa học</p>
      <RouterLink to="/courses" class="rounded-full bg-slate-950 px-6 py-3 text-sm font-semibold text-white dark:bg-white dark:text-slate-950">
        Quay lại danh sách
      </RouterLink>
    </div>

    <template v-else>
      <!-- ── HERO ── -->
      <section class="relative overflow-hidden px-4 pb-0 pt-24 sm:px-6 lg:px-8 lg:pt-28">
        <!-- Background image (same as CoursesPage) -->
        <div class="absolute inset-0">
          <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
          <div class="absolute inset-0 bg-slate-950/55 dark:bg-black/60"></div>
        </div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.22),transparent_28%),radial-gradient(circle_at_80%_20%,rgba(16,185,129,0.14),transparent_22%)]"></div>
        <div class="absolute -left-16 top-20 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl"></div>
        <div class="absolute -right-20 top-24 h-80 w-80 rounded-full bg-cyan-300/15 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-emerald-300/10 blur-3xl"></div>

        <div class="mx-auto max-w-7xl">
          <!-- Breadcrumb -->
          <nav class="flex items-center gap-2 text-sm text-white/50">
            <RouterLink to="/" class="hover:text-white transition">Trang chủ</RouterLink>
            <span>/</span>
            <RouterLink to="/courses" class="hover:text-white transition">Khóa học</RouterLink>
            <span>/</span>
            <span class="text-white/80 truncate max-w-[200px]">{{ course.title }}</span>
          </nav>

          <div class="mt-8 grid gap-10 pb-20 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <!-- Left -->
            <div>
              <div class="flex flex-wrap gap-2">
                <span class="rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white/80 backdrop-blur">
                  {{ course.category?.name ?? '—' }}
                </span>
                <span class="rounded-full border border-sky-400/30 bg-sky-400/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-sky-300">
                  {{ course.level?.name ?? '—' }}
                </span>
              </div>

              <h1 class="mt-5 text-4xl font-semibold tracking-tight text-white sm:text-5xl xl:text-6xl leading-tight">
                {{ course.title }}
              </h1>

              <p class="mt-5 max-w-2xl text-base leading-8 text-white/65 sm:text-lg">
                {{ course.description }}
              </p>

              <div class="mt-6 flex flex-wrap gap-3">
                <span class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/75 backdrop-blur">
                  📚 {{ totalLessons }} bài học
                </span>
                <span v-if="course.duration" class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/75 backdrop-blur">
                  ⏱ {{ course.duration }}
                </span>
                <span class="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-sm text-white/75 backdrop-blur">
                  ⭐ {{ reviews.length }} đánh giá
                </span>
              </div>

              <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <!-- Chưa đăng nhập -->
                <template v-if="!authStore.isLoggedIn">
                  <RouterLink to="/register" class="rounded-full bg-white px-7 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-2xl">
                    Đăng ký học ngay
                  </RouterLink>
                  <RouterLink to="/login" class="rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-center text-sm font-semibold text-white backdrop-blur transition hover:bg-white/15">
                    Đăng nhập
                  </RouterLink>
                </template>
                <!-- Đã đăng ký -->
                <template v-else-if="isEnrolled">
                  <RouterLink :to="`/student/courses/${course.id}/learn`" class="rounded-full bg-white px-7 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-2xl">
                    ✅ Vào học ngay
                  </RouterLink>
                </template>
                <!-- Đã đăng nhập, chưa đăng ký -->
                <template v-else>
                  <button @click="handleEnroll" :disabled="enrolling" class="rounded-full bg-white px-7 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:-translate-y-0.5 hover:shadow-2xl disabled:opacity-70">
                    {{ enrolling ? 'Đang đăng ký...' : 'Đăng ký học ngay' }}
                  </button>
                </template>
                <RouterLink to="/courses" class="rounded-full border border-white/20 bg-white/10 px-7 py-3.5 text-center text-sm font-semibold text-white backdrop-blur transition hover:bg-white/15">
                  Quay lại khóa học
                </RouterLink>
              </div>
            </div>

            <!-- Right: thumbnail -->
            <div class="relative">
              <div class="overflow-hidden rounded-[32px] shadow-[0_40px_100px_rgba(0,0,0,0.5)] ring-1 ring-white/10">
                <div class="relative h-[360px] lg:h-[420px]">
                  <img
                    :src="course.thumbnail ? $storage(course.thumbnail) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80'"
                    :alt="course.title"
                    class="h-full w-full object-cover"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/10 to-transparent"></div>
                  <div class="absolute bottom-5 left-5 right-5">
                    <p class="text-xs uppercase tracking-widest text-white/50">{{ course.category?.name }}</p>
                    <p class="mt-1 text-xl font-semibold text-white line-clamp-2">{{ course.title }}</p>
                  </div>
                </div>
              </div>
              <!-- Price badge -->
              <div class="absolute -bottom-5 right-6 rounded-[20px] bg-white px-5 py-3 shadow-[0_20px_60px_rgba(0,0,0,0.25)]">
                <p class="text-xs uppercase tracking-widest text-slate-400">Học phí</p>
                <p class="mt-1 text-xl font-semibold text-slate-950">{{ course.price == 0 ? 'Miễn phí' : formatPrice(course.price) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Wave divider -->
        <div class="h-14 w-full bg-slate-50 dark:bg-slate-950" style="clip-path: ellipse(55% 100% at 50% 100%)"></div>
      </section>

      <!-- ── BODY ── -->
      <section class="px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1fr_340px]">

          <!-- Main -->
          <div class="space-y-8">

            <!-- Curriculum -->
            <div class="rounded-[32px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-7 shadow-[0_18px_50px_rgba(15,23,42,0.05)]">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Nội dung khóa học</p>
                  <h2 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Lộ trình học tập</h2>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ sections.length }} phần • {{ totalLessons }} bài học</p>
              </div>

              <div v-if="sectionsLoading" class="mt-8 flex justify-center py-8">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 dark:border-slate-700 border-t-sky-500"></div>
              </div>

              <div v-else class="mt-6 space-y-3">
                <article v-for="(section, index) in sections" :key="section.id"
                  class="overflow-hidden rounded-[24px] border border-slate-200 dark:border-slate-700">
                  <div class="flex flex-col gap-3 bg-slate-50 dark:bg-slate-900 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                      <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-sky-600 text-sm font-semibold text-white">
                        {{ index + 1 }}
                      </div>
                      <h3 class="font-semibold text-slate-950 dark:text-white">{{ section.title }}</h3>
                    </div>
                    <span class="rounded-full bg-white dark:bg-slate-800 px-3 py-1 text-xs font-semibold text-slate-500 dark:text-slate-400 ring-1 ring-slate-200 dark:ring-slate-700">
                      {{ section.lessons?.length ?? 0 }} bài
                    </span>
                  </div>
                  <div v-if="section.lessons?.length" class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    <div v-for="lesson in section.lessons" :key="lesson.id"
                      class="flex items-center gap-3 px-5 py-3.5">
                      <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-100 dark:bg-slate-700 text-sm">📄</div>
                      <div class="min-w-0 flex-1">
                        <p class="font-medium text-slate-900 dark:text-slate-100 truncate">{{ lesson.title }}</p>
                        <p v-if="lesson.description" class="mt-0.5 text-xs text-slate-500 dark:text-slate-400 truncate">{{ lesson.description }}</p>
                      </div>
                      <span v-if="lesson.duration" class="shrink-0 text-xs text-slate-400 dark:text-slate-500">{{ lesson.duration }}</span>
                    </div>
                  </div>
                </article>

                <div v-if="!sections.length" class="rounded-[24px] border border-dashed border-slate-200 dark:border-slate-700 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                  Chưa có nội dung cho khóa học này.
                </div>
              </div>
            </div>

            <!-- Reviews -->
            <div class="rounded-[32px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-7 shadow-[0_18px_50px_rgba(15,23,42,0.05)]">
              <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Đánh giá học viên</p>
              <h2 class="mt-3 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Học viên nói gì về khóa học này</h2>

              <div v-if="reviewsLoading" class="mt-6 flex justify-center py-8">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 dark:border-slate-700 border-t-sky-500"></div>
              </div>

              <div v-else-if="reviews.length" class="mt-6 grid gap-4 md:grid-cols-2">
                <article v-for="review in reviews" :key="review.id"
                  class="rounded-[24px] border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-5 space-y-3">
                  <div class="flex items-center gap-3">
                    <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-sky-600 text-base font-semibold text-white">
                      {{ review.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                    </div>
                    <div>
                      <p class="font-semibold text-slate-950 dark:text-white">{{ review.user?.name ?? 'Ẩn danh' }}</p>
                      <div class="flex items-center gap-0.5 mt-0.5">
                        <span v-for="s in 5" :key="s" class="text-sm" :class="s <= review.rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'">★</span>
                      </div>
                    </div>
                  </div>
                  <p v-if="review.comment" class="text-sm leading-7 text-slate-600 dark:text-slate-400">"{{ review.comment }}"</p>
                </article>
              </div>

              <div v-else class="mt-6 rounded-[24px] border border-dashed border-slate-200 dark:border-slate-700 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                Chưa có đánh giá nào cho khóa học này.
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <aside>
            <div class="sticky top-24 space-y-5">
              <div class="rounded-[28px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-[0_18px_50px_rgba(15,23,42,0.06)]">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500">Thông tin nhanh</p>

                <div class="mt-5 space-y-3">
                  <div class="flex items-center justify-between rounded-2xl bg-slate-50 dark:bg-slate-900 px-4 py-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Cấp độ</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ course.level?.name ?? '—' }}</span>
                  </div>
                  <div class="flex items-center justify-between rounded-2xl bg-slate-50 dark:bg-slate-900 px-4 py-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Bài học</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ totalLessons }}</span>
                  </div>
                  <div v-if="course.duration" class="flex items-center justify-between rounded-2xl bg-slate-50 dark:bg-slate-900 px-4 py-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Thời lượng</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ course.duration }}</span>
                  </div>
                  <div class="flex items-center justify-between rounded-2xl bg-slate-50 dark:bg-slate-900 px-4 py-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Học phí</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ course.price == 0 ? 'Miễn phí' : formatPrice(course.price) }}</span>
                  </div>
                  <div class="flex items-center justify-between rounded-2xl bg-slate-50 dark:bg-slate-900 px-4 py-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Đánh giá</span>
                    <span class="font-semibold text-slate-900 dark:text-slate-100">{{ reviews.length }} lượt</span>
                  </div>
                </div>

                <template v-if="!authStore.isLoggedIn">
                  <RouterLink to="/register" class="mt-5 block rounded-full bg-slate-950 dark:bg-sky-600 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:opacity-90 hover:shadow-xl">
                    Đăng ký học khóa này
                  </RouterLink>
                  <RouterLink to="/login" class="mt-3 block rounded-full border border-slate-300 dark:border-slate-600 px-5 py-3.5 text-center text-sm font-semibold text-slate-800 dark:text-slate-200 transition hover:bg-slate-50 dark:hover:bg-slate-700">
                    Đăng nhập để tiếp tục
                  </RouterLink>
                </template>
                <template v-else-if="isEnrolled">
                  <div class="mt-5 flex items-center justify-center gap-2 rounded-full bg-emerald-50 dark:bg-emerald-900/30 px-5 py-3 text-sm font-semibold text-emerald-700 dark:text-emerald-400 ring-1 ring-emerald-200 dark:ring-emerald-800">
                    ✅ Đã đăng ký
                  </div>
                  <RouterLink :to="`/student/courses/${course.id}/learn`" class="mt-3 block rounded-full bg-slate-950 dark:bg-sky-600 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:opacity-90 hover:shadow-xl">
                    Vào học ngay
                  </RouterLink>
                </template>
                <template v-else>
                  <button @click="handleEnroll" :disabled="enrolling" class="mt-5 block w-full rounded-full bg-slate-950 dark:bg-sky-600 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:opacity-90 hover:shadow-xl disabled:opacity-60">
                    {{ enrolling ? 'Đang đăng ký...' : 'Đăng ký học khóa này' }}
                  </button>
                </template>
              </div>

              <!-- CTA mini -->
              <div class="overflow-hidden rounded-[28px] p-6 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%)">
                <p class="text-sm font-semibold text-white/60">Sẵn sàng chưa?</p>
                <p class="mt-2 text-lg font-semibold leading-7">Bắt đầu học ngay hôm nay và nâng trình từng ngày.</p>
                <RouterLink to="/courses" class="mt-4 inline-block rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-slate-950 transition hover:shadow-xl">
                  Xem thêm khóa học
                </RouterLink>
              </div>
            </div>
          </aside>
        </div>
      </section>

      <!-- ── CTA BOTTOM ── -->
      <section class="px-4 pb-24 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] px-6 py-12 text-white sm:px-8 lg:px-12" style="background: linear-gradient(135deg,#0f172a 0%,#111827 35%,#0369a1 100%)">
          <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/45">Sẵn sàng bắt đầu</p>
              <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
                Bắt đầu khóa học này và nâng trình tiếng Anh của bạn từng ngày.
              </h2>
            </div>
            <div class="rounded-[32px] border border-white/10 bg-white/8 p-6 backdrop-blur space-y-3">
              <RouterLink v-if="!authStore.isLoggedIn" to="/register"
                class="block w-full rounded-full bg-white px-5 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:shadow-2xl">
                Tạo tài khoản miễn phí
              </RouterLink>
              <RouterLink to="/courses"
                class="block w-full rounded-full border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:bg-white/15">
                Xem thêm khóa học
              </RouterLink>
            </div>
          </div>
        </div>
      </section>
    </template>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import courseService from '@/services/course.service'
import { useAuthStore } from '@/stores/auth'
import { useToast } from '@/composables/useToast'
import api from '@/services/axios'
import heroBg from '@/assets/images/home/khoahoc.jpg'

const authStore = useAuthStore()
const toast = useToast()
const route = useRoute()

const course = ref({})
const sections = ref([])
const reviews = ref([])
const loading = ref(true)
const sectionsLoading = ref(true)
const reviewsLoading = ref(true)
const error = ref(false)
const isEnrolled = ref(false)
const enrolling = ref(false)

const totalLessons = computed(() =>
  sections.value.reduce((sum, s) => sum + (s.lessons?.length ?? 0), 0)
)

function formatPrice(price) {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price)
}

async function fetchCourse() {
  try {
    const res = await courseService.get(route.params.slug)
    course.value = res.data.data
  } catch {
    error.value = true
  } finally {
    loading.value = false
  }
}

async function fetchSections() {
  try {
    const res = await courseService.sections(route.params.slug)
    sections.value = res.data.data ?? []
  } catch {
    sections.value = []
  } finally {
    sectionsLoading.value = false
  }
}

async function fetchReviews() {
  try {
    const res = await courseService.reviews(route.params.slug)
    reviews.value = res.data.data ?? []
  } catch {
    reviews.value = []
  } finally {
    reviewsLoading.value = false
  }
}

async function checkEnrollment() {
  if (!authStore.isLoggedIn) return
  try {
    const res = await api.get('/student/my-courses')
    const enrollments = res.data?.data ?? res.data ?? []
    isEnrolled.value = enrollments.some(e => e.course_id === course.value.id)
  } catch { /* ignore */ }
}

async function handleEnroll() {
  if (enrolling.value) return
  enrolling.value = true
  try {
    await api.post(`/student/courses/${course.value.id}/enroll`)
    isEnrolled.value = true
    toast.success('Đăng ký khóa học thành công!')
  } catch (e) {
    const msg = e.response?.data?.message ?? 'Có lỗi xảy ra.'
    if (e.response?.status === 422) {
      isEnrolled.value = true
      toast.info('Bạn đã đăng ký khóa học này rồi.')
    } else {
      toast.error(msg)
    }
  } finally {
    enrolling.value = false
  }
}

onMounted(async () => {
  await fetchCourse()
  fetchSections()
  fetchReviews()
  checkEnrollment()
})
</script>
