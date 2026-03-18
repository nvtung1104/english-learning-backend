<template>
  <header class="navbar-root">
    <!-- Top bar: social left, brand center, auth right -->
    <div class="navbar-top">
      <div class="nav-wrap">
        <!-- Left: socials -->
        <div class="nav-socials">
          <a v-for="s in socials" :key="s.icon" :href="s.href" class="nav-social-link" target="_blank" rel="noopener">
            <svg v-if="s.icon==='fb'" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            <svg v-else-if="s.icon==='ig'" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            <svg v-else-if="s.icon==='yt'" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.96A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/></svg>
            <svg v-else-if="s.icon==='tw'" width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
        </div>

        <!-- Center: brand -->
        <RouterLink to="/" class="nav-brand">
          <span class="nav-brand-sub">English Learning</span>
          <span class="nav-brand-name">Academy</span>
        </RouterLink>

        <!-- Right: auth + search -->
        <div class="nav-auth">
          <template v-if="auth.isLoggedIn">
            <span class="nav-role-badge" :class="`nav-role-${auth.role}`">{{ auth.role }}</span>
            <RouterLink :to="dashboardLink" class="nav-link">{{ auth.user?.name?.split(' ')[0] }}</RouterLink>
            <span class="nav-sep">|</span>
            <button @click="handleLogout" class="nav-link nav-link-btn">Sign Out</button>
          </template>
          <template v-else>
            <RouterLink to="/login" class="nav-link">Sign In</RouterLink>
            <span class="nav-sep">|</span>
            <RouterLink to="/register" class="nav-btn-register">Register</RouterLink>
          </template>
          <button @click="toggleSearch" class="nav-search-btn" :class="{ active: searchOpen }">
            <svg v-if="!searchOpen" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <svg v-else width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Mobile: brand + hamburger -->
        <RouterLink to="/" class="nav-brand-mobile">Academy</RouterLink>
        <button @click="mobileOpen = !mobileOpen" class="nav-hamburger">
          <svg v-if="!mobileOpen" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10"/></svg>
          <svg v-else width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    <!-- Bottom bar: nav links centered -->
    <div class="navbar-bottom">
      <div class="nav-wrap">
        <nav class="nav-links">
          <RouterLink
            v-for="item in navItems" :key="item.to" :to="item.to"
            class="nav-item" :class="{ 'nav-item-active': isActive(item.to) }"
          >{{ item.label }}</RouterLink>
        </nav>
      </div>
    </div>

    <!-- Search dropdown -->
    <div v-if="searchOpen" class="nav-search-drop">
      <div class="nav-wrap">
        <div class="nav-search-inner">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#9ca3af;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input
            v-model="searchQuery"
            ref="searchInput"
            type="text"
            placeholder="Search courses, lessons, vocabulary..."
            class="nav-search-input"
            @keydown.escape="searchOpen = false"
          />
          <kbd class="nav-search-esc" @click="searchOpen = false">ESC</kbd>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="mobileOpen" class="nav-mobile-menu">
      <div class="nav-wrap">
        <RouterLink
          v-for="item in navItems" :key="item.to" :to="item.to"
          class="nav-mobile-item" @click="mobileOpen = false"
        >{{ item.label }}</RouterLink>
        <div class="nav-mobile-divider"></div>
        <template v-if="auth.isLoggedIn">
          <RouterLink :to="dashboardLink" class="nav-mobile-item" @click="mobileOpen = false">Dashboard</RouterLink>
          <button @click="handleLogout" class="nav-mobile-btn">Sign Out</button>
        </template>
        <template v-else>
          <RouterLink to="/login" class="nav-mobile-item" @click="mobileOpen = false">Sign In</RouterLink>
          <RouterLink to="/register" class="nav-mobile-btn-primary" @click="mobileOpen = false">Register Free</RouterLink>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const route  = useRoute()
const router = useRouter()
const auth   = useAuthStore()

