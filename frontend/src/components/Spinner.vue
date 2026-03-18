<template>
  <div class="flex items-center justify-center py-16">
    <div class="flex flex-col items-center gap-3">
      <div class="w-10 h-10 rounded-full border-2 border-[var(--border)] border-t-navy animate-spin" />
      <p v-if="label" class="text-sm text-slate-400">{{ label }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({ label: { type: String, default: '' } })
</script>
