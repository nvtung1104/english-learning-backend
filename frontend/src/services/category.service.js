import api from './axios'

export default {
  list: () => api.get('/categories'),
}
