import { createRouter, createWebHistory } from 'vue-router'

import publicRoutes from './modules/public.routes'
import authRoutes from './modules/auth.routes'
import studentRoutes from './modules/student.routes'
import teacherRoutes from './modules/teacher.routes'
import adminRoutes from './modules/admin.routes'
import { setupRouterGuards } from './guards'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...publicRoutes,
    ...authRoutes,
    ...studentRoutes,
    ...teacherRoutes,
    ...adminRoutes,
  ],
})

setupRouterGuards(router)

export default router