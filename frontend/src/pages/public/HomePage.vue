<template>
  <main class="bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <section class="hero-section relative overflow-hidden px-4 pb-20 pt-28 sm:px-6 lg:px-8 lg:pb-28 lg:pt-36">
      <div class="absolute inset-0">
        <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
        <div class="absolute inset-0 bg-slate-950/45 dark:bg-black/50"></div>
      </div>
      <div class="absolute inset-0 hero-grid opacity-30 dark:opacity-20"></div>
      <div class="absolute -left-16 top-10 h-72 w-72 rounded-full bg-sky-300/20 blur-3xl floating-orb"></div>
      <div class="absolute right-10 top-24 h-80 w-80 rounded-full bg-cyan-200/20 blur-3xl floating-orb-delayed"></div>
      <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-emerald-200/10 blur-3xl floating-orb-slow"></div>

      <div class="relative mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.08fr_0.92fr]">
        <div>
          <div class="reveal shine-hover inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur-md dark:border-sky-200/20 dark:bg-slate-900/70 dark:text-sky-300">
            Nền tảng học và thi tiếng Anh online
          </div>

          <h1 class="hero-title mt-6 max-w-3xl text-5xl font-semibold leading-snug tracking-tight text-white sm:text-6xl xl:text-7xl">
            <span class="block pb-1">Học tiếng Anh theo</span>
            <span class="block pb-1">
              <span class="gradient-text">{{ gradientDisplayed }}</span><span v-if="phase === 'gradient'" class="tw-cursor">|</span>
            </span>
            <span class="block min-h-[1.4em]">
              {{ line2Displayed }}<span v-if="phase === 'line2'" class="tw-cursor">|</span>
            </span>
          </h1>

          <p class="reveal mt-6 max-w-2xl text-lg leading-8 text-white/85 sm:text-xl dark:text-white/80">
            Kết hợp khóa học, luyện từ vựng, ngữ pháp, listening, speaking và quiz trong một trải nghiệm hiện đại, rõ ràng, cao cấp và dễ dùng.
          </p>

          <div class="reveal mt-9 flex flex-col gap-4 sm:flex-row">
            <RouterLink
              to="/register"
              class="magnetic-btn shine-hover rounded-full bg-slate-950 px-7 py-4 text-center text-sm font-semibold text-white transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:bg-white dark:text-slate-950"
            >
              Bắt đầu học ngay
            </RouterLink>

            <RouterLink
              to="/courses"
              class="magnetic-btn shine-hover rounded-full border border-white/60 bg-white/85 px-7 py-4 text-center text-sm font-semibold text-slate-900 transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-200 dark:hover:bg-slate-700"
            >
              Khám phá khóa học
            </RouterLink>
          </div>

          <div class="mt-10 grid max-w-2xl grid-cols-2 gap-4 md:grid-cols-4">
            <div
              v-for="item in heroStats"
              :key="item.label"
              class="reveal stat-card rounded-[28px] border border-white/70 bg-white/75 p-4 shadow-[0_20px_50px_rgba(15,23,42,0.08)] backdrop-blur dark:border-white/10 dark:bg-slate-800/75"
            >
              <p class="text-2xl font-semibold text-slate-950 dark:text-white">{{ item.value }}</p>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ item.label }}</p>
            </div>
          </div>
        </div>

        <div class="relative reveal">
          <div class="absolute -left-8 top-10 hidden h-28 w-28 rounded-full bg-sky-200/60 blur-3xl lg:block dark:bg-sky-400/20"></div>
          <div class="absolute -right-6 bottom-10 hidden h-28 w-28 rounded-full bg-emerald-200/60 blur-3xl lg:block dark:bg-emerald-400/20"></div>

          <div class="glass-panel relative rounded-[36px] border border-white/70 bg-white/80 p-4 shadow-[0_35px_80px_rgba(15,23,42,0.14)] backdrop-blur-xl sm:p-5 dark:border-white/10 dark:bg-slate-900/70">
            <div class="neon-border overflow-hidden rounded-[30px] bg-slate-100 p-5 text-slate-900 dark:bg-slate-950 dark:text-white">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs uppercase tracking-[0.24em] text-slate-500 dark:text-white/50">Bảng học tập</p>
                  <h2 class="mt-2 text-2xl font-semibold">Lộ trình IELTS Foundation</h2>
                </div>
                <div class="rounded-full bg-slate-900/8 px-3 py-1 text-xs font-medium text-slate-700 dark:bg-white/10 dark:text-white/80">
                  Live progress
                </div>
              </div>

              <div class="mt-6 grid gap-4 md:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-[28px] bg-white p-4 shadow-sm transition duration-300 hover:bg-slate-50 dark:bg-white/8 dark:hover:bg-white/10">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-slate-500 dark:text-white/55">Tỉ lệ hoàn thành</p>
                      <p class="mt-2 text-3xl font-semibold">{{ completionRate !== null ? completionRate + '%' : '—' }}</p>
                    </div>
                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-emerald-400/15 text-emerald-600 shadow-[0_0_30px_rgba(52,211,153,0.20)] dark:bg-emerald-400/20 dark:text-emerald-300">
                      ▲
                    </div>
                  </div>

                  <div class="mt-5 h-3 rounded-full bg-slate-200 dark:bg-white/10">
                    <div class="progress-glow h-3 rounded-full bg-gradient-to-r from-sky-400 to-emerald-400" :style="{ width: completionRate ? completionRate + '%' : '0%' }"></div>
                  </div>

                  <div class="mt-5 grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-2xl bg-slate-100 p-3 transition duration-300 hover:-translate-y-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10">
                      <p class="text-slate-500 dark:text-white/45">Khóa học</p>
                      <p class="mt-1 font-semibold">{{ totalCourses || '—' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-100 p-3 transition duration-300 hover:-translate-y-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10">
                      <p class="text-slate-500 dark:text-white/45">Đề thi</p>
                      <p class="mt-1 font-semibold">{{ totalTests !== null ? totalTests : '—' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-100 p-3 transition duration-300 hover:-translate-y-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10">
                      <p class="text-slate-500 dark:text-white/45">Điểm TB</p>
                      <p class="mt-1 font-semibold">{{ avgScore !== null ? avgScore : '—' }}</p>
                    </div>
                  </div>
                </div>

                <div class="space-y-4">
                  <div class="rounded-[28px] bg-white p-4 text-slate-900 transition duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-slate-800 dark:text-slate-100">
                    <p class="text-sm text-slate-400">Lượt thi toàn hệ thống</p>
                    <p class="mt-2 text-lg font-semibold">Quiz &amp; Mock Test</p>

                    <div class="mt-4 flex items-end justify-between">
                      <div>
                        <p class="text-3xl font-semibold">{{ totalAttempts || '—' }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">lượt làm bài đã ghi nhận</p>
                      </div>

                      <RouterLink
                        to="/tests"
                        class="shine-hover rounded-full bg-slate-950 px-4 py-2 text-xs font-semibold text-white dark:bg-white dark:text-slate-950"
                      >
                        Thi thử ngay
                      </RouterLink>
                    </div>
                  </div>

                  <div class="rounded-[28px] bg-white p-4 shadow-sm transition duration-300 hover:bg-slate-50 dark:bg-white/8 dark:hover:bg-white/10">
                    <div class="flex items-center justify-between">
                      <p class="text-sm text-slate-500 dark:text-white/55">Thống kê hệ thống</p>
                      <p class="text-xs text-slate-400 dark:text-white/40">live</p>
                    </div>

                    <div class="mt-3 space-y-3 text-sm">
                      <RouterLink
                        to="/courses"
                        class="group flex items-center justify-between rounded-2xl bg-slate-100 px-3 py-3 transition duration-300 hover:translate-x-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10"
                      >
                        <span>Học viên đăng ký</span>
                        <span class="text-emerald-500 dark:text-emerald-300">{{ totalEnrollments || '—' }}</span>
                      </RouterLink>

                      <RouterLink
                        to="/practice"
                        class="group flex items-center justify-between rounded-2xl bg-slate-100 px-3 py-3 transition duration-300 hover:translate-x-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10"
                      >
                        <span>Bài học hoàn thành</span>
                        <span class="text-sky-500 dark:text-sky-300">{{ lessonsCompleted || '—' }}</span>
                      </RouterLink>

                      <RouterLink
                        to="/tests"
                        class="group flex items-center justify-between rounded-2xl bg-slate-100 px-3 py-3 transition duration-300 hover:translate-x-1 hover:bg-slate-200 dark:bg-white/6 dark:hover:bg-white/10"
                      >
                        <span>Đánh giá TB</span>
                        <span class="text-amber-500 dark:text-amber-300">{{ avgRating ? '⭐ ' + avgRating : '—' }}</span>
                      </RouterLink>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-3 hidden grid-cols-2 gap-3 md:grid">
              <RouterLink
                to="/courses"
                class="shine-hover rounded-[20px] border border-white/70 bg-white/90 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-slate-800/90"
              >
                <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Học viên</p>
                <p class="mt-1.5 text-2xl font-semibold text-slate-950 dark:text-white">{{ totalUsers ? totalUsers.toLocaleString('vi-VN') + '+' : '—' }}</p>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">đang học mỗi tháng</p>
              </RouterLink>
              <RouterLink
                to="/tests"
                class="shine-hover rounded-[20px] border border-white/70 bg-white/90 p-4 transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:border-white/10 dark:bg-slate-800/90"
              >
                <p class="text-xs uppercase tracking-[0.26em] text-slate-500">Đề luyện tập</p>
                <p class="mt-1.5 text-2xl font-semibold text-slate-950 dark:text-white">{{ totalTests !== null ? totalTests : '—' }}</p>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">quiz và mock test sẵn sàng</p>
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
      <div class="mx-auto max-w-7xl">
        <div class="max-w-2xl reveal">
          <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-500">Thế mạnh hệ thống</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
            Mọi thứ bạn cần để học, luyện tập và thi online hiệu quả.
          </h2>
        </div>

        <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
          <article
            v-for="feature in features"
            :key="feature.title"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
            class="reveal tilt-card shine-hover group rounded-[30px] border border-slate-200 bg-white p-6 shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_24px_60px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_16px_40px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_24px_60px_rgba(0,0,0,0.3)]"
          >
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-xl transition duration-300 group-hover:scale-110 group-hover:bg-slate-950 group-hover:text-white dark:bg-slate-700 dark:group-hover:bg-white dark:group-hover:text-slate-950">
              {{ feature.icon }}
            </div>

            <h3 class="mt-6 text-xl font-semibold text-slate-950 dark:text-white">{{ feature.title }}</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">{{ feature.description }}</p>

            <RouterLink
              :to="feature.to"
              class="mt-5 inline-block text-sm font-semibold text-slate-900 dark:text-white"
            >
              Tìm hiểu thêm
              <span class="inline-block transition duration-300 group-hover:translate-x-1">→</span>
            </RouterLink>
          </article>
        </div>
      </div>
    </section>

    <section class="px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <!-- Khung background đẹp -->
        <div class="relative overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#f0f9ff_0%,#e0f2fe_50%,#ecfdf5_100%)] px-8 py-12 dark:bg-[linear-gradient(135deg,#0c1a2e_0%,#0f172a_50%,#0c1f18_100%)] sm:px-10 lg:px-12">
          <!-- Orbs -->
          <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-sky-300/40 blur-3xl dark:bg-sky-500/10"></div>
          <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-emerald-300/40 blur-3xl dark:bg-emerald-500/10"></div>
          <div class="absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-200/25 blur-3xl dark:bg-violet-500/5"></div>

          <div class="relative">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
              <div class="max-w-2xl reveal">
                <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur dark:border-sky-800 dark:bg-sky-950/60 dark:text-sky-400">
                  ✦ Khóa học nổi bật
                </span>
                <h2 class="mt-5 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
                  Thiết kế như một tạp chí học tập hiện đại, nhưng tập trung hoàn toàn vào hiệu quả học tiếng Anh.
                </h2>
              </div>

              <RouterLink
                to="/courses"
                class="reveal self-start rounded-full border border-slate-300 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm backdrop-blur transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-200 dark:hover:bg-slate-700"
              >
                Xem tất cả khóa học →
              </RouterLink>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
          <article
            v-for="course in courses"
            :key="course.id"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
            class="reveal tilt-card shine-hover overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_26px_70px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_26px_70px_rgba(0,0,0,0.3)]"
          >
            <RouterLink :to="`/courses/${course.id}`" class="block">
              <div class="relative h-72 overflow-hidden">
                <img
                  :src="$storage(course.thumbnail) || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80'"
                  :alt="course.title"
                  class="course-image h-full w-full object-cover transition duration-700"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/10 to-transparent"></div>
                <div v-if="course.level" class="absolute left-5 top-5 inline-flex rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 backdrop-blur dark:bg-slate-900/80 dark:text-slate-100">
                  {{ course.level.name }}
                </div>
                <div class="absolute bottom-5 left-5 right-5">
                  <p v-if="course.category" class="text-xs uppercase tracking-[0.24em] text-white/65">{{ course.category.name }}</p>
                  <h3 class="mt-2 text-2xl font-semibold text-white">{{ course.title }}</h3>
                </div>
              </div>
            </RouterLink>

            <div class="p-6">
              <p class="text-sm leading-7 text-slate-600 dark:text-slate-400">{{ course.description }}</p>

              <div class="mt-6 flex items-center justify-between">
                <div>
                  <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Học phí</p>
                  <p class="mt-1 text-xl font-semibold text-slate-950 dark:text-white">
                    {{ course.price == 0 ? 'Miễn phí' : new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(course.price) }}
                  </p>
                </div>

                <RouterLink
                  :to="`/courses/${course.id}`"
                  class="shine-hover rounded-full bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:shadow-xl dark:bg-white dark:text-slate-950"
                >
                  Xem chi tiết
                </RouterLink>
              </div>
            </div>
          </article>
        </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
      <div class="mx-auto max-w-7xl space-y-6">
        <div class="max-w-2xl reveal">
          <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">4 kỹ năng trọng tâm</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
            Mỗi khối nội dung là một không gian học tập riêng, rõ mục tiêu và đẹp mắt.
          </h2>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
          <article
            v-for="skill in skills"
            :key="skill.title"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
            class="reveal tilt-card shine-hover relative overflow-hidden rounded-[34px] border border-slate-200 bg-white p-7 shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)]"
          >
            <div class="absolute right-0 top-0 h-36 w-36 rounded-full blur-3xl" :class="skill.glow"></div>

            <div class="relative">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <p class="text-xs uppercase tracking-[0.26em] text-slate-500">{{ skill.kicker }}</p>
                  <h3 class="mt-3 text-2xl font-semibold text-slate-950 dark:text-white">{{ skill.title }}</h3>
                </div>
                <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-950 text-xl text-white transition duration-300 hover:scale-110 dark:bg-slate-950 dark:text-white">
                  {{ skill.icon }}
                </div>
              </div>

              <p class="mt-4 max-w-xl text-sm leading-7 text-slate-600 dark:text-slate-400">{{ skill.description }}</p>

              <div class="mt-6 flex flex-wrap gap-3 text-sm">
                <span
                  v-for="chip in skill.chips"
                  :key="chip"
                  class="rounded-full border border-slate-300 px-3 py-1.5 text-slate-600 transition duration-300 hover:border-slate-900 hover:bg-slate-900 hover:text-white dark:border-slate-700 dark:text-slate-400 dark:hover:border-white dark:hover:bg-white dark:hover:text-slate-950"
                >
                  {{ chip }}
                </span>
              </div>

              <RouterLink
                :to="skill.to"
                class="mt-7 inline-block rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-900 transition duration-300 hover:-translate-y-1 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700"
              >
                Khám phá chuyên mục
              </RouterLink>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="px-4 py-20 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-7xl">
        <div class="relative overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#f0f9ff_0%,#e0f2fe_50%,#ecfdf5_100%)] px-8 py-12 dark:bg-[linear-gradient(135deg,#0c1a2e_0%,#0f172a_50%,#0c1f18_100%)] sm:px-10 lg:px-12">
          <!-- Orbs -->
          <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-sky-300/40 blur-3xl dark:bg-sky-500/10"></div>
          <div class="absolute -bottom-16 -right-16 h-64 w-64 rounded-full bg-emerald-300/40 blur-3xl dark:bg-emerald-500/10"></div>
          <div class="absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-200/25 blur-3xl dark:bg-violet-500/5"></div>

          <div class="relative">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
              <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur dark:border-sky-800 dark:bg-sky-950/60 dark:text-sky-400">
                  ✦ Quiz và đề thi
                </span>
                <h2 class="mt-5 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
                  Luyện tập mỗi ngày với các bài thi thử đẹp, nhanh và có phản hồi tức thì.
                </h2>
              </div>

              <RouterLink
                to="/tests"
                class="shine-hover self-start rounded-full border border-slate-300 bg-white/80 px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm backdrop-blur transition duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-lg dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-200 dark:hover:bg-slate-700"
              >
                Xem toàn bộ đề thi →
              </RouterLink>
            </div>

            <div class="mt-10 grid gap-5 lg:grid-cols-3">
              <article
                v-for="test in tests"
                :key="test.id"
                @mousemove="handleTilt"
                @mouseleave="resetTilt"
                class="reveal tilt-card shine-hover rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_26px_70px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60"
              >
                <h3 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ test.title }}</h3>

                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                  <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-700/50">
                    <p class="text-slate-400 dark:text-slate-500">Thời gian</p>
                    <p class="mt-1 font-medium text-slate-900 dark:text-white">{{ test.time_limit ? `${test.time_limit} phút` : '—' }}</p>
                  </div>
                  <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-700/50">
                    <p class="text-slate-400 dark:text-slate-500">Câu hỏi</p>
                    <p class="mt-1 font-medium text-slate-900 dark:text-white">{{ test.questions_count ?? '—' }} câu</p>
                  </div>
                </div>

                <RouterLink
                  :to="`/tests/${test.id}`"
                  class="shine-hover mt-6 inline-block rounded-full bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition duration-300 hover:shadow-xl dark:bg-white dark:text-slate-950"
                >
                  Làm thử ngay
                </RouterLink>
              </article>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
      <div class="mx-auto max-w-7xl">
        <div class="max-w-2xl reveal">
          <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Cách bắt đầu</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
            Quy trình học đơn giản, rõ ràng và tạo động lực ngay từ lần đầu truy cập.
          </h2>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-4">
          <article
            v-for="(step, index) in steps"
            :key="step.title"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
            class="reveal tilt-card shine-hover rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_12px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_18px_50px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_12px_40px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_18px_50px_rgba(0,0,0,0.3)]"
          >
            <div class="flex items-center justify-between">
              <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-sm font-semibold text-white transition duration-300 hover:scale-110 dark:bg-white dark:text-slate-950">
                0{{ index + 1 }}
              </div>
              <span class="text-xs uppercase tracking-[0.24em] text-slate-500">Step</span>
            </div>

            <h3 class="mt-6 text-xl font-semibold text-slate-950 dark:text-white">{{ step.title }}</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">{{ step.description }}</p>

            <RouterLink
              :to="step.to"
              class="mt-5 inline-block text-sm font-semibold text-slate-900 dark:text-white"
            >
              Đi tới bước này →
            </RouterLink>
          </article>
        </div>
      </div>
    </section>

    <section class="bg-slate-50 px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
      <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="max-w-2xl reveal">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Phản hồi học viên</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
              Trải nghiệm đủ đẹp để thích học, đủ rõ để tiến bộ thật.
            </h2>
          </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
          <article
            v-for="testimonial in testimonials"
            :key="testimonial.name"
            @mousemove="handleTilt"
            @mouseleave="resetTilt"
            class="reveal tilt-card shine-hover rounded-[30px] border border-slate-200 bg-white p-6 shadow-[0_14px_45px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_20px_60px_rgba(15,23,42,0.10)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_14px_45px_rgba(0,0,0,0.2)] dark:hover:shadow-[0_20px_60px_rgba(0,0,0,0.3)]"
          >
            <div class="flex items-center gap-4">
              <img :src="testimonial.avatar" :alt="testimonial.name" class="h-14 w-14 rounded-full object-cover ring-2 ring-white shadow-md transition duration-300 hover:scale-110 dark:ring-slate-700" />
              <div>
                <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ testimonial.name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ testimonial.role }}</p>
              </div>
            </div>

            <p class="mt-5 text-sm leading-8 text-slate-600 dark:text-slate-400">“{{ testimonial.quote }}”</p>
          </article>
        </div>
      </div>
    </section>

    <section class="bg-white px-4 pb-24 pt-10 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
      <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#e2e8f0_0%,#cbd5e1_35%,#0ea5e9_100%)] px-6 py-12 text-slate-950 reveal dark:bg-[linear-gradient(135deg,#0f172a_0%,#111827_35%,#0369a1_100%)] dark:text-white">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-800/60 dark:text-white/45">Sẵn sàng bắt đầu</p>
            <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
              Tạo tài khoản miễn phí và bắt đầu hành trình học tiếng Anh đẹp, rõ và hiệu quả hơn mỗi ngày.
            </h2>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-900/75 dark:text-white/75">
              Bạn có thể mở rộng phần này thành landing page chính thức, rồi nối thẳng vào backend Laravel để lấy courses, quizzes, reviews và notifications thật.
            </p>
          </div>

          <div class="shine-hover rounded-[32px] border border-white/30 bg-white/40 p-6 backdrop-blur transition duration-300 hover:bg-white/50 dark:border-white/10 dark:bg-white/8 dark:hover:bg-white/10">
            <div class="space-y-4">
              <input
                type="text"
                placeholder="Họ và tên"
                class="w-full rounded-2xl border border-slate-300/80 bg-white/75 px-4 py-3 text-slate-900 placeholder:text-slate-500 outline-none transition duration-300 focus:border-slate-500 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:text-white dark:placeholder:text-white/40 dark:focus:border-white/30 dark:focus:bg-white/15"
              />
              <input
                type="email"
                placeholder="Email"
                class="w-full rounded-2xl border border-slate-300/80 bg-white/75 px-4 py-3 text-slate-900 placeholder:text-slate-500 outline-none transition duration-300 focus:border-slate-500 focus:bg-white dark:border-white/10 dark:bg-white/10 dark:text-white dark:placeholder:text-white/40 dark:focus:border-white/30 dark:focus:bg-white/15"
              />

              <RouterLink
                to="/register"
                class="shine-hover block w-full rounded-full bg-slate-950 px-5 py-3.5 text-center text-sm font-semibold text-white transition duration-300 hover:-translate-y-1 hover:shadow-2xl dark:bg-white dark:text-slate-950"
              >
                Tạo tài khoản miễn phí
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { RouterLink } from 'vue-router'
import heroBg from '@/assets/images/home/trangchu.jpg'
import avatarMiMi from '@/assets/images/avatars/MiMi.jpg'
import avatarNgVanTung from '@/assets/images/avatars/NgVanTung.jpg'
import avatarLeeThanhHang from '@/assets/images/avatars/LeeThanhHang.jpg'
import courseService from '@/services/course.service'
import quizService from '@/services/quiz.service'
import statsService from '@/services/stats.service'

// Hero title typewriter
const GRADIENT_TEXT = 'lộ trình đẹp mắt,'
const LINE2_TEXT = 'thi thử thông minh và tiến bộ mỗi ngày.'

const gradientDisplayed = ref('')
const line2Displayed = ref('')
const phase = ref('gradient')
let twTimer = null
let observer = null

function runTypewriter() {
  const TYPE = 60
  const DELETE = 30
  const PAUSE = 30000

  function typeGradient() {
    phase.value = 'gradient'
    if (gradientDisplayed.value.length < GRADIENT_TEXT.length) {
      gradientDisplayed.value = GRADIENT_TEXT.slice(0, gradientDisplayed.value.length + 1)
      twTimer = setTimeout(typeGradient, TYPE)
    } else {
      twTimer = setTimeout(typeLine2, 300)
    }
  }

  function typeLine2() {
    phase.value = 'line2'
    if (line2Displayed.value.length < LINE2_TEXT.length) {
      line2Displayed.value = LINE2_TEXT.slice(0, line2Displayed.value.length + 1)
      twTimer = setTimeout(typeLine2, TYPE)
    } else {
      twTimer = setTimeout(deleteLine2, PAUSE)
    }
  }

  function deleteLine2() {
    phase.value = 'line2'
    if (line2Displayed.value.length > 0) {
      line2Displayed.value = line2Displayed.value.slice(0, -1)
      twTimer = setTimeout(deleteLine2, DELETE)
    } else {
      twTimer = setTimeout(deleteGradient, 200)
    }
  }

  function deleteGradient() {
    phase.value = 'gradient'
    if (gradientDisplayed.value.length > 0) {
      gradientDisplayed.value = gradientDisplayed.value.slice(0, -1)
      twTimer = setTimeout(deleteGradient, DELETE)
    } else {
      twTimer = setTimeout(typeGradient, 400)
    }
  }

  typeGradient()
}

const heroStats = computed(() => [
  { value: totalUsers.value ? `${totalUsers.value.toLocaleString('vi-VN')}+` : '—', label: 'Học viên' },
  { value: totalCourses.value ? `${totalCourses.value}+` : '—', label: 'Khóa học' },
  { value: totalTests.value !== null ? `${totalTests.value}` : '—', label: 'Đề luyện' },
  { value: completionRate.value !== null ? `${completionRate.value}%` : '—', label: 'Hoàn thành' },
])

const courses = ref([])
const tests = ref([])
const totalTests = ref(null)
const totalCourses = ref(0)
const totalUsers = ref(0)
const totalEnrollments = ref(0)
const totalAttempts = ref(0)
const lessonsCompleted = ref(0)
const completionRate = ref(null)
const avgScore = ref(null)
const avgRating = ref(null)

const features = [
  {
    icon: '🧭',
    title: 'Học theo lộ trình',
    description: 'Mỗi khóa học được chia theo cấp độ, section và lesson rõ ràng để người học không bị rối khi bắt đầu.',
    to: '/courses',
  },
  {
    icon: '📝',
    title: 'Quiz và thi thử',
    description: 'Làm quiz nhanh, đề thi thử và xem kết quả tức thì với giao diện tập trung và dễ thao tác.',
    to: '/tests',
  },
  {
    icon: '📈',
    title: 'Theo dõi tiến độ',
    description: 'Xem mức hoàn thành, lịch sử học, điểm số và kết quả từng kỹ năng trong cùng một trải nghiệm.',
    to: '/student/dashboard',
  },
  {
    icon: '🎧',
    title: 'Listening và Speaking',
    description: 'Luyện nghe, luyện nói và thực hành theo bài với cảm giác như đang dùng một sản phẩm học tập cao cấp.',
    to: '/student/listening',
  },
]

const skills = [
  {
    kicker: 'Word power',
    title: 'Vocabulary',
    icon: '📚',
    glow: 'bg-sky-200/70 dark:bg-sky-400/15',
    description: 'Học theo chủ đề, flashcard, quiz từ vựng và ghi nhớ bằng các cụm dùng thật trong giao tiếp và thi cử.',
    chips: ['Flashcards', 'Theo chủ đề', 'Mini quiz'],
    to: '/student/vocabulary',
  },
  {
    kicker: 'Clean rules',
    title: 'Grammar',
    icon: '✍️',
    glow: 'bg-amber-200/70 dark:bg-amber-400/15',
    description: 'Ngữ pháp được chia nhỏ, dễ hiểu và luôn gắn với bài tập thực hành để tránh học thuộc máy móc.',
    chips: ['Bài giảng ngắn', 'Ví dụ rõ', 'Thực hành ngay'],
    to: '/student/grammar',
  },
  {
    kicker: 'Real input',
    title: 'Listening',
    icon: '🎧',
    glow: 'bg-emerald-200/70 dark:bg-emerald-400/15',
    description: 'Luyện nghe theo cấp độ với transcript, từ khóa, bài tập điền từ và câu hỏi bám sát từng audio.',
    chips: ['Audio + transcript', 'Keyword note', 'Repeat mode'],
    to: '/student/listening',
  },
  {
    kicker: 'Speak better',
    title: 'Speaking',
    icon: '🎙️',
    glow: 'bg-violet-200/70 dark:bg-violet-400/15',
    description: 'Thực hành nói theo chủ đề, lưu lần nói, so sánh tiến bộ và xây phản xạ diễn đạt tự nhiên hơn mỗi ngày.',
    chips: ['Topic prompts', 'Practice flow', 'Track progress'],
    to: '/student/speaking',
  },
]

const steps = [
  {
    title: 'Tạo tài khoản',
    description: 'Đăng ký nhanh để mở dashboard cá nhân và lưu toàn bộ lịch sử học tập của bạn.',
    to: '/register',
  },
  {
    title: 'Chọn khóa học phù hợp',
    description: 'Bắt đầu từ nền tảng cơ bản hoặc đi thẳng vào lộ trình luyện thi theo mục tiêu.',
    to: '/courses',
  },
  {
    title: 'Học và luyện tập mỗi ngày',
    description: 'Kết hợp lesson, vocabulary, grammar, listening và speaking trong cùng một giao diện.',
    to: '/practice',
  },
  {
    title: 'Làm quiz và theo dõi tiến độ',
    description: 'Hoàn thành bài kiểm tra, xem điểm số và nhận phản hồi để cải thiện từng bước.',
    to: '/student/dashboard',
  },
]

const testimonials = [
  {
    name: 'MiMi',
    role: 'Sinh viên năm 2',
    quote: 'Mình thích nhất là giao diện rất đẹp nhưng không rối. Học lesson xong chuyển qua quiz cực mượt và dễ tập trung.',
    avatar: avatarMiMi,
  },
  {
    name: 'Nguyễn Văn Tung',
    role: 'Người đi làm',
    quote: 'Cảm giác giống đang dùng một nền tảng học tập xịn chứ không phải web học bình thường. Nhìn vào là muốn học ngay.',
    avatar: avatarNgVanTung,
  },
  {
    name: 'Lê Thanh Hằng',
    role: 'Luyện IELTS',
    quote: 'Phần mock test và dashboard tiến độ rất rõ. Mình dễ thấy mình đang yếu ở đâu để tập trung cải thiện.',
    avatar: avatarLeeThanhHang,
  },
]

onMounted(async () => {
  try {
    const [coursesRes, testsRes, statsRes] = await Promise.all([
      courseService.list({ per_page: 3, sort_by: 'id', sort_order: 'desc' }),
      quizService.list({ per_page: 3, sort_by: 'id', sort_order: 'desc' }),
      statsService.public(),
    ])

    courses.value = coursesRes.data.data ?? []
    tests.value = testsRes.data.data ?? []
    totalUsers.value = statsRes.data.users ?? 0
    totalCourses.value = statsRes.data.courses ?? 0
    totalTests.value = statsRes.data.practice_total ?? statsRes.data.quizzes ?? null
    totalEnrollments.value = statsRes.data.enrollments ?? 0
    totalAttempts.value = statsRes.data.attempts ?? 0
    lessonsCompleted.value = statsRes.data.lessons_completed ?? 0
    completionRate.value = statsRes.data.completion_rate ?? null
    avgScore.value = statsRes.data.avg_score ?? null
    avgRating.value = statsRes.data.avg_rating ?? null
  } catch {
    courses.value = []
    tests.value = []
  }

  await nextTick()
  initRevealAnimations()
  runTypewriter()
})

onBeforeUnmount(() => {
  if (observer) observer.disconnect()
  if (twTimer) clearTimeout(twTimer)
})

function initRevealAnimations() {
  const els = document.querySelectorAll('.reveal')

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible')
        }
      })
    },
    { threshold: 0.12 }
  )

  els.forEach((el, index) => {
    el.style.transitionDelay = `${Math.min(index % 4, 3) * 90}ms`
    observer.observe(el)
  })
}

