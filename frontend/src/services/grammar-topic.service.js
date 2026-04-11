import api from './axios'

export default {
  list: (params) => api.get('/grammar-topics', { params }),
  get: (id) => api.get(`/grammar-topics/${id}`),
}
