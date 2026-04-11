import api from './axios'

export default {
  list: (params) => api.get('/courses', { params }),
  get: (slug) => api.get(`/courses/${slug}`),
  sections: (courseId) => api.get(`/courses/${courseId}/sections`),
  reviews: (courseId, params) => api.get(`/courses/${courseId}/reviews`, { params }),
}
