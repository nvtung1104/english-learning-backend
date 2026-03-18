<template>
  <div style="background:#faf8f4;min-height:100vh;padding:24px 24px 64px;">
    <div style="max-width:900px;margin:0 auto;">
      <div style="margin-bottom:28px;">
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#1a2540;margin:0 0 4px;">Quiz History</h1>
        <p style="font-size:13px;color:#9ca3af;margin:0;">Review your past quiz results</p>
      </div>

      <!-- Summary stats -->
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;">
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:20px;text-align:center;">
          <div style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:#1a2540;">{{ attempts.length }}</div>
          <div style="font-size:13px;color:#9ca3af;margin-top:4px;">Total Attempts</div>
        </div>
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:20px;text-align:center;">
          <div style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:#16a34a;">{{ passCount }}</div>
          <div style="font-size:13px;color:#9ca3af;margin-top:4px;">Passed</div>
        </div>
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:20px;text-align:center;">
          <div style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:#1a2540;">{{ avgScore }}%</div>
          <div style="font-size:13px;color:#9ca3af;margin-top:4px;">Avg Score</div>
        </div>
      </div>

      <!-- History list -->
      <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #f0ece4;">
          <h2 style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#1a2540;margin:0;">All Attempts</h2>
        </div>
        <div>
          <div v-for="a in attempts" :key="a.id"
            style="display:flex;align-items:center;gap:16px;padding:16px 20px;border-bottom:1px solid #f9f7f4;transition:background 0.15s;"
            @mouseenter="e=>e.currentTarget.style.background='#faf8f4'"
            @mouseleave="e=>e.currentTarget.style.background='transparent'"
          >
            <div :style="`width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;background:${a.score>=70?'#f0fdf4':'#fef2f2'};color:${a.score>=70?'#16a34a':'#dc2626'};`">
              {{ a.score }}%
            </div>
            <div style="flex:1;min-width:0;">
              <p style="font-size:14px;font-weight:600;color:#1a2540;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ a.quiz_title }}</p>
              <p style="font-size:12px;color:#9ca3af;margin:0;">{{ a.course }} · {{ a.date }}</p>
            </div>
            <div style="text-align:right;flex-shrink:0;">
              <span :style="`font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:${a.score>=70?'#dcfce7':'#fee2e2'};color:${a.score>=70?'#16a34a':'#dc2626'};`">
                {{ a.score >= 70 ? 'Passed' : 'Failed' }}
              </span>
              <p style="font-size:11px;color:#9ca3af;margin:4px 0 0;">{{ a.correct }}/{{ a.total }} correct</p>
            </div>
          </div>
        </div>
        <div v-if="!attempts.length" style="text-align:center;padding:48px 24px;">
          <div style="font-size:36px;margin-bottom:12px;">🧩</div>
          <p style="font-size:15px;font-weight:600;color:#1a2540;margin:0 0 6px;">No quiz history yet</p>
          <p style="font-size:13px;color:#9ca3af;margin:0;">Complete a quiz to see your results here.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const attempts = ref([
  { id: 1, quiz_title: 'Vocabulary Quiz - Unit 3',       course: 'IELTS Prep',  score: 85, correct: 17, total: 20, date: 'Today, 10:30' },
  { id: 2, quiz_title: 'Grammar Test - Present Tense',   course: 'Grammar A1',  score: 60, correct: 12, total: 20, date: 'Yesterday, 15:00' },
  { id: 3, quiz_title: 'Listening Comprehension Unit 1', course: 'IELTS Prep',  score: 90, correct: 9,  total: 10, date: '3 days ago' },
  { id: 4, quiz_title: 'Business Vocabulary',            course: 'Business EN', score: 45, correct: 9,  total: 20, date: '5 days ago' },
])

const passCount = computed(() => attempts.value.filter(a => a.score >= 70).length)
const avgScore  = computed(() => {
  if (!attempts.value.length) return 0
  return Math.round(attempts.value.reduce((s, a) => s + a.score, 0) / attempts.value.length)
})
</script>
