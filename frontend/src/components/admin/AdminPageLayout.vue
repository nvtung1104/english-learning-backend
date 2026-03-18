<template>
  <div class="space-y-5">
    <!-- Page header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">{{ title }}</h1>
        <p v-if="subtitle" class="page-subtitle">{{ subtitle }}</p>
      </div>
      <div v-if="$slots.actions" class="flex items-center gap-2 shrink-0">
        <slot name="actions" />
      </div>
    </div>

    <!-- Filters -->
    <div v-if="$slots.filters" class="card p-4">
      <div class="flex flex-wrap items-center gap-3">
        <slot name="filters" />
      </div>
    </div>

    <!-- Content -->
    <slot />
  </div>
</template>

<script setup>
defineProps({ title: String, subtitle: String })
</script>
