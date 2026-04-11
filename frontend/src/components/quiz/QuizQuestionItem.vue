<template>
  <div class="question-card rounded-[24px] p-5 space-y-4">
    <!-- Header -->
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-2 flex-wrap">
        <span class="question-number">{{ index + 1 }}</span>
        <span class="type-badge rounded-full px-2.5 py-1 text-xs font-semibold">
          {{ typeLabel }}
        </span>
      </div>
      <button v-if="removable" @click="$emit('remove')" class="danger-text-btn text-xs" type="button">
        Xóa
      </button>
    </div>

    <!-- Question text -->
    <div class="form-group">
      <label class="form-label">Câu hỏi</label>
      <textarea
        :value="modelValue.question"
        @input="update('question', $event.target.value)"
        class="form-input"
        rows="2"
        placeholder="Nhập nội dung câu hỏi..."
      />
    </div>

    <!-- Answers -->
    <div class="space-y-2">
      <label class="form-label">Đáp án — chọn đáp án đúng</label>
      <div
        v-for="(ans, i) in modelValue.answers"
        :key="i"
        class="flex items-center gap-3"
      >
        <input
          type="radio"
          :name="`correct-${uid}`"
          :checked="ans.is_correct"
          @change="setCorrect(i)"
          style="accent-color: var(--accent)"
          class="shrink-0"
        />
        <span class="text-xs font-bold theme-text-muted w-5 shrink-0">{{ labels[i] }}.</span>
        <input
          :value="ans.answer_text"
          @input="updateAnswer(i, 'answer_text', $event.target.value)"
          type="text"
          class="form-input flex-1 text-sm"
          :placeholder="`Đáp án ${labels[i]}`"
        />
      </div>
    </div>

    <!-- Validation hint -->
    <p v-if="!hasCorrect" class="text-xs" style="color: var(--danger)">
      ⚠ Chưa chọn đáp án đúng
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: Object, required: true },
  index:      { type: Number, required: true },
  removable:  { type: Boolean, default: true },
})

const emit = defineEmits(['update:modelValue', 'remove'])

// Unique id for radio group isolation
const uid = `q-${props.index}-${Math.random().toString(36).slice(2, 7)}`

const labels = ['A', 'B', 'C', 'D', 'E', 'F']

const typeLabel = computed(() => {
  const map = {
    multiple_choice: 'Trắc nghiệm',
    true_false:      'Đúng / Sai',
    fill_blank:      'Điền từ',
  }
  return map[props.modelValue.question_type] ?? 'Trắc nghiệm'
})

const hasCorrect = computed(() => props.modelValue.answers?.some(a => a.is_correct))

function update(key, value) {
  emit('update:modelValue', { ...props.modelValue, [key]: value })
}

function updateAnswer(index, key, value) {
  const answers = props.modelValue.answers.map((a, i) =>
    i === index ? { ...a, [key]: value } : a
  )
  emit('update:modelValue', { ...props.modelValue, answers })
}

function setCorrect(index) {
  const answers = props.modelValue.answers.map((a, i) => ({
    ...a,
    is_correct: i === index,
  }))
  emit('update:modelValue', { ...props.modelValue, answers })
}
</script>

<style scoped>
.question-card {
  border: 1px solid var(--border);
  background: var(--surface-muted);
}
.question-number {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 1.75rem;
  height: 1.75rem;
  border-radius: 50%;
  font-size: 0.75rem;
  font-weight: 700;
  background: var(--accent);
  color: #fff;
  flex-shrink: 0;
}
.type-badge {
  background: color-mix(in srgb, var(--accent) 12%, var(--surface-muted));
  color: var(--accent);
}
.form-group { display: flex; flex-direction: column; gap: 0.375rem; }
.form-label { font-size: 0.8125rem; font-weight: 600; color: var(--text); }
.form-input {
  border: 1px solid var(--border);
  background: var(--surface-strong);
  color: var(--text);
  border-radius: 0.875rem;
  padding: 0.5rem 0.875rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
  resize: vertical;
}
.form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--ring); }
.danger-text-btn { color: var(--danger); font-weight: 600; }
.danger-text-btn:hover { opacity: 0.75; }
</style>
