<template>
  <footer class="bg-navy text-white">
    <!-- Main footer -->
    <div class="container-app py-16 lg:py-20">
      <div class="grid gap-12 lg:grid-cols-[1.6fr_1fr_1fr_1.2fr]">

        <!-- Brand -->
        <div>
          <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center font-bold text-sm">
              EL
            </div>
            <div>
              <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-white/50">English Learning</p>
              <p class="font-display text-lg font-bold text-white leading-none">Academy</p>
            </div>
          </div>
          <p class="text-sm text-white/55 leading-7 max-w-xs">
            Nền tảng học tiếng Anh trực tuyến cao cấp. Lộ trình cá nhân hóa, nội dung chuẩn quốc tế, học mọi lúc mọi nơi.
          </p>
          <div class="flex gap-2 mt-6">
            <a v-for="s in socials" :key="s.label" :href="s.href"
              class="w-9 h-9 rounded-xl bg-white/8 border border-white/12 flex items-center justify-center text-white/60 hover:text-white hover:bg-white/15 transition-all text-xs font-bold">
              {{ s.short }}
            </a>
          </div>
        </div>

        <!-- Explore -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">Khám phá</p>
          <ul class="space-y-3">
            <li v-for="item in exploreLinks" :key="item.to">
              <RouterLink :to="item.to" class="text-sm text-white/60 hover:text-white transition-colors">
                {{ item.label }}
              </RouterLink>
            </li>
          </ul>
        </div>

        <!-- System -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">Hệ thống</p>
          <ul class="space-y-3">
            <li v-for="item in systemLinks" :key="item.to">
              <RouterLink :to="item.to" class="text-sm text-white/60 hover:text-white transition-colors">
                {{ item.label }}
              </RouterLink>
            </li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/40 mb-5">Nhận cập nhật</p>
          <p class="text-sm text-white/55 leading-6 mb-4">
            Mẹo học, tài liệu mới và khóa học nổi bật gửi thẳng về email.
          </p>
          <form class="space-y-2" @submit.prevent>
            <input
              type="email"
              placeholder="Email của bạn"
              class="w-full rounded-xl bg-white/8 border border-white/15 px-4 py-2.5 text-sm text-white placeholder:text-white/35 focus:outline-none focus:border-gold/60 focus:bg-white/12 transition-all"
            />
            <button type="submit" class="btn btn-md w-full bg-gold-500 text-white border-0 hover:bg-gold-400" style="background:var(--gold)">
              Đăng ký nhận tin
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/10">
      <div class="container-app py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/35">
        <p>© {{ new Date().getFullYear() }} English Learning Academy. All rights reserved.</p>
        <div class="flex gap-5">
          <RouterLink to="/terms" class="hover:text-white/70 transition-colors">Điều khoản</RouterLink>
          <RouterLink to="/privacy" class="hover:text-white/70 transition-colors">Chính sách</RouterLink>
          <RouterLink to="/support" class="hover:text-white/70 transition-colors">Hỗ trợ</RouterLink>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { RouterLink } from 'vue-router'

const socials = [
  { label: 'Facebook', short: 'Fb', href: '#' },
  { label: 'Instagram', short: 'Ig', href: '#' },
  { label: 'YouTube', short: 'Yt', href: '#' },
]

const exploreLinks = [
  { label: 'Trang chủ', to: '/' },
  { label: 'Khóa học', to: '/courses' },
  { label: 'Luyện thi Quiz', to: '/quiz' },
  { label: 'Đăng ký học', to: '/register' },
]

const systemLinks = [
  { label: 'Dashboard học viên', to: '/student/dashboard' },
  { label: 'Khóa học của tôi', to: '/student/my-courses' },
  { label: 'Lịch sử Quiz', to: '/student/quiz-history' },
  { label: 'Từ vựng', to: '/student/vocabulary' },
]
</script>
