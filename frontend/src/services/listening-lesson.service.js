import api from './axios'

export default {
  list: (params) => api.get('/listening-lessons', { params }),
  get: (id) => api.get(`/listening-lessons/${id}`),
}
