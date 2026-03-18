<template>
  <header class="h-[64px] bg-white border-b border-[var(--border-soft)] flex items-center justify-between px-4 sm:px-6 shrink-0 gap-4">
    <!-- Left -->
    <div class="flex items-center gap-3 min-w-0">
      <button
        @click="$emit('toggle-sidebar')"
        class="xl:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:bg-cream-soft hover:text-navy transition-all border border-[var(--border)]"
      >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <AppBreadcrumb :items="breadcrumbs" />
    </div>

    <!-- Right -->
    <div class="flex items-center gap-2 shrink-0">
      <!-- Search -->
      <div class="relative hidden md:block">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Tìm kiếm..."
          class="w-52 pl-9 pr-4 py-2 rounded-xl border border-[var(--border)] text-sm bg-cream-soft focus:bg-white focus:outline-none focus:border-navy/40 focus:ring-2 focus:ring-navy/8 transition-all placeholder:text-slate-400"
        />
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>

      <!-- Notifications -->
      <button class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:bg-cream-soft hover:text-navy transition-all border border-[var(--border)]">
        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-danger-500 rounded-full ring-2 ring-white"></span>
      </button>

      <!-- User dropdown -->
      <div class="relative" ref="dropRef">
        <button
          @click="open = !open"
          class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-cream-soft transition-all border border-transparent hover:border-[var(--border)]"
        >
          <div class="w-7 h-7 rounded-full bg-navy flex items-center justify-center text-white text-xs font-bold shrink-0">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() || 'A' }}
          </div>
          <div class="hidden sm:block text-left">
            <p class="text-xs font-bold text-navy leading-none">{{ auth.user?.name?.split(' ').pop() || 'Admin' }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Administrator</p>
          </div>
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <Transition name="dropdown">
          <div v-if="open" class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-modal border border-[var(--border-soft)] overflow-hidden z-50">
            <div class="px-4 py-3 border-b border-[var(--border-soft)]">
              <p class="text-sm font-bold text-navy">{{ auth.user?.name }}</p>
              <p class="text-xs text-slate-400 truncate">{{ auth.user?.email }}</p>
            </div>
            <div class="py-1.5">
              <RouterLink to="/profile" @click="open = false"
                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-cream-soft transition-colors">
                <span>👤</span> Hồ sơ cá nhân
              </RouterLink>
              <RouterLink to="/" @click="open = false"
                class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-cream-soft transition-colors">
                <span>🌐</span> Về trang chủ
              </RouterLink>
            </div>
            <div class="border-t border-[var(--border-soft)] py-1.5">
              <button @click="handleLogout"
                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-danger-600 hover:bg-danger-50 transition-colors">
                <span>🚪</span> Đăng xuất
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import AppBreadcrumb from '../ui/AppBreadcrumb.vue'

defineEmits(['toggle-sidebar'])

const auth   = useAuthStore()
const router = useRouter()
const route  = useRoute()
const open   = ref(false)
const searchQuery = ref('')

const routeNameMap = {
  '/admin':               'Dashboard',
  '/admin/users':         'Người dùng',
  '/admin/roles':         'Vai trò',
  '/admin/permissions':   'Quyền hạn',
  '/admin/courses':       'Khóa học',
  '/admin/categories':    'Danh mục',
  '/admin/levels':        'Cấp độ',
  '/admin/sections':      'Chương học',
  '/admin/lessons':       'Bài học',
  '/admin/lesson-types':  'Loại bài học',
  '/admin/materials':     'Tài liệu',
  '/admin/vocabularies':  'Từ vựng',
  '/admin/grammar':       'Ngữ pháp',
  '/admin/listening':     'Nghe',
  '/admin/speaking':      'Speaking',
  '/admin/quizzes':       'Quiz',
  '/admin/questions':     'Câu hỏi',
  '/admin/attempts':      'Lượt làm bài',
  '/admin/enrollments':   'Đăng ký học',
  '/admin/progress':      'Tiến độ',
  '/admin/reviews':       'Đánh giá',
  '/admin/notifications': 'Thông báo',
}

const breadcrumbs = computed(() => {
  const current = routeNameMap[route.path] || 'Trang'
  return [{ label: 'Admin', to: '/admin' }, { label: current }]
})

async function handleLogout() {
  open.value = false
  auth.logout()
  router.push('/login')
}
</script>

<style scoped>
.dropdown-enter-active, .dropdown-leave-active { transition: all 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
