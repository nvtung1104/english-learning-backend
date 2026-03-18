import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../api/axios'
import { login as apiLogin, register as apiRegister, logout as apiLogout } from '../api/auth'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('token') || null)
  const user  = ref(JSON.parse(localStorage.getItem('user') || 'null'))

  const isLoggedIn = computed(() => !!token.value)
  const isAdmin    = computed(() => user.value?.roles?.includes('admin'))
  const isTeacher  = computed(() => user.value?.roles?.includes('teacher') || isAdmin.value)
  const isStudent  = computed(() => user.value?.roles?.includes('student'))

  // Primary role for routing (first role wins: admin > teacher > student)
  const role = computed(() => {
    const roles = user.value?.roles || []
    if (roles.includes('admin'))   return 'admin'
    if (roles.includes('teacher')) return 'teacher'
    if (roles.includes('student')) return 'student'
    return null
  })

  function setAuth(data) {
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
  }

  async function login(credentials) {
    const res = await apiLogin(credentials)
    setAuth(res.data)
    return res.data
  }

  async function register(payload) {
    const res = await apiRegister(payload)
    setAuth(res.data)
    return res.data
  }

  function logout() {
    try { apiLogout() } catch {}
    token.value = null
    user.value  = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    delete api.defaults.headers.common['Authorization']
  }

  // Restore token on init
  if (token.value) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
  }

  return { token, user, role, isLoggedIn, isAdmin, isTeacher, isStudent, setAuth, login, register, logout }
})
