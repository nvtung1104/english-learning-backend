<template>
  <div class="app-shell min-h-screen">
    <RouterView />
    <ToastNotification />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import { useTheme } from '@/composables/useTheme'
import ToastNotification from '@/components/ui/ToastNotification.vue'

useTheme()
</script>
