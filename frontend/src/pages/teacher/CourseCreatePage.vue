<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <!-- Header -->
      <div>
        <RouterLink to="/teacher/courses" class="text-sm theme-text-muted hover:theme-text">← Quay lại</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Tạo khóa học mới</h1>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-5">

        <!-- Basic info -->
        <div class="theme-card rounded-[30px] p-6 space-y-5">
          <h2 class="text-base font-semibold theme-text">Thông tin cơ bản</h2>

          <div class="form-group">
            <label class="form-label">Tên khóa học <span class="text-red-500">*</span></label>
            <input
              v-model="form.title"
              type="text"
              class="form-input"
              placeholder="VD: IELTS Foundation Course"
              maxlength="255"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Mô tả</label>
            <textarea
              v-model="form.description"
              class="form-input resize-none"
              rows="4"
              placeholder="Mô tả nội dung và mục tiêu khóa học..."
            ></textarea>
          </div>

          <div class="grid gap-5 sm:grid-cols-2">
            <div class="form-group">
              <label class="form-label">Danh mục <span class="text-red-500">*</span></label>
              <select v-model="form.category_id" class="form-input">
                <option value="">Chọn danh mục</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Cấp độ <span class="text-red-500">*</span></label>
              <select v-model="form.level_id" class="form-input">
                <option value="">Chọn cấp độ</option>
                <option v-for="l in levels" :key="l.id" :value="l.id">{{ l.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Pricing & duration -->
        <div class="theme-card rounded-[30px] p-6 space-y-5">
          <h2 class="text-base font-semibold theme-text">Giá & Thời lượng</h2>

          <div class="grid gap-5 sm:grid-cols-2">
            <div class="form-group">
              <label class="form-label">Giá (VNĐ)</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm theme-text-muted">₫</span>
                <input
                  v-model="form.price"
                  type="number"
                  min="0"
                  class="form-input pl-8"
                  placeholder="0 = Miễn phí"
                />
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Thời lượng (phút)</label>
              <div class="relative">
                <input
                  v-model="form.duration"
                  type="number"
                  min="0"
                  class="form-input pr-14"
                  placeholder="VD: 120"
                />
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs theme-text-muted">phút</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Thumbnail -->
        <div class="theme-card rounded-[30px] p-6 space-y-4">
          <div>
            <h2 class="text-base font-semibold theme-text">Ảnh thumbnail</h2>
            <p class="mt-1 text-sm theme-text-muted">Ảnh đại diện hiển thị trên danh sách khóa học.</p>
          </div>
          <UploadInput
            v-model="form.thumbnail"
            type="image"
            folder="thumbnails"
            :show-url="true"
          />
        </div>

        <!-- Status -->
        <div class="theme-card rounded-[30px] p-6 space-y-4">
          <h2 class="text-base font-semibold theme-text">Trạng thái xuất bản</h2>
          <div class="flex flex-col gap-3 sm:flex-row">
            <div
              class="status-option flex-1 flex items-center gap-3 rounded-2xl px-4 py-3 cursor-pointer transition"
              :class="!form.status ? 'is-selected' : ''"
              @click="form.status = 0"
            >
              <span class="text-xl">📝</span>
              <div>
                <p class="text-sm font-semibold theme-text">Bản nháp</p>
                <p class="text-xs theme-text-muted">Chỉ bạn thấy, chưa công khai</p>
              </div>
            </div>
            <div
              class="status-option flex-1 flex items-center gap-3 rounded-2xl px-4 py-3 cursor-pointer transition"
              :class="form.status ? 'is-selected' : ''"
              @click="form.status = 1"
            >
              <span class="text-xl">🌐</span>
              <div>
                <p class="text-sm font-semibold theme-text">Xuất bản</p>
                <p class="text-xs theme-text-muted">Công khai cho học viên đăng ký</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Error -->
        <div v-if="error" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
          ⚠ {{ error }}
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
          <button
            type="submit"
            :disabled="saving"
            class="submit-btn flex-1 rounded-full px-6 py-3 text-sm font-semibold text-white transition disabled:opacity-60 sm:flex-none"
          >
            {{ saving ? 'Đang tạo...' : '+ Tạo khóa học' }}
          </button>
          <RouterLink
            to="/teacher/courses"
            class="cancel-btn rounded-full px-6 py-3 text-sm font-semibold transition"
          >
            Hủy
          </RouterLink>
        </div>
      </form>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { courseService, categoryService, levelService } from '@/services/teacher.service'
import UploadInput from '@/components/upload/UploadInput.vue'
import { useToast } from '@/composables/useToast'

const router = useRouter()
const toast = useToast()
const categories = ref([])
const levels = ref([])
const saving = ref(false)
const error = ref('')

const form = ref({
  title: '', description: '', category_id: '', level_id: '',
  price: 0, duration: 0, thumbnail: '', status: 0,
})

onMounted(async () => {
  const [catRes, lvlRes] = await Promise.all([categoryService.list(), levelService.list()])
  categories.value = catRes.data?.data ?? catRes.data ?? []
  levels.value = lvlRes.data?.data ?? lvlRes.data ?? []
})

async function handleSubmit() {
  // Manual validation
  if (!form.value.title?.trim()) {
    error.value = 'Vui lòng nhập tên khóa học.'
    return
  }

  saving.value = true
  error.value = ''
  try {
    const res = await courseService.create({
      title:       form.value.title,
      description: form.value.description,
      category_id: form.value.category_id || null,
      level_id:    form.value.level_id    || null,
      price:       Number(form.value.price)    || 0,
      duration:    Number(form.value.duration) || 0,
      thumbnail:   form.value.thumbnail   || null,
      status:      form.value.status,
    })
    const newId = res.data?.data?.id ?? res.data?.id
    toast.success('Tạo khóa học thành công!')
    router.push(newId ? `/teacher/courses/${newId}/sections` : '/teacher/courses')
  } catch (e) {
    error.value = e.response?.data?.message
      ?? Object.values(e.response?.data?.errors ?? {}).flat().join(' ')
      ?? 'Có lỗi xảy ra, vui lòng thử lại.'
    toast.error(error.value)
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-label { font-size: 0.875rem; font-weight: 600; color: var(--text); }

.form-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 1rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }

.status-option {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.status-option:hover { background: var(--surface-strong); }
.status-option.is-selected {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 8%, var(--surface-muted));
}

.submit-btn { background: var(--accent); }
.submit-btn:hover:not(:disabled) { opacity: 0.88; }

.cancel-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.cancel-btn:hover { background: var(--surface-strong); }
</style>
