<template>
  <UploadInput
    :model-value="modelValue"
    type="audio"
    :placeholder="placeholder"
    :show-url="true"
    @update:model-value="$emit('update:modelValue', $event)"
  />
</template>

<script setup>
import UploadInput from './UploadInput.vue'
defineProps({ modelValue: { type: String, default: '' }, placeholder: { type: String, default: '' } })
defineEmits(['update:modelValue'])
</script>
