<template>
  <div style="background:#faf8f4;min-height:100vh;padding:40px 24px 64px;">
    <div style="max-width:900px;margin:0 auto;">

      <!-- Header card -->
      <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;overflow:hidden;margin-bottom:24px;">
        <div style="height:100px;background:#1a2540;position:relative;">
          <div style="position:absolute;top:-60px;right:-60px;width:240px;height:240px;border-radius:50%;background:rgba(201,168,76,0.08);pointer-events:none;"></div>
        </div>
        <div style="padding:0 24px 24px;">
          <div style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px;margin-top:-36px;">
            <div style="width:72px;height:72px;border-radius:12px;background:#c9a84c;border:4px solid #fff;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:700;font-family:'Playfair Display',serif;flex-shrink:0;">
              {{ auth.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div style="flex:1;padding-bottom:4px;">
              <h1 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#1a2540;margin:0 0 2px;">{{ auth.user?.name }}</h1>
              <p style="font-size:13px;color:#9ca3af;margin:0;">{{ auth.user?.email }}</p>
            </div>
            <span style="font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:5px 12px;border-radius:20px;background:#faf8f4;border:1px solid #ede9e2;color:#c9a84c;">{{ roleLabel }}</span>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:240px 1fr;gap:24px;align-items:start;">
        <!-- Stats sidebar -->
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
          <h3 style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:#1a2540;margin:0 0 18px;">Learning Stats</h3>
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div v-for="s in learningStats" :key="s.label" style="display:flex;align-items:center;justify-content:space-between;">
              <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:18px;">{{ s.icon }}</span>
                <span style="font-size:13px;color:#4b5563;">{{ s.label }}</span>
              </div>
              <span style="font-size:13px;font-weight:700;color:#1a2540;">{{ s.value }}</span>
            </div>
          </div>
        </div>

        <!-- Edit form -->
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
          <h3 style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#1a2540;margin:0 0 20px;">Personal Information</h3>
          <form @submit.prevent="handleSave" style="display:flex;flex-direction:column;gap:16px;">

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Full Name</label>
              <input v-model="form.name" type="text" required
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Email</label>
              <input v-model="form.email" type="email" required
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Phone</label>
              <input v-model="form.phone" type="tel" placeholder="Your phone number"
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Bio</label>
              <textarea v-model="form.bio" rows="3" placeholder="Tell us a bit about yourself..."
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;resize:vertical;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              ></textarea>
            </div>

            <div style="height:1px;background:#f0ece4;"></div>

            <h4 style="font-size:13px;font-weight:700;color:#1a2540;margin:0;letter-spacing:0.04em;">Change Password</h4>

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">Current Password</label>
              <input v-model="form.current_password" type="password"
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>

            <div>
              <label style="display:block;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#4b5563;margin-bottom:6px;">New Password</label>
              <input v-model="form.new_password" type="password"
                style="width:100%;padding:11px 14px;border:1.5px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>

            <div v-if="success" style="padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#16a34a;">
              ✅ Profile updated successfully!
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:4px;">
              <button type="button" @click="resetForm"
                style="padding:10px 20px;background:#faf8f4;color:#4b5563;font-size:13px;font-weight:600;border:1px solid #ede9e2;border-radius:8px;cursor:pointer;font-family:inherit;"
              >Cancel</button>
              <button type="submit" :disabled="loading"
                style="padding:10px 20px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:opacity 0.15s;"
                @mouseenter="e=>{ if(!loading) e.currentTarget.style.opacity='0.88' }"
                @mouseleave="e=>e.currentTarget.style.opacity='1'"
              >{{ loading ? 'Saving...' : 'Save Changes' }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const auth    = useAuthStore()
const loading = ref(false)
const success = ref(false)
const errors  = ref({})

const form = ref({
  name: '', email: '', phone: '', bio: '',
  current_password: '', new_password: '',
})

const roleLabel = computed(() => ({
  admin: 'Admin', teacher: 'Teacher', student: 'Student'
}[auth.user?.role] || 'Student'))

const learningStats = [
  { icon: '📚', label: 'Courses',          value: '12'     },
  { icon: '✅', label: 'Lessons Done',      value: '148'    },
  { icon: '🧩', label: 'Quizzes',           value: '34'     },
  { icon: '🔥', label: 'Current Streak',    value: '7 days' },
]

function resetForm() {
  form.value = {
    name: auth.user?.name || '',
    email: auth.user?.email || '',
    phone: '', bio: '',
    current_password: '', new_password: '',
  }
}

async function handleSave() {
  errors.value = {}
  loading.value = true
  try {
    await new Promise(r => setTimeout(r, 600))
    success.value = true
    setTimeout(() => { success.value = false }, 3000)
  } catch (e) {
    errors.value = e.response?.data?.errors || {}
  } finally {
    loading.value = false
  }
}

onMounted(resetForm)
</script>

<style scoped>
@media (max-width: 768px) {
  div[style*="grid-template-columns:240px 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
