<template>
  <div class="space-y-1.5">
    <label v-if="label" :for="id" class="label">
      {{ label }}
      <span v-if="required" class="text-danger-600 ml-0.5">*</span>
    </label>
    <textarea
      :id="id"
      :value="modelValue"
      :placeholder="placeholder"
      :rows="rows"
      :disabled="disabled"
      :class="['input resize-none', error ? 'input-error' : '', disabled ? 'opacity-60 cursor-not-allowed bg-cream-soft' : '']"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="text-xs text-danger-600">{{ error }}</p>
    <p v-else-if="hint" class="text-xs text-slate-400">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue:  String,
  label:       String,
  id:          String,
  placeholder: String,
  rows:        { type: Number, default: 4 },
  error:       String,
  hint:        String,
  disabled:    Boolean,
  required:    Boolean,
})
defineEmits(['update:modelValue'])
</script>
