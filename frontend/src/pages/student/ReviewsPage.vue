<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-4xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">&larr; Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Danh gia cua toi</h1>
        <p class="mt-1 text-sm theme-text-soft">Quan ly danh gia cac khoa hoc ban da hoc</p>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="space-y-4">
        <div v-for="i in 3" :key="i" class="theme-card rounded-[24px] p-5 animate-pulse h-28 skeleton"></div>
      </div>

      <!-- No courses enrolled -->
      <div v-else-if="!enrollments.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">&#128218;</p>
        <p class="mt-4 text-lg font-semibold theme-text">Ban chua dang ky khoa hoc nao</p>
        <RouterLink to="/student/courses" class="mt-6 inline-block rounded-full px-5 py-3 text-sm font-semibold text-white" style="background: var(--accent)">
          Xem khoa hoc cua toi
        </RouterLink>
      </div>

      <!-- Course list with review status -->
      <div v-else class="space-y-4">
        <div v-for="e in enrollments" :key="e.id" class="theme-card rounded-[24px] p-5 space-y-3">
          <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
              <p class="font-semibold theme-text truncate">{{ e.course?.title ?? 'Khoa hoc #' + e.course_id }}</p>
              <!-- Has review -->
              <template v-if="reviewMap[e.course_id]">
                <div class="mt-1 flex items-center gap-1">
                  <span v-for="s in 5" :key="s" class="text-base" :class="s <= reviewMap[e.course_id].rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'">&#9733;</span>
                  <span class="ml-1 text-xs theme-text-muted">{{ reviewMap[e.course_id].rating }}/5</span>
                </div>
                <p v-if="reviewMap[e.course_id].comment" class="mt-1 text-sm theme-text-soft">{{ reviewMap[e.course_id].comment }}</p>
                <p v-else class="mt-1 text-xs theme-text-muted italic">Chua co nhan xet</p>
                <p class="text-xs theme-text-muted">{{ formatDate(reviewMap[e.course_id].created_at) }}</p>
              </template>
              <!-- No review yet -->
              <p v-else class="mt-1 text-xs theme-text-muted italic">Chua co danh gia</p>
            </div>
            <div class="flex gap-2 shrink-0">
              <button v-if="reviewMap[e.course_id]" @click="openEdit(e)" class="action-btn rounded-full px-3 py-1.5 text-xs font-semibold transition">Sua</button>
              <button v-if="reviewMap[e.course_id]" @click="deleteReview(e)" class="danger-btn rounded-full px-3 py-1.5 text-xs font-semibold transition">Xoa</button>
              <button v-else @click="openCreate(e)" class="create-btn rounded-full px-3 py-1.5 text-xs font-semibold transition">+ Danh gia</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal: create or edit -->
      <div v-if="modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)" @click.self="modal = false">
        <div class="theme-card rounded-[30px] p-6 w-full max-w-md space-y-4">
          <h2 class="text-lg font-semibold theme-text">{{ modalMode === 'create' ? 'Viet danh gia' : 'Chinh sua danh gia' }}</h2>
          <p class="text-sm theme-text-soft">{{ modalTarget?.course?.title }}</p>

          <div class="space-y-1">
            <p class="text-sm font-semibold theme-text">Diem danh gia</p>
            <div class="flex gap-2">
              <button v-for="s in 5" :key="s" type="button" class="text-2xl transition hover:scale-110"
                :class="s <= form.rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600'"
                @click="form.rating = s">&#9733;</button>
            </div>
          </div>

          <div class="space-y-1">
            <label class="text-sm font-semibold theme-text">Nhan xet</label>
            <textarea v-model="form.comment" rows="4" class="form-input resize-none" placeholder="Chia se trai nghiem cua ban..."></textarea>
          </div>

          <div class="flex gap-3">
            <button @click="modal = false" class="flex-1 rounded-full py-2.5 text-sm font-semibold transition" style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)">Huy</button>
            <button @click="submitForm" :disabled="saving" class="flex-1 rounded-full py-2.5 text-sm font-semibold text-white disabled:opacity-60 transition" style="background: var(--accent)">
              {{ saving ? 'Dang luu...' : 'Luu' }}
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useToast } from '@/composables/useToast'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/axios'

