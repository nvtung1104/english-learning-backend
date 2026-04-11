<template>
  <div class="min-h-screen" style="background: var(--bg); color: var(--text);">
    <AppHeader />
    <main class="pt-20">
      <RouterView />
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router'
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
import { useTheme } from '@/composables/useTheme'

useTheme()
</script>
