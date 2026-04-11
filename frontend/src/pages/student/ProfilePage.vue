<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-2xl space-y-6">

      <!-- Header -->
      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <p class="mt-2 text-sm font-semibold uppercase tracking-[0.28em] theme-text-muted">Học viên</p>
        <h1 class="mt-1 text-3xl font-semibold tracking-tight theme-text">Hồ sơ cá nhân</h1>
      </div>

      <!-- Loading skeleton -->
      <div v-if="pageLoading" class="space-y-6">
        <div class="theme-card rounded-[30px] p-6 animate-pulse flex gap-5">
          <div class="h-24 w-24 rounded-full skeleton shrink-0"></div>
          <div class="flex-1 space-y-3 pt-2">
            <div class="h-5 rounded skeleton w-1/2"></div>
            <div class="h-4 rounded skeleton w-2/3"></div>
            <div class="h-4 rounded skeleton w-1/3"></div>
          </div>
        </div>
        <div class="theme-card rounded-[30px] p-6 animate-pulse space-y-4">
          <div class="h-5 rounded skeleton w-1/3"></div>
          <div class="h-10 rounded-2xl skeleton"></div>
          <div class="h-10 rounded-2xl skeleton"></div>
          <div class="h-10 rounded-2xl skeleton"></div>
        </div>
      </div>

      <template v-else>
        <!-- Avatar + info card -->
        <div class="theme-card rounded-[30px] p-6">
          <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start">

            <!-- Avatar with upload overlay -->
            <div class="relative shrink-0 group">
              <img
                v-if="localAvatar"
                :src="$storage(localAvatar)"
                :alt="user?.name"
                class="h-24 w-24 rounded-full object-cover ring-4 ring-[var(--border)]"
              />
              <div
                v-else
                class="grid h-24 w-24 place-items-center rounded-full text-3xl font-bold text-white ring-4 ring-[var(--border)]"
                style="background: linear-gradient(135deg, var(--accent), #0ea5e9)"
              >
                {{ user?.name?.charAt(0)?.toUpperCase() ?? 'S' }}
              </div>

              <label
                class="absolute inset-0 flex cursor-pointer flex-col items-center justify-center gap-1 rounded-full bg-black/50 opacity-0 transition group-hover:opacity-100"
                :class="{ 'opacity-100': avatarUploading }"
              >
                <span class="text-white text-xl leading-none">
                  {{ avatarUploading ? '⏳' : '📷' }}
                </span>
                <span class="text-white text-[10px] font-semibold leading-none">
                  {{ avatarUploading ? 'Đang tải' : 'Đổi ảnh' }}
                </span>
                <input
                  type="file"
                  accept="image/jpeg,image/png,image/webp,image/gif"
                  class="hidden"
                  :disabled="avatarUploading"
                  @change="handleAvatarUpload"
                />
              </label>
            </div>

            <!-- Info -->
            <div class="text-center sm:text-left min-w-0">
              <p class="text-xl font-semibold theme-text truncate">{{ user?.name }}</p>
              <p class="text-sm theme-text-soft truncate">{{ user?.email }}</p>
              <div class="mt-2 flex flex-wrap justify-center gap-2 sm:justify-start">
                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/50 dark:text-sky-300">
                  Học viên
                </span>
                <span
                  class="rounded-full px-3 py-1 text-xs font-semibold"
                  :class="user?.status
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                    : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300'"
                >
                  {{ user?.status ? 'Hoạt động' : 'Bị khóa' }}
                </span>
              </div>
              <p v-if="user?.phone" class="mt-2 text-sm theme-text-muted">📞 {{ user.phone }}</p>
              <p v-if="user?.last_login" class="mt-1 text-xs theme-text-muted">
                Đăng nhập lần cuối: {{ formatDate(user.last_login) }}
              </p>
              <p class="mt-1 text-xs theme-text-muted opacity-60">Nhấn vào ảnh để thay đổi</p>
            </div>
          </div>
        </div>

        <!-- Stats nhanh -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
          <div
            v-for="s in statCards"
            :key="s.label"
            class="theme-card rounded-[24px] p-4 text-center"
          >
            <p class="text-2xl">{{ s.icon }}</p>
            <p class="mt-2 text-xl font-semibold theme-text">{{ s.value }}</p>
            <p class="mt-0.5 text-xs theme-text-muted">{{ s.label }}</p>
          </div>
        </div>

        <!-- Edit profile form -->
        <form @submit.prevent="handleUpdate" class="theme-card rounded-[30px] p-6 space-y-5">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold theme-text">Cập nhật thông tin</h2>
            <span v-if="saveSuccess" class="text-xs font-semibold text-emerald-500 flex items-center gap-1">
              ✓ Đã lưu
            </span>
          </div>

          <div class="form-group">
            <label class="form-label">Họ và tên <span class="text-red-500">*</span></label>
            <input
              v-model="form.name"
              type="text"
              class="form-input"
              required
              placeholder="Nhập họ và tên"
              maxlength="255"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input
              :value="user?.email"
              type="email"
              class="form-input opacity-60 cursor-not-allowed"
              disabled
            />
            <p class="text-xs theme-text-muted">Email không thể thay đổi.</p>
          </div>

          <div class="form-group">
            <label class="form-label">Số điện thoại</label>
            <input
              v-model="form.phone"
              type="tel"
              class="form-input"
              placeholder="VD: 0912 345 678"
              maxlength="50"
            />
          </div>

          <div v-if="updateError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ updateError }}
          </div>

          <button
            type="submit"
            :disabled="saving"
            class="submit-btn w-full rounded-full px-6 py-3 text-sm font-semibold text-white disabled:opacity-60 transition"
          >
            {{ saving ? 'Đang lưu...' : 'Lưu thay đổi' }}
          </button>
        </form>

        <!-- Change password form -->
        <form @submit.prevent="handlePassword" class="theme-card rounded-[30px] p-6 space-y-5">
          <h2 class="text-lg font-semibold theme-text">Đổi mật khẩu</h2>

          <div class="form-group">
            <label class="form-label">Mật khẩu hiện tại <span class="text-red-500">*</span></label>
            <div class="relative">
              <input
                v-model="pwForm.current_password"
                :type="showPw.current ? 'text' : 'password'"
                class="form-input pr-11"
                required
                autocomplete="current-password"
                placeholder="••••••••"
              />
              <button
                type="button"
                class="pw-eye"
                @click="showPw.current = !showPw.current"
                tabindex="-1"
              >{{ showPw.current ? '🙈' : '👁️' }}</button>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Mật khẩu mới <span class="text-red-500">*</span></label>
            <div class="relative">
              <input
                v-model="pwForm.password"
                :type="showPw.new ? 'text' : 'password'"
                class="form-input pr-11"
                required
                minlength="6"
                autocomplete="new-password"
                placeholder="Tối thiểu 6 ký tự"
              />
              <button
                type="button"
                class="pw-eye"
                @click="showPw.new = !showPw.new"
                tabindex="-1"
              >{{ showPw.new ? '🙈' : '👁️' }}</button>
            </div>

            <!-- Strength bar -->
            <div v-if="pwForm.password" class="mt-1.5 space-y-1">
              <div class="flex gap-1">
                <div
                  v-for="i in 4"
                  :key="i"
                  class="h-1 flex-1 rounded-full transition-all"
                  :style="{ background: i <= pwStrength.level ? pwStrength.color : 'var(--border)' }"
                ></div>
              </div>
              <p class="text-xs" :style="{ color: pwStrength.color }">{{ pwStrength.label }}</p>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Xác nhận mật khẩu mới <span class="text-red-500">*</span></label>
            <div class="relative">
              <input
                v-model="pwForm.password_confirmation"
                :type="showPw.confirm ? 'text' : 'password'"
                class="form-input pr-11"
                required
                autocomplete="new-password"
                placeholder="Nhập lại mật khẩu mới"
              />
              <button
                type="button"
                class="pw-eye"
                @click="showPw.confirm = !showPw.confirm"
                tabindex="-1"
              >{{ showPw.confirm ? '🙈' : '👁️' }}</button>
            </div>
            <p
              v-if="pwForm.password_confirmation && pwForm.password !== pwForm.password_confirmation"
              class="text-xs text-red-500"
            >Mật khẩu không khớp.</p>
          </div>

          <div v-if="pwError" class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
            {{ pwError }}
          </div>

          <button
            type="submit"
            :disabled="pwSaving"
            class="submit-btn w-full rounded-full px-6 py-3 text-sm font-semibold text-white disabled:opacity-60 transition"
          >
            {{ pwSaving ? 'Đang lưu...' : 'Đổi mật khẩu' }}
          </button>
        </form>

        <!-- Account info -->
        <div class="theme-card rounded-[30px] p-6 space-y-3">
          <h2 class="text-lg font-semibold theme-text">Thông tin tài khoản</h2>

          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">ID tài khoản</span>
            <span class="text-sm font-mono theme-text">#{{ user?.id }}</span>
          </div>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Ngày tham gia</span>
            <span class="text-sm theme-text">{{ formatDate(user?.created_at) }}</span>
          </div>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Xác thực email</span>
            <span
              class="rounded-full px-3 py-1 text-xs font-semibold"
              :class="user?.email_verified_at
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'
                : 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300'"
            >
              {{ user?.email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
            </span>
          </div>
          <div class="info-row flex items-center justify-between rounded-2xl px-4 py-3">
            <span class="text-sm theme-text-soft">Số điện thoại</span>
            <span class="text-sm theme-text">{{ user?.phone || '—' }}</span>
          </div>
        </div>

        <!-- Danger zone -->
        <div class="theme-card rounded-[30px] p-6 space-y-3" style="border: 1px solid color-mix(in srgb, var(--danger, #ef4444) 20%, var(--border))">
          <h2 class="text-lg font-semibold text-red-500">Vùng nguy hiểm</h2>
          <p class="text-sm theme-text-soft">Đăng xuất khỏi tài khoản trên thiết bị này.</p>
          <button
            type="button"
            class="logout-btn rounded-full px-5 py-2.5 text-sm font-semibold transition"
            @click="handleLogout"
          >
            Đăng xuất
          </button>
        </div>
      </template>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToast } from '@/composables/useToast'
import statsService from '@/services/stats.service'
import api from '@/services/axios'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()
const user = computed(() => authStore.user)

// ─── Page load ────────────────────────────────────────────────────
const pageLoading = ref(true)
const stats = ref({ enrollments: null, lessons_completed: null, quiz_attempts: null, avg_score: null })

onMounted(async () => {
  try {
    const [profileRes, statsRes] = await Promise.allSettled([
      api.get('/profile'),
      statsService.student(),
    ])

    if (profileRes.status === 'fulfilled') {
      const data = profileRes.value.data?.data ?? profileRes.value.data
      authStore.user = { ...authStore.user, ...data }
      localStorage.setItem('user', JSON.stringify(authStore.user))
    }

    if (statsRes.status === 'fulfilled') {
      stats.value = statsRes.value.data
    }
  } finally {
    pageLoading.value = false
    // Sync form sau khi có data
    form.value.name  = user.value?.name  ?? ''
    form.value.phone = user.value?.phone ?? ''
    localAvatar.value = user.value?.avatar ?? ''
  }
})

const statCards = computed(() => [
  { icon: '📚', label: 'Khóa học', value: stats.value.enrollments ?? '—' },
  { icon: '✅', label: 'Bài hoàn thành', value: stats.value.lessons_completed ?? '—' },
  { icon: '📝', label: 'Quiz đã làm', value: stats.value.quiz_attempts ?? '—' },
  { icon: '📈', label: 'Điểm TB', value: stats.value.avg_score != null ? stats.value.avg_score + '%' : '—' },
])

// ─── Avatar ───────────────────────────────────────────────────────
const localAvatar = ref(user.value?.avatar ?? '')
const avatarUploading = ref(false)

async function handleAvatarUpload(e) {
  const file = e.target.files?.[0]
  if (!file) return

  const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif']
  if (!allowed.includes(file.type)) {
    toast.error('Chỉ chấp nhận ảnh JPG, PNG, WebP, GIF.')
    e.target.value = ''
    return
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ảnh không được vượt quá 10MB.')
    e.target.value = ''
    return
  }

  avatarUploading.value = true
  try {
    const fd = new FormData()
    fd.append('avatar', file)
    const res = await api.post('/profile/upload-avatar', fd, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    const url = res.data?.data?.url ?? ''
    localAvatar.value = url
    authStore.user = { ...authStore.user, avatar: url }
    localStorage.setItem('user', JSON.stringify(authStore.user))
    toast.success('Đã cập nhật ảnh đại diện!')
  } catch (err) {
    toast.error(err.response?.data?.message ?? 'Tải ảnh thất bại, thử lại.')
  } finally {
    avatarUploading.value = false
    e.target.value = ''
  }
}

// ─── Update profile ───────────────────────────────────────────────
const form = ref({ name: user.value?.name ?? '', phone: user.value?.phone ?? '' })
const saving = ref(false)
const saveSuccess = ref(false)
const updateError = ref('')

async function handleUpdate() {
  saving.value = true
  updateError.value = ''
  saveSuccess.value = false
  try {
    const res = await api.put('/profile', {
      name:   form.value.name.trim(),
      phone:  form.value.phone.trim() || null,
      avatar: localAvatar.value || null,
    })
    const updated = res.data?.data ?? res.data
    authStore.user = { ...authStore.user, ...updated }
    localStorage.setItem('user', JSON.stringify(authStore.user))
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
    toast.success('Đã cập nhật thông tin thành công!')
  } catch (e) {
    const errors = e.response?.data?.errors
    updateError.value = errors
      ? Object.values(errors).flat().join(' ')
      : (e.response?.data?.message ?? 'Có lỗi xảy ra, thử lại.')
    toast.error(updateError.value)
  } finally {
    saving.value = false
  }
}

// ─── Change password ──────────────────────────────────────────────
const pwForm = ref({ current_password: '', password: '', password_confirmation: '' })
const pwSaving = ref(false)
const pwError = ref('')
const showPw = ref({ current: false, new: false, confirm: false })

const pwStrength = computed(() => {
  const p = pwForm.value.password
  if (!p) return { level: 0, label: '', color: '' }
  let score = 0
  if (p.length >= 6)  score++
  if (p.length >= 10) score++
  if (/[A-Z]/.test(p) && /[0-9]/.test(p)) score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  const map = [
    { level: 1, label: 'Yếu', color: '#ef4444' },
    { level: 2, label: 'Trung bình', color: '#f59e0b' },
    { level: 3, label: 'Khá mạnh', color: '#3b82f6' },
    { level: 4, label: 'Mạnh', color: '#22c55e' },
  ]
  return map[Math.min(score, 3)]
})

async function handlePassword() {
  if (pwForm.value.password !== pwForm.value.password_confirmation) {
    toast.error('Mật khẩu xác nhận không khớp.')
    return
  }
  pwSaving.value = true
  pwError.value = ''
  try {
    await api.post('/profile/change-password', pwForm.value)
    toast.success('Đã đổi mật khẩu thành công!')
    pwForm.value = { current_password: '', password: '', password_confirmation: '' }
  } catch (e) {
    const errors = e.response?.data?.errors
    pwError.value = errors
      ? Object.values(errors).flat().join(' ')
      : (e.response?.data?.message ?? 'Có lỗi xảy ra.')
    toast.error(pwError.value)
  } finally {
    pwSaving.value = false
  }
}

// ─── Logout ───────────────────────────────────────────────────────
async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

// ─── Helpers ──────────────────────────────────────────────────────
function formatDate(date) {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('vi-VN', {
    year: 'numeric', month: 'long', day: 'numeric',
  })
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
.form-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
}

.pw-eye {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  line-height: 1;
  padding: 0;
  opacity: 0.6;
  transition: opacity 0.15s;
}
.pw-eye:hover { opacity: 1; }

.info-row {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}

.submit-btn {
  background: var(--accent);
}
.submit-btn:hover:not(:disabled) { opacity: 0.88; }

.logout-btn {
  border: 1px solid color-mix(in srgb, #ef4444 30%, var(--border));
  color: #ef4444;
  background: color-mix(in srgb, #ef4444 8%, var(--surface-muted));
}
.logout-btn:hover {
  background: color-mix(in srgb, #ef4444 14%, var(--surface-muted));
}

.skeleton { background: var(--border); }
</style>
