import MainLayout from '@/layouts/MainLayout.vue'

import HomePage from '@/pages/public/HomePage.vue'
import CoursesPage from '@/pages/public/CoursesPage.vue'
import CourseDetailPage from '@/pages/public/CourseDetailPage.vue'
import PracticePage from '@/pages/public/PracticePage.vue'
import TestsPage from '@/pages/public/TestsPage.vue'
import TestDetailPage from '@/pages/public/TestDetailPage.vue'
import AboutPage from '@/pages/public/AboutPage.vue'
import ContactPage from '@/pages/public/ContactPage.vue'
import NotFoundPage from '@/pages/public/NotFoundPage.vue'

const publicRoutes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: HomePage,
        meta: { title: 'Trang chủ' },
      },
      {
        path: 'courses',
        name: 'courses',
        component: CoursesPage,
        meta: { title: 'Khóa học' },
      },
      {
        path: 'courses/:slug',
        name: 'course-detail',
        component: CourseDetailPage,
        props: true,
        meta: { title: 'Chi tiết khóa học' },
      },
      {
        path: 'practice',
        name: 'practice',
        component: PracticePage,
        meta: { title: 'Luyện tập' },
      },
      {
        path: 'tests',
        name: 'tests',
        component: TestsPage,
        meta: { title: 'Đề thi' },
      },
      {
        path: 'tests/:slug',
        name: 'test-detail',
        component: TestDetailPage,
        props: true,
        meta: { title: 'Chi tiết đề thi' },
      },
      {
        path: 'about',
        name: 'about',
        component: AboutPage,
        meta: { title: 'Giới thiệu' },
      },
      {
        path: 'contact',
        name: 'contact',
        component: ContactPage,
        meta: { title: 'Liên hệ' },
      },
    ],
  },

  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFoundPage,
    meta: { title: 'Không tìm thấy trang' },
  },
]

export default publicRoutes