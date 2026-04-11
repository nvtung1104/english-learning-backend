<template>
  <div class="relative min-h-screen overflow-hidden bg-slate-950">
    <!-- Particles background -->
    <img :src="bgParticles" alt="" aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" />
    <!-- Dark overlay -->
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-slate-950/80 via-slate-900/60 to-slate-950/80"></div>

    <!-- Floating back button -->
    <RouterLink to="/" class="absolute left-4 top-4 z-20 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/20">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Trang chủ
    </RouterLink>

    <div class="relative z-10 flex min-h-screen items-center justify-center p-4 py-10">
      <div class="grid w-full max-w-5xl overflow-hidden rounded-[32px] shadow-[0_32px_80px_rgba(15,23,42,0.14)] dark:shadow-[0_32px_80px_rgba(0,0,0,0.5)] lg:grid-cols-[1fr_1fr]">

        <!-- LEFT — always dark brand panel -->
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[linear-gradient(145deg,#0f172a_0%,#1e293b_50%,#064e3b_100%)] p-10 text-white lg:flex">
          <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-400/10 blur-3xl"></div>
          <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>

          <div class="relative">
            <div class="inline-flex items-center gap-2.5 rounded-full border border-white/10 bg-white/5 px-4 py-2">
              <span class="grid h-7 w-7 place-items-center rounded-full bg-white text-xs font-bold text-slate-950">EN</span>
              <span class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Online English</span>
            </div>

            <h1 class="mt-10 text-4xl font-semibold leading-[1.2] tracking-tight">
              Bắt đầu hành trình<br />
              <span class="text-emerald-300">học tiếng Anh</span><br />
              của bạn.
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/55">
              Truy cập khóa học, quiz, luyện nghe và theo dõi tiến độ trên một nền tảng hiện đại.
            </p>

            <div class="mt-10 space-y-4">
              <div v-for="item in highlights" :key="item.title" class="flex items-start gap-3 rounded-2xl border border-white/8 bg-white/5 px-4 py-3.5">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-base">{{ item.icon }}</div>
                <div>
                  <p class="text-sm font-semibold text-white">{{ item.title }}</p>
                  <p class="mt-0.5 text-xs leading-5 text-white/50">{{ item.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative grid grid-cols-3 gap-4">
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">{{ registerStats.lessons }}</p>
              <p class="mt-0.5 text-xs text-white/50">Bài học</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">{{ registerStats.practice }}</p>
              <p class="mt-0.5 text-xs text-white/50">Đề luyện</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">Miễn phí</p>
              <p class="mt-0.5 text-xs text-white/50">Đăng ký</p>
            </div>
          </div>
        </div>

        <!-- RIGHT — form panel -->
        <div class="flex flex-col justify-center bg-white px-8 py-12 dark:bg-slate-900 sm:px-12">
          <div class="mx-auto w-full max-w-sm">
            <div class="mb-6">
              <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
              <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Tạo tài khoản</h2>
              <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Điền thông tin để bắt đầu học ngay.</p>
            </div>

            <form class="space-y-4" @submit.prevent="handleRegister">
              <!-- Role selector -->
              <div>
                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Vai trò</label>
                <div class="grid grid-cols-2 gap-2">
                  <button
                    v-for="r in roles" :key="r.value" type="button"
                    @click="form.role = r.value"
                    class="flex items-center gap-2.5 rounded-xl border px-3.5 py-3 text-left text-sm transition"
                    :class="form.role === r.value
                      ? 'border-slate-900 bg-slate-950 text-white dark:border-white dark:bg-white dark:text-slate-950'
                      : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600'"
                  >
                    <span class="text-base">{{ r.icon }}</span>
                    <div>
                      <p class="font-semibold leading-none">{{ r.label }}</p>
                      <p class="mt-1 text-xs leading-4 opacity-60">{{ r.desc }}</p>
                    </div>
                  </button>
                </div>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Họ và tên</label>
                <input v-model="form.name" type="text" placeholder="Nguyễn Văn A" required class="auth-input" />
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                <input v-model="form.email" type="email" placeholder="you@example.com" required class="auth-input" />
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Mật khẩu</label>
                <div class="relative">
                  <input v-model="form.password" :type="showPwd ? 'text' : 'password'" placeholder="Tối thiểu 8 ký tự" required class="auth-input pr-12" />
                  <button type="button" @click="showPwd = !showPwd" class="absolute inset-y-0 right-3.5 my-auto text-xs font-medium text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200">
                    {{ showPwd ? 'Ẩn' : 'Hiện' }}
                  </button>
                </div>
                <!-- strength bar -->
                <div class="mt-2">
                  <div class="h-1.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                    <div class="h-full rounded-full transition-all duration-300" :class="pwdStrength.color" :style="{ width: pwdStrength.width }"></div>
                  </div>
                  <p class="mt-1 text-xs" :class="pwdStrength.textColor">{{ pwdStrength.label }}</p>
                </div>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Xác nhận mật khẩu</label>
                <input v-model="form.password_confirmation" :type="showPwd ? 'text' : 'password'" placeholder="Nhập lại mật khẩu" required class="auth-input" />
              </div>

              <label class="flex cursor-pointer items-start gap-2.5 rounded-xl bg-slate-50 px-3.5 py-3 text-sm text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                <input v-model="form.accept_terms" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-slate-900 dark:accent-white" />
                <span>Tôi đồng ý với điều khoản sử dụng và chính sách bảo mật.</span>
              </label>

              <div v-if="errorMsg" class="auth-error">{{ errorMsg }}</div>
              <div v-if="successMsg" class="auth-success">{{ successMsg }}</div>

              <button type="submit" :disabled="loading" class="auth-btn">
                <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white dark:border-slate-900/30 dark:border-t-slate-900"></span>
                {{ loading ? 'Đang tạo tài khoản...' : 'Tạo tài khoản miễn phí' }}
              </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
              Đã có tài khoản?
              <RouterLink to="/login" class="font-semibold text-slate-950 hover:underline dark:text-white">Đăng nhập ngay</RouterLink>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import authService from '@/services/auth.service'
import statsService from '@/services/stats.service'
import bgParticles from '@/assets/images/auth/CSS-Particles.gif'

const router = useRouter()
const authStore = useAuthStore()

const showPwd = ref(false)
const loading = ref(false)
const errorMsg = ref('')
const successMsg = ref('')

const _stats = ref({ lessons_completed: null, practice_total: null })
const registerStats = computed(() => ({
  lessons: _stats.value.lessons_completed !== null ? `${_stats.value.lessons_completed}+` : '—',
  practice: _stats.value.practice_total !== null ? `${_stats.value.practice_total}+` : '—',
}))

onMounted(async () => {
  try {
    const res = await statsService.public()
    _stats.value = res.data
  } catch { /* ignore */ }
})

const roles = [
  { label: 'Học viên', value: 'student', icon: '🎓', desc: 'Học và luyện tập' },
  { label: 'Giáo viên', value: 'teacher', icon: '👨‍🏫', desc: 'Tạo nội dung' },
]

const highlights = [
  { icon: '📚', title: 'Học theo lộ trình', desc: 'Khóa học chia section và lesson rõ ràng.' },
  { icon: '📝', title: 'Luyện tập thường xuyên', desc: 'Quiz và mock test theo từng kỹ năng.' },
  { icon: '📈', title: 'Theo dõi tiến độ', desc: 'Xem kết quả và hành trình học tập.' },
]

const form = reactive({
  name: '', email: '', password: '', password_confirmation: '',
  role: 'student', accept_terms: false,
})

const pwdStrength = computed(() => {
  const p = form.password || ''
  if (!p) return { width: '0%', color: 'bg-slate-300', textColor: 'text-slate-400', label: 'Nhập mật khẩu để kiểm tra' }
  let s = 0
  if (p.length >= 8) s++
  if (/[A-Z]/.test(p)) s++
  if (/[0-9]/.test(p)) s++
  if (/[^A-Za-z0-9]/.test(p)) s++
  if (s <= 1) return { width: '25%', color: 'bg-red-500', textColor: 'text-red-500', label: 'Còn yếu' }
  if (s === 2) return { width: '50%', color: 'bg-amber-500', textColor: 'text-amber-500', label: 'Trung bình' }
  if (s === 3) return { width: '75%', color: 'bg-sky-500', textColor: 'text-sky-500', label: 'Khá tốt' }
  return { width: '100%', color: 'bg-emerald-500', textColor: 'text-emerald-500', label: 'Mạnh' }
})

const handleRegister = async () => {
  errorMsg.value = ''
  successMsg.value = ''
  if (!form.accept_terms) { errorMsg.value = 'Bạn cần đồng ý với điều khoản sử dụng.'; return }
  if (form.password !== form.password_confirmation) { errorMsg.value = 'Mật khẩu xác nhận không khớp.'; return }
  loading.value = true
  try {
    const res = await authService.register({
      name: form.name, email: form.email,
      password: form.password, password_confirmation: form.password_confirmation,
      role: form.role,
    })
    const data = res.data
    authStore.setAuth(data.token, data.user)
    const primaryRole = data.user?.roles?.[0] ?? 'student'
    const routes = { admin: '/admin/dashboard', teacher: '/teacher/dashboard', student: '/student/dashboard' }
    successMsg.value = 'Tạo tài khoản thành công. Đang chuyển hướng...'
    setTimeout(() => router.push(routes[primaryRole] ?? '/student/dashboard'), 700)
  } catch (err) {
    const errors = err.response?.data?.errors
    if (errors) {
      errorMsg.value = errors[Object.keys(errors)[0]][0]
    } else {
      errorMsg.value = err.response?.data?.message ?? 'Đăng ký thất bại. Vui lòng thử lại.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-input {
  width: 100%; border-radius: 0.75rem; border: 1px solid rgb(226 232 240);
  background: rgb(248 250 252); padding: 0.75rem 1rem; font-size: 0.875rem;
  color: rgb(15 23 42); outline: none; transition: all 0.2s ease;
}
.auth-input::placeholder { color: rgb(148 163 184); }
.auth-input:focus { border-color: rgb(52 211 153); background: white; box-shadow: 0 0 0 3px rgba(52,211,153,0.2); }
:global(.dark) .auth-input { border-color: rgb(51 65 85); background: rgb(30 41 59); color: rgb(241 245 249); }
:global(.dark) .auth-input::placeholder { color: rgb(100 116 139); }
:global(.dark) .auth-input:focus { border-color: rgb(52 211 153); box-shadow: 0 0 0 3px rgba(52,211,153,0.15); }
.auth-btn {
  display: flex; width: 100%; align-items: center; justify-content: center; gap: 0.5rem;
  border-radius: 0.75rem; background: rgb(2 6 23); padding: 0.75rem 1.25rem;
  font-size: 0.875rem; font-weight: 600; color: white; transition: all 0.2s ease;
}
.auth-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(15,23,42,0.22); }
.auth-btn:disabled { cursor: not-allowed; opacity: 0.6; transform: none; }
:global(.dark) .auth-btn { background: white; color: rgb(2 6 23); }
.auth-error {
  border-radius: 0.75rem; border: 1px solid rgb(254 202 202);
  background: rgb(254 242 242); padding: 0.75rem 1rem; font-size: 0.875rem; color: rgb(185 28 28);
}
:global(.dark) .auth-error { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.1); color: rgb(252 165 165); }
.auth-success {
  border-radius: 0.75rem; border: 1px solid rgb(167 243 208);
  background: rgb(236 253 245); padding: 0.75rem 1rem; font-size: 0.875rem; color: rgb(4 120 87);
}
:global(.dark) .auth-success { border-color: rgba(52,211,153,0.3); background: rgba(52,211,153,0.1); color: rgb(110 231 183); }
</style>
