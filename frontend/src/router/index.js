import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Layout wrappers
const withAdmin   = (component) => ({ ...component, layout: 'admin' })
const withTeacher = (component) => ({ ...component, layout: 'teacher' })

const routes = [
  // ── Public (PublicLayout) ──────────────────────────────────────
  { path: '/',               component: () => import('../pages/Home.vue') },
  { path: '/courses',        component: () => import('../pages/Courses.vue') },
  { path: '/courses/:id',    component: () => import('../pages/CourseDetail.vue') },
  { path: '/login',          component: () => import('../pages/Login.vue') },
  { path: '/register',       component: () => import('../pages/Register.vue') },
  { path: '/forgot-password',component: () => import('../pages/ForgotPassword.vue') },
  { path: '/profile',        component: () => import('../pages/Profile.vue'),       meta: { requiresAuth: true } },
  { path: '/quiz/:id',       component: () => import('../pages/QuizPage.vue'),      meta: { requiresAuth: true } },
  { path: '/learn/:courseId/lesson/:id', component: () => import('../pages/LessonLearn.vue'), meta: { requiresAuth: true } },

  // ── Student (StudentLayout) ────────────────────────────────────
  {
    path: '/student',
    component: () => import('../layouts/StudentLayout.vue'),
    meta: { requiresAuth: true, role: 'student' },
    children: [
      { path: '',          redirect: '/student/dashboard' },
      { path: 'dashboard', component: () => import('../pages/student/Dashboard.vue') },
      { path: 'my-courses',component: () => import('../pages/student/MyCourses.vue') },
      { path: 'progress',  component: () => import('../pages/student/Progress.vue') },
      { path: 'quiz-history', component: () => import('../pages/student/QuizHistory.vue') },
      { path: 'vocabulary',component: () => import('../pages/student/Vocabulary.vue') },
    ],
  },

  // ── Admin (AdminLayout) ────────────────────────────────────────
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: '',            component: () => import('../pages/admin/Dashboard.vue') },
      { path: 'users',       component: () => import('../pages/admin/Users.vue') },
      { path: 'roles',       component: () => import('../pages/admin/Roles.vue') },
      { path: 'permissions', component: () => import('../pages/admin/Permissions.vue') },
      { path: 'courses',     component: () => import('../pages/admin/Courses.vue') },
      { path: 'categories',  component: () => import('../pages/admin/Categories.vue') },
      { path: 'levels',      component: () => import('../pages/admin/Levels.vue') },
      { path: 'sections',    component: () => import('../pages/admin/Sections.vue') },
      { path: 'lessons',     component: () => import('../pages/admin/Lessons.vue') },
      { path: 'lesson-types',component: () => import('../pages/admin/LessonTypes.vue') },
      { path: 'materials',   component: () => import('../pages/admin/Materials.vue') },
      { path: 'vocabularies',component: () => import('../pages/admin/Vocabularies.vue') },
      { path: 'grammar',     component: () => import('../pages/admin/Grammar.vue') },
      { path: 'listening',   component: () => import('../pages/admin/Listening.vue') },
      { path: 'speaking',    component: () => import('../pages/admin/Speaking.vue') },
      { path: 'quizzes',     component: () => import('../pages/admin/Quizzes.vue') },
      { path: 'questions',   component: () => import('../pages/admin/Questions.vue') },
      { path: 'attempts',    component: () => import('../pages/admin/Attempts.vue') },
      { path: 'enrollments', component: () => import('../pages/admin/Enrollments.vue') },
      { path: 'progress',    component: () => import('../pages/admin/LessonProgress.vue') },
      { path: 'reviews',     component: () => import('../pages/admin/Reviews.vue') },
      { path: 'notifications',component: () => import('../pages/admin/Notifications.vue') },
    ],
  },

  // ── Teacher (TeacherLayout) ────────────────────────────────────
  {
    path: '/teacher',
    component: () => import('../layouts/TeacherLayout.vue'),
    meta: { requiresAuth: true, role: 'teacher' },
    children: [
      { path: '',            component: () => import('../pages/teacher/Dashboard.vue') },
      { path: 'courses',     component: () => import('../pages/teacher/Courses.vue') },
      { path: 'sections',    component: () => import('../pages/teacher/Sections.vue') },
      { path: 'lessons',     component: () => import('../pages/teacher/Lessons.vue') },
      { path: 'materials',   component: () => import('../pages/teacher/Materials.vue') },
      { path: 'vocabularies',component: () => import('../pages/teacher/Vocabularies.vue') },
      { path: 'grammar',     component: () => import('../pages/teacher/Grammar.vue') },
      { path: 'listening',   component: () => import('../pages/teacher/Listening.vue') },
      { path: 'quizzes',     component: () => import('../pages/teacher/Quizzes.vue') },
      { path: 'questions',   component: () => import('../pages/teacher/Questions.vue') },
      { path: 'students',    component: () => import('../pages/teacher/Students.vue') },
      { path: 'reviews',     component: () => import('../pages/teacher/Reviews.vue') },
    ],
  },

  // 404
  { path: '/:pathMatch(.*)*', component: () => import('../pages/NotFound.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach((to) => {
  const auth = useAuthStore()

  const requiresAuth = to.matched.some(r => r.meta?.requiresAuth)
  if (requiresAuth && !auth.token) return '/login'

  // Kiểm tra quyền dựa trên matched routes (hỗ trợ nested routes)
  const requiredRole = to.matched.find(r => r.meta?.role)?.meta?.role
  if (requiredRole) {
    const userRoles = auth.user?.roles || []
    // Admin có thể vào tất cả
    if (userRoles.includes('admin')) return
    // Teacher có thể vào teacher routes
    if (requiredRole === 'teacher' && userRoles.includes('teacher')) return
    // Student có thể vào student routes
    if (requiredRole === 'student' && userRoles.includes('student')) return
    // Không có quyền → về trang chủ
    return '/'
  }
})

export default router
