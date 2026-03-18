<template>
  <div :class="['space-y-1.5', block ? 'w-full' : '']">
    <label v-if="label" :for="id" class="label">
      {{ label }}
      <span v-if="required" class="text-danger-600 ml-0.5">*</span>
    </label>
    <div class="relative">
      <div v-if="$slots['icon-left']"
        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
        <slot name="icon-left" />
      </div>
      <input
        :id="id"
        v-bind="$attrs"
        :value="modelValue"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :class="[
          'input',
          $slots['icon-left'] ? 'pl-10' : '',
          $slots['icon-right'] ? 'pr-10' : '',
          error ? 'input-error' : '',
          disabled ? 'opacity-60 cursor-not-allowed bg-cream-soft' : '',
        ]"
        @input="$emit('update:modelValue', $event.target.value)"
      />
      <div v-if="$slots['icon-right']"
        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
        <slot name="icon-right" />
      </div>
    </div>
    <p v-if="error" class="flex items-center gap-1 text-xs text-danger-600">
      <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
      </svg>
      {{ error }}
    </p>
    <p v-else-if="hint" class="text-xs text-slate-400">{{ hint }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue:  [String, Number],
  label:       String,
  id:          String,
  type:        { type: String, default: 'text' },
  placeholder: String,
  error:       String,
  hint:        String,
  disabled:    Boolean,
  required:    Boolean,
  block:       { type: Boolean, default: true },
})
defineEmits(['update:modelValue'])
defineOptions({ inheritAttrs: false })
</script>
