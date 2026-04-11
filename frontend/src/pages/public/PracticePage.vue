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
                Luyện tập
              </div>
              <h1 class="mt-4 text-4xl font-semibold leading-snug tracking-tight text-white sm:text-5xl">
                <span class="block">Luyện tập tiếng Anh theo từng kỹ năng</span>
                <span class="block min-h-[1.4em]">{{ twDisplayed }}<span class="tw-cursor">|</span></span>
              </h1>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/80 sm:text-lg">
                Chọn chuyên mục luyện tập phù hợp để rèn từ vựng, ngữ pháp, nghe, nói hoặc thử sức với các quiz và bài kiểm tra ngắn.
              </p>
              <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-white/60">
                <RouterLink to="/" class="transition hover:text-white">Trang chủ</RouterLink>
                <span>/</span>
                <span class="font-medium text-white/90">Luyện tập</span>
              </div>
            </div>

            <!-- Right: skill progress -->
            <div class="hidden lg:block">
              <div class="overflow-hidden rounded-[32px] border border-white/80 bg-white/80 p-6 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.26em] text-slate-400 dark:text-slate-500">Kỹ năng luyện tập</p>
                <div class="mt-4 space-y-4">
                  <div v-for="skill in skills" :key="skill.name">
                    <div class="flex items-center justify-between text-sm">
                      <span class="flex items-center gap-2 font-medium text-slate-700"><span>{{ skill.icon }}</span>{{ skill.name }}</span>
                      <span class="text-xs text-slate-500">{{ skill.count }} bài</span>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200">
                      <div class="h-full rounded-full transition-all duration-700" :class="skill.color" :style="{ width: skill.pct + '%' }"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white px-4 py-20 transition-colors duration-300 sm:px-6 lg:px-8 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl">
          <div class="max-w-2xl reveal">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Chuyên mục luyện tập</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
              Mỗi kỹ năng là một không gian học tập riêng, rõ mục tiêu và dễ theo dõi tiến bộ.
            </h2>
          </div>
          <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <article
              v-for="item in practiceItems"
              :key="item.title"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
              class="reveal tilt-card shine-hover relative overflow-hidden rounded-[34px] border border-slate-200 bg-white p-7 shadow-[0_18px_50px_rgba(15,23,42,0.06)] transition duration-300 dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-[0_18px_50px_rgba(0,0,0,0.2)]"
            >
              <div class="absolute right-0 top-0 h-36 w-36 rounded-full blur-3xl" :class="item.glow"></div>

              <div class="relative">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <p class="text-xs uppercase tracking-[0.26em] text-slate-500">{{ item.kicker }}</p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-950 dark:text-white">{{ item.title }}</h2>
                  </div>
                  <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-950 text-xl text-white dark:bg-white dark:text-slate-950">
                    {{ item.icon }}
                  </div>
                </div>

                <p class="mt-4 max-w-xl text-sm leading-7 text-slate-600 dark:text-slate-400">{{ item.description }}</p>

                <div class="mt-6 flex flex-wrap gap-3 text-sm">
                  <span
                    v-for="chip in item.chips"
                    :key="chip"
                    class="rounded-full border border-slate-300 px-3 py-1.5 text-slate-600 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white dark:border-slate-700 dark:text-slate-400 dark:hover:border-white dark:hover:bg-white dark:hover:text-slate-950"
                  >
                    {{ chip }}
                  </span>
                </div>

                <RouterLink
                  :to="item.to"
                  class="mt-7 inline-block rounded-full border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-900 transition hover:-translate-y-1 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-700"
                >
                  Bắt đầu luyện tập
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
                    ✦ Bài luyện nổi bật
                  </span>
                  <h2 class="mt-5 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl dark:text-white">
                    Những bài luyện tập giúp bạn bắt nhịp nhanh và thấy tiến bộ rõ ràng.
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
                  v-for="test in highlightTests"
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
        <div class="mx-auto max-w-7xl overflow-hidden rounded-[40px] bg-[linear-gradient(135deg,#0f172a_0%,#111827_35%,#0369a1_100%)] px-6 py-12 text-white sm:px-8 lg:px-12">
          <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/45">Bắt đầu ngay</p>
              <h2 class="mt-4 max-w-2xl text-3xl font-semibold tracking-tight sm:text-4xl">
                Duy trì thói quen luyện tập mỗi ngày để tiến bộ bền vững.
              </h2>
              <p class="mt-5 max-w-2xl text-base leading-8 text-white/75">
                Bạn có thể bắt đầu từ các quiz ngắn, luyện nghe theo chủ đề hoặc tham gia các bài thi thử chuyên sâu.
              </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
              <RouterLink
                to="/tests"
                class="shine-hover rounded-full bg-white px-5 py-3.5 text-center text-sm font-semibold text-slate-950 transition hover:shadow-2xl"
              >
                Làm bài thi
              </RouterLink>
              <RouterLink
                to="/courses"
                class="shine-hover rounded-full border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white transition hover:bg-white/15"
              >
                Xem khóa học
              </RouterLink>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { RouterLink } from 'vue-router'