function handleTilt(event) {
  if (window.innerWidth < 1024) return

  const card = event.currentTarget
  const rect = card.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top

  const rotateY = ((x / rect.width) - 0.5) * 10
  const rotateX = ((y / rect.height) - 0.5) * -10

  card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px)`
  card.style.setProperty('--mx', `${x}px`)
  card.style.setProperty('--my', `${y}px`)
}

function resetTilt(event) {
  const card = event.currentTarget
  card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)'
}
</script>

<style scoped>
.tw-cursor {
  display: inline-block;
  font-weight: 300;
  color: #0ea5e9;
  animation: blink-cursor 0.7s step-end infinite;
  margin-left: 1px;
}

@keyframes blink-cursor {
  0%, 100% { opacity: 1; }
  50% { opacity: 0; }
}

.hero-section {
  isolation: isolate;
}

.hero-grid {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
  background-size: 28px 28px;
  mask-image: linear-gradient(to bottom, rgba(0, 0, 0, .85), transparent);
}

:global(.dark) .hero-grid {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
}

.gradient-text {
  background: linear-gradient(90deg, #0ea5e9, #2563eb, #14b8a6, #0ea5e9);
  background-size: 220% auto;
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  animation: gradientShift 6s linear infinite;
}

.glass-panel {
  position: relative;
}

.glass-panel::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  padding: 1px;
  background: linear-gradient(135deg, rgba(255,255,255,.82), rgba(255,255,255,.16));
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
}

.neon-border {
  position: relative;
}

.neon-border::after {
  content: '';
  position: absolute;
  inset: -1px;
  border-radius: 30px;
  background: linear-gradient(135deg, rgba(56,189,248,.18), rgba(16,185,129,.14), transparent 65%);
  filter: blur(20px);
  z-index: -1;
  pointer-events: none;
}

.progress-glow {
  box-shadow: 0 0 18px rgba(56, 189, 248, 0.35);
}

.course-image {
  will-change: transform;
}

article:hover .course-image {
  transform: scale(1.08);
}

.reveal {
  opacity: 0;
  transform: translateY(32px) scale(0.98);
  transition:
    opacity 0.8s ease,
    transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.reveal.is-visible {
  opacity: 1;
  transform: translateY(0) scale(1);
}

.tilt-card {
  position: relative;
  transform-style: preserve-3d;
  will-change: transform;
}

.tilt-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  pointer-events: none;
  background:
    radial-gradient(
      220px circle at var(--mx, 50%) var(--my, 50%),
      rgba(255,255,255,0.18),
      transparent 45%
    );
  opacity: 0;
  transition: opacity .25s ease;
}

.tilt-card:hover::before {
  opacity: 1;
}

.shine-hover {
  position: relative;
  overflow: hidden;
}

.shine-hover::after {
  content: '';
  position: absolute;
  top: -120%;
  left: -140%;
  width: 60%;
  height: 300%;
  transform: rotate(24deg);
  background: linear-gradient(
    to right,
    transparent 0%,
    rgba(255,255,255,0.08) 40%,
    rgba(255,255,255,0.34) 50%,
    transparent 60%
  );
  transition: left .85s ease;
  pointer-events: none;
}

.shine-hover:hover::after {
  left: 180%;
}

.stat-card {
  transition:
    transform .35s ease,
    box-shadow .35s ease,
    background-color .35s ease;
}

.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
}

.magnetic-btn {
  position: relative;
  isolation: isolate;
}

.magnetic-btn:hover {
  box-shadow: 0 18px 40px rgba(15, 23, 42, 0.15);
}

.floating-orb {
  animation: floatY 7s ease-in-out infinite;
}

.floating-orb-delayed {
  animation: floatY 9s ease-in-out infinite;
}

.floating-orb-slow {
  animation: floatY 11s ease-in-out infinite;
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 220% 50%;
  }
}

@keyframes floatY {
  0%, 100% {
    transform: translate3d(0, 0, 0);
  }
  50% {
    transform: translate3d(0, -18px, 0);
  }
}

@media (prefers-reduced-motion: reduce) {
  .reveal,
  .gradient-text,
  .floating-orb,
  .floating-orb-delayed,
  .floating-orb-slow,
  .shine-hover::after,
  .tilt-card,
  .course-image {
    animation: none !important;
    transition: none !important;
    transform: none !important;
  }
}

@media (max-width: 1023px) {
  .tilt-card:hover::before {
    opacity: 0;
  }
}
</style>