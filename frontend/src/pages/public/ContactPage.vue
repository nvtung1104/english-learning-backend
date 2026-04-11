<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <main>
      <section class="relative overflow-hidden px-4 pb-14 pt-28 sm:px-6 lg:px-8 lg:pb-16 lg:pt-32">
        <div class="absolute inset-0">
          <img :src="heroBg" alt="" class="h-full w-full object-cover object-center" />
          <div class="absolute inset-0 bg-black/40"></div>
        </div>

        <div class="relative mx-auto max-w-7xl">
          <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
              <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-700 shadow-sm backdrop-blur-md">
                Liên hệ
              </div>
              <h1 class="mt-4 text-4xl font-bold leading-snug tracking-tight text-white sm:text-5xl">
                <span class="block">Chúng tôi luôn sẵn sàng</span>
                <span class="block min-h-[1.4em]">{{ twDisplayed }}<span class="tw-cursor">|</span></span>
              </h1>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                Nếu bạn cần tư vấn khóa học, hỗ trợ kỹ thuật hoặc muốn hợp tác phát triển hệ thống, hãy gửi thông tin cho chúng tôi.
              </p>
              <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-white/60">
                <RouterLink to="/" class="transition hover:text-white">Trang chủ</RouterLink>
                <span>/</span>
                <span class="font-medium text-white/80">Liên hệ</span>
              </div>
            </div>

            <!-- Right: quick contact card -->
            <div class="hidden lg:block">
              <div class="overflow-hidden rounded-[32px] border border-white/80 bg-white/80 p-6 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">Liên hệ nhanh</p>
                <div class="mt-4 space-y-3">
                  <div v-for="item in quickContacts" :key="item.label" class="flex items-center gap-4 rounded-2xl bg-slate-50 dark:bg-slate-800 px-4 py-3">
                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-slate-950 text-lg">{{ item.icon }}</div>
                    <div>
                      <p class="text-xs text-slate-400">{{ item.label }}</p>
                      <p class="font-semibold text-slate-950 dark:text-white">{{ item.value }}</p>
                    </div>
                  </div>
                </div>
                <div class="mt-4 rounded-2xl bg-slate-950 px-4 py-3 text-center">
                  <p class="text-sm font-semibold text-white">Phản hồi trong vòng 24h</p>
                  <p class="mt-1 text-xs text-white/50">Thứ 2 – Thứ 6, 8:00 – 17:00</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.95fr_1.05fr]">
          <div class="space-y-6">
            <div class="reveal rounded-[32px] border border-slate-200 bg-white p-7 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)]">
              <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Thông tin liên hệ</p>

              <div class="mt-6 space-y-4">
                <div
                  v-for="item in contactItems"
                  :key="item.label"
                  class="flex gap-4 rounded-[24px] bg-slate-50 p-4 dark:bg-slate-800"
                >
                  <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-xl dark:bg-slate-700">
                    {{ item.icon }}
                  </div>
                  <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ item.label }}</p>
                    <p class="mt-1 font-semibold text-slate-950 dark:text-white">{{ item.value }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="reveal rounded-[32px] border border-slate-200 bg-white p-7 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)]">
              <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Hỗ trợ nhanh</p>
              <h2 class="mt-4 text-2xl font-semibold text-slate-950 dark:text-white">Bạn cần hỗ trợ về điều gì?</h2>

              <div class="mt-6 space-y-3">
                <div
                  v-for="topic in supportTopics"
                  :key="topic"
                  class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                >
                  {{ topic }}
                </div>
              </div>
            </div>
          </div>

          <div class="rounded-[32px] border border-slate-200 bg-white p-7 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)]">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-500">Gửi tin nhắn</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">
              Liên hệ với chúng tôi
            </h2>
            <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-400">
              Điền thông tin bên dưới, chúng tôi sẽ phản hồi sớm nhất có thể.
            </p>

            <form class="mt-8 space-y-5" @submit.prevent="handleSubmit">
              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Họ và tên</label>
                  <input
                    v-model="form.name"
                    type="text"
                    placeholder="Nguyễn Văn A"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  />
                </div>

                <div>
                  <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                  <input
                    v-model="form.email"
                    type="email"
                    placeholder="email@example.com"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  />
                </div>
              </div>

              <div class="grid gap-5 sm:grid-cols-2">
                <div>
                  <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Số điện thoại</label>
                  <input
                    v-model="form.phone"
                    type="text"
                    placeholder="+84..."
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  />
                </div>

                <div>
                  <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Chủ đề</label>
                  <select
                    v-model="form.subject"
                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                  >
                    <option value="">Chọn chủ đề</option>
                    <option value="Tư vấn khóa học">Tư vấn khóa học</option>
                    <option value="Hỗ trợ kỹ thuật">Hỗ trợ kỹ thuật</option>
                    <option value="Hợp tác">Hợp tác</option>
                    <option value="Khác">Khác</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nội dung</label>
                <textarea
                  v-model="form.message"
                  rows="6"
                  placeholder="Nhập nội dung bạn muốn gửi..."
                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:border-slate-500"
                ></textarea>
              </div>

              <button
                type="submit"
                class="shine-hover rounded-full bg-slate-950 px-7 py-3.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:shadow-xl dark:bg-white dark:text-slate-950"
              >
                Gửi liên hệ
              </button>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { reactive, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { RouterLink } from 'vue-router'
import { useTypewriter } from '@/composables/useTypewriter'
import heroBg from '@/assets/images/home/lienhe.jpg'

const { displayed: twDisplayed } = useTypewriter('hỗ trợ bạn trong quá trình học tập và sử dụng hệ thống.')

const quickContacts = [
  { icon: '📧', label: 'Email hỗ trợ', value: 'ngvantung1104@gmail.com' },
  { icon: '📞', label: 'Hotline', value: '0338944015' },
  { icon: '💬', label: 'Live chat', value: 'Trực tuyến ngay bây giờ' },
]

const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
})

