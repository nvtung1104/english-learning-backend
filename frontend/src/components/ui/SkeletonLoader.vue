<template>
  <div
    :class="[
      'animate-pulse rounded-xl bg-gradient-to-r from-slate-100 via-slate-50 to-slate-100 bg-[length:200%_100%]',
      height, width, extraClass
    ]"
    style="animation: shimmer 1.5s infinite; background-size: 200% 100%;"
  />
</template>

<script setup>
const props = defineProps({
  height:     { type: String, default: 'h-4' },
  width:      { type: String, default: 'w-full' },
  extraClass: { type: String, default: '' },
})
</script>

<style scoped>
@keyframes shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}
</style>
