<template>
  <div style="background:#faf8f4;min-height:100vh;display:flex;flex-direction:column;">
    <Navbar />
    <!-- Student sub-nav -->
    <div style="position:fixed;top:82px;left:0;right:0;z-index:100;background:#fff;border-bottom:1px solid #ede9e2;box-shadow:0 1px 4px rgba(0,0,0,0.04);">
      <div style="max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;gap:4px;height:44px;overflow-x:auto;">
        <RouterLink
          v-for="item in navItems" :key="item.to"
          :to="item.to"
          :class="['student-nav-item', isActive(item.to) ? 'active' : '']"
        >
          <span>{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </RouterLink>
      </div>
    </div>
    <main style="flex:1;padding-top:126px;">
      <RouterView />
    </main>
    <PublicFooter />
  </div>
</template>

<script setup>
import { useRoute, RouterView } from 'vue-router'
import Navbar from '../components/Navbar.vue'
import PublicFooter from '../components/PublicFooter.vue'

const route = useRoute()

const navItems = [
  { to: '/student/dashboard',   icon: '🏠', label: 'Dashboard' },
  { to: '/student/my-courses',  icon: '📚', label: 'Khóa học' },
  { to: '/student/progress',    icon: '📈', label: 'Tiến độ' },
  { to: '/student/quiz-history',icon: '🧩', label: 'Quiz' },
  { to: '/student/vocabulary',  icon: '📝', label: 'Từ vựng' },
  { to: '/profile',             icon: '👤', label: 'Hồ sơ' },
]

const isActive = (to) => route.path === to || route.path.startsWith(to + '/')
</script>

<style scoped>
.student-nav-item {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.04em;
  color: #6b7280;
  text-decoration: none;
  border-radius: 8px;
  white-space: nowrap;
  transition: background 0.15s, color 0.15s;
}
.student-nav-item:hover { background: #f5f2ec; color: #1a2540; }
.student-nav-item.active { background: #1a2540; color: #fff; }
</style>