const toast = useToast()
const authStore = useAuthStore()

const enrollments = ref([])
const reviewMap = ref({})   // course_id -> review object
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/student/my-courses')
    enrollments.value = res.data?.data ?? res.data ?? []
    const myId = authStore.user?.id

    await Promise.allSettled(
      enrollments.value.map(async (e) => {
        try {
          const rRes = await api.get(`/courses/${e.course_id}/reviews`)
          const items = rRes.data?.data ?? rRes.data ?? []
          const mine = items.find(r => r.user_id === myId)
          if (mine) reviewMap.value[e.course_id] = mine
        } catch { /* ignore */ }
      })
    )
  } finally {
    loading.value = false
  }
})

// Modal state
const modal = ref(false)
const modalMode = ref('create')   // 'create' | 'edit'
const modalTarget = ref(null)     // enrollment object
const form = ref({ rating: 5, comment: '' })
const saving = ref(false)

function openCreate(enrollment) {
  modalTarget.value = enrollment
  modalMode.value = 'create'
  form.value = { rating: 5, comment: '' }
  modal.value = true
}

function openEdit(enrollment) {
  const review = reviewMap.value[enrollment.course_id]
  modalTarget.value = enrollment
  modalMode.value = 'edit'
  form.value = { rating: review.rating, comment: review.comment ?? '' }
  modal.value = true
}

async function submitForm() {
  saving.value = true
  try {
    if (modalMode.value === 'create') {
      const res = await api.post('/student/reviews', {
        course_id: modalTarget.value.course_id,
        rating: form.value.rating,
        comment: form.value.comment || null,
      })
      reviewMap.value[modalTarget.value.course_id] = res.data?.data ?? res.data
      toast.success('Da gui danh gia!')
    } else {
      const review = reviewMap.value[modalTarget.value.course_id]
      const res = await api.put(`/student/reviews/${review.id}`, {
        rating: form.value.rating,
        comment: form.value.comment || null,
      })
      reviewMap.value[modalTarget.value.course_id] = res.data?.data ?? { ...review, ...form.value }
      toast.success('Da cap nhat danh gia!')
    }
    modal.value = false
  } catch (e) {
    toast.error(e.response?.data?.message ?? 'Co loi xay ra.')
  } finally {
    saving.value = false
  }
}

async function deleteReview(enrollment) {
  const review = reviewMap.value[enrollment.course_id]
  if (!review || !confirm('Xoa danh gia nay?')) return
  try {
    await api.delete(`/student/reviews/${review.id}`)
    delete reviewMap.value[enrollment.course_id]
    reviewMap.value = { ...reviewMap.value }
    toast.success('Da xoa danh gia.')
  } catch (e) {
    toast.error(e.response?.data?.message ?? 'Co loi xay ra.')
  }
}

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('vi-VN', { year: 'numeric', month: 'long', day: 'numeric' })
}
</script>

<style scoped>
.skeleton { background: var(--border); }
.action-btn { border: 1px solid var(--border); background: var(--surface-muted); color: var(--text); }
.action-btn:hover { background: var(--surface-strong); }
.create-btn { border: 1px solid color-mix(in srgb, var(--accent) 40%, var(--border)); color: var(--accent); background: color-mix(in srgb, var(--accent) 8%, var(--surface-muted)); }
.create-btn:hover { background: color-mix(in srgb, var(--accent) 15%, var(--surface-muted)); }
.danger-btn { border: 1px solid color-mix(in srgb, #ef4444 30%, var(--border)); color: #ef4444; background: color-mix(in srgb, #ef4444 6%, var(--surface-muted)); }
.danger-btn:hover { background: color-mix(in srgb, #ef4444 12%, var(--surface-muted)); }
.form-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 1rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
}
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
</style>
