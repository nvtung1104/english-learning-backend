<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <main class="px-4 pb-12 pt-24 sm:px-6 lg:px-8">
      <div class="mx-auto max-w-6xl">
        <!-- Loading -->
        <div v-if="loading" class="flex min-h-[55vh] items-center justify-center">
          <div class="flex flex-col items-center gap-4">
            <div class="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-slate-900 dark:border-slate-700 dark:border-t-white"></div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Đang tải đề thi...</p>
          </div>
        </div>

        <!-- Error -->
        <div
          v-else-if="error"
          class="mx-auto max-w-2xl rounded-[28px] border border-red-200 bg-red-50 p-8 text-center shadow-sm dark:border-red-800 dark:bg-red-900/20"
        >
          <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-red-100 text-2xl dark:bg-red-900/40">
            ⚠️
          </div>
          <h2 class="mt-5 text-2xl font-semibold text-red-700 dark:text-red-300">Đã có lỗi xảy ra</h2>
          <p class="mt-2 text-red-600 dark:text-red-400">{{ error }}</p>
          <RouterLink
            to="/tests"
            class="mt-6 inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
          >
            <span>←</span>
            <span>Quay lại</span>
          </RouterLink>
        </div>

        <!-- Result screen -->
        <div v-else-if="submitted" class="mx-auto max-w-4xl space-y-6">
          <RouterLink
            to="/tests"
            class="inline-flex items-center gap-2 text-sm text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
          >
            <span>←</span>
            <span>Quay lại đề thi</span>
          </RouterLink>

          <section
            class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] dark:border-slate-700 dark:bg-slate-900"
          >
            <div class="absolute -left-12 top-0 h-40 w-40 rounded-full bg-sky-200/50 blur-3xl dark:bg-sky-500/10"></div>
            <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-emerald-200/50 blur-3xl dark:bg-emerald-500/10"></div>

            <div class="relative text-center">
              <div
                class="mx-auto grid h-24 w-24 place-items-center rounded-full text-5xl shadow-inner"
                :class="passed ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30'"
              >
                {{ passed ? '🎉' : '😔' }}
              </div>

              <h2 class="mt-6 text-3xl font-semibold tracking-tight">
                {{ passed ? 'Chúc mừng, bạn đã vượt qua!' : 'Bạn chưa đạt bài thi này' }}
              </h2>

              <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-500 dark:text-slate-400">
                {{
                  passed
                    ? 'Kết quả của bạn rất tốt. Hãy tiếp tục giữ phong độ và thử các đề khó hơn để nâng cao kỹ năng.'
                    : 'Đừng lo, bạn vẫn có thể luyện tập thêm và quay lại làm lại bài thi này bất cứ lúc nào.'
                }}
              </p>

              <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-[22px] bg-slate-50 p-5 dark:bg-slate-800/70">
                  <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ result.score }}%</p>
                  <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Điểm số</p>
                </div>
                <div class="rounded-[22px] bg-slate-50 p-5 dark:bg-slate-800/70">
                  <p class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ result.correct_count }}/{{ result.total_questions }}
                  </p>
                  <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Câu đúng</p>
                </div>
                <div class="rounded-[22px] bg-slate-50 p-5 dark:bg-slate-800/70">
                  <p class="text-3xl font-bold" :class="passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400'">
                    {{ passed ? 'Đạt' : 'Chưa đạt' }}
                  </p>
                  <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Kết quả</p>
                </div>
              </div>

              <div class="mt-8 flex flex-wrap justify-center gap-3">
                <button
                  @click="restart"
                  class="rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100 hover:shadow-md dark:border-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700"
                >
                  Làm lại
                </button>
                <RouterLink
                  to="/tests"
                  class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:shadow-lg dark:bg-white dark:text-slate-900"
                >
                  Xem đề thi khác
                </RouterLink>
              </div>
            </div>
          </section>
        </div>

        <!-- Quiz screen -->
        <template v-else-if="quiz">
          <!-- Top hero -->
          <section
            class="relative overflow-hidden rounded-[32px] border border-slate-200 bg-white p-6 shadow-[0_18px_50px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-900 sm:p-8"
          >
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.10),transparent_28%),radial-gradient(circle_at_80%_20%,rgba(16,185,129,0.08),transparent_22%)] dark:bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,0.12),transparent_28%),radial-gradient(circle_at_80%_20%,rgba(16,185,129,0.10),transparent_22%)]"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
              <div class="max-w-3xl">
                <RouterLink
                  to="/tests"
                  class="inline-flex items-center gap-2 text-sm text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
                >
                  <span>←</span>
                  <span>Quay lại đề thi</span>
                </RouterLink>

                <div class="mt-4 flex flex-wrap gap-2">
                  <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    Quiz Test
                  </span>
                  <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    {{ flatQuestions.length }} câu
                  </span>
                  <span
                    v-if="quiz.time_limit"
                    class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300"
                  >
                    {{ quiz.time_limit }} phút
                  </span>
                </div>

                <h1 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-3xl">
                  {{ quiz.title }}
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500 dark:text-slate-400">
                  Hãy hoàn thành bài thi theo thứ tự bạn muốn. Bạn có thể chuyển câu linh hoạt và nộp bài khi đã sẵn sàng.
                </p>
              </div>

              <div class="grid gap-3 sm:grid-cols-2 lg:w-[280px]">
                <div class="rounded-[22px] bg-slate-50 p-4 dark:bg-slate-800/70">
                  <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Đã trả lời</p>
                  <p class="mt-2 text-3xl font-semibold text-slate-950 dark:text-white">{{ answeredCount }}</p>
                </div>

                <div
                  v-if="quiz.time_limit && timeLeft !== null"
                  class="rounded-[22px] p-4"
                  :class="timeLeft < 60 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-slate-50 dark:bg-slate-800/70'"
                >
                  <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Thời gian</p>
                  <p
                    class="mt-2 text-3xl font-semibold"
                    :class="timeLeft < 60 ? 'text-red-500 dark:text-red-400' : 'text-slate-950 dark:text-white'"
                  >
                    {{ formatTime(timeLeft) }}
                  </p>
                </div>
              </div>
            </div>

            <!-- progress -->
            <div class="relative mt-8">
              <div class="mb-2 flex items-center justify-between text-sm">
                <span class="text-slate-600 dark:text-slate-300">
                  Câu {{ currentIndex + 1 }}/{{ flatQuestions.length }}
                </span>
                <span class="text-slate-500 dark:text-slate-400">
                  {{ Math.round(progress) }}% hoàn thành lộ trình làm bài
                </span>
              </div>

              <div class="h-3 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                <div
                  class="h-full rounded-full bg-gradient-to-r from-sky-500 via-cyan-500 to-emerald-500 transition-all duration-500"
                  :style="{ width: progress + '%' }"
                ></div>
              </div>
            </div>
          </section>

          <section class="mt-6 grid gap-6 xl:grid-cols-[1fr_320px]">
            <!-- Main question -->
            <div class="space-y-6">
              <div
                class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_16px_45px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-900 sm:p-8"
              >
                <template v-if="currentQ">
                  <!-- Group content -->
                  <div v-if="currentQ._parent" class="mb-6 space-y-4">
                    <p
                      v-if="currentQ._parent.meta?.instruction"
                      class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300"
                    >
                      {{ currentQ._parent.meta.instruction }}
                    </p>

                    <div
                      v-if="currentQ._parent.passage"
                      class="rounded-[20px] border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-700 dark:border-slate-700 dark:bg-slate-800/70 dark:text-slate-300 max-h-56 overflow-y-auto"
                    >
                      {{ currentQ._parent.passage }}
                    </div>

                    <img
                      v-if="currentQ._parent.image_url"
                      :src="$storage(currentQ._parent.image_url)"
                      class="rounded-[20px] max-h-64 w-full object-contain border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70"
                      alt=""
                    />

                    <audio
                      v-if="currentQ._parent.audio_url"
                      :src="$storage(currentQ._parent.audio_url)"
                      controls
                      class="w-full rounded-xl"
                    ></audio>
                  </div>

                  <!-- Question heading -->
                  <div class="mb-6 flex items-start gap-4">
                    <div
                      class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-slate-900 text-sm font-bold text-white dark:bg-white dark:text-slate-900"
                    >
                      {{ currentIndex + 1 }}
                    </div>
                    <div>
                      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                        Câu hỏi hiện tại
                      </p>
                      <p class="mt-2 text-lg font-medium leading-8 text-slate-900 dark:text-white">
                        {{ currentQ.question }}
                      </p>
                    </div>
                  </div>

                  <!-- multiple_choice -->
                  <div v-if="hasAnswerOptions(currentQ)" class="space-y-3">
                    <button
                      v-for="(ans, i) in currentQ.answers"
                      :key="ans.id ?? i"
                      @click="selectAnswer(currentQ._key, ans.id ?? i)"
                      class="group flex w-full items-center gap-3 rounded-[20px] border px-4 py-4 text-left text-sm transition duration-300"
                      :class="userAnswers[currentQ._key] === (ans.id ?? i)
                        ? 'border-slate-900 bg-slate-900 text-white shadow-lg dark:border-white dark:bg-white dark:text-slate-900'
                        : 'border-slate-200 bg-white hover:-translate-y-0.5 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:bg-slate-800'"
                    >
                      <span
                        class="grid h-9 w-9 shrink-0 place-items-center rounded-full text-xs font-bold transition"
                        :class="userAnswers[currentQ._key] === (ans.id ?? i)
                          ? 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white'
                          : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200'"
                      >
                        {{ labels[i] }}
                      </span>

                      <span class="leading-7">
                        {{ ans.answer_text }}
                      </span>
                    </button>
                  </div>

                  <!-- fill blank -->
                  <div v-else-if="currentQ._type === 'fill_blank' || currentQ._type === 'fill_blank_group'">
                    <input
                      v-model="userAnswers[currentQ._key]"
                      type="text"
                      placeholder="Nhập đáp án..."
                      class="w-full rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3.5 text-sm outline-none transition focus:border-slate-400 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-slate-500"
                    />
                  </div>

                  <!-- true false -->
                  <div v-else-if="currentQ._type === 'true_false' || currentQ._type === 'true_false_group'" class="grid gap-3 sm:grid-cols-2">
                    <button
                      v-for="opt in [{ label: 'Đúng', val: true }, { label: 'Sai', val: false }]"
                      :key="opt.label"
                      @click="selectAnswer(currentQ._key, opt.val)"
                      class="rounded-[20px] border py-4 text-sm font-semibold transition duration-300"
                      :class="userAnswers[currentQ._key] === opt.val
                        ? 'border-slate-900 bg-slate-900 text-white shadow-lg dark:border-white dark:bg-white dark:text-slate-900'
                        : 'border-slate-200 bg-white hover:-translate-y-0.5 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:bg-slate-800'"
                    >
                      {{ opt.label }}
                    </button>
                  </div>

                  <!-- ordering -->
                  <div v-else-if="currentQ._type === 'ordering'" class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Sắp xếp theo thứ tự đúng</p>
                    <div
                      v-for="(item, i) in getOrderingItems(currentQ)"
                      :key="i"
                      class="flex items-center gap-3 rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800/70"
                    >
                      <span class="grid h-7 w-7 place-items-center rounded-full bg-slate-200 text-xs font-bold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                        {{ i + 1 }}
                      </span>
                      {{ item.text }}
                    </div>
                  </div>

                  <!-- matching -->
                  <div v-else-if="currentQ._type === 'matching'" class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nối các cặp tương ứng</p>
                    <div
                      v-for="(pair, i) in getMatchingPairs(currentQ)"
                      :key="i"
                      class="grid grid-cols-[1fr_auto_1fr] items-center gap-3 rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-800/70"
                    >
                      <span>{{ pair.left }}</span>
                      <span class="text-slate-400">↔</span>
                      <span>{{ pair.right }}</span>
                    </div>
                  </div>

                  <!-- writing -->
                  <div v-else-if="currentQ._type === 'writing'">
                    <textarea
                      v-model="userAnswers[currentQ._key]"
                      rows="7"
                      placeholder="Viết câu trả lời của bạn..."
                      class="w-full rounded-[18px] border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-slate-500 resize-vertical"
                    ></textarea>

                    <p v-if="currentQ.meta?.min_words || currentQ.meta?.max_words" class="mt-2 text-xs text-slate-400">
                      <span v-if="currentQ.meta?.min_words">Tối thiểu {{ currentQ.meta.min_words }} từ</span>
                      <span v-if="currentQ.meta?.min_words && currentQ.meta?.max_words"> · </span>
                      <span v-if="currentQ.meta?.max_words">Tối đa {{ currentQ.meta.max_words }} từ</span>
                    </p>
                  </div>
                </template>
              </div>

              <!-- Navigation actions -->
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button
                  @click="prev"
                  :disabled="currentIndex === 0"
                  class="rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100 disabled:opacity-40 dark:border-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700"
                >
                  ← Trước
                </button>

                <div class="flex gap-3">
                  <button
                    v-if="currentIndex < flatQuestions.length - 1"
                    @click="next"
                    class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:shadow-lg dark:bg-white dark:text-slate-900"
                  >
                    Tiếp →
                  </button>

                  <button
                    v-else
                    @click="confirmSubmit"
                    :disabled="submitting"
                    class="rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-lg disabled:opacity-60"
                  >
                    {{ submitting ? 'Đang nộp...' : 'Nộp bài' }}
                  </button>
                </div>
              </div>

              <p v-if="submitError" class="text-center text-sm text-red-500">{{ submitError }}</p>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-6">
              <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_16px_45px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                  <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                    Điều hướng câu hỏi
                  </h3>
                  <span class="text-xs text-slate-400">{{ answeredCount }}/{{ flatQuestions.length }}</span>
                </div>

                <div class="mt-4 grid grid-cols-5 gap-2">
                  <button
                    v-for="(q, i) in flatQuestions"
                    :key="i"
                    @click="currentIndex = i"
                    class="h-10 w-10 rounded-full text-xs font-semibold transition duration-300"
                    :class="i === currentIndex
                      ? 'bg-slate-900 text-white shadow-md dark:bg-white dark:text-slate-900'
                      : userAnswers[q._key] !== undefined
                        ? 'bg-emerald-500 text-white hover:opacity-90'
                        : 'bg-slate-200 text-slate-600 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600'"
                  >
                    {{ i + 1 }}
                  </button>
                </div>

                <div class="mt-5 space-y-2 text-xs text-slate-500 dark:text-slate-400">
                  <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-slate-900 dark:bg-white"></span>
                    <span>Câu hiện tại</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    <span>Đã trả lời</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                    <span>Chưa trả lời</span>
                  </div>
                </div>
              </div>

              <div class="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_16px_45px_rgba(15,23,42,0.06)] dark:border-slate-700 dark:bg-slate-900">
                <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                  Trạng thái bài thi
                </h3>

                <div class="mt-4 space-y-4">
                  <div class="rounded-[18px] bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Tiến độ</p>
                    <p class="mt-2 text-2xl font-semibold">{{ Math.round(progress) }}%</p>
                  </div>

                  <div class="rounded-[18px] bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Hoàn thành</p>
                    <p class="mt-2 text-2xl font-semibold">{{ answeredCount }}/{{ flatQuestions.length }}</p>
                  </div>

                  <div v-if="quiz.time_limit && timeLeft !== null" class="rounded-[18px] bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Thời gian còn lại</p>
                    <p
                      class="mt-2 text-2xl font-semibold"
                      :class="timeLeft < 60 ? 'text-red-500 dark:text-red-400' : ''"
                    >
                      {{ formatTime(timeLeft) }}
                    </p>
                  </div>
                </div>
              </div>
            </aside>
          </section>
        </template>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import quizService from '@/services/quiz.service'
