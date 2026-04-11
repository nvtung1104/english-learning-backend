import { computed, ref, watch } from 'vue'

const STORAGE_KEY = 'theme'
const theme = ref('system')

function getSystemTheme() {
  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}

function resolveTheme(value) {
  return value === 'system' ? getSystemTheme() : value
}

function getInitialTheme() {
  const saved = localStorage.getItem(STORAGE_KEY)
  if (saved === 'light' || saved === 'dark' || saved === 'system') return saved
  return 'system'
}

function applyTheme(value) {
  const resolved = resolveTheme(value)
  const root = document.documentElement

  root.classList.toggle('dark', resolved === 'dark')
  root.setAttribute('data-theme', resolved)
  localStorage.setItem(STORAGE_KEY, value)
}

if (typeof window !== 'undefined') {
  theme.value = getInitialTheme()

  watch(
    theme,
    (value) => {
      applyTheme(value)
    },
    { immediate: true }
  )

  const media = window.matchMedia('(prefers-color-scheme: dark)')
  const handleChange = () => {
    if (theme.value === 'system') applyTheme('system')
  }

  if (media.addEventListener) {
    media.addEventListener('change', handleChange)
  } else {
    media.addListener(handleChange)
  }
}

export function useTheme() {
  const resolvedTheme = computed(() => resolveTheme(theme.value))
  const isDark = computed(() => resolvedTheme.value === 'dark')
  const isLight = computed(() => resolvedTheme.value === 'light')

  function toggle() {
    theme.value = resolvedTheme.value === 'dark' ? 'light' : 'dark'
  }

  function setTheme(value) {
    if (!['light', 'dark', 'system'].includes(value)) return
    theme.value = value
  }

  return {
    theme,
    resolvedTheme,
    isDark,
    isLight,
    toggle,
    setTheme,
  }
}