<template>
  <Teleport to="body">
    <TransitionGroup name="toast" tag="div" class="toast-container">
      <div
        v-for="t in toastState.items"
        :key="t.id"
        class="toast"
        :class="`toast--${t.type}`"
      >
        <span class="toast-icon">{{ icons[t.type] }}</span>
        <span class="toast-msg">{{ t.message }}</span>
        <button class="toast-close" @click="remove(t.id)">✕</button>
      </div>
    </TransitionGroup>
  </Teleport>
</template>

<script setup>
import { toastState } from '@/composables/useToast'

const icons = { success: '✓', error: '✕', info: 'ℹ' }

function remove(id) {
  const i = toastState.items.findIndex(t => t.id === id)
  if (i !== -1) toastState.items.splice(i, 1)
}
</script>

<style scoped>
.toast-container {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  pointer-events: none;
}

.toast {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  min-width: 240px;
  max-width: 360px;
  padding: 0.75rem 1rem;
  border-radius: 16px;
  font-size: 0.875rem;
  font-weight: 500;
  pointer-events: all;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  backdrop-filter: blur(12px);
}

.toast--success {
  background: color-mix(in srgb, #22c55e 15%, var(--surface-strong));
  border: 1px solid color-mix(in srgb, #22c55e 30%, transparent);
  color: #16a34a;
}

.toast--error {
  background: color-mix(in srgb, #ef4444 12%, var(--surface-strong));
  border: 1px solid color-mix(in srgb, #ef4444 30%, transparent);
  color: #dc2626;
}

.toast--info {
  background: color-mix(in srgb, var(--accent) 12%, var(--surface-strong));
  border: 1px solid color-mix(in srgb, var(--accent) 30%, transparent);
  color: var(--accent);
}

.toast-icon { font-size: 1rem; flex-shrink: 0; }
.toast-msg  { flex: 1; }
.toast-close {
  flex-shrink: 0;
  opacity: 0.5;
  font-size: 0.75rem;
  cursor: pointer;
  background: none;
  border: none;
  color: inherit;
  padding: 0 2px;
}
.toast-close:hover { opacity: 1; }

.toast-enter-active { transition: all 0.3s cubic-bezier(0.22,1,0.36,1); }
.toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from  { opacity: 0; transform: translateX(40px); }
.toast-leave-to    { opacity: 0; transform: translateX(40px); }
</style>
