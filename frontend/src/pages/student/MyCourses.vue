<template>
  <div style="background:#faf8f4;min-height:100vh;padding:24px 24px 64px;">
    <div style="max-width:1280px;margin:0 auto;">
      <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px;">
        <div>
          <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#1a2540;margin:0 0 4px;">My Courses</h1>
          <p style="font-size:13px;color:#9ca3af;margin:0;">{{ courses.length }} courses enrolled</p>
        </div>
        <RouterLink to="/courses"
          style="padding:10px 20px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;text-decoration:none;border-radius:8px;letter-spacing:0.04em;"
        >+ Add Course</RouterLink>
      </div>

      <!-- Tabs -->
      <div style="display:flex;gap:0;border-bottom:2px solid #ede9e2;margin-bottom:24px;">
        <button v-for="t in tabs" :key="t.value" @click="activeTab = t.value"
          :style="`padding:10px 20px;font-size:13px;font-weight:600;border:none;background:none;cursor:pointer;font-family:inherit;letter-spacing:0.04em;transition:color 0.15s;border-bottom:2px solid transparent;margin-bottom:-2px;color:${activeTab===t.value?'#1a2540':'#9ca3af'};border-bottom-color:${activeTab===t.value?'#1a2540':'transparent'};`"
        >{{ t.label }} <span style="font-size:11px;margin-left:4px;">({{ t.count }})</span></button>
      </div>

      <!-- Loading -->
      <div v-if="loading" style="display:flex;justify-content:center;padding:64px 0;">
        <div style="width:32px;height:32px;border:3px solid #e5e0d5;border-top-color:#1a2540;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
      </div>

      <!-- Grid -->
      <div v-else-if="filteredCourses.length" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
        <div v-for="c in filteredCourses" :key="c.id"
          style="background:#fff;border:1px solid #ede9e2;border-radius:12px;overflow:hidden;transition:box-shadow 0.2s,transform 0.2s;"
          @mouseenter="e=>{e.currentTarget.style.boxShadow='0 8px 24px rgba(26,37,64,0.1)';e.currentTarget.style.transform='translateY(-2px)'}"
          @mouseleave="e=>{e.currentTarget.style.boxShadow='none';e.currentTarget.style.transform='none'}"
        >
          <div style="position:relative;aspect-ratio:16/9;background:#f0ece4;overflow:hidden;">
            <img v-if="c.thumbnail" :src="c.thumbnail" :alt="c.title" style="width:100%;height:100%;object-fit:cover;" />
            <div v-else style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:40px;">📚</div>
            <div style="position:absolute;bottom:0;left:0;right:0;height:4px;background:#e5e0d5;">
              <div :style="`height:100%;background:#1a2540;width:${c.progress}%;transition:width 0.3s;`"></div>
            </div>
          </div>
          <div style="padding:20px;">
            <p style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#c9a84c;margin:0 0 6px;">{{ c.category }}</p>
            <h3 style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:#1a2540;margin:0 0 12px;line-height:1.3;">{{ c.title }}</h3>
            <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;color:#9ca3af;margin-bottom:16px;">
              <span>{{ c.completed_lessons }}/{{ c.total_lessons }} lessons</span>
              <span style="font-weight:700;color:#1a2540;">{{ c.progress }}%</span>
            </div>
            <RouterLink :to="`/learn/${c.id}/lesson/${c.last_lesson_id || 1}`"
              style="display:block;text-align:center;padding:10px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;text-decoration:none;border-radius:8px;transition:opacity 0.15s;"
              @mouseenter="e=>e.currentTarget.style.opacity='0.88'"
              @mouseleave="e=>e.currentTarget.style.opacity='1'"
            >{{ c.progress === 100 ? 'Review' : 'Continue' }}</RouterLink>
          </div>
        </div>
      </div>

      <!-- Empty -->
      <div v-else style="text-align:center;padding:64px 24px;">
        <div style="font-size:40px;margin-bottom:16px;">📚</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#1a2540;margin:0 0 8px;">No courses here</h3>
        <p style="font-size:14px;color:#9ca3af;margin:0 0 20px;">You haven't enrolled in any courses in this category.</p>
        <RouterLink to="/courses"
          style="display:inline-block;padding:10px 24px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;text-decoration:none;border-radius:8px;"
        >Explore Courses</RouterLink>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'

const loading   = ref(false)
const activeTab = ref('all')

const tabs = [
  { value: 'all',        label: 'All',       count: 3 },
  { value: 'inprogress', label: 'In Progress', count: 2 },
  { value: 'completed',  label: 'Completed',  count: 1 },
]

const courses = ref([
  { id: 1, title: 'IELTS Preparation Course', category: 'IELTS',    thumbnail: null, completed_lessons: 12, total_lessons: 30, progress: 40,  last_lesson_id: 3 },
  { id: 2, title: 'Business English',         category: 'Business', thumbnail: null, completed_lessons: 5,  total_lessons: 20, progress: 25,  last_lesson_id: 6 },
  { id: 3, title: 'English Grammar A1-B2',    category: 'Grammar',  thumbnail: null, completed_lessons: 20, total_lessons: 20, progress: 100, last_lesson_id: 20 },
])

const filteredCourses = computed(() => {
  if (activeTab.value === 'inprogress') return courses.value.filter(c => c.progress > 0 && c.progress < 100)
  if (activeTab.value === 'completed')  return courses.value.filter(c => c.progress === 100)
  return courses.value
})
</script>

<style scoped>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
