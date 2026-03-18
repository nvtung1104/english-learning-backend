<template>
  <Teleport to="body">
    <div class="fixed top-5 right-5 z-[100] flex flex-col gap-2.5 pointer-events-none">
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          :class="['pointer-events-auto flex items-start gap-3 px-4 py-3.5 rounded-2xl shadow-modal min-w-[280px] max-w-sm border', toastClass(toast.type)]"
        >
          <span class="text-lg shrink-0 mt-0.5">{{ toastIcon(toast.type) }}</span>
          <div class="flex-1 min-w-0">
            <p v-if="toast.title" class="text-sm font-bold mb-0.5">{{ toast.title }}</p>
            <p class="text-sm leading-snug">{{ toast.message }}</p>
          </div>
          <button @click="remove(toast.id)" class="shrink-0 opacity-50 hover:opacity-100 transition-opacity mt-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToastStore } from '../../stores/toast'
import { storeToRefs } from 'pinia'

const store = useToastStore()
const { toasts } = storeToRefs(store)
const { remove } = store

const toastClass = (type) => ({
  success: 'bg-white border-success-200 text-success-800',
  error:   'bg-white border-danger-200 text-danger-800',
  warning: 'bg-white border-warning-200 text-warning-800',
  info:    'bg-white border-navy/20 text-navy',
}[type] || 'bg-white border-slate-200 text-slate-800')

const toastIcon = (type) => ({ success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' }[type] || 'ℹ️')
</script>

<style scoped>
.toast-enter-active { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
.toast-leave-active { transition: all 0.2s ease; }
.toast-enter-from   { opacity: 0; transform: translateX(100%) scale(0.9); }
.toast-leave-to     { opacity: 0; transform: translateX(100%); }
</style>
