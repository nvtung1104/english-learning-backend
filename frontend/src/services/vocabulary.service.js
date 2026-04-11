import api from './axios'

export default {
  list: (params) => api.get('/vocabularies', { params }),
  get: (id) => api.get(`/vocabularies/${id}`),
}