const contactItems = [
  { icon: '📧', label: 'Email', value: 'ngvantung1104@gmail.com' },
  { icon: '📞', label: 'Điện thoại', value: '0338944015' },
  { icon: '📍', label: 'Địa chỉ', value: 'phú Thọ, Việt Nam' },
]

const supportTopics = [
  'Tư vấn lộ trình học tiếng Anh phù hợp',
  'Hướng dẫn sử dụng tài khoản và dashboard',
  'Hỗ trợ lỗi đăng nhập hoặc truy cập khóa học',
  'Giải đáp về quiz, kết quả và tiến độ học tập',
]

const handleSubmit = () => {
  console.log('Contact form:', form)
  alert('Đã gửi liên hệ thành công!')
}

let observer = null

onMounted(async () => {
  await nextTick()
  initRevealAnimations()
})

onBeforeUnmount(() => {
  if (observer) observer.disconnect()
})

function initRevealAnimations() {
  const els = document.querySelectorAll('.reveal')
  observer = new IntersectionObserver(
    (entries) => { entries.forEach((entry) => { if (entry.isIntersecting) entry.target.classList.add('is-visible') }) },
    { threshold: 0.12 }
  )
  els.forEach((el, index) => {
    el.style.transitionDelay = `${Math.min(index % 4, 3) * 90}ms`
    observer.observe(el)
  })
}
</script>

<style scoped>
.tw-cursor { display: inline-block; color: #0ea5e9; font-weight: 300; animation: blink 0.7s step-end infinite; }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.reveal {
  opacity: 0;
  transform: translateY(32px) scale(0.98);
  transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}
.reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }

.shine-hover { position: relative; overflow: hidden; }
.shine-hover::after {
  content: '';
  position: absolute;
  top: -120%;
  left: -140%;
  width: 60%;
  height: 300%;
  transform: rotate(24deg);
  background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.08) 40%, rgba(255,255,255,0.34) 50%, transparent 60%);
  transition: left .85s ease;
  pointer-events: none;
}
.shine-hover:hover::after { left: 180%; }

@media (prefers-reduced-motion: reduce) {
  .reveal, .shine-hover::after { animation: none !important; transition: none !important; transform: none !important; }
}
</style>