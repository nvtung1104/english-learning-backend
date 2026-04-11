<template>
  <header
    :class="[
      'app-header fixed inset-x-0 top-0 z-50',
      scrolled ? 'is-scrolled' : 'is-top',
      mobileOpen ? 'is-mobile-open' : '',
    ]"
    :style="headerStyle"
  >
    <div class="header-glow header-glow-left"></div>
    <div class="header-glow header-glow-right"></div>
    <div class="header-noise"></div>

    <div class="section-container relative flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
      <!-- Logo -->
      <RouterLink to="/" class="brand group flex items-center gap-3" @click="mobileOpen = false">
        <div class="brand-mark-wrap">
          <div class="brand-mark-ring"></div>
          <div class="brand-mark" :style="logoBoxStyle">
            <span>EN</span>
          </div>
        </div>

        <div class="min-w-0">
          <p class="brand-kicker text-xs font-semibold tracking-[0.28em] theme-text-muted">
            ONLINE ENGLISH
          </p>
          <p class="brand-title text-base font-semibold theme-text">
            Mastery Platform
          </p>
        </div>
      </RouterLink>

      <!-- Desktop nav -->
      <nav class="desktop-nav hidden items-center gap-2 lg:flex">
        <RouterLink
          v-for="(item, index) in navItems"
          :key="item.label"
          :to="item.to"
          class="nav-link"
          :class="{ 'is-active': isActive(item.to) }"
          :style="{ '--delay': `${index * 40}ms` }"
        >
          <span class="nav-link-text">{{ item.label }}</span>
          <span class="nav-link-hover-bg"></span>
          <span class="nav-link-active-line"></span>
        </RouterLink>
      </nav>

      <!-- Desktop actions -->
      <div class="hidden items-center gap-3 lg:flex">
        <button
          @click="setTheme(isDark ? 'light' : 'dark')"
          class="theme-switch group"
          :class="{ 'is-dark': isDark }"
          :title="isDark ? 'Chuyển sang sáng' : 'Chuyển sang tối'"
          :aria-label="isDark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối'"
          type="button"
        >
          <span class="theme-switch-track">
            <span class="theme-switch-bg day-bg"></span>
            <span class="theme-switch-bg night-bg"></span>

            <span class="theme-star theme-star-1"></span>
            <span class="theme-star theme-star-2"></span>
            <span class="theme-star theme-star-3"></span>
            <span class="theme-cloud"></span>

            <span class="theme-switch-thumb">
              <svg
                class="theme-icon sun-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
              >
                <circle cx="12" cy="12" r="4" />
                <line x1="12" y1="1.8" x2="12" y2="4.2" />
                <line x1="12" y1="19.8" x2="12" y2="22.2" />
                <line x1="1.8" y1="12" x2="4.2" y2="12" />
                <line x1="19.8" y1="12" x2="22.2" y2="12" />
                <line x1="4.5" y1="4.5" x2="6.3" y2="6.3" />
                <line x1="17.7" y1="17.7" x2="19.5" y2="19.5" />
                <line x1="4.5" y1="19.5" x2="6.3" y2="17.7" />
                <line x1="17.7" y1="6.3" x2="19.5" y2="4.5" />
              </svg>

              <svg
                class="theme-icon moon-icon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.1"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79z" />
              </svg>
            </span>
          </span>
        </button>

        <template v-if="!isLoggedIn">
          <RouterLink
            to="/login"
            class="action-btn action-btn-ghost"
          >
            <span>Đăng nhập</span>
          </RouterLink>

          <RouterLink
            to="/register"
            class="action-btn action-btn-primary"
          >
            <span>Bắt đầu học</span>
          </RouterLink>
        </template>

        <template v-else>
          <RouterLink
            :to="dashboardLink"
            class="action-btn action-btn-ghost"
          >
            <span>Dashboard</span>
          </RouterLink>

          <RouterLink :to="profileLink" class="user-avatar" :title="user?.name">
            <img v-if="user?.avatar" :src="$storage(user.avatar)" :alt="user?.name" class="w-full h-full object-cover" />
            <span v-else class="avatar-initials">{{ user?.name?.charAt(0)?.toUpperCase() ?? '?' }}</span>
          </RouterLink>

          <button
            @click="handleLogout"
            class="action-btn action-btn-danger"
            type="button"
          >
            <span>Đăng xuất</span>
          </button>
        </template>
      </div>

      <!-- Mobile actions -->
      <div class="flex items-center gap-2 lg:hidden">
        <button
          @click="toggle()"
          class="theme-icon-btn"
          :class="{ 'is-dark': isDark }"
          :title="isDark ? 'Chuyển sang sáng' : 'Chuyển sang tối'"
          :aria-label="isDark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối'"
          type="button"
        >
          <span class="theme-icon-btn-glow"></span>

          <svg
            v-if="!isDark"
            class="theme-icon-btn-svg sun-mini"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.1"
            stroke-linecap="round"
          >
            <circle cx="12" cy="12" r="4" />
            <line x1="12" y1="2" x2="12" y2="5" />
            <line x1="12" y1="19" x2="12" y2="22" />
            <line x1="2" y1="12" x2="5" y2="12" />
            <line x1="19" y1="12" x2="22" y2="12" />
            <line x1="4.22" y1="4.22" x2="6.34" y2="6.34" />
            <line x1="17.66" y1="17.66" x2="19.78" y2="19.78" />
            <line x1="4.22" y1="19.78" x2="6.34" y2="17.66" />
            <line x1="17.66" y1="6.34" x2="19.78" y2="4.22" />
          </svg>

          <svg
            v-else
            class="theme-icon-btn-svg moon-mini"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2.1"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79z" />
          </svg>
        </button>

        <button
          @click="mobileOpen = !mobileOpen"
          class="mobile-menu-btn"
          :class="{ 'is-open': mobileOpen }"
          type="button"
          aria-label="Mở menu"
        >
          <span class="mobile-menu-btn-bg"></span>
          <span class="mobile-menu-line line-1"></span>
          <span class="mobile-menu-line line-2"></span>
          <span class="mobile-menu-line line-3"></span>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <transition name="mobile-panel">
      <div
        v-if="mobileOpen"
        class="mobile-panel lg:hidden"
      >
        <div class="mobile-panel-inner section-container px-4 pb-5 pt-3 sm:px-6 lg:px-8">
          <div class="mobile-panel-card">
            <div class="space-y-2">
              <RouterLink
                v-for="(item, index) in navItems"
                :key="item.label"
                :to="item.to"
                class="mobile-nav-link"
                :class="{ 'is-active': isActive(item.to) }"
                :style="{ '--item-delay': `${index * 45}ms` }"
                @click="mobileOpen = false"
              >
                <span>{{ item.label }}</span>
                <span class="mobile-nav-arrow">→</span>
              </RouterLink>
            </div>

            <div class="mobile-theme-row">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] theme-text-muted">
                  Giao diện
                </p>
                <p class="mt-1 text-sm theme-text-soft">
                  {{ isDark ? 'Dark mode' : 'Light mode' }}
                </p>
              </div>

              <button
                @click="setTheme(isDark ? 'light' : 'dark')"
                class="theme-switch theme-switch-mobile"
                :class="{ 'is-dark': isDark }"
                :title="isDark ? 'Chuyển sang sáng' : 'Chuyển sang tối'"
                type="button"
              >
                <span class="theme-switch-track">
                  <span class="theme-switch-bg day-bg"></span>
                  <span class="theme-switch-bg night-bg"></span>

                  <span class="theme-star theme-star-1"></span>
                  <span class="theme-star theme-star-2"></span>
                  <span class="theme-star theme-star-3"></span>
                  <span class="theme-cloud"></span>

                  <span class="theme-switch-thumb">
                    <svg
                      class="theme-icon sun-icon"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                    >
                      <circle cx="12" cy="12" r="4" />
                      <line x1="12" y1="1.8" x2="12" y2="4.2" />
                      <line x1="12" y1="19.8" x2="12" y2="22.2" />
                      <line x1="1.8" y1="12" x2="4.2" y2="12" />
                      <line x1="19.8" y1="12" x2="22.2" y2="12" />
                      <line x1="4.5" y1="4.5" x2="6.3" y2="6.3" />
                      <line x1="17.7" y1="17.7" x2="19.5" y2="19.5" />
                      <line x1="4.5" y1="19.5" x2="6.3" y2="17.7" />
                      <line x1="17.7" y1="6.3" x2="19.5" y2="4.5" />
                    </svg>

                    <svg
                      class="theme-icon moon-icon"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2.1"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    >
                      <path d="M21 12.79A9 9 0 1 1 11.21 3A7 7 0 0 0 21 12.79z" />
                    </svg>
                  </span>
                </span>
              </button>
            </div>

            <template v-if="!isLoggedIn">
              <div class="mobile-actions-grid">
                <RouterLink
                  to="/login"
                  class="action-btn action-btn-ghost action-btn-mobile"
                  @click="mobileOpen = false"
                >
                  <span>Đăng nhập</span>
                </RouterLink>
                <RouterLink
                  to="/register"
                  class="action-btn action-btn-primary action-btn-mobile"
                  @click="mobileOpen = false"
                >
                  <span>Bắt đầu học</span>
                </RouterLink>
              </div>
            </template>

            <template v-else>
              <div class="mobile-user-row">
                <div class="user-avatar user-avatar-sm">
                  <img v-if="user?.avatar" :src="$storage(user.avatar)" :alt="user?.name" class="w-full h-full object-cover" />
                  <span v-else class="avatar-initials">{{ user?.name?.charAt(0)?.toUpperCase() ?? '?' }}</span>
                </div>
                <div class="min-w-0">
                  <p class="text-sm font-semibold theme-text truncate">{{ user?.name }}</p>
                  <p class="text-xs theme-text-muted truncate">{{ user?.email }}</p>
                </div>
              </div>
              <div class="mobile-actions-grid">
                <RouterLink
                  :to="dashboardLink"
                  class="action-btn action-btn-ghost action-btn-mobile"
                  @click="mobileOpen = false"
                >
                  <span>Dashboard</span>
                </RouterLink>
                <button
                  @click="handleLogout"
                  class="action-btn action-btn-danger action-btn-mobile"
                  type="button"
                >
                  <span>Đăng xuất</span>
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTheme } from '@/composables/useTheme'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const { isDark, toggle, setTheme } = useTheme()

