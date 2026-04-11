<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
    <main class="flex min-h-screen items-center px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1fr_1fr]">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">404 - Not Found</p>
          <h1 class="mt-4 text-5xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-6xl">
            Trang bạn tìm kiếm không tồn tại.
          </h1>
          <p class="mt-6 max-w-2xl text-base leading-8 text-slate-600 dark:text-slate-400 sm:text-lg">
            Có thể đường dẫn đã thay đổi, trang đã bị xóa hoặc bạn truy cập sai địa chỉ. Hãy quay lại trang chủ hoặc tiếp tục khám phá các khu vực khác của hệ thống.
          </p>

          <div class="mt-10 flex flex-col gap-4 sm:flex-row">
            <RouterLink
              to="/"
              class="rounded-full bg-slate-950 px-7 py-4 text-center text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:shadow-2xl"
            >
              Về trang chủ
            </RouterLink>
            <RouterLink
              to="/courses"
              class="rounded-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-7 py-4 text-center text-sm font-semibold text-slate-800 dark:text-slate-200 transition hover:bg-slate-50 dark:bg-slate-800"
            >
              Xem khóa học
            </RouterLink>
          </div>
        </div>

        <div class="relative">
          <div class="absolute -left-8 top-10 hidden h-28 w-28 rounded-full bg-sky-200/60 blur-3xl lg:block"></div>
          <div class="absolute -right-6 bottom-10 hidden h-28 w-28 rounded-full bg-emerald-200/60 blur-3xl lg:block"></div>

          <div class="rounded-[40px] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-[0_30px_80px_rgba(15,23,42,0.08)]">
            <div class="grid min-h-[420px] place-items-center rounded-[32px] bg-[linear-gradient(180deg,#f8fbff_0%,#ffffff_45%,#f8fafc_100%)]">
              <div class="text-center">
                <div class="text-[120px] font-semibold leading-none tracking-tight text-slate-950 dark:text-white sm:text-[160px]">
                  404
                </div>
                <p class="mt-4 text-base font-medium text-slate-500 dark:text-slate-400">
                  Không tìm thấy nội dung bạn yêu cầu
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>

<style scoped>
:root {
  --bg: #f8fafc;
}
</style>