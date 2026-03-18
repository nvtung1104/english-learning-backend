<template>
  <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
    <div class="w-16 h-16 rounded-3xl bg-cream-soft border border-[var(--border)] flex items-center justify-center text-3xl mb-4">
      {{ icon }}
    </div>
    <h3 class="font-display text-lg font-bold text-navy mb-1.5">{{ title }}</h3>
    <p class="text-sm text-slate-400 max-w-xs leading-relaxed mb-5">{{ description }}</p>
    <slot />
  </div>
</template>

<script setup>
defineProps({
  icon:        { type: String, default: '📭' },
  title:       { type: String, default: 'Không có dữ liệu' },
  description: { type: String, default: 'Chưa có dữ liệu nào để hiển thị.' },
})
</script>
