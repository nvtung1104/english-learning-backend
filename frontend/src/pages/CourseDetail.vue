<template>
  <div class="bg-cream min-h-screen">
    <Spinner v-if="loading" class="pt-40" />
    <div v-else-if="course">
      <!-- Hero banner -->
      <div class="relative bg-navy pt-28 pb-16 overflow-hidden">
        <img v-if="course.thumbnail" :src="course.thumbnail" alt="" class="absolute inset-0 w-full h-full object-cover opacity-15" />
        <div class="absolute inset-0 hero-overlay" />
        <div class="relative container-app">
          <div class="max-w-3xl">
            <div class="flex flex-wrap gap-2 mb-4">
              <AppBadge variant="gold">{{ course.category?.name || 'Tiếng Anh' }}</AppBadge>
              <AppBadge :variant="levelVariant">{{ levelLabel }}</AppBadge>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
              {{ course.title }}
            </h1>
            <p class="text-white/60 text-base leading-relaxed mb-6 max-w-2xl">{{ course.description }}</p>
            <div class="flex flex-wrap items-center gap-5 text-sm text-white/55">
              <span v-if="course.teacher" class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold text-white">
                  {{ course.teacher.name?.charAt(0) }}
                </div>
                {{ course.teacher.name }}
              </span>
              <span v-if="course.lessons_count">📖 {{ course.lessons_count }} bài học</span>
              <span v-if="course.students_count">👥 {{ course.students_count }} học viên</span>
              <span v-if="course.rating" class="flex items-center gap-1 text-gold">
                ★ {{ Number(course.rating).toFixed(1) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="container-app py-10">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Main content -->
          <div class="flex-1 min-w-0 space-y-6">
            <!-- What you'll learn -->
            <div class="card p-6">
              <h2 class="font-display text-xl font-bold text-navy mb-4">Bạn sẽ học được gì?</h2>
              <div class="grid sm:grid-cols-2 gap-3">
                <div v-for="item in (course.outcomes || defaultOutcomes)" :key="item" class="flex items-start gap-2.5">
                  <svg class="w-4 h-4 text-gold mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  <span class="text-sm text-slate-700">{{ item }}</span>
                </div>
              </div>
            </div>

            <!-- Syllabus -->
            <div class="card overflow-hidden">
              <div class="px-6 py-4 border-b border-[var(--border-soft)]">
                <h2 class="font-display text-xl font-bold text-navy">Nội dung khóa học</h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ course.sections?.length || 0 }} chương · {{ course.lessons_count || 0 }} bài học</p>
              </div>
              <AppAccordion :items="syllabusItems" />
            </div>

            <!-- Reviews -->
            <div class="card p-6">
              <h2 class="font-display text-xl font-bold text-navy mb-5">Đánh giá từ học viên</h2>
              <div v-if="course.reviews?.length" class="space-y-4">
                <div v-for="r in course.reviews" :key="r.id" class="flex gap-4 pb-4 border-b border-[var(--border-soft)] last:border-0 last:pb-0">
                  <div class="w-9 h-9 rounded-full bg-navy/10 flex items-center justify-center text-sm font-bold text-navy shrink-0">
                    {{ r.user?.name?.charAt(0) }}
                  </div>
                  <div>
                    <div class="flex items-center gap-2 mb-1">
                      <span class="font-semibold text-navy text-sm">{{ r.user?.name }}</span>
                      <span class="text-gold text-xs">{{ '★'.repeat(r.rating) }}</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ r.comment }}</p>
                  </div>
                </div>
              </div>
              <EmptyState v-else icon="⭐" title="Chưa có đánh giá" description="Hãy là người đầu tiên đánh giá khóa học này." />
            </div>
          </div>

          <!-- Sticky sidebar -->
          <div class="lg:w-80 shrink-0">
            <div class="card p-6 lg:sticky lg:top-24">
              <div class="aspect-video rounded-2xl overflow-hidden bg-navy/10 mb-5">
                <img v-if="course.thumbnail" :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-5xl">📚</div>
              </div>

              <div class="mb-5">
                <div class="font-display text-3xl font-bold text-navy mb-1">
                  {{ course.is_free || !course.price ? 'Miễn phí' : Number(course.price).toLocaleString('vi-VN') + 'đ' }}
                </div>
                <p v-if="course.original_price && course.price" class="text-sm text-slate-400 line-through">
                  {{ Number(course.original_price).toLocaleString('vi-VN') }}đ
                </p>
              </div>

              <AppButton variant="primary" size="lg" block @click="handleEnroll" :loading="enrolling">
                {{ enrolled ? 'Tiếp tục học' : 'Đăng ký học ngay' }}
              </AppButton>
              <p class="text-center text-xs text-slate-400 mt-3">Hoàn tiền trong 30 ngày nếu không hài lòng</p>

              <div class="mt-5 space-y-2.5 pt-5 border-t border-[var(--border-soft)]">
                <div v-for="f in courseFeatures" :key="f" class="flex items-center gap-2.5 text-sm text-slate-600">
                  <svg class="w-4 h-4 text-gold shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ f }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <EmptyState v-else icon="📭" title="Không tìm thấy khóa học" description="Khóa học không tồn tại hoặc đã bị xóa." />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Spinner from '../components/Spinner.vue'
import EmptyState from '../components/ui/EmptyState.vue'
import AppBadge from '../components/ui/AppBadge.vue'
import AppButton from '../components/ui/AppButton.vue'
import AppAccordion from '../components/ui/AppAccordion.vue'
import { getCourses } from '../api/courses'

const route    = useRoute()
const router   = useRouter()
const course   = ref(null)
const loading  = ref(true)
const enrolling = ref(false)
const enrolled  = ref(false)

const levelMap = {
  beginner:     { label: 'Cơ bản',   variant: 'success' },
  intermediate: { label: 'Trung cấp', variant: 'warning' },
  advanced:     { label: 'Nâng cao', variant: 'danger' },
}
const levelLabel   = computed(() => levelMap[course.value?.level]?.label || course.value?.level || 'Tất cả')
const levelVariant = computed(() => levelMap[course.value?.level]?.variant || 'gray')

const syllabusItems = computed(() =>
  (course.value?.sections || []).map(s => ({
    title:   `${s.title} (${s.lessons?.length || 0} bài)`,
    content: s.lessons?.map(l => l.title).join(' · ') || 'Chưa có bài học',
  }))
)

const defaultOutcomes = [
  'Giao tiếp tiếng Anh tự tin trong cuộc sống hàng ngày',
  'Nắm vững ngữ pháp từ cơ bản đến nâng cao',
  'Mở rộng vốn từ vựng theo chủ đề',
  'Luyện kỹ năng nghe, nói, đọc, viết toàn diện',
]

const courseFeatures = [
  'Truy cập trọn đời',
  'Học trên mọi thiết bị',
  'Chứng chỉ hoàn thành',
  'Hỗ trợ từ giáo viên',
]

async function handleEnroll() {
  if (enrolled.value) { router.push(`/lesson/${course.value?.first_lesson_id || 1}`); return }
  enrolling.value = true
  await new Promise(r => setTimeout(r, 800))
  enrolled.value  = true
  enrolling.value = false
}

onMounted(async () => {
  try {
    const res = await getCourses({ id: route.params.id })
    course.value = res.data?.data?.[0] || res.data || null
  } catch { course.value = null }
  finally { loading.value = false }
})
</script>
