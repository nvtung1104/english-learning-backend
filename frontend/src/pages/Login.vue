<template>
  <div style="display:flex;min-height:calc(100vh - 82px);">

    <!-- Left panel (desktop) -->
    <div class="login-left" style="display:none;position:relative;flex:0 0 48%;background:#1a2540;overflow:hidden;">
      <img
        src="https://images.unsplash.com/photo-1513258496099-48168024aec0?w=1400&q=80"
        alt=""
        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.15;"
      />
      <div style="position:absolute;inset:0;background:linear-gradient(160deg,#1a2540 40%,#2d3f6b 100%);"></div>

      <!-- Decorative circles -->
      <div style="position:absolute;top:-120px;right:-120px;width:400px;height:400px;border-radius:50%;border:1px solid rgba(201,168,76,0.12);pointer-events:none;"></div>
      <div style="position:absolute;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;border:1px solid rgba(255,255,255,0.06);pointer-events:none;"></div>

      <div style="position:relative;display:flex;flex-direction:column;justify-content:space-between;padding:52px;height:100%;box-sizing:border-box;">
        <!-- Logo -->
        <RouterLink to="/" style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;">
          <div style="width:38px;height:38px;border-radius:9px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#fff;letter-spacing:0.05em;">EL</div>
          <span style="font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:#fff;letter-spacing:-0.01em;">Academy</span>
        </RouterLink>

        <!-- Main copy -->
        <div>
          <p style="font-size:10px;font-weight:700;letter-spacing:0.3em;text-transform:uppercase;color:rgba(201,168,76,0.75);margin:0 0 18px;">Welcome Back</p>
          <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,2.8vw,2.6rem);font-weight:700;color:#fff;line-height:1.12;margin:0 0 18px;letter-spacing:-0.02em;">
            Continue your<br/>English journey
          </h2>
          <p style="font-size:15px;color:rgba(255,255,255,0.45);line-height:1.75;margin:0 0 40px;max-width:320px;">
            Thousands of learners studying every day. Don't let your progress stop.
          </p>

          <!-- Stats -->
          <div style="display:flex;gap:32px;">
            <div v-for="s in stats" :key="s.label">
              <p style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 3px;">{{ s.value }}</p>
              <p style="font-size:11px;color:rgba(255,255,255,0.35);margin:0;letter-spacing:0.06em;">{{ s.label }}</p>
            </div>
          </div>
        </div>

        <p style="font-size:11px;color:rgba(255,255,255,0.18);">© {{ new Date().getFullYear() }} English Learning Academy</p>
      </div>
    </div>

    <!-- Right panel -->
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:48px 24px;background:#faf8f4;">
      <div style="width:100%;max-width:380px;">

        <!-- Mobile logo -->
        <RouterLink to="/" class="login-mobile-logo" style="display:flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:36px;">
          <div style="width:34px;height:34px;border-radius:8px;background:#1a2540;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;color:#fff;">EL</div>
          <span style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:#1a2540;">Academy</span>
        </RouterLink>

        <!-- Heading -->
        <div style="margin-bottom:32px;">
          <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#1a2540;margin:0 0 8px;letter-spacing:-0.02em;">Sign In</h1>
          <p style="font-size:14px;color:#6b7280;margin:0;">
            Don't have an account?
            <RouterLink to="/register" style="color:#c9a84c;font-weight:600;text-decoration:none;margin-left:3px;">Register free</RouterLink>
          </p>
        </div>

        <!-- Success banner -->
        <div v-if="loginSuccess"
          style="margin-bottom:20px;padding:14px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:10px;"
        >
          <span style="font-size:18px;">✅</span>
          <div>
            <p style="font-size:13px;font-weight:700;color:#16a34a;margin:0 0 2px;">Signed in successfully!</p>
            <p style="font-size:12px;color:#4b5563;margin:0;">
              Welcome back, <strong>{{ auth.user?.name }}</strong> ·
              <span :style="`font-weight:700;color:${roleBadgeColor};`">{{ roleLabel }}</span>
            </p>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleLogin" style="display:flex;flex-direction:column;gap:18px;">
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:7px;">Email</label>
            <input v-model="form.email" type="email" placeholder="your@email.com" required autocomplete="email"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
            <p v-if="errors.email" style="font-size:12px;color:#dc2626;margin:5px 0 0;">{{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}</p>
          </div>

          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
              <label style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;">Password</label>
              <RouterLink to="/forgot-password" style="font-size:12px;color:#c9a84c;font-weight:500;text-decoration:none;">Forgot password?</RouterLink>
            </div>
            <input v-model="form.password" type="password" placeholder="••••••••" required autocomplete="current-password"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
            <p v-if="errors.password" style="font-size:12px;color:#dc2626;margin:5px 0 0;">{{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}</p>
          </div>

          <div v-if="errors.general"
            style="padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:13px;color:#dc2626;line-height:1.5;"
          >{{ errors.general }}</div>

          <button type="submit" :disabled="loading"
            style="padding:13px;background:#1a2540;color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:opacity 0.15s,transform 0.1s;margin-top:2px;"
            @mouseenter="e=>{ if(!loading) e.currentTarget.style.opacity='0.88' }"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >{{ loading ? 'Signing in…' : 'Sign In' }}</button>
        </form>

        <p style="font-size:11px;color:#b0a99a;text-align:center;margin-top:24px;line-height:1.6;">
          By signing in you agree to our
          <RouterLink to="/terms" style="color:#6b7280;text-decoration:underline;">Terms of Service</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth   = useAuthStore()

const form         = ref({ email: '', password: '' })
const errors       = ref({})
const loading      = ref(false)
const loginSuccess = ref(false)

const stats = [
  { value: '500+', label: 'Courses'  },
  { value: '50K+', label: 'Students' },
  { value: '4.9★', label: 'Rating'   },
]

const roleLabel = computed(() => {
  const r = auth.role
  if (r === 'admin')   return 'Administrator'
  if (r === 'teacher') return 'Teacher'
  return 'Student'
})

const roleBadgeColor = computed(() => {
  const r = auth.role
  if (r === 'admin')   return '#dc2626'
  if (r === 'teacher') return '#c9a84c'
  return '#16a34a'
})

async function handleLogin() {
  errors.value  = {}
  loading.value = true
  try {
    await auth.login(form.value)
    loginSuccess.value = true
    setTimeout(() => {
      const r = auth.role
      if (r === 'admin')        router.push('/admin')
      else if (r === 'teacher') router.push('/teacher')
      else                      router.push('/student/dashboard')
    }, 1000)
  } catch (e) {
    const data = e.response?.data
    if (data?.errors) errors.value = data.errors
    else errors.value.general = data?.message || 'Invalid email or password.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@media (min-width: 1024px) {
  .login-left       { display: flex !important; }
  .login-mobile-logo { display: none !important; }
}
</style>
