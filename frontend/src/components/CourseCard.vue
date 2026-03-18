<template>
  <RouterLink :to="`/courses/${course.id}`" class="card-hover block group overflow-hidden rounded-3xl">
    <!-- Thumbnail -->
    <div class="relative overflow-hidden aspect-[4/3] bg-gradient-to-br from-navy/10 to-navy/20">
      <img
        v-if="course.thumbnail"
        :src="course.thumbnail"
        :alt="course.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-cream-soft to-parchment">
        📚
      </div>
      <!-- Overlay on hover -->
      <div class="absolute inset-0 bg-navy/0 group-hover:bg-navy/10 transition-all duration-300" />
      <!-- Badges -->
      <div class="absolute top-3 left-3">
        <AppBadge :variant="levelVariant">{{ levelLabel }}</AppBadge>
      </div>
      <div v-if="course.is_free || !course.price" class="absolute top-3 right-3">
        <AppBadge variant="success">Miễn phí</AppBadge>
      </div>
    </div>

    <!-- Body -->
    <div class="p-5">
      <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-gold mb-2">
        {{ course.category?.name || 'Tiếng Anh' }}
      </p>
      <h3 class="font-display font-bold text-navy text-[15px] leading-snug line-clamp-2 mb-3 group-hover:text-navy/80 transition-colors">
        {{ course.title }}
      </h3>

      <!-- Teacher -->
      <div v-if="course.teacher" class="flex items-center gap-2 mb-4">
        <div class="w-6 h-6 rounded-full bg-navy/10 flex items-center justify-center text-[11px] font-bold text-navy shrink-0">
          {{ course.teacher.name?.charAt(0) }}
        </div>
        <span class="text-xs text-slate-500 truncate">{{ course.teacher.name }}</span>
      </div>

      <!-- Stats -->
      <div class="flex items-center gap-4 text-xs text-slate-400 mb-4">
        <span v-if="course.lessons_count" class="flex items-center gap-1">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
          {{ course.lessons_count }} bài
        </span>
        <span v-if="course.students_count" class="flex items-center gap-1">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          {{ course.students_count }}
        </span>
        <span v-if="course.rating" class="flex items-center gap-1 text-gold">
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <span class="text-slate-600">{{ Number(course.rating).toFixed(1) }}</span>
        </span>
      </div>

      <!-- Price row -->
      <div class="flex items-center justify-between pt-4 border-t border-[var(--border-soft)]">
        <span class="font-display font-bold text-navy text-base">
          {{ course.is_free || !course.price ? 'Miễn phí' : Number(course.price).toLocaleString('vi-VN') + 'đ' }}
        </span>
        <span class="text-xs font-semibold text-gold group-hover:underline transition-all">
          Xem chi tiết →
        </span>
      </div>
    </div>
  </RouterLink>
</template>

<script setup>
import { computed } from 'vue'
import AppBadge from './ui/AppBadge.vue'

const props = defineProps({ course: { type: Object, required: true } })

const levelMap = {
  beginner:     { label: 'Cơ bản',   variant: 'success' },
  intermediate: { label: 'Trung cấp', variant: 'warning' },
  advanced:     { label: 'Nâng cao', variant: 'danger' },
}
const levelLabel   = computed(() => levelMap[props.course.level]?.label || props.course.level || 'Tất cả')
const levelVariant = computed(() => levelMap[props.course.level]?.variant || 'gray')
</script>
