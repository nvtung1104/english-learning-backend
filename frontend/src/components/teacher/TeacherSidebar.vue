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
          <p class="font-display text-[15px] font-bold text-white leading-none">Giảng viên</p>
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
            'flex items-center gap-3 rounded-xl text-sm font-medium transition-all duration-150',
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

defineProps({ collapsed: Boolean })
defineEmits(['toggle', 'navigate'])

const route = useRoute()
const isActive = (to) => to === '/teacher' ? route.path === '/teacher' : route.path.startsWith(to)

const navGroups = [
  {
    label: 'Tổng quan',
    items: [
      { to: '/teacher', icon: '🏠', label: 'Dashboard' },
    ],
  },
  {
    label: 'Nội dung',
    items: [
      { to: '/teacher/courses',  icon: '📚', label: 'Khóa học của tôi' },
      { to: '/teacher/sections', icon: '📂', label: 'Chương học' },
      { to: '/teacher/lessons',  icon: '📖', label: 'Bài học' },
      { to: '/teacher/materials',icon: '📎', label: 'Tài liệu' },
    ],
  },
  {
    label: 'Học tập',
    items: [
      { to: '/teacher/vocabularies', icon: '📝', label: 'Từ vựng' },
      { to: '/teacher/grammar',      icon: '📐', label: 'Ngữ pháp' },
      { to: '/teacher/listening',    icon: '🎧', label: 'Nghe' },
    ],
  },
  {
    label: 'Kiểm tra',
    items: [
      { to: '/teacher/quizzes',   icon: '🧩', label: 'Quiz' },
      { to: '/teacher/questions', icon: '❓', label: 'Câu hỏi' },
    ],
  },
  {
    label: 'Học viên',
    items: [
      { to: '/teacher/students', icon: '👥', label: 'Học viên' },
      { to: '/teacher/reviews',  icon: '⭐', label: 'Đánh giá' },
    ],
  },
]
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
