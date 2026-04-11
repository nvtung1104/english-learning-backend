import TeacherLayout from '@/layouts/TeacherLayout.vue'

import DashboardPage from '@/pages/teacher/DashboardPage.vue'
import MyCoursesPage from '@/pages/teacher/MyCoursesPage.vue'
import CourseCreatePage from '@/pages/teacher/CourseCreatePage.vue'
import CourseEditPage from '@/pages/teacher/CourseEditPage.vue'
import CourseSectionsPage from '@/pages/teacher/CourseSectionsPage.vue'
import LessonManagePage from '@/pages/teacher/LessonManagePage.vue'
import LessonMaterialPage from '@/pages/teacher/LessonMaterialPage.vue'
import VocabularyManagePage from '@/pages/teacher/VocabularyManagePage.vue'
import GrammarTopicManagePage from '@/pages/teacher/GrammarTopicManagePage.vue'
import ListeningLessonManagePage from '@/pages/teacher/ListeningLessonManagePage.vue'
import QuizManagePage from '@/pages/teacher/QuizManagePage.vue'
import QuizQuestionManagePage from '@/pages/teacher/QuizQuestionManagePage.vue'
import QuizAnswerManagePage from '@/pages/teacher/QuizAnswerManagePage.vue'
import StudentEnrollmentsPage from '@/pages/teacher/StudentEnrollmentsPage.vue'
import StudentProgressPage from '@/pages/teacher/StudentProgressPage.vue'
import StudentAttemptsPage from '@/pages/teacher/StudentAttemptsPage.vue'
import ReviewsPage from '@/pages/teacher/ReviewsPage.vue'
import NotificationsPage from '@/pages/teacher/NotificationsPage.vue'
import ProfilePage from '@/pages/teacher/ProfilePage.vue'

const teacherRoutes = [
  {
    path: '/teacher',
    component: TeacherLayout,
    meta: { requiresAuth: true, roles: ['teacher'] },
    children: [
      {
        path: 'dashboard',
        name: 'teacher-dashboard',
        component: DashboardPage,
        meta: { title: 'Dashboard giáo viên' },
      },
      {
        path: 'courses',
        name: 'teacher-courses',
        component: MyCoursesPage,
        meta: { title: 'Khóa học quản lý' },
      },
      {
        path: 'courses/create',
        name: 'teacher-course-create',
        component: CourseCreatePage,
        meta: { title: 'Tạo khóa học' },
      },
      {
        path: 'courses/:courseId/edit',
        name: 'teacher-course-edit',
        component: CourseEditPage,
        props: true,
        meta: { title: 'Sửa khóa học' },
      },
      {
        path: 'courses/:courseId/sections',
        name: 'teacher-course-sections',
        component: CourseSectionsPage,
        props: true,
        meta: { title: 'Quản lý section khóa học' },
      },
      {
        path: 'lessons',
        name: 'teacher-lessons',
        component: LessonManagePage,
        meta: { title: 'Quản lý bài học' },
      },
      {
        path: 'lesson-materials',
        name: 'teacher-lesson-materials',
        component: LessonMaterialPage,
        meta: { title: 'Tài liệu bài học' },
      },
      {
        path: 'vocabularies',
        name: 'teacher-vocabularies',
        component: VocabularyManagePage,
        meta: { title: 'Quản lý từ vựng' },
      },
      {
        path: 'grammar-topics',
        name: 'teacher-grammar-topics',
        component: GrammarTopicManagePage,
        meta: { title: 'Quản lý ngữ pháp' },
      },
      {
        path: 'listening-lessons',
        name: 'teacher-listening-lessons',
        component: ListeningLessonManagePage,
        meta: { title: 'Quản lý listening' },
      },
      {
        path: 'quizzes',
        name: 'teacher-quizzes',
        component: QuizManagePage,
        meta: { title: 'Quản lý quiz' },
      },
      {
        path: 'quiz-questions',
        name: 'teacher-quiz-questions',
        component: QuizQuestionManagePage,
        meta: { title: 'Quản lý câu hỏi quiz' },
      },
      {
        path: 'quiz-answers',
        name: 'teacher-quiz-answers',
        component: QuizAnswerManagePage,
        meta: { title: 'Quản lý đáp án quiz' },
      },
      {
        path: 'enrollments',
        name: 'teacher-enrollments',
        component: StudentEnrollmentsPage,
        meta: { title: 'Học viên đăng ký' },
      },
      {
        path: 'student-progress',
        name: 'teacher-student-progress',
        component: StudentProgressPage,
        meta: { title: 'Tiến độ học viên' },
      },
      {
        path: 'student-attempts',
        name: 'teacher-student-attempts',
        component: StudentAttemptsPage,
        meta: { title: 'Lượt làm bài học viên' },
      },
      {
        path: 'reviews',
        name: 'teacher-reviews',
        component: ReviewsPage,
        meta: { title: 'Đánh giá khóa học' },
      },
      {
        path: 'notifications',
        name: 'teacher-notifications',
        component: NotificationsPage,
        meta: { title: 'Thông báo' },
      },
      {
        path: 'profile',
        name: 'teacher-profile',
        component: ProfilePage,
        meta: { title: 'Hồ sơ giáo viên' },
      },
    ],
  },
]

export default teacherRoutes