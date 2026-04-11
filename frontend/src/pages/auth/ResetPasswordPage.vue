<template>
  <div class="relative min-h-screen overflow-hidden bg-slate-950">
    <!-- Particles background -->
    <img :src="bgParticles" alt="" aria-hidden="true" class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-40" />
    <!-- Dark overlay -->
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-slate-950/80 via-slate-900/60 to-slate-950/80"></div>

    <!-- Floating back button -->
    <RouterLink to="/login" class="absolute left-4 top-4 z-20 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/20">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Quay lại đăng nhập
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
              Thiết lập<br />
              <span class="text-emerald-300">mật khẩu mới</span><br />
              của bạn.
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/55">
              Bảo mật tài khoản giúp bạn an tâm học tập và theo dõi tiến độ một cách liền mạch.
            </p>

            <div class="mt-10 space-y-4">
              <div v-for="tip in securityTips" :key="tip.title" class="flex items-start gap-3 rounded-2xl border border-white/8 bg-white/5 px-4 py-3.5">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-base">{{ tip.icon }}</div>
                <div>
                  <p class="text-sm font-semibold text-white">{{ tip.title }}</p>
                  <p class="mt-0.5 text-xs leading-5 text-white/50">{{ tip.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative grid grid-cols-3 gap-4">
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">An toàn</p>
              <p class="mt-0.5 text-xs text-white/50">Bảo vệ tài khoản</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">Nhanh</p>
              <p class="mt-0.5 text-xs text-white/50">Khôi phục truy cập</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">Mượt</p>
              <p class="mt-0.5 text-xs text-white/50">Tiếp tục học tập</p>
            </div>
          </div>
        </div>

        <!-- RIGHT — form panel -->
        <div class="flex flex-col justify-center bg-white px-8 py-12 dark:bg-slate-900 sm:px-12">
          <div class="mx-auto w-full max-w-sm">

            <!-- INVALID TOKEN -->
            <template v-if="!token">
              <div class="text-center">
                <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-2xl bg-red-500 text-2xl text-white shadow-lg shadow-red-500/30">✕</div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Link không hợp lệ</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
                  Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu lại.
                </p>
              </div>
              <div class="mt-8 space-y-3">
                <RouterLink to="/forgot-password" class="auth-btn block text-center">Gửi lại link đặt lại mật khẩu</RouterLink>
                <RouterLink to="/login" class="auth-btn-outline block text-center">Quay lại đăng nhập</RouterLink>
              </div>
            </template>

            <!-- SUCCESS -->
            <template v-else-if="done">
              <div class="text-center">
                <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-2xl bg-emerald-500 text-2xl text-white shadow-lg shadow-emerald-500/30">✓</div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Đặt lại thành công</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
                  Mật khẩu đã được cập nhật. Hệ thống sẽ chuyển về trang đăng nhập trong giây lát.
                </p>
              </div>
              <RouterLink to="/login" class="auth-btn mt-8 block text-center">Đăng nhập ngay</RouterLink>
            </template>

            <!-- FORM -->
            <template v-else>
              <div class="mb-8">
                <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Đặt lại mật khẩu</h2>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Nhập mật khẩu mới để tiếp tục truy cập tài khoản.</p>
              </div>

              <div v-if="email" class="mb-5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                Đang đặt lại cho: <span class="font-semibold text-slate-900 dark:text-white">{{ email }}</span>
              </div>

              <form class="space-y-5" @submit.prevent="handleSubmit">
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Mật khẩu mới</label>
                  <div class="relative">
                    <input v-model="form.password" :type="showPwd ? 'text' : 'password'" placeholder="Tối thiểu 6 ký tự" required class="auth-input pr-12" />
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
                  <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Xác nhận mật khẩu mới</label>
                  <input v-model="form.password_confirmation" :type="showPwd ? 'text' : 'password'" placeholder="Nhập lại mật khẩu" required class="auth-input" />
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3 text-xs leading-6 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                  Mẹo: dùng chữ hoa, số và ký tự đặc biệt để tăng độ an toàn.
                </div>

                <div v-if="errorMsg" class="auth-error">{{ errorMsg }}</div>

                <button type="submit" :disabled="loading" class="auth-btn">
                  <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white dark:border-slate-900/30 dark:border-t-slate-900"></span>
                  {{ loading ? 'Đang cập nhật...' : 'Đặt lại mật khẩu' }}
                </button>
              </form>
            </template>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import authService from '@/services/auth.service'
import bgParticles from '@/assets/images/auth/CSS-Particles.gif'

const route = useRoute()
const router = useRouter()

const token = route.query.token ?? ''
const email = route.query.email ?? ''

const showPwd = ref(false)
const loading = ref(false)
const done = ref(false)
const errorMsg = ref('')

const form = reactive({ password: '', password_confirmation: '' })

const securityTips = [
  { icon: '🔐', title: 'Mật khẩu mạnh hơn', desc: 'Kết hợp chữ hoa, số và ký tự đặc biệt.' },
  { icon: '⚡', title: 'Khôi phục nhanh', desc: 'Cập nhật mật khẩu mới để tiếp tục học tập.' },
  { icon: '🛡️', title: 'Bảo vệ tài khoản', desc: 'Không chia sẻ mật khẩu với người khác.' },
]

const pwdStrength = computed(() => {
  const p = form.password || ''
  if (!p) return { width: '0%', color: 'bg-slate-300', textColor: 'text-slate-400', label: 'Nhập mật khẩu để kiểm tra' }
  let s = 0
  if (p.length >= 6) s++
  if (/[A-Z]/.test(p)) s++
  if (/[0-9]/.test(p)) s++
  if (/[^A-Za-z0-9]/.test(p)) s++
  if (s <= 1) return { width: '25%', color: 'bg-red-500', textColor: 'text-red-500', label: 'Còn yếu' }
  if (s === 2) return { width: '50%', color: 'bg-amber-500', textColor: 'text-amber-500', label: 'Trung bình' }
  if (s === 3) return { width: '75%', color: 'bg-sky-500', textColor: 'text-sky-500', label: 'Khá tốt' }
  return { width: '100%', color: 'bg-emerald-500', textColor: 'text-emerald-500', label: 'Mạnh' }
})

const handleSubmit = async () => {
  errorMsg.value = ''
  if (form.password !== form.password_confirmation) {
    errorMsg.value = 'Mật khẩu xác nhận không khớp.'
    return
  }
  loading.value = true
  try {
    await authService.resetPassword({
      token, email,
      password: form.password,
      password_confirmation: form.password_confirmation,
    })
    done.value = true
    setTimeout(() => router.push('/login'), 2000)
  } catch (err) {
    const errors = err.response?.data?.errors
    if (errors) {
      errorMsg.value = errors[Object.keys(errors)[0]][0]
    } else {
      errorMsg.value = err.response?.data?.message ?? 'Đặt lại mật khẩu thất bại. Vui lòng thử lại.'
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
.auth-btn-outline {
  display: flex; width: 100%; align-items: center; justify-content: center; gap: 0.5rem;
  border-radius: 0.75rem; border: 1px solid rgb(226 232 240); background: white;
  padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 600; color: rgb(51 65 85); transition: all 0.2s ease;
}
.auth-btn-outline:hover { background: rgb(248 250 252); }
:global(.dark) .auth-btn-outline { border-color: rgb(51 65 85); background: rgb(30 41 59); color: rgb(203 213 225); }
:global(.dark) .auth-btn-outline:hover { background: rgb(51 65 85); }
.auth-error {
  border-radius: 0.75rem; border: 1px solid rgb(254 202 202);
  background: rgb(254 242 242); padding: 0.75rem 1rem; font-size: 0.875rem; color: rgb(185 28 28);
}
:global(.dark) .auth-error { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.1); color: rgb(252 165 165); }
</style>
