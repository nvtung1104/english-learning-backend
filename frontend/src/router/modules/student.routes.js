import StudentLayout from '@/layouts/StudentLayout.vue'

import DashboardPage from '@/pages/student/DashboardPage.vue'
import MyCoursesPage from '@/pages/student/MyCoursesPage.vue'
import CourseLearnPage from '@/pages/student/CourseLearnPage.vue'
import LessonLearnPage from '@/pages/student/LessonLearnPage.vue'
import QuizPage from '@/pages/student/QuizPage.vue'
import QuizResultPage from '@/pages/student/QuizResultPage.vue'
import ListeningPage from '@/pages/student/ListeningPage.vue'
import SpeakingPage from '@/pages/student/SpeakingPage.vue'
import VocabularyPage from '@/pages/student/VocabularyPage.vue'
import GrammarPage from '@/pages/student/GrammarPage.vue'
import NotificationsPage from '@/pages/student/NotificationsPage.vue'
import ProfilePage from '@/pages/student/ProfilePage.vue'
import ReviewsPage from '@/pages/student/ReviewsPage.vue'
import SettingsPage from '@/pages/student/SettingsPage.vue'

const studentRoutes = [
  {
    path: '/student',
    component: StudentLayout,
    meta: { requiresAuth: true, roles: ['student'] },
    children: [
      {
        path: 'dashboard',
        name: 'student-dashboard',
        component: DashboardPage,
        meta: { title: 'Dashboard học viên' },
      },
      {
        path: 'courses',
        name: 'student-courses',
        component: MyCoursesPage,
        meta: { title: 'Khóa học của tôi' },
      },
      {
        path: 'courses/:courseId/learn',
        name: 'student-course-learn',
        component: CourseLearnPage,
        props: true,
        meta: { title: 'Học khóa học' },
      },
      {
        path: 'lessons/:lessonId',
        name: 'student-lesson-learn',
        component: LessonLearnPage,
        props: true,
        meta: { title: 'Học bài học' },
      },
      {
        path: 'quizzes/:quizId',
        name: 'student-quiz',
        component: QuizPage,
        props: true,
        meta: { title: 'Làm quiz' },
      },
      {
        path: 'quiz-results/:attemptId',
        name: 'student-quiz-result',
        component: QuizResultPage,
        props: true,
        meta: { title: 'Kết quả quiz' },
      },
      {
        path: 'listening',
        name: 'student-listening',
        component: ListeningPage,
        meta: { title: 'Listening' },
      },
      {
        path: 'speaking',
        name: 'student-speaking',
        component: SpeakingPage,
        meta: { title: 'Speaking' },
      },
      {
        path: 'vocabulary',
        name: 'student-vocabulary',
        component: VocabularyPage,
        meta: { title: 'Từ vựng' },
      },
      {
        path: 'grammar',
        name: 'student-grammar',
        component: GrammarPage,
        meta: { title: 'Ngữ pháp' },
      },
      {
        path: 'notifications',
        name: 'student-notifications',
        component: NotificationsPage,
        meta: { title: 'Thông báo' },
      },
      {
        path: 'profile',
        name: 'student-profile',
        component: ProfilePage,
        meta: { title: 'Hồ sơ cá nhân' },
      },
      {
        path: 'reviews',
        name: 'student-reviews',
        component: ReviewsPage,
        meta: { title: 'Đánh giá của tôi' },
      },
      {
        path: 'settings',
        name: 'student-settings',
        component: SettingsPage,
        meta: { title: 'Cài đặt' },
      },
    ],
  },
]

export default studentRoutes