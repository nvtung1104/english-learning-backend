<template>
  <div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-4 h-[64px] border-b border-white/8 shrink-0">
      <div class="w-9 h-9 rounded-xl bg-gold/20 border border-gold/30 flex items-center justify-center font-bold text-sm text-gold shrink-0">
        EL
      </div>
      <Transition name="fade">
        <div v-if="!collapsed" class="min-w-0">
          <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/35">English Learning</p>
          <p class="font-display text-[15px] font-bold text-white leading-none">Academy</p>
        </div>
      </Transition>
    </div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 px-2 space-y-0.5">
      <template v-for="group in navGroups" :key="group.label">
        <p v-if="!collapsed" class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-white/30 uppercase tracking-widest first:pt-2">
          {{ group.label }}
        </p>
        <div v-else class="my-3 mx-2 border-t border-white/8" />

        <RouterLink
          v-for="item in group.items" :key="item.to"
          :to="item.to"
          :title="collapsed ? item.label : ''"
          :class="[
            'flex items-center gap-3 rounded-xl text-sm font-medium transition-all duration-150 group',
            collapsed ? 'px-2 py-2.5 justify-center' : 'px-3 py-2.5',
            isActive(item.to)
              ? 'bg-white/12 text-white'
              : 'text-white/50 hover:bg-white/8 hover:text-white/90'
          ]"
          @click="$emit('navigate')"
        >
          <span class="text-base shrink-0 w-5 text-center">{{ item.icon }}</span>
          <Transition name="fade">
            <span v-if="!collapsed" class="truncate flex-1">{{ item.label }}</span>
          </Transition>
          <AppBadge v-if="item.badge && !collapsed" variant="danger" class="ml-auto shrink-0 text-[10px]">
            {{ item.badge }}
          </AppBadge>
        </RouterLink>
      </template>
    </nav>

    <!-- Collapse toggle -->
    <div class="px-2 py-3 border-t border-white/8 shrink-0">
      <button
        @click="$emit('toggle')"
        :class="[
          'w-full flex items-center gap-2 rounded-xl text-white/40 hover:bg-white/8 hover:text-white/80 transition-colors text-sm py-2.5',
          collapsed ? 'justify-center px-2' : 'px-3'
        ]"
      >
        <svg :class="['w-4 h-4 transition-transform shrink-0', collapsed ? 'rotate-180' : '']"
          fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
        </svg>
        <Transition name="fade">
          <span v-if="!collapsed" class="text-xs">Thu gọn</span>
        </Transition>
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import AppBadge from '../ui/AppBadge.vue'

defineProps({ collapsed: Boolean })
defineEmits(['toggle', 'navigate'])

const route = useRoute()
const isActive = (to) => to === '/admin' ? route.path === '/admin' : route.path.startsWith(to)

const navGroups = [
  {
    label: 'T\u1ed5ng quan',
    items: [
      { to: '/admin', icon: '🏠', label: 'Dashboard' },
    ],
  },
  {
    label: 'Ng\u01b0\u1eddi d\u00f9ng',
    items: [
      { to: '/admin/users',       icon: '👥', label: 'Ng\u01b0\u1eddi d\u00f9ng' },
      { to: '/admin/roles',       icon: '🔰', label: 'Vai tr\u00f2' },
      { to: '/admin/permissions', icon: '🔑', label: 'Quy\u1ec1n h\u1ea1n' },
    ],
  },
  {
    label: 'Kh\u00f3a h\u1ecdc',
    items: [
      { to: '/admin/courses',      icon: '📚', label: 'Kh\u00f3a h\u1ecdc' },
      { to: '/admin/categories',   icon: '📁', label: 'Danh m\u1ee5c' },
      { to: '/admin/levels',       icon: '📈', label: 'C\u1ea5p \u0111\u1ed9' },
      { to: '/admin/sections',     icon: '📂', label: 'Ch\u01b0\u01a1ng h\u1ecdc' },
      { to: '/admin/lessons',      icon: '📖', label: 'B\u00e0i h\u1ecdc' },
      { to: '/admin/lesson-types', icon: '🎯', label: 'Lo\u1ea1i b\u00e0i h\u1ecdc' },
      { to: '/admin/materials',    icon: '📎', label: 'T\u00e0i li\u1ec7u' },
    ],
  },
  {
    label: 'N\u1ed9i dung',
    items: [
      { to: '/admin/vocabularies', icon: '📝', label: 'T\u1eeb v\u1ef1ng' },
      { to: '/admin/grammar',      icon: '📐', label: 'Ng\u1eef ph\u00e1p' },
      { to: '/admin/listening',    icon: '🎧', label: 'Nghe' },
      { to: '/admin/speaking',     icon: '🎤', label: 'N\u00f3i' },
    ],
  },
  {
    label: 'Ki\u1ec3m tra',
    items: [
      { to: '/admin/quizzes',   icon: '🧩', label: 'Quiz' },
      { to: '/admin/questions', icon: '❓', label: 'C\u00e2u h\u1ecfi' },
      { to: '/admin/attempts',  icon: '📋', label: 'L\u01b0\u1ee3t l\u00e0m b\u00e0i' },
    ],
  },
  {
    label: 'Qu\u1ea3n l\u00fd',
    items: [
      { to: '/admin/enrollments',   icon: '🎓', label: '\u0110\u0103ng k\u00fd h\u1ecdc' },
      { to: '/admin/progress',      icon: '📉', label: 'Ti\u1ebfn \u0111\u1ed9' },
      { to: '/admin/reviews',       icon: '⭐', label: '\u0110\u00e1nh gi\u00e1' },
      { to: '/admin/notifications', icon: '🔔', label: 'Th\u00f4ng b\u00e1o' },
    ],
  },
]
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