import { useTypewriter } from '@/composables/useTypewriter'
import heroBg from '@/assets/images/home/luyentap.jpg'
import quizService from '@/services/quiz.service'
import vocabularyService from '@/services/vocabulary.service'
import grammarService from '@/services/grammar-topic.service'
import listeningService from '@/services/listening-lesson.service'

const { displayed: twDisplayed } = useTypewriter('và mục tiêu học tập của bạn.')

// Skills widget — counts from API
const skillCounts = ref({ vocab: 0, grammar: 0, listening: 0, quiz: 0 })

const skills = computed(() => {
  const max = Math.max(skillCounts.value.vocab, skillCounts.value.grammar, skillCounts.value.listening, skillCounts.value.quiz, 1)
  return [
    { icon: '📖', name: 'Từ vựng',  count: skillCounts.value.vocab,    pct: Math.round((skillCounts.value.vocab / max) * 100),    color: 'bg-sky-500' },
    { icon: '✏️', name: 'Ngữ pháp', count: skillCounts.value.grammar,  pct: Math.round((skillCounts.value.grammar / max) * 100),  color: 'bg-violet-500' },
    { icon: '🎧', name: 'Listening', count: skillCounts.value.listening, pct: Math.round((skillCounts.value.listening / max) * 100), color: 'bg-emerald-500' },
    { icon: '📝', name: 'Quiz',      count: skillCounts.value.quiz,     pct: Math.round((skillCounts.value.quiz / max) * 100),     color: 'bg-rose-500' },
  ]
})

const practiceItems = [
  {
    kicker: 'Word power',
    title: 'Vocabulary',
    icon: '📚',
    glow: 'bg-sky-200/70',
    description: 'Ôn từ vựng theo chủ đề, ghi nhớ bằng flashcards và củng cố bằng mini quiz ngắn.',
    chips: ['Flashcards', 'Theo chủ đề', 'Mini quiz'],
    to: '/student/vocabulary',
  },
  {
    kicker: 'Clean rules',
    title: 'Grammar',
    icon: '✍️',
    glow: 'bg-amber-200/70',
    description: 'Luyện ngữ pháp theo từng chuyên đề với ví dụ rõ ràng và bài tập áp dụng ngay.',
    chips: ['Chuyên đề', 'Bài tập', 'Checkpoint'],
    to: '/student/grammar',
  },
  {
    kicker: 'Real input',
    title: 'Listening',
    icon: '🎧',
    glow: 'bg-emerald-200/70',
    description: 'Rèn kỹ năng nghe qua audio, transcript, từ khóa và bài tập bám sát nội dung.',
    chips: ['Audio', 'Transcript', 'Keyword'],
    to: '/student/listening',
  },
  {
    kicker: 'Speak better',
    title: 'Speaking',
    icon: '🎙️',
    glow: 'bg-violet-200/70',
    description: 'Luyện phản xạ nói qua chủ đề, prompt và quá trình thực hành có theo dõi tiến bộ.',
    chips: ['Prompt', 'Practice', 'Progress'],
    to: '/student/speaking',
  },
]

const highlightTests = ref([])
let observer = null

onMounted(async () => {
  try {
    const [vocabRes, grammarRes, listeningRes, quizRes, testsRes] = await Promise.allSettled([
      vocabularyService.list({ per_page: 1 }),
      grammarService.list({ per_page: 1 }),
      listeningService.list({ per_page: 1 }),
      quizService.list({ per_page: 1 }),
      quizService.list({ per_page: 3, sort_by: 'id', sort_order: 'desc' }),
    ])

    const getTotal = (res) => {
      if (res.status !== 'fulfilled') return 0
      const d = res.value.data
      return d?.meta?.total ?? d?.total ?? (Array.isArray(d?.data) ? d.data.length : 0)
    }

    skillCounts.value = {
      vocab:     getTotal(vocabRes),
      grammar:   getTotal(grammarRes),
      listening: getTotal(listeningRes),
      quiz:      getTotal(quizRes),
    }

    highlightTests.value = testsRes.status === 'fulfilled'
      ? (testsRes.value.data.data ?? [])
      : []
  } catch {
    highlightTests.value = []
  }
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
.tw-cursor { display: inline-block; color: #0ea5e9; font-weight: 300; animation: blink 0.7s step-end infinite; }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.reveal {
  opacity: 0;
  transform: translateY(32px) scale(0.98);
  transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}
.reveal.is-visible { opacity: 1; transform: translateY(0) scale(1); }

.tilt-card { position: relative; transform-style: preserve-3d; will-change: transform; }
.tilt-card::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: inherit;
  pointer-events: none;
  background: radial-gradient(220px circle at var(--mx, 50%) var(--my, 50%), rgba(255,255,255,0.18), transparent 45%);
  opacity: 0;
  transition: opacity .25s ease;
}
.tilt-card:hover::before { opacity: 1; }

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
  .reveal, .shine-hover::after, .tilt-card { animation: none !important; transition: none !important; transform: none !important; }
}
@media (max-width: 1023px) { .tilt-card:hover::before { opacity: 0; } }
</style>