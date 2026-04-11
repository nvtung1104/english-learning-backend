<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-3xl space-y-6">

      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/student/dashboard" class="text-sm theme-text-muted hover:theme-text">← Dashboard</RouterLink>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Thông báo</h1>
        </div>
        <button
          v-if="unread > 0"
          @click="markAllRead"
          class="self-start rounded-full px-4 py-2 text-sm font-semibold transition"
          style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
        >
          Đánh dấu tất cả đã đọc ({{ unread }})
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="space-y-3">
        <div v-for="i in 5" :key="i" class="theme-card rounded-[24px] p-5 animate-pulse h-20 skeleton"></div>
      </div>

      <!-- Empty -->
      <div v-else-if="!notifications.length" class="theme-card rounded-[30px] p-12 text-center">
        <p class="text-5xl">🔔</p>
        <p class="mt-4 text-lg font-semibold theme-text">Không có thông báo nào</p>
        <p class="mt-2 text-sm theme-text-soft">Bạn đã cập nhật hết rồi.</p>
      </div>

      <!-- List -->
      <div v-else class="space-y-3">
        <div
          v-for="n in notifications"
          :key="n.id"
          class="notif-item rounded-[24px] p-5 transition cursor-pointer"
          :class="{ 'is-unread': !n.is_read }"
          @click="markRead(n)"
        >
          <div class="flex items-start gap-4">
            <div
              class="grid h-10 w-10 place-items-center rounded-2xl text-xl shrink-0"
              style="background: var(--surface-strong)"
            >🔔</div>
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-2">
                <p class="font-semibold theme-text">{{ n.title ?? 'Thông báo mới' }}</p>
                <span
                  v-if="!n.is_read"
                  class="shrink-0 h-2 w-2 rounded-full mt-1.5"
                  style="background: var(--accent)"
                ></span>
              </div>
              <p class="mt-1 text-sm theme-text-soft">{{ n.message ?? n.body }}</p>
              <p class="mt-2 text-xs theme-text-muted">{{ formatDate(n.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { notificationService } from '@/services/teacher.service'

const notifications = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await notificationService.list()
    notifications.value = res.data?.data ?? res.data ?? []
  } finally {
    loading.value = false
  }
})

const unread = computed(() => notifications.value.filter(n => !n.is_read).length)

async function markRead(n) {
  if (n.is_read) return
  try {
    await notificationService.markRead(n.id)
    n.is_read = true
  } catch { /* ignore */ }
}

async function markAllRead() {
  const items = notifications.value.filter(n => !n.is_read)
  await Promise.allSettled(items.map(n => notificationService.markRead(n.id)))
  items.forEach(n => { n.is_read = true })
}

function formatDate(date) {
  if (!date) return ''
  return new Date(date).toLocaleString('vi-VN')
}
</script>

<style scoped>
.skeleton { background: var(--border); }
.notif-item {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.notif-item:hover { background: var(--surface-strong); }
.notif-item.is-unread {
  border-color: color-mix(in srgb, var(--accent) 30%, var(--border));
  background: color-mix(in srgb, var(--accent) 4%, var(--surface-muted));
}
</style>
