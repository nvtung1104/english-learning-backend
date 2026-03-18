import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastStore = defineStore('toast', () => {
  const toasts = ref([])
  let counter = 0

  function add({ type = 'info', title = '', message = '', duration = 4000 }) {
    const id = ++counter
    toasts.value.push({ id, type, title, message })
    if (duration > 0) setTimeout(() => remove(id), duration)
    return id
  }

  function remove(id) {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  const success = (message, title = 'Thành công') => add({ type: 'success', title, message })
  const error   = (message, title = 'Lỗi')        => add({ type: 'error', title, message })
  const warning = (message, title = 'Cảnh báo')   => add({ type: 'warning', title, message })
  const info    = (message, title = '')            => add({ type: 'info', title, message })

  return { toasts, add, remove, success, error, warning, info }
})