const scrolled = ref(false)
const mobileOpen = ref(false)

const navItems = [
  { label: 'Trang chủ', to: '/' },
  { label: 'Khóa học', to: '/courses' },
  { label: 'Luyện tập', to: '/practice' },
  { label: 'Đề thi', to: '/tests' },
  { label: 'Giới thiệu', to: '/about' },
  { label: 'Liên hệ', to: '/contact' },
]

const user = computed(() => authStore.user)
const isLoggedIn = computed(() => !!authStore.token)

const dashboardLink = computed(() => {
  const role = user.value?.role || user.value?.roles?.[0] || 'student'
  if (role === 'admin') return '/admin/dashboard'
  if (role === 'teacher') return '/teacher/dashboard'
  return '/student/dashboard'
})

const profileLink = computed(() => {
  const role = user.value?.role || user.value?.roles?.[0] || 'student'
  if (role === 'admin') return '/admin/dashboard'
  if (role === 'teacher') return '/teacher/profile'
  return '/student/profile'
})

const headerStyle = computed(() => ({
  background: scrolled.value
    ? 'color-mix(in srgb, var(--bg) 82%, transparent)'
    : 'color-mix(in srgb, var(--bg) 72%, transparent)',
  borderColor: 'color-mix(in srgb, var(--border) 88%, transparent)',
  boxShadow: scrolled.value
    ? '0 18px 50px rgba(15, 23, 42, 0.10)'
    : '0 8px 24px rgba(15, 23, 42, 0.03)',
}))

