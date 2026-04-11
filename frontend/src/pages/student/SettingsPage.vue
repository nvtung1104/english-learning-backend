<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-2xl space-y-6">

      <div>
        <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Cài đặt</h1>
      </div>

      <!-- Giao diện -->
      <div class="theme-card rounded-[30px] p-6 space-y-5">
        <h2 class="text-lg font-semibold theme-text">Giao diện</h2>

        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold theme-text">Chế độ tối</p>
            <p class="text-xs theme-text-muted">Chuyển đổi giữa giao diện sáng và tối</p>
          </div>
          <button
            type="button"
            class="toggle-btn relative"
            :class="{ 'toggle-btn--on': isDark }"
            @click="toggleTheme"
            :aria-label="isDark ? 'Tắt chế độ tối' : 'Bật chế độ tối'"
          >
            <span class="toggle-thumb"></span>
          </button>
        </div>

        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold theme-text">Ngôn ngữ</p>
            <p class="text-xs theme-text-muted">Ngôn ngữ hiển thị của ứng dụng</p>
          </div>
          <span class="rounded-full px-3 py-1 text-xs font-semibold" style="background: var(--surface-strong); color: var(--text)">
            Tiếng Việt
          </span>
        </div>
      </div>

      <!-- Thông báo -->
      <div class="theme-card rounded-[30px] p-6 space-y-5">
        <h2 class="text-lg font-semibold theme-text">Thông báo</h2>

        <div
          v-for="item in notifSettings"
          :key="item.key"
          class="flex items-center justify-between"
        >
          <div>
            <p class="text-sm font-semibold theme-text">{{ item.label }}</p>
            <p class="text-xs theme-text-muted">{{ item.desc }}</p>
          </div>
          <button
            type="button"
            class="toggle-btn relative"
            :class="{ 'toggle-btn--on': notifPrefs[item.key] }"
            @click="notifPrefs[item.key] = !notifPrefs[item.key]"
          >
            <span class="toggle-thumb"></span>
          </button>
        </div>
      </div>

      <!-- Học tập -->
      <div class="theme-card rounded-[30px] p-6 space-y-5">
        <h2 class="text-lg font-semibold theme-text">Học tập</h2>

        <div class="space-y-1">
          <label class="text-sm font-semibold theme-text">Mục tiêu học mỗi ngày</label>
          <select v-model="studyGoal" class="form-input">
            <option value="10">10 phút</option>
            <option value="20">20 phút</option>
            <option value="30">30 phút</option>
            <option value="60">1 giờ</option>
          </select>
        </div>

        <div class="space-y-1">
          <label class="text-sm font-semibold theme-text">Số câu quiz mỗi lần luyện</label>
          <select v-model="quizCount" class="form-input">
            <option value="10">10 câu</option>
            <option value="20">20 câu</option>
            <option value="30">30 câu</option>
            <option value="50">50 câu</option>
          </select>
        </div>
      </div>

      <!-- Tài khoản -->
      <div class="theme-card rounded-[30px] p-6 space-y-4">
        <h2 class="text-lg font-semibold theme-text">Tài khoản</h2>
        <p class="text-sm theme-text-soft">Quản lý thông tin cá nhân và bảo mật tài khoản.</p>
        <RouterLink
          to="/student/profile"
          class="inline-block rounded-full px-5 py-2.5 text-sm font-semibold transition"
          style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
        >
          Đến trang hồ sơ →
        </RouterLink>
      </div>

      <!-- Save -->
      <button
        @click="saveSettings"
        class="w-full rounded-full py-3 text-sm font-semibold text-white transition hover:opacity-90"
        style="background: var(--accent)"
      >
        {{ saved ? '✓ Đã lưu cài đặt' : 'Lưu cài đặt' }}
      </button>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useTheme } from '@/composables/useTheme'
import { useToast } from '@/composables/useToast'

const { isDark, toggleTheme } = useTheme()
const toast = useToast()

const notifSettings = [
  { key: 'new_course',   label: 'Khóa học mới',       desc: 'Thông báo khi có khóa học mới phù hợp' },
  { key: 'quiz_remind',  label: 'Nhắc nhở quiz',       desc: 'Nhắc làm quiz hàng ngày' },
  { key: 'lesson_done',  label: 'Hoàn thành bài học',  desc: 'Xác nhận khi hoàn thành bài học' },
]

const notifPrefs = reactive({ new_course: true, quiz_remind: true, lesson_done: false })
const studyGoal = ref('20')
const quizCount = ref('20')
const saved = ref(false)

onMounted(() => {
  const stored = localStorage.getItem('student_settings')
  if (stored) {
    try {
      const s = JSON.parse(stored)
      if (s.notifPrefs) Object.assign(notifPrefs, s.notifPrefs)
      if (s.studyGoal) studyGoal.value = s.studyGoal
      if (s.quizCount) quizCount.value = s.quizCount
    } catch { /* ignore */ }
  }
})

function saveSettings() {
  localStorage.setItem('student_settings', JSON.stringify({
    notifPrefs: { ...notifPrefs },
    studyGoal: studyGoal.value,
    quizCount: quizCount.value,
  }))
  saved.value = true
  toast.success('Đã lưu cài đặt!')
  setTimeout(() => { saved.value = false }, 3000)
}
</script>

<style scoped>
.form-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 1rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
}
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }

/* Toggle switch */
.toggle-btn {
  width: 44px; height: 24px;
  border-radius: 999px;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
  background: var(--border);
  flex-shrink: 0;
}
.toggle-btn--on { background: var(--accent); }
.toggle-thumb {
  position: absolute;
  top: 3px; left: 3px;
  width: 18px; height: 18px;
  border-radius: 50%;
  background: white;
  transition: transform 0.2s;
  display: block;
}
.toggle-btn--on .toggle-thumb { transform: translateX(20px); }
</style>
