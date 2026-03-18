<template>
  <div class="card overflow-hidden">
    <!-- Table header toolbar -->
    <div v-if="$slots.toolbar || searchable" class="px-5 py-4 border-b border-[var(--border-soft)] flex flex-wrap items-center justify-between gap-3">
      <div v-if="searchable" class="relative">
        <input
          :value="search"
          @input="$emit('update:search', $event.target.value)"
          type="text"
          :placeholder="searchPlaceholder"
          class="w-64 pl-9 pr-4 py-2 rounded-xl border border-[var(--border)] text-sm bg-cream-soft focus:bg-white focus:outline-none focus:border-navy/40 focus:ring-2 focus:ring-navy/8 transition-all placeholder:text-slate-400"
        />
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <slot name="toolbar" />
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
            <th
              v-for="col in columns" :key="col.key"
              :class="['px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap', col.class || '']"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[var(--border-soft)]">
          <!-- Loading -->
          <tr v-if="loading">
            <td :colspan="columns.length" class="px-5 py-12 text-center">
              <div class="flex items-center justify-center gap-2 text-slate-400">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span class="text-sm">Đang tải...</span>
              </div>
            </td>
          </tr>
          <!-- Empty -->
          <tr v-else-if="!rows.length">
            <td :colspan="columns.length" class="px-5 py-12">
              <EmptyState :icon="emptyIcon" :title="emptyTitle" :description="emptyDescription">
                <slot name="empty-action" />
              </EmptyState>
            </td>
          </tr>
          <!-- Rows -->
          <tr
            v-else
            v-for="(row, i) in rows" :key="row.id ?? i"
            class="hover:bg-cream-soft/50 transition-colors group"
          >
            <slot name="row" :row="row" :index="i" />
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Footer / Pagination -->
    <div v-if="$slots.footer || (lastPage && lastPage > 1)" class="px-5 py-4 border-t border-[var(--border-soft)]">
      <slot name="footer">
        <AppPagination
          :current-page="currentPage"
          :last-page="lastPage"
          :total="total"
          :per-page="perPage"
          @change="$emit('page-change', $event)"
        />
      </slot>
    </div>
  </div>
</template>

<script setup>
import EmptyState from '../ui/EmptyState.vue'
import AppPagination from '../ui/AppPagination.vue'

defineProps({
  columns:           { type: Array, default: () => [] },
  rows:              { type: Array, default: () => [] },
  loading:           Boolean,
  searchable:        Boolean,
  search:            String,
  searchPlaceholder: { type: String, default: 'Tìm kiếm...' },
  currentPage:       { type: Number, default: 1 },
  lastPage:          { type: Number, default: 1 },
  total:             { type: Number, default: 0 },
  perPage:           { type: Number, default: 15 },
  emptyIcon:         { type: String, default: '📭' },
  emptyTitle:        { type: String, default: 'Không có dữ liệu' },
  emptyDescription:  { type: String, default: 'Chưa có bản ghi nào.' },
})
defineEmits(['update:search', 'page-change'])
</script>