const logoBoxStyle = computed(() => ({
  background: isDark.value
    ? 'linear-gradient(135deg, #ffffff 0%, #dbeafe 100%)'
    : 'linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%)',
  color: isDark.value ? '#0f172a' : 'white',
}))

function isActive(path) {
  return route.path === path || (path !== '/' && route.path.startsWith(path))
}

function onScroll() {
  scrolled.value = window.scrollY > 12
}

async function handleLogout() {
  mobileOpen.value = false
  await authStore.logout()
  router.push('/login')
}

watch(
  () => route.fullPath,
  () => {
    mobileOpen.value = false
  }
)

watch(mobileOpen, (value) => {
  document.body.style.overflow = value ? 'hidden' : ''
})

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', onScroll)
  document.body.style.overflow = ''
})
</script>

<style scoped>
/* =========================================
   HEADER BASE
========================================= */
.app-header {
  transition:
    background 0.35s ease,
    border-color 0.35s ease,
    box-shadow 0.35s ease,
    transform 0.35s ease;
}

.app-header::after {
  content: '';
  position: absolute;
  inset: auto 0 0 0;
  height: 1px;
  background: linear-gradient(
    90deg,
    transparent 0%,
    color-mix(in srgb, var(--accent) 25%, transparent) 20%,
    color-mix(in srgb, var(--accent) 35%, transparent) 50%,
    color-mix(in srgb, var(--accent) 25%, transparent) 80%,
    transparent 100%
  );
  opacity: 0.55;
  pointer-events: none;
}

