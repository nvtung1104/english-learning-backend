<template>
  <div>

    <!-- HERO: full-width image, text centered overlay -->
    <section style="position:relative;height:calc(100vh - 82px);min-height:480px;max-height:780px;overflow:hidden;">
      <img
        src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1800&q=80"
        alt="Hero"
        style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 30%;"
      />
      <!-- subtle dark overlay so text is readable -->
      <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(15,20,40,0.52) 0%,rgba(15,20,40,0.38) 60%,rgba(15,20,40,0.18) 100%);"></div>

      <!-- centered text block -->
      <div style="position:relative;z-index:1;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:0 24px;">
        <p style="font-size:11px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:rgba(255,255,255,0.7);margin-bottom:20px;">
          English Learning Academy
        </p>
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2.8rem,7vw,5.5rem);font-weight:700;color:#fff;line-height:1.05;letter-spacing:-0.03em;margin:0 0 20px;max-width:800px;">
          Going Places<br/>
          <em style="font-style:italic;color:#e8d5a3;">with English</em>
        </h1>
        <p style="font-size:clamp(15px,2vw,18px);color:rgba(255,255,255,0.72);max-width:480px;line-height:1.65;margin:0 0 36px;">
          500+ courses from beginner to advanced. Personalized learning paths, study anytime anywhere.
        </p>
        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
          <RouterLink to="/courses"
            style="padding:14px 32px;background:#c9a84c;color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;text-decoration:none;border-radius:4px;transition:opacity 0.2s;"
            @mouseenter="e=>e.currentTarget.style.opacity='0.88'"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >Explore Courses</RouterLink>
          <RouterLink to="/register"
            style="padding:14px 32px;background:rgba(255,255,255,0.15);color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;text-decoration:none;border-radius:4px;border:1px solid rgba(255,255,255,0.4);backdrop-filter:blur(8px);transition:background 0.2s;"
            @mouseenter="e=>e.currentTarget.style.background='rgba(255,255,255,0.25)'"
            @mouseleave="e=>e.currentTarget.style.background='rgba(255,255,255,0.15)'"
          >Start Free</RouterLink>
        </div>
      </div>

      <!-- bottom stats bar -->
      <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(255,255,255,0.12);backdrop-filter:blur(12px);border-top:1px solid rgba(255,255,255,0.15);">
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:center;gap:48px;height:64px;flex-wrap:wrap;">
          <div v-for="s in heroStats" :key="s.label" style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:20px;">{{ s.icon }}</span>
            <div>
              <div style="font-size:15px;font-weight:700;color:#fff;line-height:1;">{{ s.value }}</div>
              <div style="font-size:10px;color:rgba(255,255,255,0.55);letter-spacing:0.08em;text-transform:uppercase;">{{ s.label }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- INTRO TEXT (magazine style) -->
    <section style="padding:72px 24px;background:#fff;text-align:center;">
      <div style="max-width:640px;margin:0 auto;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:#c9a84c;margin-bottom:16px;">Our Story</p>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.75rem,3.5vw,2.5rem);font-weight:700;color:#1a2540;line-height:1.15;letter-spacing:-0.025em;margin:0 0 20px;">
          Learn English the smart way
        </h2>
        <p style="font-size:16px;color:#6b7280;line-height:1.8;margin:0 0 32px;">
          We combine AI technology with expert teaching methods to create a personalized learning experience that actually works. Join 50,000+ learners who have transformed their English skills.
        </p>
        <RouterLink to="/courses"
          style="font-size:13px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#1a2540;text-decoration:none;border-bottom:1px solid #1a2540;padding-bottom:2px;transition:color 0.2s;"
        >View All Courses &rarr;</RouterLink>
      </div>
    </section>

    <!-- FEATURED COURSES -->
    <section style="padding:72px 24px;background:#faf8f4;">
      <div style="max-width:1280px;margin:0 auto;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
          <div>
            <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:#c9a84c;margin-bottom:10px;">Featured</p>
            <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2.25rem);font-weight:700;color:#1a2540;margin:0;letter-spacing:-0.02em;">Popular Courses</h2>
          </div>
          <RouterLink to="/courses" style="font-size:13px;font-weight:600;color:#1a2540;text-decoration:none;letter-spacing:0.08em;text-transform:uppercase;border-bottom:1px solid #1a2540;padding-bottom:2px;">
            See All &rarr;
          </RouterLink>
        </div>

        <div v-if="loading" style="display:flex;justify-content:center;padding:48px 0;">
          <div style="width:32px;height:32px;border:3px solid #e5e0d5;border-top-color:#1a2540;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
        </div>
        <div v-else style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;">
          <CourseCard v-for="c in courses" :key="c.id" :course="c" />
          <!-- fallback cards if no data -->
          <template v-if="!courses.length">
            <div v-for="c in fallbackCourses" :key="c.id"
              style="background:#fff;border:1px solid #ede9e2;border-radius:12px;overflow:hidden;transition:box-shadow 0.2s,transform 0.2s;cursor:pointer;"
              @mouseenter="e=>{e.currentTarget.style.boxShadow='0 12px 32px rgba(26,37,64,0.12)';e.currentTarget.style.transform='translateY(-3px)'}"
              @mouseleave="e=>{e.currentTarget.style.boxShadow='none';e.currentTarget.style.transform='none'}"
            >
              <div style="height:180px;overflow:hidden;">
                <img :src="c.img" :alt="c.title" style="width:100%;height:100%;object-fit:cover;"/>
              </div>
              <div style="padding:20px;">
                <span style="font-size:10px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:#c9a84c;">{{ c.level }}</span>
                <h3 style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:#1a2540;margin:8px 0 6px;line-height:1.3;">{{ c.title }}</h3>
                <p style="font-size:13px;color:#6b7280;margin:0 0 14px;line-height:1.5;">{{ c.desc }}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                  <span style="font-size:13px;color:#9ca3af;">{{ c.students }} students</span>
                  <span style="font-size:15px;font-weight:700;color:#1a2540;">{{ c.price }}</span>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </section>

    <!-- FEATURES GRID (3 col editorial) -->
    <section style="padding:72px 24px;background:#fff;">
      <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:52px;">
          <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:#c9a84c;margin-bottom:12px;">Why Choose Us</p>
          <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.75rem,3.5vw,2.5rem);font-weight:700;color:#1a2540;margin:0;letter-spacing:-0.025em;">Modern Learning Methods</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;">
          <div v-for="f in features" :key="f.title"
            style="padding:32px;border:1px solid #ede9e2;border-radius:12px;background:#faf8f4;transition:border-color 0.2s,box-shadow 0.2s;"
            @mouseenter="e=>{e.currentTarget.style.borderColor='#c9a84c';e.currentTarget.style.boxShadow='0 8px 24px rgba(201,168,76,0.1)'}"
            @mouseleave="e=>{e.currentTarget.style.borderColor='#ede9e2';e.currentTarget.style.boxShadow='none'}"
          >
            <div style="font-size:28px;margin-bottom:16px;">{{ f.icon }}</div>
            <h3 style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:#1a2540;margin:0 0 10px;">{{ f.title }}</h3>
            <p style="font-size:14px;color:#6b7280;line-height:1.7;margin:0;">{{ f.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SPLIT SECTION: image left, text right -->
    <section style="padding:72px 24px;background:#faf8f4;">
      <div style="max-width:1280px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;">
        <div style="border-radius:12px;overflow:hidden;aspect-ratio:4/3;">
          <img
            src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80"
            alt="Learning"
            style="width:100%;height:100%;object-fit:cover;"
          />
        </div>
        <div>
          <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:#c9a84c;margin-bottom:16px;">Learning Path</p>
          <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.75rem,3vw,2.5rem);font-weight:700;color:#1a2540;line-height:1.15;letter-spacing:-0.025em;margin:0 0 20px;">
            From A1 to C2 —<br/>clear and structured
          </h2>
          <p style="font-size:15px;color:#6b7280;line-height:1.8;margin:0 0 32px;">
            Each level is scientifically designed to help you progress steadily and confidently at every step of your journey.
          </p>
          <div style="display:flex;flex-direction:column;gap:16px;">
            <div v-for="step in learningPath" :key="step.level" style="display:flex;align-items:flex-start;gap:16px;">
              <div style="width:40px;height:40px;border-radius:8px;background:#1a2540;display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700;flex-shrink:0;">
                {{ step.level }}
              </div>
              <div>
                <p style="font-size:14px;font-weight:600;color:#1a2540;margin:0 0 3px;">{{ step.title }}</p>
                <p style="font-size:13px;color:#9ca3af;margin:0;line-height:1.5;">{{ step.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section style="padding:72px 24px;background:#fff;">
      <div style="max-width:1280px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:48px;">
          <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:#c9a84c;margin-bottom:12px;">Testimonials</p>
          <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.75rem,3.5vw,2.5rem);font-weight:700;color:#1a2540;margin:0 0 12px;letter-spacing:-0.025em;">What Our Students Say</h2>
          <div style="display:flex;align-items:center;justify-content:center;gap:4px;">
            <span v-for="i in 5" :key="i" style="color:#c9a84c;font-size:18px;">&#9733;</span>
            <span style="font-size:13px;color:#9ca3af;margin-left:8px;">4.9/5 from 12,000+ reviews</span>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;">
          <div v-for="t in testimonials" :key="t.name"
            style="padding:28px;border:1px solid #ede9e2;border-radius:12px;background:#faf8f4;"
          >
            <div style="display:flex;gap:3px;margin-bottom:16px;">
              <span v-for="i in 5" :key="i" style="color:#c9a84c;font-size:14px;">&#9733;</span>
            </div>
            <p style="font-size:14px;color:#4b5563;line-height:1.75;margin:0 0 20px;font-style:italic;">"{{ t.text }}"</p>
            <div style="display:flex;align-items:center;gap:12px;padding-top:16px;border-top:1px solid #ede9e2;">
              <div :style="`width:36px;height:36px;border-radius:50%;background:${t.color};display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;`">
                {{ t.name.charAt(0) }}
              </div>
              <div>
                <p style="font-size:13px;font-weight:600;color:#1a2540;margin:0;">{{ t.name }}</p>
                <p style="font-size:12px;color:#9ca3af;margin:0;">{{ t.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BANNER -->
    <section style="padding:80px 24px;background:#1a2540;text-align:center;position:relative;overflow:hidden;">
      <div style="position:absolute;top:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:rgba(201,168,76,0.06);pointer-events:none;"></div>
      <div style="position:relative;max-width:560px;margin:0 auto;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:rgba(201,168,76,0.8);margin-bottom:16px;">Get Started Today</p>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:700;color:#fff;line-height:1.1;letter-spacing:-0.025em;margin:0 0 16px;">
          Ready to start your journey?
        </h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.55);margin:0 0 36px;line-height:1.7;">
          Join 50,000+ learners studying English every day.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
          <RouterLink to="/register"
            style="padding:14px 32px;background:#c9a84c;color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;text-decoration:none;border-radius:4px;transition:opacity 0.2s;"
            @mouseenter="e=>e.currentTarget.style.opacity='0.88'"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >Register Free</RouterLink>
          <RouterLink to="/courses"
            style="padding:14px 32px;background:rgba(255,255,255,0.08);color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em;text-decoration:none;border-radius:4px;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;"
            @mouseenter="e=>e.currentTarget.style.background='rgba(255,255,255,0.15)'"
            @mouseleave="e=>e.currentTarget.style.background='rgba(255,255,255,0.08)'"
          >Browse Courses</RouterLink>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import CourseCard from '../components/CourseCard.vue'

const courses = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { getCourses } = await import('../api/courses')
    const res = await getCourses({ per_page: 8 })
    courses.value = res.data?.data || res.data || []
  } catch { courses.value = [] }
  finally { loading.value = false }
})

