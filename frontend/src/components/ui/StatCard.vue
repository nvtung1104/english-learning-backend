<template>
  <div class="card p-5 flex flex-col gap-4">
    <div class="flex items-start justify-between">
      <div :class="['w-11 h-11 rounded-2xl flex items-center justify-center text-xl shrink-0', iconBg]">
        {{ icon }}
      </div>
      <span
        v-if="trend !== undefined"
        :class="[
          'flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-lg',
          trend >= 0 ? 'bg-success-50 text-success-700' : 'bg-danger-50 text-danger-700'
        ]"
      >
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round"
            :d="trend >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3'"/>
        </svg>
        {{ Math.abs(trend) }}%
      </span>
    </div>
    <div>
      <div class="text-2xl font-bold text-navy font-display">{{ value }}</div>
      <div class="text-sm text-slate-500 mt-0.5">{{ label }}</div>
      <p v-if="sub" class="text-xs text-slate-400 mt-1">{{ sub }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  icon:  String,
  label: String,
  value: [String, Number],
  trend: Number,
  sub:   String,
  color: { type: String, default: 'navy' },
})

const bgMap = {
  navy:    'bg-navy/8',
  gold:    'bg-gold-pale',
  emerald: 'bg-success-50',
  amber:   'bg-warning-50',
  red:     'bg-danger-50',
  purple:  'bg-purple-50',
  sage:    'bg-sage-50',
}
const iconBg = computed(() => bgMap[props.color] || 'bg-navy/8')
</script>
