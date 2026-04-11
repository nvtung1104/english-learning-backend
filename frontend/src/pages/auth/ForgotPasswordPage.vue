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
        <div class="relative hidden flex-col justify-between overflow-hidden bg-[linear-gradient(145deg,#0f172a_0%,#1e293b_50%,#1e1b4b_100%)] p-10 text-white lg:flex">
          <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-violet-400/10 blur-3xl"></div>
          <div class="absolute -bottom-20 -left-10 h-72 w-72 rounded-full bg-sky-500/10 blur-3xl"></div>

          <div class="relative">
            <div class="inline-flex items-center gap-2.5 rounded-full border border-white/10 bg-white/5 px-4 py-2">
              <span class="grid h-7 w-7 place-items-center rounded-full bg-white text-xs font-bold text-slate-950">EN</span>
              <span class="text-xs font-semibold uppercase tracking-[0.22em] text-white/60">Online English</span>
            </div>

            <h1 class="mt-10 text-4xl font-semibold leading-[1.2] tracking-tight">
              Khôi phục<br />
              <span class="text-violet-300">tài khoản</span><br />
              của bạn.
            </h1>
            <p class="mt-4 text-sm leading-7 text-white/55">
              Đừng lo nếu bạn quên mật khẩu. Chúng tôi sẽ giúp bạn lấy lại quyền truy cập chỉ trong vài bước.
            </p>

            <div class="mt-10 space-y-3">
              <div v-for="tip in tips" :key="tip.title" class="flex items-start gap-3 rounded-2xl border border-white/8 bg-white/5 px-4 py-3.5">
                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white/10 text-base">{{ tip.icon }}</div>
                <div>
                  <p class="text-sm font-semibold text-white">{{ tip.title }}</p>
                  <p class="mt-0.5 text-xs leading-5 text-white/50">{{ tip.desc }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="relative mt-6 grid grid-cols-3 gap-4">
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">Nhanh</p>
              <p class="mt-0.5 text-xs text-white/50">Chỉ 30 giây</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">An toàn</p>
              <p class="mt-0.5 text-xs text-white/50">Bảo mật cao</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/5 p-4">
              <p class="text-xl font-semibold">3 bước</p>
              <p class="mt-0.5 text-xs text-white/50">Đơn giản</p>
            </div>
          </div>
        </div>

        <!-- RIGHT — form panel -->
        <div class="flex flex-col justify-center bg-white px-8 py-12 dark:bg-slate-900 sm:px-12">
          <div class="mx-auto w-full max-w-sm">

            <!-- SUCCESS STATE -->
            <template v-if="sent">
              <div class="text-center">
                <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-2xl bg-emerald-500 text-2xl text-white shadow-lg shadow-emerald-500/30">✓</div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Kiểm tra email</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
                  Link đặt lại mật khẩu đã được gửi đến<br />
                  <span class="font-semibold text-slate-900 dark:text-white">{{ form.email }}</span>
                </p>
              </div>

              <div class="mt-8 space-y-3">
                <button @click="handleSubmit" :disabled="loading" class="auth-btn-outline">
                  <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-slate-400/30 border-t-slate-700 dark:border-t-white"></span>
                  Gửi lại email
                </button>
                <RouterLink to="/login" class="auth-btn block text-center">Quay lại đăng nhập</RouterLink>
              </div>
            </template>

            <!-- FORM STATE -->
            <template v-else>
              <div class="mb-8">
                <div class="mb-4 grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">EN</div>
                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Quên mật khẩu?</h2>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Nhập email để nhận link đặt lại mật khẩu.</p>
              </div>

              <form class="space-y-5" @submit.prevent="handleSubmit">
                <div>
                  <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                  <input v-model="form.email" type="email" placeholder="you@example.com" required class="auth-input" />
                </div>

                <div v-if="errorMsg" class="auth-error">{{ errorMsg }}</div>

                <button type="submit" :disabled="loading" class="auth-btn">
                  <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white dark:border-slate-900/30 dark:border-t-slate-900"></span>
                  {{ loading ? 'Đang gửi...' : 'Gửi link đặt lại mật khẩu' }}
                </button>
              </form>

              <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Nhớ mật khẩu?
                <RouterLink to="/login" class="font-semibold text-slate-950 hover:underline dark:text-white">Đăng nhập</RouterLink>
              </p>
            </template>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import authService from '@/services/auth.service'
import bgParticles from '@/assets/images/auth/CSS-Particles.gif'

const loading = ref(false)
const sent = ref(false)
const errorMsg = ref('')
const form = reactive({ email: '' })

const tips = [
  { icon: '📩', title: 'Nhận email nhanh', desc: 'Link được gửi ngay lập tức đến hộp thư.' },
  { icon: '🔒', title: 'Bảo mật cao', desc: 'Token reset có thời hạn, dùng một lần.' },
  { icon: '⚡', title: 'Khôi phục nhanh', desc: 'Chỉ mất vài bước đơn giản.' },
]

const handleSubmit = async () => {
  errorMsg.value = ''
  loading.value = true
  try {
    await authService.forgotPassword(form.email)
    sent.value = true
  } catch (err) {
    const errors = err.response?.data?.errors
    errorMsg.value = errors?.email?.[0] || err.response?.data?.message || 'Không thể gửi email.'
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
.auth-btn-outline:disabled { opacity: 0.6; cursor: not-allowed; }
:global(.dark) .auth-btn-outline { border-color: rgb(51 65 85); background: rgb(30 41 59); color: rgb(203 213 225); }
:global(.dark) .auth-btn-outline:hover { background: rgb(51 65 85); }
.auth-error {
  border-radius: 0.75rem; border: 1px solid rgb(254 202 202);
  background: rgb(254 242 242); padding: 0.75rem 1rem; font-size: 0.875rem; color: rgb(185 28 28);
}
:global(.dark) .auth-error { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.1); color: rgb(252 165 165); }
</style>