import api from '@/services/axios'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({ slug: String })
const route = useRoute()
const auth = useAuthStore()

const quizId = computed(() => props.slug ?? route.params.slug)

const quiz = ref(null)
const loading = ref(true)
const error = ref('')
const submitted = ref(false)
const submitting = ref(false)
const submitError = ref('')
const result = ref({ score: 0, correct_count: 0, total_questions: 0, passed: false })

const currentIndex = ref(0)
const userAnswers = ref({})

const labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']

const timeLeft = ref(null)
let timerInterval = null

const flatQuestions = computed(() => {
  if (!quiz.value?.questions) return []
  const flat = []

  for (const q of quiz.value.questions) {
    const type = q.question_type ?? 'multiple_choice'

    if (type.endsWith('_group') && Array.isArray(q.questions) && q.questions.length) {
      for (const sub of q.questions) {
        flat.push({
          ...sub,
          _key: `${q.id}-${sub.id ?? flat.length}`,
          _type: type,
          _parent: q,
        })
      }
    } else {
      flat.push({
        ...q,
        _key: String(q.id),
        _type: type,
        _parent: null,
      })
    }
  }

  return flat
})

const currentQ = computed(() => flatQuestions.value[currentIndex.value] ?? null)

const answeredCount = computed(() =>
  flatQuestions.value.filter((q) => userAnswers.value[q._key] !== undefined && userAnswers.value[q._key] !== '').length
)

