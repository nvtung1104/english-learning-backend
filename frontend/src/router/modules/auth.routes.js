import AuthLayout from '@/layouts/AuthLayout.vue'

import LoginPage from '@/pages/auth/LoginPage.vue'
import RegisterPage from '@/pages/auth/RegisterPage.vue'
import ForgotPasswordPage from '@/pages/auth/ForgotPasswordPage.vue'
import ResetPasswordPage from '@/pages/auth/ResetPasswordPage.vue'

const authRoutes = [
  {
    path: '/',
    component: AuthLayout,
    children: [
      {
        path: 'login',
        name: 'login',
        component: LoginPage,
        meta: { title: 'Đăng nhập', guestOnly: true },
      },
      {
        path: 'register',
        name: 'register',
        component: RegisterPage,
        meta: { title: 'Đăng ký', guestOnly: true },
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: ForgotPasswordPage,
        meta: { title: 'Quên mật khẩu', guestOnly: true },
      },
      {
        path: 'reset-password',
        name: 'reset-password',
        component: ResetPasswordPage,
        meta: { title: 'Đặt lại mật khẩu', guestOnly: true },
      },
    ],
  },
]

export default authRoutes