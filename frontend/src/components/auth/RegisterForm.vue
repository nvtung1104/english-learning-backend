<template>
  <div>
    <div class="mb-6">
      <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
      <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Tạo tài khoản</h2>
      <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Điền thông tin để bắt đầu học ngay.</p>
    </div>

    <form class="space-y-4" @submit.prevent="handleRegister">
      <!-- Role -->
      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Vai trò</label>
        <div class="grid grid-cols-2 gap-2">
          <button
            v-for="r in roles" :key="r.value" type="button"
            @click="form.role = r.value"
            class="role-btn"
            :class="form.role === r.value ? 'role-btn--active' : 'role-btn--idle'"
          >
            <span class="text-base">{{ r.icon }}</span>
            <div class="text-left">
              <p class="text-sm font-semibold leading-none">{{ r.label }}</p>
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
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import authService from '@/services/auth.service'

const router = useRouter()
const authStore = useAuthStore()

const showPwd = ref(false)
const loading = ref(false)
const errorMsg = ref('')
const successMsg = ref('')

const roles = [
  { label: 'Học viên', value: 'student', icon: '🎓', desc: 'Học và luyện tập' },
  { label: 'Giáo viên', value: 'teacher', icon: '👨‍🏫', desc: 'Tạo nội dung' },
]

const form = reactive({
  name: '', email: '', password: '', password_confirmation: '',
  role: 'student', accept_terms: false,
})

const pwdStrength = computed(() => {
  const p = form.password || ''
  if (!p) return { width: '0%', color: 'bg-slate-300 dark:bg-slate-600', textColor: 'text-slate-400', label: 'Nhập mật khẩu để kiểm tra' }
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
    errorMsg.value = errors ? errors[Object.keys(errors)[0]][0] : (err.response?.data?.message ?? 'Đăng ký thất bại.')
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
  border-color: rgb(52 211 153);
  background: white;
  box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2);
}
:global(.dark) .auth-input {
  border-color: rgb(51 65 85);
  background: rgb(30 41 59);
  color: rgb(241 245 249);
}
:global(.dark) .auth-input::placeholder { color: rgb(100 116 139); }
:global(.dark) .auth-input:focus {
  border-color: rgb(52 211 153);
  box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.15);
}
.role-btn {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  border-radius: 0.75rem;
  border: 1px solid;
  padding: 0.75rem;
  transition: all 0.15s ease;
}
.role-btn--active {
  border-color: rgb(2 6 23);
  background: rgb(2 6 23);
  color: white;
}
.role-btn--idle {
  border-color: rgb(226 232 240);
  background: rgb(248 250 252);
  color: rgb(51 65 85);
}
.role-btn--idle:hover { border-color: rgb(203 213 225); }
:global(.dark) .role-btn--active {
  border-color: white;
  background: white;
  color: rgb(2 6 23);
}
:global(.dark) .role-btn--idle {
  border-color: rgb(51 65 85);
  background: rgb(30 41 59);
  color: rgb(203 213 225);
}
:global(.dark) .role-btn--idle:hover { border-color: rgb(71 85 105); }
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
.auth-success {
  border-radius: 0.75rem;
  border: 1px solid rgb(167 243 208);
  background: rgb(236 253 245);
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: rgb(4 120 87);
}
:global(.dark) .auth-success {
  border-color: rgba(52, 211, 153, 0.3);
  background: rgba(52, 211, 153, 0.1);
  color: rgb(110 231 183);
}
</style>