const progress = computed(() =>
  flatQuestions.value.length ? ((currentIndex.value + 1) / flatQuestions.value.length) * 100 : 0
)

const passed = computed(() => result.value.passed)

function hasAnswerOptions(q) {
  return Array.isArray(q.answers) && q.answers.length > 0
}

function getOrderingItems(q) {
  return q.meta?.ordering_items ?? []
}

function getMatchingPairs(q) {
  return q.meta?.matching_pairs ?? []
}

function selectAnswer(key, val) {
  userAnswers.value = { ...userAnswers.value, [key]: val }
}

function next() {
  if (currentIndex.value < flatQuestions.value.length - 1) currentIndex.value++
}

function prev() {
  if (currentIndex.value > 0) currentIndex.value--
}

function formatTime(secs) {
  const m = Math.floor(secs / 60).toString().padStart(2, '0')
  const s = (secs % 60).toString().padStart(2, '0')
  return `${m}:${s}`
}

function startTimer() {
  if (!quiz.value?.time_limit) return
  clearInterval(timerInterval)
  timeLeft.value = quiz.value.time_limit * 60

  timerInterval = setInterval(() => {
    if (timeLeft.value <= 0) {
      clearInterval(timerInterval)
      doSubmit()
    } else {
      timeLeft.value--
    }
  }, 1000)
}