const heroStats = [
  { icon: '📚', value: '500+',  label: 'Courses' },
  { icon: '👥', value: '50K+',  label: 'Students' },
  { icon: '⭐', value: '4.9/5', label: 'Rating' },
  { icon: '🏆', value: '98%',   label: 'Success Rate' },
]

const fallbackCourses = [
  { id: 1, title: 'IELTS Foundation',    level: 'B1–B2', desc: 'Master all 4 IELTS skills with expert guidance.',          img: 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80', students: '2,340', price: 'Free' },
  { id: 2, title: 'TOEIC 600+',          level: 'A2–B1', desc: 'Achieve your target TOEIC score with proven strategies.',   img: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80', students: '1,890', price: '$29' },
  { id: 3, title: 'English for Business', level: 'B2',    desc: 'Professional English for meetings, emails and reports.',   img: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80', students: '1,120', price: '$39' },
  { id: 4, title: 'Everyday Speaking',   level: 'A1–A2', desc: 'Build confidence in daily English conversations.',          img: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&q=80', students: '3,450', price: 'Free' },
]

const features = [
  { icon: '🎯', title: 'Personalized Path',    desc: 'AI analyzes your level and recommends the perfect learning path for you.' },
  { icon: '🎧', title: 'Listening & Speaking', desc: 'Thousands of native-speaker audio lessons and AI pronunciation feedback.' },
  { icon: '📝', title: 'Structured Grammar',   desc: 'From A1 to C2, grammar taught clearly and systematically.' },
  { icon: '🏆', title: 'Gamification',         desc: 'Points, badges and streaks keep you motivated every day.' },
  { icon: '📱', title: 'Learn Anywhere',       desc: 'Works perfectly on all devices. Study offline without internet.' },
  { icon: '🎓', title: 'Certificates',         desc: 'Earn internationally recognized certificates upon course completion.' },
]

const learningPath = [
  { level: 'A1', title: 'Beginner',     desc: 'Alphabet, pronunciation, basic vocabulary and simple conversations.' },
  { level: 'B1', title: 'Intermediate', desc: 'Advanced grammar, reading comprehension, writing paragraphs.' },
  { level: 'C1', title: 'Advanced',     desc: 'Natural conversation, academic writing, IELTS/TOEIC preparation.' },
]

const testimonials = [
  { name: 'Minh Anh',  role: 'IELTS Student',    text: 'EnglishLearn helped me improve my IELTS from 5.5 to 7.0 in just 3 months. The method is incredibly effective!', color: '#1a2540' },
  { name: 'Bao Long',  role: 'University Student', text: 'Beautiful interface, easy to use. The lessons are scientifically designed and helped me learn much faster.', color: '#c9a84c' },
  { name: 'Le Huong',  role: 'Office Worker',      text: 'I can now communicate confidently in English after 6 months on EnglishLearn. Totally worth it!', color: '#6b8f71' },
]
</script>

<style scoped>
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 768px) {
  section > div[style*="grid-template-columns:1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>
