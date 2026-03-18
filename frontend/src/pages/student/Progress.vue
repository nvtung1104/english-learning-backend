<template>
  <div style="background:#faf8f4;min-height:100vh;padding:24px 24px 64px;">
    <div style="max-width:900px;margin:0 auto;">
      <div style="margin-bottom:28px;">
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#1a2540;margin:0 0 4px;">Learning Progress</h1>
        <p style="font-size:13px;color:#9ca3af;margin:0;">Track your English learning journey</p>
      </div>

      <!-- Stats -->
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px;">
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

      <!-- Course progress -->
      <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;margin-bottom:24px;">
        <h2 style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a2540;margin:0 0 20px;">Course Progress</h2>
        <div style="display:flex;flex-direction:column;gap:20px;">
          <div v-for="c in courseProgress" :key="c.id">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
              <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:36px;height:36px;border-radius:8px;background:#faf8f4;border:1px solid #ede9e2;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">📚</div>
                <div>
                  <p style="font-size:14px;font-weight:600;color:#1a2540;margin:0 0 2px;">{{ c.title }}</p>
                  <p style="font-size:12px;color:#9ca3af;margin:0;">{{ c.completed }}/{{ c.total }} lessons</p>
                </div>
              </div>
              <span style="font-size:14px;font-weight:700;color:#1a2540;">{{ c.progress }}%</span>
            </div>
            <div style="height:8px;background:#f0ece4;border-radius:8px;overflow:hidden;">
              <div :style="`height:100%;border-radius:8px;transition:width 0.7s;background:${c.progress===100?'#16a34a':'#1a2540'};width:${c.progress}%;`"></div>
            </div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <!-- Skills -->
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
          <h3 style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#1a2540;margin:0 0 18px;">Skills</h3>
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div v-for="skill in skills" :key="skill.name">
              <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;">
                <span style="color:#4b5563;">{{ skill.name }}</span>
                <span style="font-weight:700;color:#1a2540;">{{ skill.score }}%</span>
              </div>
              <div style="height:6px;background:#f0ece4;border-radius:6px;overflow:hidden;">
                <div :style="`height:100%;border-radius:6px;background:${skill.color};width:${skill.score}%;`"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent activity -->
        <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
          <h3 style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#1a2540;margin:0 0 18px;">Recent Activity</h3>
          <div style="display:flex;flex-direction:column;gap:12px;">
            <div v-for="a in recentActivity" :key="a.id" style="display:flex;align-items:flex-start;gap:12px;">
              <div style="width:32px;height:32px;border-radius:8px;background:#faf8f4;border:1px solid #ede9e2;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">{{ a.icon }}</div>
              <div>
                <p style="font-size:13px;font-weight:500;color:#1a2540;margin:0 0 2px;">{{ a.title }}</p>
                <p style="font-size:11px;color:#9ca3af;margin:0;">{{ a.time }}</p>
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

const stats = [
  { icon: '📚', label: 'Courses',           value: '3'   },
  { icon: '✅', label: 'Lessons Done',       value: '48'  },
  { icon: '⏱️', label: 'Study Hours',        value: '24h' },
  { icon: '🔥', label: 'Streak',             value: '7'   },
]

const courseProgress = ref([
  { id: 1, title: 'IELTS Preparation', completed: 12, total: 30, progress: 40 },
  { id: 2, title: 'Business English',  completed: 5,  total: 20, progress: 25 },
  { id: 3, title: 'Grammar A1-B2',     completed: 20, total: 20, progress: 100 },
])

const skills = [
  { name: 'Listening', score: 72, color: '#1a2540' },
  { name: 'Speaking',  score: 58, color: '#c9a84c' },
  { name: 'Reading',   score: 85, color: '#6b8f71' },
  { name: 'Writing',   score: 65, color: '#7c3aed' },
]

const recentActivity = ref([
  { id: 1, icon: '✅', title: 'Completed "Basic Pronunciation"', time: '2 hours ago' },
  { id: 2, icon: '🧩', title: 'Vocabulary Quiz Unit 3 — 85%',   time: 'Yesterday' },
  { id: 3, icon: '📝', title: 'Learned 15 new words',           time: '2 days ago' },
])
</script>

<style scoped>
@media (max-width: 640px) {
  div[style*="grid-template-columns:1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