.app-header.is-top {
  transform: translateY(0);
}

.app-header.is-scrolled {
  transform: translateY(0);
}

.header-noise {
  position: absolute;
  inset: 0;
  opacity: 0.045;
  pointer-events: none;
  background-image:
    radial-gradient(circle at 1px 1px, currentColor 1px, transparent 0);
  background-size: 14px 14px;
  color: var(--text);
  mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.4), transparent);
}

.header-glow {
  position: absolute;
  top: -48px;
  width: 220px;
  height: 120px;
  border-radius: 999px;
  filter: blur(34px);
  opacity: 0.22;
  pointer-events: none;
  animation: headerFloat 9s ease-in-out infinite;
}

.header-glow-left {
  left: 4%;
  background: color-mix(in srgb, var(--accent) 28%, transparent);
}

.header-glow-right {
  right: 4%;
  background: color-mix(in srgb, var(--primary) 22%, transparent);
  animation-delay: -3s;
}

@keyframes headerFloat {
  0%, 100% {
    transform: translate3d(0, 0, 0) scale(1);
  }
  50% {
    transform: translate3d(0, 10px, 0) scale(1.04);
  }
}

/* =========================================
   BRAND
========================================= */
.brand {
  position: relative;
  transition: transform 0.28s ease;
}

.brand:hover {
  transform: translateY(-1px);
}

.brand-mark-wrap {
  position: relative;
  width: 40px;
  height: 40px;
}

.brand-mark-ring {
  position: absolute;
  inset: -4px;
  border-radius: 18px;
  background: linear-gradient(
    135deg,
    color-mix(in srgb, var(--accent) 40%, transparent),
    transparent 60%
  );
  opacity: 0;
  transform: scale(0.92);
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}

.brand:hover .brand-mark-ring {
  opacity: 1;
  transform: scale(1);
}

.brand-mark {
  position: relative;
  z-index: 1;
  display: grid;
  place-items: center;
  width: 40px;
  height: 40px;
  border-radius: 16px;
  font-size: 0.875rem;
  font-weight: 800;
  letter-spacing: 0.04em;
  box-shadow:
    0 14px 28px rgba(15, 23, 42, 0.16),
    inset 0 1px 1px rgba(255,255,255,0.28);
  transition:
    transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
    box-shadow 0.35s ease;
}

.brand:hover .brand-mark {
  transform: rotate(-8deg) scale(1.06);
  box-shadow:
    0 18px 34px rgba(15, 23, 42, 0.18),
    inset 0 1px 1px rgba(255,255,255,0.28);
}

.brand-kicker,
.brand-title {
  transition:
    transform 0.28s ease,
    opacity 0.28s ease;
}

.brand:hover .brand-kicker,
.brand:hover .brand-title {
  transform: translateX(1px);
}

/* =========================================
   DESKTOP NAV
========================================= */
.desktop-nav {
  position: relative;
  padding: 0.35rem;
  border-radius: 999px;
  background: color-mix(in srgb, var(--surface) 84%, transparent);
  border: 1px solid color-mix(in srgb, var(--border) 90%, transparent);
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.06),
    0 12px 28px rgba(15, 23, 42, 0.06);
}

.nav-link {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 88px;
  height: 42px;
  padding: 0 1rem;
  border-radius: 999px;
  overflow: hidden;
  color: color-mix(in srgb, var(--text) 75%, transparent);
  transition:
    color 0.28s ease,
    transform 0.28s ease;
  animation: navFadeDown 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
  animation-delay: var(--delay, 0ms);
}

