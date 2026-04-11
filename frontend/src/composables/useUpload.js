import { ref } from 'vue'
import { storageUrl } from '@/utils/url'

const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

function getToken() {
  return localStorage.getItem('token') ?? ''
}

async function uploadFile(endpoint, fieldName, file, extraFields = {}) {
  const fd = new FormData()
  fd.append(fieldName, file)
  for (const [k, v] of Object.entries(extraFields)) fd.append(k, v)

  const res = await fetch(`${API_BASE}${endpoint}`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${getToken()}`,
      Accept: 'application/json',
      // NO Content-Type — let browser set multipart boundary automatically
    },
    body: fd,
  })

  const json = await res.json()
  if (!res.ok) throw new Error(json.message ?? `HTTP ${res.status}`)
  return json
}

/**
 * useImageUpload — upload image via multipart FormData
 */
export function useImageUpload(type = 'thumbnails') {
  const uploading = ref(false)
  const error = ref('')
  const preview = ref('')

  async function upload(file) {
    if (!file) return null
    error.value = ''
    uploading.value = true
    preview.value = URL.createObjectURL(file)
    try {
      const json = await uploadFile('/upload-image', 'image', file, { type })
      const url = json.data?.url ?? ''
      preview.value = storageUrl(url)
      return url
    } catch (e) {
      error.value = e.response?.data?.message ?? e.message ?? 'Upload failed'
      preview.value = ''
      return null
    } finally {
      uploading.value = false
    }
  }

  function reset() {
    preview.value = ''
    error.value = ''
  }

  return { uploading, error, preview, upload, reset }
}

/**
 * useVideoUpload — upload video via multipart FormData
 */
export function useVideoUpload() {
  const uploading = ref(false)
  const progress = ref(0)
  const error = ref('')
  const filename = ref('')

  async function upload(file) {
    if (!file) return null
    error.value = ''
    uploading.value = true
    progress.value = 10
    filename.value = file.name
    try {
      const json = await uploadFile('/upload-video', 'video', file)
      progress.value = 100
      return json.data?.url ?? ''
    } catch (e) {
      error.value = e.response?.data?.message ?? e.message ?? 'Upload failed'
      filename.value = ''
      return null
    } finally {
      uploading.value = false
    }
  }

  function reset() {
    filename.value = ''
    error.value = ''
    progress.value = 0
  }

  return { uploading, progress, error, filename, upload, reset }
}

/**
 * useAudioUpload — upload audio via multipart FormData
 */
export function useAudioUpload() {
  const uploading = ref(false)
  const error = ref('')
  const filename = ref('')

  async function upload(file) {
    if (!file) return null
    error.value = ''
    uploading.value = true
    filename.value = file.name
    try {
      const json = await uploadFile('/upload-audio', 'audio', file)
      return json.data?.url ?? ''
    } catch (e) {
      error.value = e.response?.data?.message ?? e.message ?? 'Upload failed'
      filename.value = ''
      return null
    } finally {
      uploading.value = false
    }
  }

  function reset() { filename.value = ''; error.value = '' }

  return { uploading, error, filename, upload, reset }
}