function confirmSubmit() {
  const unanswered = flatQuestions.value.length - answeredCount.value
  if (unanswered > 0) {
    if (!confirm(`Còn ${unanswered} câu chưa trả lời. Bạn có muốn nộp bài không?`)) return
  }
  doSubmit()
}

async function doSubmit() {
  clearInterval(timerInterval)
  submitting.value = true
  submitError.value = ''

  try {
    if (auth.isLoggedIn) {
      const answers = flatQuestions.value
        .filter((q) => hasAnswerOptions(q))
        .map((q) => ({
          question_id: q.id,
          answer_id: typeof userAnswers.value[q._key] === 'number'
            ? userAnswers.value[q._key]
            : null,
        }))

      const res = await api.post(`/quizzes/${quizId.value}/submit`, { answers })
      result.value = res.data
    } else {
      let correct = 0
      const total = flatQuestions.value.filter((q) => hasAnswerOptions(q)).length

      for (const q of flatQuestions.value) {
        if (!hasAnswerOptions(q)) continue
        const chosen = userAnswers.value[q._key]
        const correctAns = q.answers?.find((a) => a.is_correct)
        if (correctAns && chosen === (correctAns.id ?? null)) correct++
      }

      const score = total > 0 ? Math.round((correct / total) * 100) : 0
      result.value = {
        score,
        correct_count: correct,
        total_questions: total,
        passed: score >= (quiz.value.pass_score ?? 50),
      }
    }

    submitted.value = true
  } catch (e) {
    submitError.value = e.response?.data?.message ?? 'Có lỗi khi nộp bài.'
  } finally {
    submitting.value = false
  }
}

function restart() {
  userAnswers.value = {}
  currentIndex.value = 0
  submitted.value = false
  submitError.value = ''
  result.value = { score: 0, correct_count: 0, total_questions: 0, passed: false }
  startTimer()
}

onMounted(async () => {
  try {
    const res = await quizService.get(quizId.value)
    quiz.value = res.data?.data ?? res.data
    startTimer()
  } catch {
    error.value = 'Không tìm thấy đề thi.'
  } finally {
    loading.value = false
  }
})

onBeforeUnmount(() => {
  clearInterval(timerInterval)
})
</script>