.nav-link:hover {
  color: var(--text);
  transform: translateY(-1px);
}

.nav-link.is-active {
  color: var(--text);
}

.nav-link-text {
  position: relative;
  z-index: 2;
  font-size: 0.92rem;
  font-weight: 600;
}

.nav-link-hover-bg {
  position: absolute;
  inset: 5px;
  border-radius: 999px;
  background:
    linear-gradient(
      135deg,
      color-mix(in srgb, var(--surface) 92%, transparent),
      color-mix(in srgb, var(--surface) 78%, transparent)
    );
  opacity: 0;
  transform: scale(0.86);
  transition:
    opacity 0.28s ease,
    transform 0.28s ease;
}

.nav-link:hover .nav-link-hover-bg,
.nav-link.is-active .nav-link-hover-bg {
  opacity: 1;
  transform: scale(1);
}

.nav-link-active-line {
  position: absolute;
  left: 18px;
  right: 18px;
  bottom: 6px;
  height: 2px;
  border-radius: 999px;
  background: linear-gradient(90deg, var(--accent), color-mix(in srgb, var(--primary) 70%, white));
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.28s ease;
}

.nav-link:hover .nav-link-active-line,
.nav-link.is-active .nav-link-active-line {
  transform: scaleX(1);
}

@keyframes navFadeDown {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* =========================================
   ACTION BUTTONS
========================================= */
.action-btn {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 110px;
  height: 42px;
  padding: 0 1.1rem;
  border-radius: 999px;
  font-size: 0.92rem;
  font-weight: 700;
  overflow: hidden;
  transition:
    transform 0.28s ease,
    box-shadow 0.28s ease,
    border-color 0.28s ease,
    background 0.28s ease,
    color 0.28s ease;
}

.action-btn span {
  position: relative;
  z-index: 2;
}

.action-btn::after {
  content: '';
  position: absolute;
  top: -140%;
  left: -130%;
  width: 54%;
  height: 300%;
  transform: rotate(22deg);
  background: linear-gradient(
    to right,
    transparent 0%,
    rgba(255,255,255,0.08) 40%,
    rgba(255,255,255,0.28) 50%,
    transparent 60%
  );
  transition: left 0.8s ease;
  pointer-events: none;
}

.action-btn:hover::after {
  left: 180%;
}

.action-btn:hover {
  transform: translateY(-1px);
}

.action-btn-ghost {
  border: 1px solid var(--border);
  color: color-mix(in srgb, var(--text) 78%, transparent);
  background: color-mix(in srgb, var(--surface) 78%, transparent);
}

.action-btn-ghost:hover {
  color: var(--text);
  background: color-mix(in srgb, var(--surface) 100%, transparent);
  box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
}

.action-btn-primary {
  border: 1px solid color-mix(in srgb, var(--accent) 70%, transparent);
  color: white;
  background: linear-gradient(
    135deg,
    color-mix(in srgb, var(--accent) 100%, white),
    color-mix(in srgb, var(--primary) 88%, white)
  );
  box-shadow: 0 14px 28px color-mix(in srgb, var(--accent) 30%, transparent);
}

.action-btn-primary:hover {
  box-shadow: 0 18px 34px color-mix(in srgb, var(--accent) 36%, transparent);
}

.action-btn-danger {
  border: 1px solid color-mix(in srgb, var(--danger) 18%, var(--border));
  color: var(--danger);
  background: color-mix(in srgb, var(--surface) 72%, transparent);
}

.action-btn-danger:hover {
  background: color-mix(in srgb, var(--danger) 8%, var(--surface));
  box-shadow: 0 12px 24px rgba(239, 68, 68, 0.10);
}

.action-btn-mobile {
  width: 100%;
}

/* =========================================
   MOBILE BUTTON
========================================= */
.mobile-menu-btn {
  position: relative;
  display: grid;
  place-items: center;
  width: 44px;
  height: 44px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: color-mix(in srgb, var(--surface) 86%, transparent);
  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    background 0.25s ease;
  overflow: hidden;
}

.mobile-menu-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 12px 24px rgba(15, 23, 42, 0.10);
}

.mobile-menu-btn:active {
  transform: scale(0.97);
}

.mobile-menu-btn-bg {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at center, color-mix(in srgb, var(--accent) 12%, transparent), transparent 60%);
  opacity: 0;
  transition: opacity 0.25s ease;
}

