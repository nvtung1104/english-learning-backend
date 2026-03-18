<template>
  <div style="display:flex;align-items:center;justify-content:center;min-height:calc(100vh - 82px);padding:40px 24px;background:#faf8f4;">
    <div style="width:100%;max-width:400px;">
      <RouterLink to="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:36px;">
        <div style="width:36px;height:36px;border-radius:8px;background:#1a2540;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#fff;">EL</div>
        <span style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a2540;">Academy</span>
      </RouterLink>

      <div v-if="!sent">
        <div style="margin-bottom:28px;">
          <div style="width:52px;height:52px;border-radius:12px;background:#faf8f4;border:1px solid #ede9e2;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:20px;">🔑</div>
          <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#1a2540;margin:0 0 8px;">Forgot Password?</h1>
          <p style="font-size:14px;color:#6b7280;line-height:1.65;margin:0;">
            Enter your email and we'll send you a reset link.
          </p>
        </div>

        <form @submit.prevent="handleSubmit" style="display:flex;flex-direction:column;gap:16px;">
          <div>
            <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Email</label>
            <input v-model="email" type="email" placeholder="email@example.com" required
              style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s;"
              @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
              @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
            />
            <p v-if="error" style="font-size:12px;color:#dc2626;margin:4px 0 0;">{{ error }}</p>
          </div>

          <button type="submit" :disabled="loading"
            style="padding:13px;background:#1a2540;color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;border:none;border-radius:8px;cursor:pointer;transition:opacity 0.15s;font-family:inherit;"
            @mouseenter="e=>{ if(!loading) e.currentTarget.style.opacity='0.88' }"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
          </button>
        </form>
      </div>

      <div v-else style="text-align:center;padding:24px 0;">
        <div style="width:60px;height:60px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 20px;">✉️</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:#1a2540;margin:0 0 10px;">Check your email</h2>
        <p style="font-size:14px;color:#6b7280;line-height:1.65;margin:0 0 24px;">
          We sent a reset link to <strong style="color:#1a2540;">{{ email }}</strong>
        </p>
        <button @click="sent = false"
          style="padding:10px 24px;background:#faf8f4;color:#1a2540;font-size:13px;font-weight:600;border:1px solid #ede9e2;border-radius:8px;cursor:pointer;font-family:inherit;"
        >Resend</button>
      </div>

      <div style="margin-top:24px;text-align:center;">
        <RouterLink to="/login" style="font-size:13px;color:#6b7280;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
          </svg>
          Back to Sign In
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email   = ref('')
const error   = ref('')
const loading = ref(false)
const sent    = ref(false)

async function handleSubmit() {
  error.value = ''
  if (!email.value) { error.value = 'Please enter your email.'; return }
  loading.value = true
  try {
    await new Promise(r => setTimeout(r, 800))
    sent.value = true
  } catch (e) {
    error.value = e.response?.data?.message || 'Something went wrong. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
