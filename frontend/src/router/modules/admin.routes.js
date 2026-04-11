import AdminLayout from '@/layouts/AdminLayout.vue'

import DashboardPage from '@/pages/admin/DashboardPage.vue'

import UserListPage from '@/pages/admin/users/UserListPage.vue'
import UserCreatePage from '@/pages/admin/users/UserCreatePage.vue'
import UserEditPage from '@/pages/admin/users/UserEditPage.vue'

import RoleListPage from '@/pages/admin/roles/RoleListPage.vue'
import PermissionListPage from '@/pages/admin/roles/PermissionListPage.vue'
import RolePermissionPage from '@/pages/admin/roles/RolePermissionPage.vue'

import CourseListPage from '@/pages/admin/courses/CourseListPage.vue'
import CourseCreatePage from '@/pages/admin/courses/CourseCreatePage.vue'
import CourseEditPage from '@/pages/admin/courses/CourseEditPage.vue'
import CategoryListPage from '@/pages/admin/courses/CategoryListPage.vue'
import LevelListPage from '@/pages/admin/courses/LevelListPage.vue'
import SectionListPage from '@/pages/admin/courses/SectionListPage.vue'
import EnrollmentListPage from '@/pages/admin/courses/EnrollmentListPage.vue'

import LessonListPage from '@/pages/admin/lessons/LessonListPage.vue'
import LessonCreatePage from '@/pages/admin/lessons/LessonCreatePage.vue'
import LessonEditPage from '@/pages/admin/lessons/LessonEditPage.vue'
import LessonTypePage from '@/pages/admin/lessons/LessonTypePage.vue'
import MaterialPage from '@/pages/admin/lessons/MaterialPage.vue'
import VocabularyPage from '@/pages/admin/lessons/VocabularyPage.vue'
import GrammarTopicPage from '@/pages/admin/lessons/GrammarTopicPage.vue'
import ListeningLessonPage from '@/pages/admin/lessons/ListeningLessonPage.vue'

import QuizListPage from '@/pages/admin/quizzes/QuizListPage.vue'
import QuizCreatePage from '@/pages/admin/quizzes/QuizCreatePage.vue'
import QuizEditPage from '@/pages/admin/quizzes/QuizEditPage.vue'
import QuizQuestionPage from '@/pages/admin/quizzes/QuizQuestionPage.vue'
import QuizAnswerPage from '@/pages/admin/quizzes/QuizAnswerPage.vue'
import QuizAttemptPage from '@/pages/admin/quizzes/QuizAttemptPage.vue'

import ReviewListPage from '@/pages/admin/reviews/ReviewListPage.vue'
import NotificationListPage from '@/pages/admin/notifications/NotificationListPage.vue'

const adminRoutes = [
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, roles: ['admin'] },
    children: [
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: DashboardPage,
        meta: { title: 'Dashboard quản trị' },
      },

      {
        path: 'users',
        name: 'admin-users',
        component: UserListPage,
        meta: { title: 'Danh sách người dùng' },
      },
      {
        path: 'users/create',
        name: 'admin-user-create',
        component: UserCreatePage,
        meta: { title: 'Thêm người dùng' },
      },
      {
        path: 'users/:id/edit',
        name: 'admin-user-edit',
        component: UserEditPage,
        props: true,
        meta: { title: 'Sửa người dùng' },
      },

      {
        path: 'roles',
        name: 'admin-roles',
        component: RoleListPage,
        meta: { title: 'Vai trò' },
      },
      {
        path: 'permissions',
        name: 'admin-permissions',
        component: PermissionListPage,
        meta: { title: 'Quyền hạn' },
      },
      {
        path: 'role-permissions',
        name: 'admin-role-permissions',
        component: RolePermissionPage,
        meta: { title: 'Phân quyền vai trò' },
      },

      {
        path: 'courses',
        name: 'admin-courses',
        component: CourseListPage,
        meta: { title: 'Khóa học' },
      },
      {
        path: 'courses/create',
        name: 'admin-course-create',
        component: CourseCreatePage,
        meta: { title: 'Tạo khóa học' },
      },
      {
        path: 'courses/:id/edit',
        name: 'admin-course-edit',
        component: CourseEditPage,
        props: true,
        meta: { title: 'Sửa khóa học' },
      },
      {
        path: 'course-categories',
        name: 'admin-course-categories',
        component: CategoryListPage,
        meta: { title: 'Danh mục khóa học' },
      },
      {
        path: 'course-levels',
        name: 'admin-course-levels',
        component: LevelListPage,
        meta: { title: 'Cấp độ khóa học' },
      },
      {
        path: 'course-sections',
        name: 'admin-course-sections',
        component: SectionListPage,
        meta: { title: 'Section khóa học' },
      },
      {
        path: 'enrollments',
        name: 'admin-enrollments',
        component: EnrollmentListPage,
        meta: { title: 'Ghi danh khóa học' },
      },

      {
        path: 'lessons',
        name: 'admin-lessons',
        component: LessonListPage,
        meta: { title: 'Bài học' },
      },
      {
        path: 'lessons/create',
        name: 'admin-lesson-create',
        component: LessonCreatePage,
        meta: { title: 'Tạo bài học' },
      },
      {
        path: 'lessons/:id/edit',
        name: 'admin-lesson-edit',
        component: LessonEditPage,
        props: true,
        meta: { title: 'Sửa bài học' },
      },
      {
        path: 'lesson-types',
        name: 'admin-lesson-types',
        component: LessonTypePage,
        meta: { title: 'Loại bài học' },
      },
      {
        path: 'lesson-materials',
        name: 'admin-lesson-materials',
        component: MaterialPage,
        meta: { title: 'Tài liệu bài học' },
      },
      {
        path: 'vocabularies',
        name: 'admin-vocabularies',
        component: VocabularyPage,
        meta: { title: 'Từ vựng' },
      },
      {
        path: 'grammar-topics',
        name: 'admin-grammar-topics',
        component: GrammarTopicPage,
        meta: { title: 'Chủ đề ngữ pháp' },
      },
      {
        path: 'listening-lessons',
        name: 'admin-listening-lessons',
        component: ListeningLessonPage,
        meta: { title: 'Listening lesson' },
      },

      {
        path: 'quizzes',
        name: 'admin-quizzes',
        component: QuizListPage,
        meta: { title: 'Quiz' },
      },
      {
        path: 'quizzes/create',
        name: 'admin-quiz-create',
        component: QuizCreatePage,
        meta: { title: 'Tạo quiz' },
      },
      {
        path: 'quizzes/:id/edit',
        name: 'admin-quiz-edit',
        component: QuizEditPage,
        props: true,
        meta: { title: 'Sửa quiz' },
      },
      {
        path: 'quiz-questions',
        name: 'admin-quiz-questions',
        component: QuizQuestionPage,
        meta: { title: 'Câu hỏi quiz' },
      },
      {
        path: 'quiz-answers',
        name: 'admin-quiz-answers',
        component: QuizAnswerPage,
        meta: { title: 'Đáp án quiz' },
      },
      {
        path: 'quiz-attempts',
        name: 'admin-quiz-attempts',
        component: QuizAttemptPage,
        meta: { title: 'Lượt làm quiz' },
      },

      {
        path: 'reviews',
        name: 'admin-reviews',
        component: ReviewListPage,
        meta: { title: 'Đánh giá' },
      },
      {
        path: 'notifications',
        name: 'admin-notifications',
        component: NotificationListPage,
        meta: { title: 'Thông báo' },
      },
    ],
  },
]

export default adminRoutes