.mobile-menu-btn:hover .mobile-menu-btn-bg,
.mobile-menu-btn.is-open .mobile-menu-btn-bg {
  opacity: 1;
}

.mobile-menu-line {
  position: absolute;
  width: 18px;
  height: 2px;
  border-radius: 999px;
  background: var(--text);
  transition:
    transform 0.28s ease,
    opacity 0.22s ease,
    background 0.22s ease;
}

.line-1 {
  transform: translateY(-6px);
}

.line-2 {
  transform: translateY(0);
}

.line-3 {
  transform: translateY(6px);
}

.mobile-menu-btn.is-open .line-1 {
  transform: rotate(45deg);
}

.mobile-menu-btn.is-open .line-2 {
  opacity: 0;
}

.mobile-menu-btn.is-open .line-3 {
  transform: rotate(-45deg);
}

/* =========================================
   MOBILE PANEL
========================================= */
.mobile-panel {
  position: relative;
  border-top: 1px solid color-mix(in srgb, var(--border) 90%, transparent);
  background: color-mix(in srgb, var(--bg) 88%, transparent);
  backdrop-filter: blur(22px);
}

.mobile-panel-inner {
  position: relative;
}

.mobile-panel-card {
  border-radius: 28px;
  border: 1px solid color-mix(in srgb, var(--border) 96%, transparent);
  background:
    linear-gradient(
      180deg,
      color-mix(in srgb, var(--surface) 96%, transparent),
      color-mix(in srgb, var(--surface) 88%, transparent)
    );
  padding: 0.9rem;
  box-shadow:
    0 22px 48px rgba(15, 23, 42, 0.12),
    inset 0 1px 0 rgba(255,255,255,0.06);
}

.mobile-nav-link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  width: 100%;
  min-height: 54px;
  padding: 0 1rem;
  border-radius: 20px;
  border: 1px solid transparent;
  color: color-mix(in srgb, var(--text) 78%, transparent);
  background: transparent;
  transition:
    transform 0.25s ease,
    color 0.25s ease,
    background 0.25s ease,
    border-color 0.25s ease,
    box-shadow 0.25s ease;
  animation: mobileLinkIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
  animation-delay: var(--item-delay, 0ms);
}

.mobile-nav-link:hover,
.mobile-nav-link.is-active {
  transform: translateX(2px);
  color: var(--text);
  background: color-mix(in srgb, var(--surface) 100%, transparent);
  border-color: var(--border);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
}

.mobile-nav-arrow {
  opacity: 0.55;
  transition:
    transform 0.25s ease,
    opacity 0.25s ease;
}

.mobile-nav-link:hover .mobile-nav-arrow,
.mobile-nav-link.is-active .mobile-nav-arrow {
  opacity: 1;
  transform: translateX(2px);
}

.mobile-theme-row {
  margin-top: 0.85rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  border: 1px solid var(--border);
  border-radius: 22px;
  background: color-mix(in srgb, var(--surface) 84%, transparent);
  padding: 0.9rem 1rem;
}

.mobile-actions-grid {
  margin-top: 0.85rem;
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 0.75rem;
}

@keyframes mobileLinkIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* =========================================
   THEME SWITCH
========================================= */
.theme-switch {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 72px;
  height: 40px;
  padding: 0;
  border: none;
  background: transparent;
  cursor: pointer;
  transition: transform 0.25s ease;
}

.theme-switch:hover {
  transform: translateY(-1px);
}

.theme-switch:active {
  transform: scale(0.97);
}

.theme-switch-mobile {
  width: 60px;
  height: 34px;
}

.theme-switch-track {
  position: relative;
  width: 72px;
  height: 40px;
  border-radius: 999px;
  overflow: hidden;
  border: 1px solid rgba(148, 163, 184, 0.24);
  box-shadow:
    inset 0 1px 2px rgba(255, 255, 255, 0.25),
    0 8px 24px rgba(15, 23, 42, 0.12);
}

.theme-switch-mobile .theme-switch-track {
  width: 60px;
  height: 34px;
}

.theme-switch-bg {
  position: absolute;
  inset: 0;
  transition:
    opacity 0.45s ease,
    transform 0.45s ease;
}