const mobileOpen  = ref(false)
const searchOpen  = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)

const navItems = [
  { label: 'Home',     to: '/' },
  { label: 'Courses',  to: '/courses' },
  { label: 'Practice', to: '/quiz' },
  { label: 'About',    to: '/about' },
]

const socials = [
  { icon: 'fb', href: '#' },
  { icon: 'ig', href: '#' },
  { icon: 'yt', href: '#' },
  { icon: 'tw', href: '#' },
]

const dashboardLink = computed(() => {
  const role = auth.role
  if (role === 'admin')   return '/admin'
  if (role === 'teacher') return '/teacher'
  return '/student/dashboard'
})

const isActive = (to) => to === '/' ? route.path === '/' : route.path.startsWith(to)

async function toggleSearch() {
  searchOpen.value = !searchOpen.value
  if (searchOpen.value) { await nextTick(); searchInput.value?.focus() }
}

watch(() => route.fullPath, () => { mobileOpen.value = false; searchOpen.value = false })

async function handleLogout() {
  mobileOpen.value = false
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
/* ── Root ── */
.navbar-root {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  z-index: 9999;
  background: #ffffff;
  border-bottom: 1px solid #e8e4de;
  box-shadow: 0 1px 6px rgba(0,0,0,0.05);
}

/* ── Wrapper ── */
.nav-wrap {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 32px;
  display: flex;
  align-items: center;
}

/* ── Top bar ── */
.navbar-top {
  border-bottom: 1px solid #f0ece4;
}
.navbar-top .nav-wrap {
  height: 44px;
  justify-content: space-between;
  position: relative;
}

/* Socials */
.nav-socials {
  display: flex;
  align-items: center;
  gap: 2px;
  flex: 1;
}
.nav-social-link {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  border-radius: 6px;
  transition: color 0.15s, background 0.15s;
  text-decoration: none;
}
.nav-social-link:hover { color: #1a2540; background: #f5f2ec; }

/* Brand (desktop center) */
.nav-brand {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  line-height: 1;
  gap: 1px;
}
.nav-brand-sub {
  font-size: 8px;
  font-weight: 700;
  letter-spacing: 0.32em;
  text-transform: uppercase;
  color: #9ca3af;
}
.nav-brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  font-weight: 700;
  color: #1a2540;
  letter-spacing: -0.02em;
}

/* Role badge */
.nav-role-badge {
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 3px 8px;
  border-radius: 20px;
  border: 1px solid currentColor;
}
.nav-role-admin   { color: #dc2626; background: #fef2f2; }
.nav-role-teacher { color: #c9a84c; background: #fefce8; }
.nav-role-student { color: #16a34a; background: #f0fdf4; }

/* Auth right */
.nav-auth {
  display: flex;
  align-items: center;
  gap: 6px;
  flex: 1;
  justify-content: flex-end;
}
.nav-link {
  font-size: 12px;
  font-weight: 500;
  color: #4b5563;
  text-decoration: none;
  padding: 4px 8px;
  border-radius: 6px;
  transition: color 0.15s, background 0.15s;
  background: none;
  border: none;
  cursor: pointer;
  font-family: inherit;
}
.nav-link:hover { color: #1a2540; background: #f5f2ec; }
.nav-link-btn { padding: 4px 8px; }
.nav-sep { color: #d1d5db; font-size: 12px; }
.nav-btn-register {
  font-size: 12px;
  font-weight: 600;
  color: #1a2540;
  text-decoration: none;
  padding: 5px 14px;
  border: 1.5px solid #1a2540;
  border-radius: 4px;
  transition: background 0.15s, color 0.15s;
  letter-spacing: 0.02em;
}
.nav-btn-register:hover { background: #1a2540; color: #fff; }

/* Search button */
.nav-search-btn {
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  background: none;
  cursor: pointer;
  color: #6b7280;
  border-radius: 6px;
  transition: color 0.15s, background 0.15s;
  margin-left: 4px;
}
.nav-search-btn:hover, .nav-search-btn.active { color: #1a2540; background: #f5f2ec; }

/* Mobile brand + hamburger — hidden on desktop */
.nav-brand-mobile {
  display: none;
  font-family: 'Playfair Display', serif;
  font-size: 19px;
  font-weight: 700;
  color: #1a2540;
  text-decoration: none;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}
.nav-hamburger {
  display: none;
  width: 36px;
  height: 36px;
  align-items: center;
  justify-content: center;
  border: none;
  background: none;
  cursor: pointer;
  color: #1a2540;
  margin-left: auto;
}

/* ── Bottom bar: nav links ── */
.navbar-bottom .nav-wrap {
  height: 38px;
  justify-content: center;
}
.nav-links {
  display: flex;
  align-items: center;
  gap: 32px;
}
.nav-item {
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: #6b7280;
  text-decoration: none;
  padding-bottom: 2px;
  border-bottom: 2px solid transparent;
  transition: color 0.15s, border-color 0.15s;
}
.nav-item:hover { color: #1a2540; }
.nav-item-active { color: #1a2540; border-bottom-color: #1a2540; }

/* ── Search dropdown ── */
.nav-search-drop {
  border-top: 1px solid #f0ece4;
  background: #fff;
  padding: 12px 0;
}
.nav-search-inner {
  display: flex;
  align-items: center;
  gap: 10px;
  max-width: 560px;
  margin: 0 auto;
  background: #faf8f4;
  border: 1px solid #e5e0d5;
  border-radius: 10px;
  padding: 8px 14px;
}
.nav-search-input {
  flex: 1;
  border: none;
  background: none;
  outline: none;
  font-size: 14px;
  color: #1a2540;
  font-family: inherit;
}
.nav-search-input::placeholder { color: #9ca3af; }
.nav-search-esc {
  font-size: 10px;
  font-weight: 600;
  color: #9ca3af;
  background: #f0ece4;
  border: 1px solid #e5e0d5;
  border-radius: 4px;
  padding: 2px 6px;
  cursor: pointer;
  font-family: inherit;
  letter-spacing: 0.05em;
}

/* ── Mobile menu ── */
.nav-mobile-menu {
  border-top: 1px solid #e8e4de;
  background: #fff;
  box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.nav-mobile-menu .nav-wrap {
  flex-direction: column;
  align-items: stretch;
  padding: 16px 24px 20px;
  gap: 2px;
}
.nav-mobile-item {
  display: block;
  padding: 10px 12px;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: #4b5563;
  text-decoration: none;
  border-radius: 8px;
  transition: background 0.15s, color 0.15s;
}
.nav-mobile-item:hover { background: #f5f2ec; color: #1a2540; }
.nav-mobile-divider { height: 1px; background: #f0ece4; margin: 8px 0; }
.nav-mobile-btn {
  display: block;
  width: 100%;
  padding: 10px 12px;
  font-size: 13px;
  font-weight: 600;
  color: #1a2540;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  border-radius: 8px;
  font-family: inherit;
  transition: background 0.15s;
}
.nav-mobile-btn:hover { background: #f5f2ec; }
.nav-mobile-btn-primary {
  display: block;
  margin-top: 8px;
  padding: 12px;
  font-size: 13px;
  font-weight: 600;
  color: #fff;
  background: #1a2540;
  text-decoration: none;
  border-radius: 8px;
  text-align: center;
  transition: opacity 0.15s;
}
.nav-mobile-btn-primary:hover { opacity: 0.88; }

/* ── Responsive ── */
@media (max-width: 1023px) {
  .nav-socials  { display: none; }
  .nav-brand    { display: none; }
  .nav-auth     { display: none; }
  .navbar-bottom { display: none; }
  .nav-brand-mobile { display: block; }
  .nav-hamburger    { display: flex; }
  .navbar-top .nav-wrap { height: 52px; }
}
</style>
