<template>
  <component
    :is="to ? 'RouterLink' : 'button'"
    :to="to"
    :type="to ? undefined : type"
    :disabled="!to && (disabled || loading)"
    :class="classes"
    v-bind="$attrs"
  >
    <svg v-if="loading" class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
    </svg>
    <slot v-else name="icon-left" />
    <slot />
    <slot name="icon-right" />
  </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'primary' },
  size:    { type: String, default: 'md' },
  loading: Boolean,
  disabled: Boolean,
  to:      String,
  type:    { type: String, default: 'button' },
  block:   Boolean,
})

const sizeMap    = { sm: 'btn-sm', md: 'btn-md', lg: 'btn-lg' }
const variantMap = {
  primary:   'btn-primary',
  secondary: 'btn-secondary',
  ghost:     'btn-ghost',
  danger:    'btn-danger',
  success:   'btn-success',
  gold:      'btn-gold',
}

const classes = computed(() => [
  'btn',
  sizeMap[props.size]    || 'btn-md',
  variantMap[props.variant] || 'btn-primary',
  props.block ? 'w-full' : '',
  (props.disabled || props.loading) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '',
])
</script>
