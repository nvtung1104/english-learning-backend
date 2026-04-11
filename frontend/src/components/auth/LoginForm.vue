<template>
  <div>
    <div class="mb-8">
      <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
      <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Đăng nhập</h2>
      <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Nhập thông tin tài khoản để tiếp tục.</p>
    </div>

    <form class="space-y-5" @submit.prevent="handleLogin">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
        <input v-model="form.email" type="email" placeholder="you@example.com" required class="auth-input" />
      </div>

      <div>
        <div class="mb-1.5 flex items-center justify-between">
          <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Mật khẩu</label>
          <RouterLink to="/forgot-password" class="text-xs text-slate-400 transition hover:text-slate-700 dark:hover:text-slate-200">
            Quên mật khẩu?
          </RouterLink>
        </div>
        <div class="relative">
          <input v-model="form.password" :type="showPwd ? 'text' : 'password'" placeholder="Nhập mật khẩu" required class="auth-input pr-12" />
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
        <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white dark:border-slate-900/30 dark:border-t-slate-900"></span>
        {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
      Chưa có tài khoản?
      <RouterLink to="/register" class="font-semibold text-slate-950 hover:underline dark:text-white">Đăng ký ngay</RouterLink>
    </p>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const showPwd = ref(false)
const loading = ref(false)
const errorMsg = ref('')
const form = reactive({ email: '', password: '', remember: false })

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
  width: 100%;
  border-radius: 0.75rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(248 250 252);
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: rgb(15 23 42);
  outline: none;
  transition: all 0.2s ease;
}
.auth-input::placeholder { color: rgb(148 163 184); }
.auth-input:focus {
  border-color: rgb(56 189 248);
  background: white;
  box-shadow: 0 0 0 3px rgba(125, 211, 252, 0.25);
}
:global(.dark) .auth-input {
  border-color: rgb(51 65 85);
  background: rgb(30 41 59);
  color: rgb(241 245 249);
}
:global(.dark) .auth-input::placeholder { color: rgb(100 116 139); }
:global(.dark) .auth-input:focus {
  border-color: rgb(56 189 248);
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
}
.auth-btn {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  border-radius: 0.75rem;
  background: rgb(2 6 23);
  padding: 0.75rem 1.25rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: white;
  transition: all 0.2s ease;
}
.auth-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(15,23,42,0.22); }
.auth-btn:disabled { cursor: not-allowed; opacity: 0.6; transform: none; }
:global(.dark) .auth-btn { background: white; color: rgb(2 6 23); }
:global(.dark) .auth-btn:hover { box-shadow: 0 8px 24px rgba(255,255,255,0.08); }
.auth-error {
  border-radius: 0.75rem;
  border: 1px solid rgb(254 202 202);
  background: rgb(254 242 242);
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: rgb(185 28 28);
}
:global(.dark) .auth-error {
  border-color: rgba(239, 68, 68, 0.3);
  background: rgba(239, 68, 68, 0.1);
  color: rgb(252 165 165);
}
</style>