.day-bg {
  background:
    radial-gradient(circle at 28% 30%, rgba(255,255,255,0.95), transparent 26%),
    linear-gradient(135deg, #dbeafe 0%, #bfdbfe 35%, #7dd3fc 100%);
  opacity: 1;
}

.night-bg {
  background:
    radial-gradient(circle at 75% 28%, rgba(255,255,255,0.12), transparent 22%),
    linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #334155 100%);
  opacity: 0;
}

.theme-switch.is-dark .day-bg {
  opacity: 0;
  transform: scale(1.05);
}

.theme-switch.is-dark .night-bg {
  opacity: 1;
  transform: scale(1);
}

.theme-star {
  position: absolute;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 8px rgba(255,255,255,0.6);
  opacity: 0;
  transform: scale(0.5);
  transition:
    opacity 0.35s ease,
    transform 0.35s ease;
}

.theme-star-1 {
  top: 9px;
  left: 42px;
  width: 3px;
  height: 3px;
}

.theme-star-2 {
  top: 15px;
  left: 52px;
  width: 2.5px;
  height: 2.5px;
}

.theme-star-3 {
  top: 24px;
  left: 46px;
  width: 2px;
  height: 2px;
}

.theme-switch-mobile .theme-star-1 {
  top: 8px;
  left: 34px;
}

.theme-switch-mobile .theme-star-2 {
  top: 13px;
  left: 42px;
}

.theme-switch-mobile .theme-star-3 {
  top: 20px;
  left: 38px;
}

.theme-switch.is-dark .theme-star {
  opacity: 1;
  transform: scale(1);
}

.theme-switch.is-dark .theme-star-1 {
  animation: twinkle 2.4s ease-in-out infinite;
}

.theme-switch.is-dark .theme-star-2 {
  animation: twinkle 2.8s ease-in-out infinite 0.25s;
}

.theme-switch.is-dark .theme-star-3 {
  animation: twinkle 2.2s ease-in-out infinite 0.45s;
}

.theme-cloud {
  position: absolute;
  left: 10px;
  bottom: 8px;
  width: 18px;
  height: 8px;
  border-radius: 999px;
  background: rgba(255,255,255,0.78);
  box-shadow:
    8px -2px 0 1px rgba(255,255,255,0.72),
    14px 1px 0 0 rgba(255,255,255,0.78);
  transition:
    opacity 0.35s ease,
    transform 0.35s ease;
}

.theme-switch-mobile .theme-cloud {
  left: 8px;
  bottom: 7px;
  width: 14px;
  height: 6px;
  box-shadow:
    6px -2px 0 1px rgba(255,255,255,0.72),
    11px 1px 0 0 rgba(255,255,255,0.78);
}

.theme-switch.is-dark .theme-cloud {
  opacity: 0;
  transform: translateX(-6px);
}

.theme-switch-thumb {
  position: absolute;
  top: 3px;
  left: 3px;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 999px;
  background:
    radial-gradient(circle at 30% 30%, rgba(255,255,255,0.95), rgba(255,255,255,0.82));
  box-shadow:
    0 8px 20px rgba(15, 23, 42, 0.18),
    inset 0 1px 1px rgba(255,255,255,0.7);
  transition:
    transform 0.42s cubic-bezier(0.22, 1, 0.36, 1),
    background 0.35s ease,
    box-shadow 0.35s ease;
}

.theme-switch-mobile .theme-switch-thumb {
  top: 3px;
  left: 3px;
  width: 26px;
  height: 26px;
}

.theme-switch.is-dark .theme-switch-thumb {
  transform: translateX(32px);
  background:
    radial-gradient(circle at 30% 30%, #f8fafc, #dbeafe 78%);
  box-shadow:
    0 8px 20px rgba(2, 6, 23, 0.45),
    0 0 20px rgba(96, 165, 250, 0.18);
}

.theme-switch-mobile.is-dark .theme-switch-thumb {
  transform: translateX(26px);
}

.theme-icon {
  position: absolute;
  width: 16px;
  height: 16px;
  transition:
    opacity 0.28s ease,
    transform 0.38s ease,
    color 0.28s ease;
}

.theme-switch-mobile .theme-icon {
  width: 13px;
  height: 13px;
}

.sun-icon {
  color: #f59e0b;
  opacity: 1;
  transform: scale(1) rotate(0deg);
}

.moon-icon {
  color: #60a5fa;
  opacity: 0;
  transform: scale(0.6) rotate(-30deg);
}

.theme-switch.is-dark .sun-icon {
  opacity: 0;
  transform: scale(0.6) rotate(35deg);
}

.theme-switch.is-dark .moon-icon {
  opacity: 1;
  transform: scale(1) rotate(0deg);
}

/* =========================================
   MOBILE THEME BTN
========================================= */
.theme-icon-btn {
  position: relative;
  display: grid;
  place-items: center;
  width: 42px;
  height: 42px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: color-mix(in srgb, var(--surface) 86%, transparent);
  color: var(--text);
  overflow: hidden;
  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    background 0.25s ease;
}

.theme-icon-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
}

.theme-icon-btn:active {
  transform: scale(0.96);
}

.theme-icon-btn-glow {
  position: absolute;
  inset: -8px;
  background: radial-gradient(circle, rgba(14,165,233,0.18), transparent 58%);
  opacity: 0;
  transition: opacity 0.25s ease;
}

.theme-icon-btn:hover .theme-icon-btn-glow {
  opacity: 1;
}

.theme-icon-btn-svg {
  position: relative;
  z-index: 1;
  width: 18px;
  height: 18px;
  transition:
    transform 0.28s ease,
    opacity 0.28s ease,
    color 0.28s ease;
}

.sun-mini {
  color: #f59e0b;
  animation: spin-soft 10s linear infinite;
}

.moon-mini {
  color: #93c5fd;
  animation: float-soft 2.6s ease-in-out infinite;
}

.theme-icon-btn:hover .theme-icon-btn-svg {
  transform: scale(1.08);
}

/* =========================================
   TRANSITIONS
========================================= */
.mobile-panel-enter-active,
.mobile-panel-leave-active {
  transition: opacity 0.28s ease;
}

.mobile-panel-enter-active .mobile-panel-card,
.mobile-panel-leave-active .mobile-panel-card {
  transition:
    transform 0.34s cubic-bezier(0.22, 1, 0.36, 1),
    opacity 0.28s ease;
}

.mobile-panel-enter-from,
.mobile-panel-leave-to {
  opacity: 0;
}

.mobile-panel-enter-from .mobile-panel-card,
.mobile-panel-leave-to .mobile-panel-card {
  opacity: 0;
  transform: translateY(-12px) scale(0.985);
}

/* =========================================
   ANIMATION
========================================= */
@keyframes twinkle {
  0%, 100% {
    opacity: 0.65;
    transform: scale(0.95);
  }
  50% {
    opacity: 1;
    transform: scale(1.2);
  }
}

@keyframes spin-soft {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes float-soft {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-1.5px);
  }
}

