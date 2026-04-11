<template>
  <UploadInput
    :model-value="modelValue"
    :type="fileType"
    :folder="uploadFolder"
    :placeholder="placeholder"
    :show-url="true"
    @update:model-value="$emit('update:modelValue', $event)"
  />
</template>

<script setup>
import UploadInput from './UploadInput.vue'
defineProps({
  modelValue:   { type: String, default: '' },
  fileType:     { type: String, default: 'file' },
  uploadFolder: { type: String, default: 'materials' },
  placeholder:  { type: String, default: '' },
})
defineEmits(['update:modelValue'])
</script>
