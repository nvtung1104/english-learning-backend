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
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[linear-gradient(145deg,#0f172a_0%,#1e293b_50%,#0c4a6e_100%)] p-10 text-white lg:flex">
          <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-sky-400/10 blur-3xl"></div>
          <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>

          <div class="relative">
            <div class="inline-flex items-center gap-2.5 rounded-full border border-white/10 bg-white/5 px-4 py-2">
              <span class="grid h-7 w-7 place-items-center rounded-full bg-white text-xs font-bold text-slate-950">EN</span>
              <span class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Online English</span>
            </div>

            <h1 class="mt-10 text-4xl font-semibold leading-[1.2] tracking-tight">
              Chào mừng<br />
              quay trở lại.
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/55">
              Tiếp tục hành trình học tiếng Anh của bạn — khóa học, quiz và luyện tập đang chờ.
            </p>

            <div class="mt-10 space-y-3">
              <div v-for="f in features" :key="f.label" class="flex items-center gap-3 rounded-2xl border border-white/8 bg-white/5 px-4 py-3.5">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-base">{{ f.icon }}</div>
                <span class="text-sm text-white/70">{{ f.label }}</span>
              </div>
            </div>
          </div>

          <div class="relative mt-6 grid grid-cols-3 gap-4">
            <div v-for="s in loginStats" :key="s.label" class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">{{ s.value }}</p>
              <p class="mt-0.5 text-xs text-white/50">{{ s.label }}</p>
            </div>
          </div>
        </div>

        <!-- RIGHT — form panel, adapts to theme -->
        <div class="flex flex-col justify-center bg-white px-8 py-12 dark:bg-slate-900 sm:px-12">
          <div class="mx-auto w-full max-w-sm">
            <div class="mb-8">
              <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
              <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Đăng nhập</h2>
              <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Nhập thông tin tài khoản để tiếp tục.</p>
            </div>

            <form class="space-y-5" @submit.prevent="handleLogin">
              <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="you@example.com"
                  required
                  class="auth-input"
                />
              </div>

              <div>
                <div class="mb-1.5 flex items-center justify-between">
                  <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Mật khẩu</label>
                  <RouterLink to="/forgot-password" class="text-xs text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200">
                    Quên mật khẩu?
                  </RouterLink>
                </div>
                <div class="relative">
                  <input
                    v-model="form.password"
                    :type="showPwd ? 'text' : 'password'"
                    placeholder="Nhập mật khẩu"
                    required
                    class="auth-input pr-12"
                  />
                  <button type="button" @click="showPwd = !showPwd" class="absolute inset-y-0 right-3.5 my-auto text-xs font-medium text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200">
                    {{ showPwd ? 'Ẩn' : 'Hiện' }}
                  </button>
                </div>
              </div>

              <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600 dark:text-slate-400">
                <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 accent-slate-900 dark:accent-white" />
                Ghi nhớ đăng nhập
              </label>

              <div v-if="errorMsg" class="auth-error">{{ errorMsg }}</div>

              <button type="submit" :disabled="loading" class="auth-btn">
                <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white"></span>
                {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
              </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
              Chưa có tài khoản?
              <RouterLink to="/register" class="font-semibold text-slate-950 hover:underline dark:text-white">Đăng ký ngay</RouterLink>
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
import statsService from '@/services/stats.service'
import bgParticles from '@/assets/images/auth/CSS-Particles.gif'

const router = useRouter()
const authStore = useAuthStore()

const showPwd = ref(false)
const loading = ref(false)
const errorMsg = ref('')

const form = reactive({ email: '', password: '', remember: false })

const _stats = ref({ lessons_completed: null, practice_total: null, users: null })
const loginStats = computed(() => [
  { value: _stats.value.lessons_completed !== null ? `${_stats.value.lessons_completed}+` : '—', label: 'Bài học' },
  { value: _stats.value.practice_total !== null ? `${_stats.value.practice_total}+` : '—', label: 'Đề luyện' },
  { value: _stats.value.users !== null ? `${_stats.value.users.toLocaleString('vi-VN')}+` : '—', label: 'Học viên' },
])

const features = [
  { icon: '📚', label: 'Hàng trăm khóa học theo lộ trình' },
  { icon: '📝', label: 'Quiz và đề luyện thi thực tế' },
  { icon: '📈', label: 'Theo dõi tiến độ học tập' },
]

onMounted(async () => {
  try {
    const res = await statsService.public()
    _stats.value = res.data
  } catch { /* ignore */ }
})

const handleLogin = async () => {
  errorMsg.value = ''
  loading.value = true
  try {
    const user = await authStore.login({ email: form.email, password: form.password })
    const primaryRole = user.roles?.[0] ?? 'student'
    const routes = { admin: '/admin/dashboard', teacher: '/teacher/dashboard', student: '/student/dashboard' }
    router.push(routes[primaryRole] ?? '/student/dashboard')
  } catch (err) {
    errorMsg.value = err.response?.data?.message ?? 'Email hoặc mật khẩu không đúng.'
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
.auth-input:focus { border-color: rgb(56 189 248); background: white; box-shadow: 0 0 0 3px rgba(125,211,252,0.25); }
:global(.dark) .auth-input { border-color: rgb(51 65 85); background: rgb(30 41 59); color: rgb(241 245 249); }
:global(.dark) .auth-input::placeholder { color: rgb(100 116 139); }
:global(.dark) .auth-input:focus { border-color: rgb(56 189 248); box-shadow: 0 0 0 3px rgba(14,165,233,0.2); }
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
</style>