/* =========================================
   REDUCED MOTION
========================================= */
@media (prefers-reduced-motion: reduce) {
  .app-header,
  .brand,
  .brand-mark,
  .brand-mark-ring,
  .brand-kicker,
  .brand-title,
  .nav-link,
  .nav-link-hover-bg,
  .nav-link-active-line,
  .action-btn,
  .mobile-menu-btn,
  .mobile-menu-line,
  .mobile-panel-card,
  .mobile-nav-link,
  .theme-switch,
  .theme-switch-track,
  .theme-switch-bg,
  .theme-switch-thumb,
  .theme-icon,
  .theme-star,
  .theme-cloud,
  .theme-icon-btn,
  .theme-icon-btn-svg,
  .theme-icon-btn-glow,
  .header-glow {
    animation: none !important;
    transition: none !important;
  }
}

/* =========================================
   USER AVATAR
========================================= */
.user-avatar {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 999px;
  overflow: hidden;
  border: 2px solid color-mix(in srgb, var(--accent) 40%, var(--border));
  background: color-mix(in srgb, var(--accent) 15%, var(--surface));
  flex-shrink: 0;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  text-decoration: none;
}

.user-avatar:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 20px color-mix(in srgb, var(--accent) 25%, transparent);
}

.user-avatar-sm {
  width: 36px;
  height: 36px;
}

.avatar-initials {
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--accent);
  line-height: 1;
}

.mobile-user-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 20px;
  border: 1px solid var(--border);
  background: color-mix(in srgb, var(--surface) 84%, transparent);
  margin-bottom: 0.75rem;
}
</style>