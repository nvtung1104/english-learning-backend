<template>
  <div style="min-height:100vh;background:#faf8f4;display:flex;flex-direction:column;">
    <Navbar />
    <main style="flex:1;padding-top:82px;">
      <slot />
    </main>
    <PublicFooter />
  </div>
</template>

<script setup>
import Navbar from '../components/Navbar.vue'
import PublicFooter from '../components/PublicFooter.vue'
</script>
