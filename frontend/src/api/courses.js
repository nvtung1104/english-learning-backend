import api from './axios'

export const getCourses = (params) => api.get('/courses', { params })
export const getCourse = (id) => api.get(`/courses/${id}`)
export const enrollCourse = (id) => api.post(`/student/courses/${id}/enroll`)
