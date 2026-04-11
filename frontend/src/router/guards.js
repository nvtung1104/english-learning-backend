function getToken() {
  return localStorage.getItem('token')
}

function getUser() {
  try {
    return JSON.parse(localStorage.getItem('user') || 'null')
  } catch {
    return null
  }
}

// user.roles là array từ API (e.g. ['admin'])
function getPrimaryRole(user) {
  return user?.roles?.[0] ?? null
}

export function setupRouterGuards(router) {
  router.beforeEach((to, from, next) => {
    const token = getToken()
    const user = getUser()
    const role = getPrimaryRole(user)

    if (to.meta?.title) {
      document.title = `${to.meta.title} | English Learning`
    } else {
      document.title = 'English Learning'
    }

    if (to.meta?.requiresAuth && !token) {
      return next({ name: 'login' })
    }

    if (to.meta?.guestOnly && token && user) {
      if (role === 'admin') return next({ name: 'admin-dashboard' })
      if (role === 'teacher') return next({ name: 'teacher-dashboard' })
      return next({ name: 'student-dashboard' })
    }

    if (to.meta?.roles?.length) {
      if (!user || !to.meta.roles.includes(role)) {
        return next({ name: 'home' })
      }
    }

    next()
  })
}