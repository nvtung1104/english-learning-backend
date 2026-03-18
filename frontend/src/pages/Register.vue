<template>
  <div style="display:flex;min-height:calc(100vh - 82px);">

    <!-- Left panel (desktop) -->
    <div class="reg-left" style="display:none;position:relative;flex:0 0 48%;background:#1a2540;overflow:hidden;">
      <img
        src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1400&q=80"
        alt=""
        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.13;"
      />
      <div style="position:absolute;inset:0;background:linear-gradient(160deg,#1a2540 40%,#2d3f6b 100%);"></div>

      <!-- Decorative -->
      <div style="position:absolute;top:-100px;right:-100px;width:360px;height:360px;border-radius:50%;border:1px solid rgba(201,168,76,0.12);pointer-events:none;"></div>
      <div style="position:absolute;bottom:-60px;left:-60px;width:260px;height:260px;border-radius:50%;border:1px solid rgba(255,255,255,0.06);pointer-events:none;"></div>

      <div style="position:relative;display:flex;flex-direction:column;justify-content:space-between;padding:52px;height:100%;box-sizing:border-box;">
        <RouterLink to="/" style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;">
          <div style="width:38px;height:38px;border-radius:9px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#fff;">EL</div>
          <span style="font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:#fff;">Academy</span>
        </RouterLink>

        <div>
          <p style="font-size:10px;font-weight:700;letter-spacing:0.3em;text-transform:uppercase;color:rgba(201,168,76,0.75);margin:0 0 18px;">Start Free</p>
          <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,2.8vw,2.6rem);font-weight:700;color:#fff;line-height:1.12;margin:0 0 18px;letter-spacing:-0.02em;">
            Open doors<br/>with English
          </h2>
          <p style="font-size:15px;color:rgba(255,255,255,0.45);line-height:1.75;margin:0 0 36px;max-width:320px;">
            Create a free account and start learning today. No credit card required.
          </p>
          <div style="display:flex;flex-direction:column;gap:13px;">
            <div v-for="b in benefits" :key="b" style="display:flex;align-items:center;gap:12px;">
              <div style="width:18px;height:18px;border-radius:50%;background:rgba(201,168,76,0.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="#c9a84c" stroke-width="3.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <span style="font-size:14px;color:rgba(255,255,255,0.6);">{{ b }}</span>
            </div>
          </div>
        </div>

        <p style="font-size:11px;color:rgba(255,255,255,0.18);">© {{ new Date().getFullYear() }} English Learning Academy</p>
      </div>
    </div>

    <!-- Right panel -->
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:48px 24px;background:#faf8f4;overflow-y:auto;">
      <div style="width:100%;max-width:400px;padding:4px 0;">

        <!-- Mobile logo -->
        <RouterLink to="/" class="reg-mobile-logo" style="display:flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:32px;">
          <div style="width:34px;height:34px;border-radius:8px;background:#1a2540;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;color:#fff;">EL</div>
          <span style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:#1a2540;">Academy</span>
        </RouterLink>

        <!-- Success banner -->
        <div v-if="registerSuccess"
          style="margin-bottom:20px;padding:14px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;display:flex;align-items:center;gap:10px;"
        >
          <span style="font-size:18px;">🎉</span>
          <div>
            <p style="font-size:13px;font-weight:700;color:#16a34a;margin:0 0 2px;">Account created!</p>
            <p style="font-size:12px;color:#4b5563;margin:0;">
              Welcome <strong>{{ auth.user?.name }}</strong> · Registered as
              <span :style="`font-weight:700;color:${form.role==='teacher'?'#c9a84c':'#16a34a'};`">
                {{ form.role === 'teacher' ? 'Teacher' : 'Student' }}
              </span>
            </p>
          </div>
        </div>

        <!-- Heading -->
        <div style="margin-bottom:28px;">
          <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#1a2540;margin:0 0 8px;letter-spacing:-0.02em;">Create Account</h1>
          <p style="font-size:14px;color:#6b7280;margin:0;">
            Already have an account?
            <RouterLink to="/login" style="color:#c9a84c;font-weight:600;text-decoration:none;margin-left:3px;">Sign in</RouterLink>
          </p>
        </div>

        <form @submit.prevent="handleRegister" style="display:flex;flex-direction:column;gap:16px;">

          <!-- Role selector: Student / Teacher only -->
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:9px;">I want to join as</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
              <button type="button" @click="form.role='student'"
                :style="`display:flex;flex-direction:column;align-items:center;padding:16px 12px;border-radius:10px;border:2px solid ${form.role==='student'?'#1a2540':'#e5e0d5'};background:${form.role==='student'?'#1a2540':'#fff'};cursor:pointer;transition:all 0.15s;font-family:inherit;gap:5px;`"
              >
                <span style="font-size:22px;">🎓</span>
                <span :style="`font-size:13px;font-weight:700;color:${form.role==='student'?'#fff':'#1a2540'};`">Student</span>
                <span :style="`font-size:11px;color:${form.role==='student'?'rgba(255,255,255,0.55)':'#9ca3af'};`">Learn English</span>
              </button>
              <button type="button" @click="form.role='teacher'"
                :style="`display:flex;flex-direction:column;align-items:center;padding:16px 12px;border-radius:10px;border:2px solid ${form.role==='teacher'?'#c9a84c':'#e5e0d5'};background:${form.role==='teacher'?'#c9a84c':'#fff'};cursor:pointer;transition:all 0.15s;font-family:inherit;gap:5px;`"
              >
                <span style="font-size:22px;">👨‍🏫</span>
                <span :style="`font-size:13px;font-weight:700;color:${form.role==='teacher'?'#fff':'#1a2540'};`">Teacher</span>
                <span :style="`font-size:11px;color:${form.role==='teacher'?'rgba(255,255,255,0.65)':'#9ca3af'};`">Teach courses</span>
              </button>
            </div>
          </div>

          <!-- Full name -->
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:7px;">Full Name</label>
            <input v-model="form.name" type="text" placeholder="Your full name" required autocomplete="name"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
            <p v-if="errors.name" style="font-size:12px;color:#dc2626;margin:5px 0 0;">{{ Array.isArray(errors.name) ? errors.name[0] : errors.name }}</p>
          </div>

          <!-- Email -->
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:7px;">Email</label>
            <input v-model="form.email" type="email" placeholder="your@email.com" required autocomplete="email"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
            <p v-if="errors.email" style="font-size:12px;color:#dc2626;margin:5px 0 0;">{{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}</p>
          </div>

          <!-- Password -->
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:7px;">
              Password
              <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#b0a99a;margin-left:4px;">min. 6 characters</span>
            </label>
            <input v-model="form.password" type="password" placeholder="••••••••" required autocomplete="new-password"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
            <p v-if="errors.password" style="font-size:12px;color:#dc2626;margin:5px 0 0;">{{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}</p>
          </div>

          <!-- Confirm password -->
          <div>
            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#6b7280;margin-bottom:7px;">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" placeholder="Repeat password" required autocomplete="new-password"
              style="width:100%;padding:12px 14px;border:1.5px solid #e5e0d5;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;transition:border-color 0.15s,box-shadow 0.15s;"
              @focus="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 0 0 3px rgba(26,37,64,0.06)'}"
              @blur="e=>{e.currentTarget.style.borderColor='#e5e0d5';e.currentTarget.style.boxShadow='none'}"
            />
          </div>

          <!-- Error -->
          <div v-if="errors.general"
            style="padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;font-size:13px;color:#dc2626;line-height:1.5;"
          >{{ errors.general }}</div>

          <!-- Submit -->
          <button type="submit" :disabled="loading"
            :style="`padding:13px;background:${form.role==='teacher'?'#c9a84c':'#1a2540'};color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:opacity 0.15s;margin-top:2px;`"
            @mouseenter="e=>{ if(!loading) e.currentTarget.style.opacity='0.88' }"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >{{ loading ? 'Creating account…' : `Register as ${form.role === 'teacher' ? 'Teacher' : 'Student'}` }}</button>
        </form>

        <p style="font-size:11px;color:#b0a99a;text-align:center;margin-top:20px;line-height:1.6;">
          By registering you agree to our
          <RouterLink to="/terms" style="color:#6b7280;text-decoration:underline;">Terms</RouterLink>
          and <RouterLink to="/privacy" style="color:#6b7280;text-decoration:underline;">Privacy Policy</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const auth   = useAuthStore()

const form    = ref({ name: '', email: '', password: '', password_confirmation: '', role: 'student' })
const errors  = ref({})
const loading = ref(false)
const registerSuccess = ref(false)

const benefits = [
  'Access 500+ free courses',
  'AI-personalized learning path',
  'Internationally recognized certificates',
  'Study offline on any device',
]

async function handleRegister() {
  errors.value = {}
  loading.value = true
  try {
    await auth.register(form.value)
    registerSuccess.value = true
    setTimeout(() => {
      if (auth.role === 'teacher') router.push('/teacher')
      else                         router.push('/student/dashboard')
    }, 1000)
  } catch (e) {
    const data = e.response?.data
    if (data?.errors) errors.value = data.errors
    else errors.value.general = data?.message || 'Registration failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@media (min-width: 1024px) {
  .reg-left        { display: flex !important; }
  .reg-mobile-logo { display: none !important; }
}
</style>
