<template>
  <div>
    <!-- SUCCESS -->
    <template v-if="sent">
      <div class="text-center">
        <div class="mx-auto mb-6 grid h-16 w-16 place-items-center rounded-2xl bg-emerald-500 text-2xl text-white shadow-lg shadow-emerald-500/25">✓</div>
        <h2 class="text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">Kiểm tra email</h2>
        <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
          Link đặt lại mật khẩu đã được gửi đến<br />
          <span class="font-semibold text-slate-900 dark:text-white">{{ form.email }}</span>
        </p>
      </div>
      <div class="mt-8 space-y-3">
        <button @click="handleSubmit" :disabled="loading" class="auth-btn-outline">
          <span v-if="loading" class="h-4 w-4 animate-spin rounded-full border-2 border-slate-400/30 border-t-slate-600 dark:border-t-slate-200"></span>
          Gửi lại email
        </button>
        <RouterLink to="/login" class="auth-btn block text-center">Quay lại đăng nhập</RouterLink>
      </div>
    </template>

    <!-- FORM -->
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
</template>

<script setup>
import { reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import authService from '@/services/auth.service'

const loading = ref(false)
const sent = ref(false)
const errorMsg = ref('')
const form = reactive({ email: '' })

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
.auth-btn-outline {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  border-radius: 0.75rem;
  border: 1px solid rgb(226 232 240);
  background: white;
  padding: 0.75rem 1.25rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: rgb(51 65 85);
  transition: all 0.2s ease;
}
.auth-btn-outline:hover { background: rgb(248 250 252); }
.auth-btn-outline:disabled { opacity: 0.6; cursor: not-allowed; }
:global(.dark) .auth-btn-outline {
  border-color: rgb(51 65 85);
  background: rgb(30 41 59);
  color: rgb(203 213 225);
}
:global(.dark) .auth-btn-outline:hover { background: rgb(51 65 85); }
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
