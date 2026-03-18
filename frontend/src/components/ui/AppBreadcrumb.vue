<template>
  <nav class="flex items-center gap-1.5 text-sm" aria-label="Breadcrumb">
    <template v-for="(item, i) in items" :key="i">
      <RouterLink
        v-if="item.to"
        :to="item.to"
        class="text-slate-400 hover:text-navy transition-colors font-medium"
      >
        {{ item.label }}
      </RouterLink>
      <span v-else class="text-navy font-semibold">{{ item.label }}</span>
      <svg
        v-if="i < items.length - 1"
        class="w-3.5 h-3.5 text-slate-300 shrink-0"
        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
      </svg>
    </template>
  </nav>
</template>

<script setup>
defineProps({ items: { type: Array, default: () => [] } })
</script>
