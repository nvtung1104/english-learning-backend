<template>
  <div v-if="lastPage > 1" class="flex flex-col sm:flex-row items-center justify-between gap-4">
    <p class="text-sm text-slate-500">
      Hiển thị <span class="font-semibold text-navy">{{ from }}–{{ to }}</span>
      trong <span class="font-semibold text-navy">{{ total }}</span> kết quả
    </p>
    <div class="flex items-center gap-1">
      <button
        @click="$emit('change', currentPage - 1)"
        :disabled="currentPage === 1"
        class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:bg-cream-soft border border-[var(--border)] disabled:opacity-40 disabled:cursor-not-allowed transition-all"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <template v-for="page in pages" :key="page">
        <span v-if="page === '...'" class="w-9 h-9 flex items-center justify-center text-slate-400 text-sm">…</span>
        <button
          v-else
          @click="$emit('change', page)"
          :class="[
            'w-9 h-9 rounded-xl text-sm font-medium transition-all border',
            page === currentPage
              ? 'bg-navy text-white border-navy shadow-card'
              : 'text-slate-600 border-[var(--border)] hover:bg-cream-soft hover:text-navy'
          ]"
        >
          {{ page }}
        </button>
      </template>

      <button
        @click="$emit('change', currentPage + 1)"
        :disabled="currentPage === lastPage"
        class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-500 hover:bg-cream-soft border border-[var(--border)] disabled:opacity-40 disabled:cursor-not-allowed transition-all"
      >
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  currentPage: { type: Number, default: 1 },
  lastPage:    { type: Number, default: 1 },
  total:       { type: Number, default: 0 },
  perPage:     { type: Number, default: 15 },
})
defineEmits(['change'])

const from  = computed(() => Math.min((props.currentPage - 1) * props.perPage + 1, props.total))
const to    = computed(() => Math.min(props.currentPage * props.perPage, props.total))
const pages = computed(() => {
  const p = [], cur = props.currentPage, last = props.lastPage
  if (last <= 7) { for (let i = 1; i <= last; i++) p.push(i); return p }
  p.push(1)
  if (cur > 3) p.push('...')
  for (let i = Math.max(2, cur - 1); i <= Math.min(last - 1, cur + 1); i++) p.push(i)
  if (cur < last - 2) p.push('...')
  p.push(last)
  return p
})
</script>
