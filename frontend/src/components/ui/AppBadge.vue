<template>
  <span :class="['badge', variantClass]">
    <span v-if="dot" :class="['w-1.5 h-1.5 rounded-full shrink-0', dotColor]" />
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'gray' },
  dot:     Boolean,
})

const variantMap = {
  primary: 'badge-primary',
  success: 'badge-success',
  warning: 'badge-warning',
  danger:  'badge-danger',
  gray:    'badge-gray',
  purple:  'badge-purple',
  gold:    'badge-gold',
}
const dotColorMap = {
  primary: 'bg-primary-500',
  success: 'bg-success-500',
  warning: 'bg-warning-500',
  danger:  'bg-danger-500',
  gray:    'bg-slate-400',
  purple:  'bg-purple-500',
  gold:    'bg-gold-500',
}

const variantClass = computed(() => variantMap[props.variant] || 'badge-gray')
const dotColor     = computed(() => dotColorMap[props.variant] || 'bg-slate-400')
</script>
