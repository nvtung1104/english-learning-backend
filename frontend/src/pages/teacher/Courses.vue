<template>
  <div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="page-title">Khóa học của tôi</h1>
        <p class="page-subtitle">Quản lý và theo dõi các khóa học bạn giảng dạy</p>
      </div>
      <AppButton variant="primary" size="md" @click="showModal = true">+ Tạo khóa học mới</AppButton>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <StatCard v-for="s in stats" :key="s.label" v-bind="s" />
    </div>

    <!-- Course list -->
    <div class="card overflow-hidden">
      <div class="px-5 py-4 border-b border-[var(--border-soft)] flex items-center justify-between gap-3">
        <h2 class="font-display font-bold text-navy text-base">Danh sách khóa học</h2>
        <div class="relative">
          <input v-model="search" type="text" placeholder="Tìm kiếm..." class="input pl-9 w-52" />
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-[var(--border-soft)] bg-cream-soft/60">
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa học</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Học viên</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Đánh giá</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
              <th class="px-5 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border-soft)]">
            <tr v-for="c in filteredCourses" :key="c.id" class="hover:bg-cream-soft/50 transition-colors">
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-navy/8 flex items-center justify-center text-xl shrink-0">📚</div>
                  <div>
                    <p class="font-semibold text-navy text-sm">{{ c.title }}</p>
                    <p class="text-xs text-slate-400">{{ c.category }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-sm text-slate-600">{{ c.students }}</td>
              <td class="px-5 py-4">
                <span class="flex items-center gap-1 text-sm">
                  <span class="text-gold">★</span>
                  <span class="font-semibold text-navy">{{ c.rating || '—' }}</span>
                </span>
              </td>
              <td class="px-5 py-4">
                <AppBadge :variant="c.status === 'published' ? 'success' : 'warning'">
                  {{ c.status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                </AppBadge>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <button @click="openEdit(c)" class="btn btn-sm btn-secondary">Chỉnh sửa</button>
                  <RouterLink :to="`/courses/${c.id}`" class="btn btn-sm btn-ghost text-slate-400 hover:text-navy">Xem</RouterLink>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <EmptyState v-if="!filteredCourses.length" icon="📚" title="Chưa có khóa học" description="Tạo khóa học đầu tiên của bạn." />
    </div>

    <!-- Modal tạo/sửa -->
    <AppModal v-model="showModal" :title="editItem ? 'Chỉnh sửa khóa học' : 'Tạo khóa học mới'" size="lg">
      <form @submit.prevent="handleSave" class="space-y-4">
        <AppInput v-model="form.title" label="Tên khóa học" id="title" required />
        <div class="grid sm:grid-cols-2 gap-4">
          <AppInput v-model="form.category" label="Danh mục" id="category" />
          <AppSelect v-model="form.status" label="Trạng thái" id="status" :options="statusOptions" />
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" size="sm" @click="showModal = false">Hủy</AppButton>
        <AppButton variant="primary" size="sm" @click="handleSave">Lưu</AppButton>
      </template>
    </AppModal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import StatCard from '../../components/ui/StatCard.vue'
import AppBadge from '../../components/ui/AppBadge.vue'
import AppButton from '../../components/ui/AppButton.vue'
import AppInput from '../../components/ui/AppInput.vue'
import AppSelect from '../../components/ui/AppSelect.vue'
import AppModal from '../../components/ui/AppModal.vue'
import EmptyState from '../../components/ui/EmptyState.vue'

const search    = ref('')
const showModal = ref(false)
const editItem  = ref(null)
const form      = ref({ title: '', category: '', status: 'draft' })

const statusOptions = [
  { value: 'draft',     label: 'Nháp' },
  { value: 'published', label: 'Xuất bản' },
]

const stats = [
  { icon: '📚', label: 'Khóa học',    value: '4',    color: 'navy' },
  { icon: '👥', label: 'Học viên',    value: '248',  color: 'emerald' },
  { icon: '⭐', label: 'Đánh giá TB', value: '4.7',  color: 'amber' },
  { icon: '💰', label: 'Doanh thu',   value: '12M₫', color: 'gold' },
]

const courses = ref([
  { id: 1, title: 'IELTS Preparation Course', category: 'IELTS',     students: 120, rating: 4.8, status: 'published' },
  { id: 2, title: 'Business English',         category: 'Giao tiếp', students: 85,  rating: 4.6, status: 'published' },
  { id: 3, title: 'Grammar Fundamentals',     category: 'Ngữ pháp',  students: 43,  rating: 4.9, status: 'published' },
  { id: 4, title: 'Advanced Writing Skills',  category: 'Viết',      students: 0,   rating: 0,   status: 'draft' },
])

const filteredCourses = computed(() =>
  search.value ? courses.value.filter(c => c.title.toLowerCase().includes(search.value.toLowerCase())) : courses.value
)

function openEdit(item) {
  editItem.value = item
  form.value = { title: item.title, category: item.category, status: item.status }
  showModal.value = true
}

function handleSave() {
  if (editItem.value) {
    Object.assign(editItem.value, form.value)
  } else {
    courses.value.push({ id: Date.now(), ...form.value, students: 0, rating: 0 })
  }
  showModal.value = false
  editItem.value = null
  form.value = { title: '', category: '', status: 'draft' }
}
</script>
