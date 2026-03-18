<template>
  <div class="min-h-screen bg-cream flex items-center justify-center p-6">
    <div class="text-center max-w-md">
      <div class="font-display text-[120px] font-bold text-navy/8 leading-none mb-4 select-none">404</div>
      <div class="w-16 h-16 rounded-3xl bg-gold-pale border border-gold/20 flex items-center justify-center text-3xl mx-auto -mt-8 mb-6">🗺️</div>
      <h1 class="font-display text-3xl font-bold text-navy mb-3">Trang không tồn tại</h1>
      <p class="text-slate-500 text-sm leading-relaxed mb-8">
        Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
      </p>
      <div class="flex flex-wrap gap-3 justify-center">
        <RouterLink to="/" class="btn btn-md btn-primary">Về trang chủ</RouterLink>
        <RouterLink to="/courses" class="btn btn-md btn-secondary">Xem khóa học</RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
