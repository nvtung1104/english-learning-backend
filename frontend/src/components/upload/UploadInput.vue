<template>
  <div class="upload-input">

    <!-- IMAGE -->
    <template v-if="type === 'image'">
      <div class="flex items-start gap-4">
        <!-- Preview -->
        <div class="shrink-0">
          <img
            v-if="previewSrc"
            :src="previewSrc"
            class="h-20 w-28 rounded-2xl object-cover ring-2 ring-[var(--border)]"
            alt="preview"
          />
          <div
            v-else
            class="grid h-20 w-28 place-items-center rounded-2xl text-3xl"
            style="background: var(--surface-strong); border: 2px dashed var(--border)"
          >🖼️</div>
        </div>

        <!-- Controls -->
        <div class="flex flex-col gap-2">
          <label
            class="upload-btn inline-flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition"
            :class="{ 'opacity-60 pointer-events-none': uploading }"
          >
            <span>{{ uploading ? '⏳' : '📷' }}</span>
            <span>{{ uploading ? 'Đang tải lên...' : 'Chọn ảnh' }}</span>
            <input
              type="file"
              accept="image/jpeg,image/png,image/webp,image/gif"
              class="hidden"
              :disabled="uploading"
              @change="handleFile"
            />
          </label>
          <p class="text-xs theme-text-muted">JPG, PNG, WebP, GIF · Tối đa 10MB</p>
          <p v-if="previewSrc && !uploading" class="text-xs text-emerald-500 font-medium">✓ Đã chọn ảnh</p>
        </div>
      </div>
    </template>

    <!-- AUDIO -->
    <template v-else-if="type === 'audio'">
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-3 flex-wrap">
          <label
            class="upload-btn inline-flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition"
            :class="{ 'opacity-60 pointer-events-none': uploading }"
          >
            <span>{{ uploading ? '⏳' : '🎵' }}</span>
            <span>{{ uploading ? 'Đang tải lên...' : 'Chọn audio' }}</span>
            <input
              type="file"
              accept="audio/*,.mp3,.wav,.ogg,.m4a,.aac"
              class="hidden"
              :disabled="uploading"
              @change="handleFile"
            />
          </label>
          <span v-if="filename" class="text-xs theme-text-muted truncate max-w-[200px]">📎 {{ filename }}</span>
          <p v-else class="text-xs theme-text-muted">MP3, WAV, OGG, M4A</p>
        </div>
        <audio v-if="modelValue" :src="$storage(modelValue)" controls class="w-full h-9 rounded-xl" />
      </div>
    </template>

    <!-- VIDEO -->
    <template v-else-if="type === 'video'">
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-3 flex-wrap">
          <label
            class="upload-btn inline-flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition"
            :class="{ 'opacity-60 pointer-events-none': uploading }"
          >
            <span>{{ uploading ? '⏳' : '🎬' }}</span>
            <span>{{ uploading ? 'Đang tải lên...' : 'Chọn video' }}</span>
            <input
              type="file"
              accept="video/*,.mp4,.mov,.webm,.avi"
              class="hidden"
              :disabled="uploading"
              @change="handleFile"
            />
          </label>
          <span v-if="filename" class="text-xs theme-text-muted truncate max-w-[200px]">📎 {{ filename }}</span>
          <p v-else class="text-xs theme-text-muted">MP4, MOV, WebM</p>
        </div>
        <div v-if="progress > 0 && progress < 100" class="h-1.5 rounded-full overflow-hidden" style="background: var(--border)">
          <div class="h-full rounded-full transition-all" style="background: var(--accent)" :style="{ width: progress + '%' }"></div>
        </div>
      </div>
    </template>

    <!-- FILE (generic) -->
    <template v-else>
      <div class="flex items-center gap-3 flex-wrap">
        <label
          class="upload-btn inline-flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition"
          :class="{ 'opacity-60 pointer-events-none': uploading }"
        >
          <span>{{ uploading ? '⏳' : '📎' }}</span>
          <span>{{ uploading ? 'Đang tải lên...' : 'Chọn file' }}</span>
          <input type="file" class="hidden" :disabled="uploading" @change="handleFile" />
        </label>
        <span v-if="filename" class="text-xs theme-text-muted truncate max-w-[200px]">{{ filename }}</span>
      </div>
    </template>

    <!-- URL fallback -->
    <details v-if="showUrl" class="mt-2">
      <summary class="cursor-pointer text-xs theme-text-muted select-none hover:theme-text transition">
        Hoặc nhập URL trực tiếp
      </summary>
      <input
        :value="modelValue"
        type="url"
        class="url-input mt-2 w-full rounded-2xl px-3 py-2 text-sm outline-none"
        :placeholder="placeholder || 'https://...'"
        @input="onUrlInput"
      />
    </details>

    <!-- Error -->
    <p v-if="uploadError" class="mt-1.5 text-xs text-red-500 font-medium">⚠ {{ uploadError }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { storageUrl } from '@/utils/url'

const props = defineProps({
  modelValue: { type: String, default: '' },
  type:       { type: String, default: 'image' },
  folder:     { type: String, default: 'thumbnails' },
  placeholder:{ type: String, default: '' },
  showUrl:    { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

const uploading  = ref(false)
const uploadError = ref('')
const preview    = ref('')
const filename   = ref('')
const progress   = ref(0)

const previewSrc = computed(() => {
  if (props.type !== 'image') return ''
  return preview.value || storageUrl(props.modelValue)
})

async function handleFile(e) {
  const file = e.target.files?.[0]
  if (!file) return

  uploadError.value = ''
  uploading.value = true
  filename.value = file.name

  // Blob preview ngay lập tức
  if (props.type === 'image') {
    preview.value = URL.createObjectURL(file)
  }

  try {
    const url = await doUpload(file)
    if (url) {
      // Cập nhật preview thành URL thật từ server
      if (props.type === 'image') {
        preview.value = storageUrl(url)
      }
      emit('update:modelValue', url)
    } else {
      // Upload không trả về URL
      uploadError.value = 'Server không trả về URL ảnh. Kiểm tra lại cấu hình.'
      if (props.type === 'image') preview.value = ''
    }
  } catch (err) {
    uploadError.value = err.message || 'Upload thất bại, thử lại.'
    if (props.type === 'image') preview.value = ''
    filename.value = ''
  } finally {
    uploading.value = false
    e.target.value = ''
  }
}

async function doUpload(file) {
  const token = localStorage.getItem('token') ?? ''
  const fd = new FormData()

  let endpoint = ''
  let fieldName = ''

  if (props.type === 'image') {
    endpoint = '/upload-image'
    fieldName = 'image'
    fd.append('type', props.folder || 'thumbnails')
  } else if (props.type === 'audio') {
    endpoint = '/upload-audio'
    fieldName = 'audio'
  } else if (props.type === 'video') {
    endpoint = '/upload-video'
    fieldName = 'video'
    progress.value = 10
  } else {
    endpoint = '/upload-file'
    fieldName = 'file'
    fd.append('type', props.folder || 'materials')
  }

  fd.append(fieldName, file)

  const apiBase = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

  const res = await fetch(`${apiBase}${endpoint}`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
    body: fd,
  })

  const json = await res.json()

  if (!res.ok) {
    throw new Error(json.message ?? `Lỗi server ${res.status}`)
  }

  const url = json.data?.url ?? json.url ?? null
  if (!url) {
    throw new Error('Server không trả về đường dẫn file.')
  }

  if (props.type === 'video') progress.value = 100

  return url
}

function onUrlInput(e) {
  preview.value = ''
  emit('update:modelValue', e.target.value)
}
</script>

<style scoped>
.upload-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.upload-btn:hover { background: var(--surface-strong); }

.url-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}
.url-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
  outline: none;
}
</style>
