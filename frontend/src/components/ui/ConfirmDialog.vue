<template>
  <AppModal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    size="sm"
    :persistent="loading"
  >
    <div class="text-center py-2">
      <div :class="['w-14 h-14 rounded-3xl flex items-center justify-center mx-auto mb-4', iconBg]">
        <svg v-if="type === 'danger'" class="w-7 h-7 text-danger-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <svg v-else class="w-7 h-7 text-warning-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <h3 class="font-display text-lg font-bold text-navy mb-2">{{ title }}</h3>
      <p class="text-sm text-slate-500 leading-relaxed">{{ message }}</p>
    </div>
    <template #footer>
      <AppButton variant="secondary" size="sm" @click="$emit('update:modelValue', false)" :disabled="loading">
        {{ cancelText }}
      </AppButton>
      <AppButton
        :variant="type === 'danger' ? 'danger' : 'primary'"
        size="sm"
        :loading="loading"
        @click="$emit('confirm')"
      >
        {{ confirmText }}
      </AppButton>
    </template>
  </AppModal>
</template>

<script setup>
import { computed } from 'vue'
import AppModal from './AppModal.vue'
import AppButton from './AppButton.vue'

const props = defineProps({
  modelValue:  Boolean,
  title:       { type: String, default: 'Xác nhận' },
  message:     { type: String, default: 'Bạn có chắc chắn muốn thực hiện hành động này?' },
  type:        { type: String, default: 'danger' },
  confirmText: { type: String, default: 'Xác nhận' },
  cancelText:  { type: String, default: 'Hủy' },
  loading:     Boolean,
})
defineEmits(['update:modelValue', 'confirm'])

const iconBg = computed(() => props.type === 'danger' ? 'bg-danger-50' : 'bg-warning-50')
</script>
