<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <!-- Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/courses" class="text-sm theme-text-muted hover:theme-text">← Quay lại</RouterLink>
          <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Giáo viên</p>
          <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Chỉnh sửa khóa học</h1>
        </div>
        <RouterLink
          :to="`/teacher/courses/${courseId}/sections`"
          class="self-start sections-btn rounded-full px-5 py-2.5 text-sm font-semibold transition"
        >
          Quản lý Sections →
        </RouterLink>
      </div>

      <!-- Loading skeleton -->
      <div v-if="loading" class="space-y-5">
        <div class="theme-card rounded-[30px] p-6 animate-pulse space-y-4">
          <div class="h-4 rounded skeleton w-1/4"></div>
          <div class="h-10 rounded-2xl skeleton"></div>
          <div class="h-24 rounded-2xl skeleton"></div>
          <div class="grid grid-cols-2 gap-4">
            <div class="h-10 rounded-2xl skeleton"></div>
            <div class="h-10 rounded-2xl skeleton"></div>
          </div>
        </div>
        <div class="theme-card rounded-[30px] p-6 animate-pulse space-y-4">
          <div class="h-4 rounded skeleton w-1/4"></div>
          <div class="grid grid-cols-2 gap-4">
            <div class="h-10 rounded-2xl skeleton"></div>
            <div class="h-10 rounded-2xl skeleton"></div>
          </div>
        </div>
      </div>

      <form v-else @submit.prevent="handleSubmit" class="space-y-5">

        <!-- Basic info -->
        <div class="theme-card rounded-[30px] p-6 space-y-5">
          <h2 class="text-base font-semibold theme-text">Thông tin cơ bản</h2>

          <div class="form-group">
            <label class="form-label">Tên khóa học <span class="text-red-500">*</span></label>
            <input
              v-model="form.title"
              type="text"
              class="form-input"
              maxlength="255"
              placeholder="Tên khóa học"
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
              <label class="form-label">Danh mục</label>
              <select v-model="form.category_id" class="form-input">
                <option value="">Chọn danh mục</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Cấp độ</label>
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
        <div v-if="updateError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
          ⚠ {{ updateError }}
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <button
            type="submit"
            :disabled="saving"
            class="submit-btn flex-1 rounded-full px-6 py-3 text-sm font-semibold text-white transition disabled:opacity-60 sm:flex-none"
          >
            {{ saving ? 'Đang lưu...' : 'Lưu thay đổi' }}
          </button>
          <span v-if="saveSuccess" class="text-sm font-semibold text-emerald-500">✓ Đã lưu</span>
          <RouterLink
            to="/teacher/courses"
            class="cancel-btn ml-auto rounded-full px-6 py-3 text-sm font-semibold transition"
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
import { RouterLink } from 'vue-router'
import { courseService, categoryService, levelService } from '@/services/teacher.service'
import UploadInput from '@/components/upload/UploadInput.vue'
import { useToast } from '@/composables/useToast'

const props = defineProps({ courseId: { type: String, required: true } })
const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const saveSuccess = ref(false)
const updateError = ref('')
const categories = ref([])
const levels = ref([])

const form = ref({
  title: '', description: '', category_id: '', level_id: '',
  price: 0, duration: 0, thumbnail: '', status: 0,
})

onMounted(async () => {
  try {
    const [courseRes, catRes, lvlRes] = await Promise.all([
      courseService.get(props.courseId),
      categoryService.list(),
      levelService.list(),
    ])
    const c = courseRes.data?.data ?? courseRes.data
    form.value = {
      title:       c.title        ?? '',
      description: c.description  ?? '',
      category_id: c.category_id  ?? '',
      level_id:    c.level_id     ?? '',
      price:       c.price        ?? 0,
      duration:    c.duration     ?? 0,
      thumbnail:   c.thumbnail    ?? '',
      status:      Number(c.status ?? 0),
    }
    categories.value = catRes.data?.data ?? catRes.data ?? []
    levels.value     = lvlRes.data?.data ?? lvlRes.data ?? []
  } finally {
    loading.value = false
  }
})

async function handleSubmit() {
  if (!form.value.title?.trim()) {
    updateError.value = 'Vui lòng nhập tên khóa học.'
    return
  }

  saving.value = true
  updateError.value = ''
  saveSuccess.value = false
  try {
    await courseService.update(props.courseId, {
      title:       form.value.title,
      description: form.value.description,
      category_id: form.value.category_id || null,
      level_id:    form.value.level_id    || null,
      price:       Number(form.value.price)    || 0,
      duration:    Number(form.value.duration) || 0,
      thumbnail:   form.value.thumbnail   || null,
      status:      form.value.status,
    })
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
    toast.success('Đã lưu thay đổi thành công!')
  } catch (e) {
    const msg = e.response?.data?.message
      ?? Object.values(e.response?.data?.errors ?? {}).flat().join(' ')
      ?? 'Có lỗi xảy ra.'
    updateError.value = msg
    toast.error(msg)
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

.sections-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.sections-btn:hover { background: var(--surface-strong); }

.skeleton { background: var(--border); }
</style>
