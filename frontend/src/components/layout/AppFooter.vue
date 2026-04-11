<template>
  <footer
    class="border-t px-4 py-12 sm:px-6 lg:px-8"
    :style="footerStyle"
  >
    <div class="section-container grid max-w-7xl gap-10 lg:grid-cols-[1.2fr_0.8fr_0.8fr_0.8fr]">
      <div>
        <RouterLink to="/" class="flex items-center gap-3">
          <div
            class="grid h-11 w-11 place-items-center rounded-2xl text-sm font-bold"
            :style="logoBoxStyle"
          >
            EN
          </div>
          <div>
            <p class="text-sm font-semibold tracking-[0.28em] theme-text-muted">ONLINE ENGLISH</p>
            <p class="font-semibold theme-text">Mastery Platform</p>
          </div>
        </RouterLink>

        <p class="mt-4 max-w-sm text-sm leading-7 theme-text-soft">
          Nền tảng học và thi tiếng Anh online với giao diện hiện đại, rõ ràng, hỗ trợ đầy đủ học viên, giáo viên và quản trị viên.
        </p>
      </div>

      <div>
        <h3 class="text-sm font-semibold uppercase tracking-[0.24em] theme-text-muted">Khám phá</h3>
        <ul class="mt-4 space-y-3 text-sm theme-text-soft">
          <li><RouterLink to="/" class="hover:opacity-100">Trang chủ</RouterLink></li>
          <li><RouterLink to="/courses">Khóa học</RouterLink></li>
          <li><RouterLink to="/practice">Luyện tập</RouterLink></li>
          <li><RouterLink to="/tests">Đề thi</RouterLink></li>
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-semibold uppercase tracking-[0.24em] theme-text-muted">Hệ thống</h3>
        <ul class="mt-4 space-y-3 text-sm theme-text-soft">
          <li><RouterLink to="/student/dashboard">Dashboard</RouterLink></li>
          <li><RouterLink to="/student/courses">Khóa học của tôi</RouterLink></li>
          <li><RouterLink to="/teacher/dashboard">Khu giáo viên</RouterLink></li>
          <li><RouterLink to="/admin/dashboard">Khu quản trị</RouterLink></li>
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-semibold uppercase tracking-[0.24em] theme-text-muted">Liên hệ</h3>
        <ul class="mt-4 space-y-3 text-sm theme-text-soft">
          <li>ngvantung1104@gmail.com</li>
          <li>0338944015</li>
          <li>Phú Thọ, Việt Nam</li>
        </ul>
      </div>
    </div>

    <div
      class="section-container mt-10 flex max-w-7xl flex-col gap-3 border-t pt-6 text-sm theme-text-muted sm:flex-row sm:items-center sm:justify-between"
      :style="{ borderColor: 'var(--border)' }"
    >
      <p>© 2026 English Mastery Platform. All rights reserved.</p>
      <p>Designed for your English learning system.</p>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useTheme } from '@/composables/useTheme'

const { isDark } = useTheme()

const footerStyle = computed(() => ({
  background: isDark.value ? 'var(--bg-soft)' : 'var(--surface-strong)',
  borderColor: 'var(--border)',
}))

const logoBoxStyle = computed(() => ({
  background: isDark.value ? 'white' : 'var(--primary)',
  color: isDark.value ? '#0f172a' : 'white',
}))
</script>