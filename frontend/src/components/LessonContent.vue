<template>
  <div class="lesson-content">
    <h1 class="text-white text-2xl font-bold mb-2">{{ lesson.title }}</h1>
    <div class="flex items-center gap-3 mb-6">
      <AppBadge :variant="typeVariant" class="text-xs">{{ typeLabel }}</AppBadge>
      <span v-if="lesson.duration" class="text-gray-400 text-xs flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ lesson.duration }} phút
      </span>
    </div>
    <div v-if="lesson.content" class="prose prose-invert prose-sm max-w-none text-gray-300 leading-relaxed" v-html="lesson.content"></div>
    <div v-else class="text-gray-500 text-sm italic">Không có nội dung bổ sung.</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import AppBadge from './ui/AppBadge.vue'

const props = defineProps({ lesson: { type: Object, required: true } })

const typeMap = { video: { label: 'Video', variant: 'primary' }, reading: { label: 'Đọc hiểu', variant: 'success' }, quiz: { label: 'Quiz', variant: 'warning' }, listening: { label: 'Nghe', variant: 'purple' }, speaking: { label: 'Speaking', variant: 'danger' } }
const typeLabel = computed(() => typeMap[props.lesson.type]?.label || props.lesson.type)
const typeVariant = computed(() => typeMap[props.lesson.type]?.variant || 'gray')
</script>
