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
      <!-- Breadcrumb -->
      <div class="flex items-center gap-1.5 text-sm min-w-0">
        <RouterLink to="/teacher" class="text-slate-400 hover:text-navy transition-colors shrink-0">Giảng viên</RouterLink>
        <span class="text-slate-300">/</span>
        <span class="font-semibold text-navy truncate">{{ currentPage }}</span>
      </div>
    </div>

    <!-- Right -->
    <div class="flex items-center gap-2 shrink-0">
      <!-- Go to site -->
      <RouterLink to="/"
        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-500 hover:bg-cream-soft hover:text-navy transition-all border border-[var(--border)]"
      >
        <span>🌐</span> Trang chủ
      </RouterLink>

      <!-- User dropdown -->
      <div class="relative" ref="dropRef">
        <button
          @click="open = !open"
          class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-cream-soft transition-all border border-transparent hover:border-[var(--border)]"
        >
          <div class="w-7 h-7 rounded-full bg-gold flex items-center justify-center text-white text-xs font-bold shrink-0">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() || 'T' }}
          </div>
          <div class="hidden sm:block text-left">
            <p class="text-xs font-bold text-navy leading-none">{{ auth.user?.name?.split(' ').pop() || 'Teacher' }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Giảng viên</p>
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

defineEmits(['toggle-sidebar'])

const auth   = useAuthStore()
const router = useRouter()
const route  = useRoute()
const open   = ref(false)

const routeNameMap = {
  '/teacher':             'Dashboard',
  '/teacher/courses':     'Khóa học của tôi',
  '/teacher/sections':    'Chương học',
  '/teacher/lessons':     'Bài học',
  '/teacher/materials':   'Tài liệu',
  '/teacher/vocabularies':'Từ vựng',
  '/teacher/grammar':     'Ngữ pháp',
  '/teacher/listening':   'Nghe',
  '/teacher/quizzes':     'Quiz',
  '/teacher/questions':   'Câu hỏi',
  '/teacher/students':    'Học viên',
  '/teacher/reviews':     'Đánh giá',
}

const currentPage = computed(() => routeNameMap[route.path] || 'Trang')

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
