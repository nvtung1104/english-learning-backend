<template>
  <div style="background:#faf8f4;min-height:100vh;padding:24px 24px 64px;">
    <div style="max-width:1280px;margin:0 auto;">

      <!-- Welcome hero -->
      <div style="position:relative;border-radius:16px;overflow:hidden;background:#1a2540;margin-bottom:28px;padding:36px 40px;">
        <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(201,168,76,0.07);pointer-events:none;"></div>
        <div style="position:relative;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px;">
          <div>
            <p style="font-size:13px;color:rgba(255,255,255,0.45);margin:0 0 6px;">Welcome back 👋</p>
            <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#fff;margin:0 0 8px;">{{ auth.user?.name || 'Student' }}</h1>
            <p style="font-size:14px;color:rgba(255,255,255,0.5);margin:0;">You've studied <span style="color:#c9a84c;font-weight:700;">{{ streak }} days</span> in a row. Keep it up!</p>
          </div>
          <div style="display:flex;gap:12px;">
            <div style="text-align:center;padding:16px 20px;border-radius:12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);">
              <div style="font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:#fff;">{{ streak }}</div>
              <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">🔥 Streak</div>
            </div>
            <div style="text-align:center;padding:16px 20px;border-radius:12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);">
              <div style="font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:#fff;">{{ totalXP }}</div>
              <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;">⚡ XP</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats row -->
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:28px;">
        <div v-for="s in stats" :key="s.label"
          style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:20px 24px;display:flex;align-items:center;gap:14px;"
        >
          <div style="font-size:24px;">{{ s.icon }}</div>
          <div>
            <div style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#1a2540;line-height:1;">{{ s.value }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:3px;">{{ s.label }}</div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
        <!-- Main column -->
        <div style="display:flex;flex-direction:column;gap:24px;">

          <!-- Continue learning -->
          <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
              <h2 style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a2540;margin:0;">Continue Learning</h2>
              <RouterLink to="/student/my-courses" style="font-size:12px;font-weight:600;color:#c9a84c;text-decoration:none;letter-spacing:0.04em;">View All →</RouterLink>
            </div>

            <div v-if="enrolledCourses.length" style="display:flex;flex-direction:column;gap:12px;">
              <RouterLink v-for="c in enrolledCourses" :key="c.id"
                :to="`/learn/${c.id}/lesson/${c.last_lesson_id || 1}`"
                style="display:flex;align-items:center;gap:16px;padding:14px;border:1px solid #ede9e2;border-radius:10px;text-decoration:none;transition:border-color 0.15s,box-shadow 0.15s;"
                @mouseenter="e=>{e.currentTarget.style.borderColor='#1a2540';e.currentTarget.style.boxShadow='0 4px 12px rgba(26,37,64,0.08)'}"
                @mouseleave="e=>{e.currentTarget.style.borderColor='#ede9e2';e.currentTarget.style.boxShadow='none'}"
              >
                <div style="width:52px;height:52px;border-radius:10px;overflow:hidden;background:#f0ece4;flex-shrink:0;">
                  <img v-if="c.thumbnail" :src="c.thumbnail" :alt="c.title" style="width:100%;height:100%;object-fit:cover;" />
                  <div v-else style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:22px;">📚</div>
                </div>
                <div style="flex:1;min-width:0;">
                  <p style="font-size:14px;font-weight:600;color:#1a2540;margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ c.title }}</p>
                  <p style="font-size:12px;color:#9ca3af;margin:0 0 8px;">{{ c.completed_lessons }}/{{ c.total_lessons }} lessons</p>
                  <div style="height:4px;background:#f0ece4;border-radius:4px;overflow:hidden;">
                    <div style="height:100%;background:#1a2540;border-radius:4px;transition:width 0.3s;" :style="`width:${c.progress}%`"></div>
                  </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                  <span style="font-size:14px;font-weight:700;color:#1a2540;">{{ c.progress }}%</span>
                  <p style="font-size:11px;color:#9ca3af;margin:3px 0 0;">done</p>
                </div>
              </RouterLink>
            </div>

            <div v-else style="text-align:center;padding:32px 0;">
              <div style="font-size:36px;margin-bottom:12px;">📚</div>
              <p style="font-size:15px;font-weight:600;color:#1a2540;margin:0 0 6px;">No courses yet</p>
              <p style="font-size:13px;color:#9ca3af;margin:0 0 16px;">Enroll in your first course to get started.</p>
              <RouterLink to="/courses"
                style="display:inline-block;padding:10px 24px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;text-decoration:none;border-radius:8px;"
              >Explore Courses</RouterLink>
            </div>
          </div>

          <!-- Recent quizzes -->
          <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
              <h2 style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a2540;margin:0;">Recent Quizzes</h2>
              <RouterLink to="/student/quiz-history" style="font-size:12px;font-weight:600;color:#c9a84c;text-decoration:none;letter-spacing:0.04em;">View All →</RouterLink>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div v-for="q in recentQuizzes" :key="q.id"
                style="display:flex;align-items:center;gap:14px;padding:12px;border:1px solid #ede9e2;border-radius:10px;"
              >
                <div :style="`width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;background:${q.score>=70?'#f0fdf4':'#fffbeb'};color:${q.score>=70?'#16a34a':'#d97706'};`">
                  {{ q.score }}%
                </div>
                <div style="flex:1;min-width:0;">
                  <p style="font-size:14px;font-weight:600;color:#1a2540;margin:0 0 3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ q.title }}</p>
                  <p style="font-size:12px;color:#9ca3af;margin:0;">{{ q.date }}</p>
                </div>
                <span :style="`font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:${q.score>=70?'#dcfce7':'#fef9c3'};color:${q.score>=70?'#16a34a':'#ca8a04'};`">
                  {{ q.score >= 70 ? 'Passed' : 'Failed' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right sidebar -->
        <div style="display:flex;flex-direction:column;gap:20px;">
          <!-- Weekly goal -->
          <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
            <h3 style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:#1a2540;margin:0 0 18px;">Weekly Goals</h3>
            <div style="display:flex;flex-direction:column;gap:14px;">
              <div v-for="g in weeklyGoals" :key="g.label">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px;">
                  <span style="color:#4b5563;">{{ g.label }}</span>
                  <span style="font-weight:700;color:#1a2540;">{{ g.current }}/{{ g.target }}</span>
                </div>
                <div style="height:6px;background:#f0ece4;border-radius:6px;overflow:hidden;">
                  <div :style="`height:100%;border-radius:6px;background:${g.color};transition:width 0.3s;width:${Math.min(100,(g.current/g.target)*100)}%`"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Upcoming -->
          <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
            <h3 style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:#1a2540;margin:0 0 18px;">Upcoming</h3>
            <div style="display:flex;flex-direction:column;gap:12px;">
              <div v-for="t in upcomingTasks" :key="t.title" style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#faf8f4;border:1px solid #ede9e2;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">{{ t.icon }}</div>
                <div>
                  <p style="font-size:13px;font-weight:600;color:#1a2540;margin:0 0 2px;">{{ t.title }}</p>
                  <p style="font-size:11px;color:#9ca3af;margin:0;">{{ t.time }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const auth    = useAuthStore()
const streak  = ref(7)
const totalXP = ref(1240)

const stats = [
  { icon: '📚', label: 'Active Courses',     value: '3'   },
  { icon: '✅', label: 'Lessons Completed',  value: '48'  },
  { icon: '🧩', label: 'Quizzes Done',       value: '12'  },
  { icon: '📝', label: 'Words Learned',      value: '320' },
]

const enrolledCourses = ref([
  { id: 1, title: 'IELTS Preparation Course', thumbnail: null, completed_lessons: 12, total_lessons: 30, progress: 40, last_lesson_id: 3 },
  { id: 2, title: 'Business English',         thumbnail: null, completed_lessons: 5,  total_lessons: 20, progress: 25, last_lesson_id: 6 },
])

const recentQuizzes = ref([
  { id: 1, title: 'Vocabulary Quiz - Unit 3',      score: 85, date: 'Today, 10:30' },
  { id: 2, title: 'Grammar Test - Present Tense',  score: 60, date: 'Yesterday, 15:00' },
])

const weeklyGoals = [
  { label: 'Lessons',    current: 5,  target: 7,  color: '#1a2540' },
  { label: 'Quizzes',    current: 2,  target: 3,  color: '#c9a84c' },
  { label: 'Vocabulary', current: 45, target: 50, color: '#6b8f71' },
]

const upcomingTasks = [
  { icon: '🧩', title: 'Grammar Quiz Unit 4',    time: 'Tomorrow, 09:00' },
  { icon: '📖', title: 'Lesson: Listening Skills', time: 'Wed, 14:00' },
]
</script>

<style scoped>
@media (max-width: 1023px) {
  div[style*="grid-template-columns:1fr 320px"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
