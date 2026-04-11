import { reactive } from 'vue'

// Shared reactive state — works immediately without onMounted
export const toastState = reactive({ items: [] })
let counter = 0

export function useToast() {
  function add(message, type = 'success', duration = 3000) {
    const id = ++counter
    toastState.items.push({ id, message, type })
    if (duration > 0) {
      setTimeout(() => {
        const i = toastState.items.findIndex(t => t.id === id)
        if (i !== -1) toastState.items.splice(i, 1)
      }, duration)
    }
  }

  return {
    success: (msg, duration) => add(msg, 'success', duration),
    error:   (msg, duration) => add(msg, 'error',   duration),
    info:    (msg, duration) => add(msg, 'info',    duration),
  }
}
