<template>
  <div class="divide-y divide-[var(--border-soft)]">
    <div v-for="(item, i) in items" :key="i">
      <button
        @click="toggle(i)"
        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-cream-soft transition-colors group"
      >
        <span class="font-semibold text-navy text-sm group-hover:text-navy/80 pr-4">{{ item.title }}</span>
        <svg
          :class="['w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0', open === i ? 'rotate-180' : '']"
          fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <Transition name="accordion">
        <div v-if="open === i" class="px-5 pb-4">
          <p class="text-sm text-slate-600 leading-relaxed">{{ item.content }}</p>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
defineProps({ items: { type: Array, default: () => [] } })
const open   = ref(null)
const toggle = (i) => { open.value = open.value === i ? null : i }
</script>

<style scoped>
.accordion-enter-active, .accordion-leave-active {
  transition: all 0.22s ease;
  overflow: hidden;
}
.accordion-enter-from, .accordion-leave-to { opacity: 0; max-height: 0; }
.accordion-enter-to, .accordion-leave-from  { opacity: 1; max-height: 300px; }
</style>
