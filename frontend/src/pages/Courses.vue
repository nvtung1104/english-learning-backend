<template>
  <div>
    <!-- Page header -->
    <div style="background:#1a2540;padding:56px 24px 48px;">
      <div style="max-width:1280px;margin:0 auto;">
        <p style="font-size:10px;font-weight:700;letter-spacing:0.28em;text-transform:uppercase;color:rgba(201,168,76,0.8);margin-bottom:12px;">Course Catalog</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:700;color:#fff;margin:0 0 12px;letter-spacing:-0.03em;line-height:1.1;">All Courses</h1>
        <p style="color:rgba(255,255,255,0.55);font-size:15px;max-width:480px;line-height:1.65;margin:0;">Explore 500+ courses from beginner to advanced, designed by leading experts.</p>
      </div>
    </div>

    <div style="max-width:1280px;margin:0 auto;padding:40px 24px;">
      <div style="display:flex;gap:32px;align-items:flex-start;">

        <!-- Sidebar filters -->
        <aside style="width:220px;flex-shrink:0;position:sticky;top:100px;">
          <div style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:24px;">
            <h3 style="font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:#1a2540;margin:0 0 20px;">Filters</h3>

            <div>
              <p style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#9ca3af;margin:0 0 10px;">Level</p>
              <div style="display:flex;flex-direction:column;gap:8px;">
                <label v-for="l in levels" :key="l.value" style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                  <input type="checkbox" v-model="filters.levels" :value="l.value" style="width:15px;height:15px;accent-color:#1a2540;cursor:pointer;" />
                  <span style="font-size:13px;color:#4b5563;">{{ l.label }}</span>
                </label>
              </div>
            </div>

            <div style="height:1px;background:#f0ece4;margin:20px 0;"></div>

            <div>
              <p style="font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#9ca3af;margin:0 0 10px;">Price</p>
              <div style="display:flex;flex-direction:column;gap:8px;">
                <label v-for="p in priceOptions" :key="p.value" style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                  <input type="radio" v-model="filters.price" :value="p.value" style="width:15px;height:15px;accent-color:#1a2540;cursor:pointer;" />
                  <span style="font-size:13px;color:#4b5563;">{{ p.label }}</span>
                </label>
              </div>
            </div>

            <div style="height:1px;background:#f0ece4;margin:20px 0;"></div>

            <button @click="resetFilters"
              style="width:100%;padding:9px;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#6b7280;background:#faf8f4;border:1px solid #ede9e2;border-radius:8px;cursor:pointer;transition:background 0.15s;"
              @mouseenter="e=>e.currentTarget.style.background='#f0ece4'"
              @mouseleave="e=>e.currentTarget.style.background='#faf8f4'"
            >Clear Filters</button>
          </div>
        </aside>

        <!-- Main content -->
        <div style="flex:1;min-width:0;">
          <!-- Toolbar -->
          <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:24px;">
            <div style="position:relative;flex:1;max-width:360px;">
              <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input v-model="search" type="text" placeholder="Search courses..."
                style="width:100%;padding:10px 12px 10px 36px;border:1px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
                @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
                @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
              />
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
              <span style="font-size:13px;color:#9ca3af;">{{ total }} courses</span>
              <select v-model="sortBy"
                style="padding:9px 12px;border:1px solid #ede9e2;border-radius:8px;font-size:13px;color:#1a2540;background:#fff;outline:none;cursor:pointer;font-family:inherit;"
              >
                <option v-for="o in sortOptions" :key="o.value" :value="o.value">{{ o.label }}</option>
              </select>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="loading" style="display:flex;justify-content:center;padding:64px 0;">
            <div style="width:32px;height:32px;border:3px solid #e5e0d5;border-top-color:#1a2540;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
          </div>

          <!-- Grid -->
          <div v-else-if="courses.length" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
            <CourseCard v-for="c in courses" :key="c.id" :course="c" />
          </div>

          <!-- Empty -->
          <div v-else style="text-align:center;padding:64px 24px;">
            <div style="font-size:40px;margin-bottom:16px;">🔍</div>
            <h3 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#1a2540;margin:0 0 8px;">No courses found</h3>
            <p style="font-size:14px;color:#9ca3af;margin:0;">Try changing your filters or search term.</p>
          </div>

          <!-- Pagination -->
          <div v-if="lastPage > 1" style="margin-top:32px;">
            <AppPagination :current-page="page" :last-page="lastPage" :total="total" @change="page = $event" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import CourseCard from '../components/CourseCard.vue'
import AppPagination from '../components/ui/AppPagination.vue'
import { getCourses } from '../api/courses'

const courses  = ref([])
const loading  = ref(true)
const search   = ref('')
const page     = ref(1)
const total    = ref(0)
const lastPage = ref(1)
const sortBy   = ref('newest')
const filters  = ref({ levels: [], price: 'all' })

const levels = [
  { value: 'beginner',     label: 'Beginner' },
  { value: 'intermediate', label: 'Intermediate' },
  { value: 'advanced',     label: 'Advanced' },
]
const priceOptions = [
  { value: 'all',  label: 'All' },
  { value: 'free', label: 'Free' },
  { value: 'paid', label: 'Paid' },
]
const sortOptions = [
  { value: 'newest',  label: 'Newest' },
  { value: 'popular', label: 'Most Popular' },
  { value: 'rating',  label: 'Top Rated' },
]

async function fetchCourses() {
  loading.value = true
  try {
    const res = await getCourses({ page: page.value, search: search.value, sort: sortBy.value, per_page: 12 })
    const data = res.data
    courses.value  = data?.data || data || []
    total.value    = data?.total || courses.value.length
    lastPage.value = data?.last_page || 1
  } catch { courses.value = [] }
  finally { loading.value = false }
}

function resetFilters() {
  filters.value = { levels: [], price: 'all' }
  search.value  = ''
  page.value    = 1
}

watch([search, sortBy, page, filters], fetchCourses, { deep: true })
onMounted(fetchCourses)
</script>

<style scoped>
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 768px) {
  aside { display: none; }
}
</style>
