<template>
  <div>
    <!-- Tab bar -->
    <div :class="tabBarClass">
      <button
        v-for="tab in tabs" :key="tab.value"
        @click="$emit('update:modelValue', tab.value)"
        :class="tabClass(tab)"
      >
        <span v-if="tab.icon" class="mr-1.5 text-base">{{ tab.icon }}</span>
        {{ tab.label }}
        <span
          v-if="tab.count !== undefined"
          :class="['ml-1.5 px-1.5 py-0.5 rounded-full text-[11px] font-bold',
            modelValue === tab.value ? 'bg-navy/15 text-navy' : 'bg-slate-100 text-slate-500']"
        >
          {{ tab.count }}
        </span>
      </button>
    </div>
    <div class="mt-4">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  tabs:       { type: Array, default: () => [] },
  modelValue: String,
  variant:    { type: String, default: 'line' }, // line | pills
})
defineEmits(['update:modelValue'])

const tabBarClass = computed(() =>
  props.variant === 'pills'
    ? 'flex gap-1 bg-cream-soft p-1 rounded-2xl w-fit border border-[var(--border)]'
    : 'flex gap-0 border-b border-[var(--border-soft)]'
)

const tabClass = (tab) => {
  const active = props.modelValue === tab.value
  if (props.variant === 'pills') {
    return [
      'flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-all',
      active ? 'bg-white text-navy shadow-card' : 'text-slate-500 hover:text-navy',
    ]
  }
  return [
    'flex items-center px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-all',
    active
      ? 'border-navy text-navy'
      : 'border-transparent text-slate-500 hover:text-navy hover:border-slate-300',
  ]
}
</script>
