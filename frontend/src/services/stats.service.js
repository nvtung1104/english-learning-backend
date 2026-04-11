import api from './axios'

export default {
  public:  () => api.get('/stats'),
  student: () => api.get('/stats/student'),
  teacher: () => api.get('/stats/teacher'),
}
