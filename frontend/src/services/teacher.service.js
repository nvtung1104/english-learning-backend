import api from './axios'

// Courses (teacher-owned)
export const courseService = {
  list:    (params) => api.get('/teacher/my-courses', { params }),
  get:     (id)     => api.get(`/courses/${id}`),
  create:  (data)   => api.post('/teacher/courses', data),
  update:  (id, data) => api.put(`/teacher/courses/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/courses/${id}`),
}

// Sections
export const sectionService = {
  byCourse: (courseId) => api.get(`/courses/${courseId}/sections`),
  create:   (data)     => api.post('/teacher/sections', data),
  update:   (id, data) => api.put(`/teacher/sections/${id}`, data),
  destroy:  (id)       => api.delete(`/teacher/sections/${id}`),
}

// Lessons
export const lessonService = {
  list:    (params) => api.get('/teacher/lessons', { params }),
  get:     (id)     => api.get(`/lessons/${id}`),
  create:  (data)   => api.post('/teacher/lessons', data),
  update:  (id, data) => api.put(`/teacher/lessons/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/lessons/${id}`),
}

// Lesson Materials
export const materialService = {
  list:    (params) => api.get('/teacher/lesson-materials', { params }),
  create:  (data)   => api.post('/teacher/lesson-materials', data),
  update:  (id, data) => api.put(`/teacher/lesson-materials/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/lesson-materials/${id}`),
}

// Vocabularies
export const vocabularyService = {
  list:    (params) => api.get('/teacher/vocabularies', { params }),
  create:  (data)   => api.post('/teacher/vocabularies', data),
  update:  (id, data) => api.put(`/teacher/vocabularies/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/vocabularies/${id}`),
}

// Grammar Topics
export const grammarService = {
  list:    (params) => api.get('/teacher/grammar-topics', { params }),
  create:  (data)   => api.post('/teacher/grammar-topics', data),
  update:  (id, data) => api.put(`/teacher/grammar-topics/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/grammar-topics/${id}`),
}

// Listening Lessons
export const listeningService = {
  list:    (params) => api.get('/teacher/listening-lessons', { params }),
  create:  (data)   => api.post('/teacher/listening-lessons', data),
  update:  (id, data) => api.put(`/teacher/listening-lessons/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/listening-lessons/${id}`),
}

// Quizzes
export const quizService = {
  list:    (params) => api.get('/teacher/quizzes', { params }),
  get:     (id)     => api.get(`/quizzes/${id}`),
  create:  (data)   => api.post('/teacher/quizzes', data),
  update:  (id, data) => api.put(`/teacher/quizzes/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/quizzes/${id}`),
}

// Quiz Questions
export const questionService = {
  list:        (params)   => api.get('/teacher/quiz-questions', { params }),
  create:      (data)     => api.post('/teacher/quiz-questions', data),
  update:      (id, data) => api.put(`/teacher/quiz-questions/${id}`, data),
  destroy:     (id)       => api.delete(`/teacher/quiz-questions/${id}`),
  import:      (formData) => api.post('/teacher/quiz-questions/import', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  }),
  importDocx:  (data)     => api.post('/teacher/quiz-questions/import-docx', data),
  parseDocx:   (formData) => api.post('/teacher/quiz-questions/parse-docx', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  }),
}

// Quiz Answers
export const answerService = {
  list:    (params) => api.get('/teacher/quiz-answers', { params }),
  create:  (data)   => api.post('/teacher/quiz-answers', data),
  update:  (id, data) => api.put(`/teacher/quiz-answers/${id}`, data),
  destroy: (id)     => api.delete(`/teacher/quiz-answers/${id}`),
}

// Enrollments (teacher view)
export const enrollmentService = {
  list: (params) => api.get('/teacher/enrollments', { params }),
}

// Notifications (teacher uses same student notification routes)
export const notificationService = {
  list:     (params) => api.get('/student/notifications', { params }),
  markRead: (id)     => api.post(`/student/notifications/${id}/read`),
}

// Categories & Levels (for selects)
export const categoryService = {
  list: () => api.get('/categories'),
}
export const levelService = {
  list: () => api.get('/levels'),
}
export const lessonTypeService = {
  list: () => api.get('/lesson-types?all=1'),
}

// Reviews (read-only for teacher)
export const reviewService = {
  byCourse: (courseId, params) => api.get(`/courses/${courseId}/reviews`, { params }),
}

// Student progress (teacher view)
export const progressService = {
  list: (params) => api.get('/teacher/lesson-progress', { params }),
}

// Quiz attempts (teacher view)
export const attemptService = {
  list: (params) => api.get('/teacher/quiz-attempts', { params }),
}
