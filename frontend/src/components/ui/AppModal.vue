<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div
          class="absolute inset-0 bg-navy/50 backdrop-blur-sm"
          @click="!persistent && $emit('update:modelValue', false)"
        />
        <!-- Panel -->
        <div :class="['relative bg-white rounded-3xl shadow-modal w-full animate-slide-up overflow-hidden', sizeClass]">
          <!-- Header -->
          <div v-if="title || $slots.header" class="flex items-center justify-between px-6 py-4 border-b border-[var(--border-soft)]">
            <slot name="header">
              <h3 class="font-display text-lg font-bold text-navy">{{ title }}</h3>
            </slot>
            <button
              v-if="!persistent"
              @click="$emit('update:modelValue', false)"
              class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-cream-soft transition-all"
            >
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <!-- Body -->
          <div class="px-6 py-5">
            <slot />
          </div>
          <!-- Footer -->
          <div v-if="$slots.footer" class="px-6 py-4 border-t border-[var(--border-soft)] flex items-center justify-end gap-3">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  title:      String,
  size:       { type: String, default: 'md' },
  persistent: Boolean,
})
defineEmits(['update:modelValue'])

const sizeClass = computed(() => ({
  sm: 'max-w-sm', md: 'max-w-lg', lg: 'max-w-2xl', xl: 'max-w-4xl'
}[props.size] || 'max-w-lg'))
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
