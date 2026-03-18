<template>
  <div class="space-y-1.5">
    <label v-if="label" :for="id" class="label">
      {{ label }}
      <span v-if="required" class="text-danger-600 ml-0.5">*</span>
    </label>
    <div class="relative">
      <select
        :id="id"
        :value="modelValue"
        :disabled="disabled"
        :class="['input appearance-none pr-9 cursor-pointer', error ? 'input-error' : '', disabled ? 'opacity-60 cursor-not-allowed' : '']"
        @change="$emit('update:modelValue', $event.target.value)"
      >
        <option v-if="placeholder" value="" disabled :selected="!modelValue">{{ placeholder }}</option>
        <option v-for="opt in options" :key="opt.value ?? opt" :value="opt.value ?? opt">
          {{ opt.label ?? opt }}
        </option>
      </select>
      <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>
    </div>
    <p v-if="error" class="text-xs text-danger-600">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue:  [String, Number],
  options:     { type: Array, default: () => [] },
  label:       String,
  id:          String,
  placeholder: String,
  error:       String,
  disabled:    Boolean,
  required:    Boolean,
})
defineEmits(['update:modelValue'])
</script>
