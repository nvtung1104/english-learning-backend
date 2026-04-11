<template>
  <div class="page-section py-8 lg:py-10">
    <div class="section-container max-w-6xl space-y-6">
      <!-- Header -->
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <RouterLink to="/teacher/quizzes" class="text-sm theme-text-muted hover:theme-text">
            ← Quay lại Quiz
          </RouterLink>
          <h1 class="mt-2 text-3xl font-semibold tracking-tight theme-text">Câu hỏi Quiz</h1>
        </div>

        <div class="flex gap-2 self-start flex-wrap">
          <button
            @click="openImport"
            class="rounded-full px-5 py-3 text-sm font-semibold"
            style="border: 1px solid var(--border); background: var(--surface-muted); color: var(--text)"
          >
            📂 Import file
          </button>

          <button
            @click="openAdd"
            class="rounded-full px-5 py-3 text-sm font-semibold text-white"
            style="background: var(--accent)"
          >
            + Thêm câu hỏi
          </button>
        </div>
      </div>

      <!-- Filters -->
      <div class="theme-card rounded-[24px] p-4 flex flex-col gap-3 lg:flex-row">
        <select v-model="quizFilter" class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả quiz</option>
          <option v-for="q in quizzes" :key="q.id" :value="q.id">{{ q.title }}</option>
        </select>

        <select v-model="typeFilter" class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none">
          <option value="">Tất cả loại</option>
          <option v-for="type in questionTypes" :key="type.value" :value="type.value">
            {{ type.label }}
          </option>
        </select>

        <input
          v-model="search"
          type="text"
          placeholder="Tìm câu hỏi..."
          class="search-input flex-1 rounded-2xl px-4 py-2.5 text-sm outline-none"
        />
      </div>

      <!-- Table -->
      <div class="theme-card rounded-[30px] overflow-hidden">
        <div v-if="loading" class="p-8 text-center theme-text-muted">Đang tải...</div>

        <div v-else-if="!filtered.length" class="p-12 text-center">
          <p class="text-4xl">❓</p>
          <p class="mt-4 font-semibold theme-text">Chưa có câu hỏi nào</p>
          <p class="mt-2 text-sm theme-text-muted">Thêm thủ công hoặc import từ file</p>
        </div>

        <table v-else class="w-full text-sm">
          <thead>
            <tr class="table-head">
              <th class="px-5 py-4 text-left font-semibold theme-text-muted">Nội dung</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden md:table-cell">Loại</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Quiz</th>
              <th class="px-5 py-4 text-left font-semibold theme-text-muted hidden lg:table-cell">Thông tin</th>
              <th class="px-5 py-4 text-right font-semibold theme-text-muted">Thao tác</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in filtered"
              :key="item.id"
              class="table-row border-t"
              style="border-color: var(--border)"
            >
              <td class="px-5 py-4 theme-text max-w-[420px]">
                <div class="font-medium truncate">
                  {{ displayQuestionTitle(item) }}
                </div>

                <div v-if="item.question_type?.endsWith('_group')" class="text-xs theme-text-muted mt-1">
                  {{ getGroupQuestionCount(item) }} câu hỏi
                </div>

                <div v-else-if="item.meta?.instruction" class="text-xs theme-text-muted mt-1 line-clamp-1">
                  {{ item.meta.instruction }}
                </div>
              </td>

              <td class="px-5 py-4 hidden md:table-cell">
                <span class="type-badge rounded-full px-2.5 py-1 text-xs font-semibold">
                  {{ typeLabel(item.question_type) }}
                </span>
              </td>

              <td class="px-5 py-4 hidden lg:table-cell theme-text-soft">
                {{ item.quiz?.title ?? '—' }}
              </td>

              <td class="px-5 py-4 hidden lg:table-cell theme-text-soft">
                <span v-if="item.question_type === 'reading_group'">
                  {{ item.passage ? 'Có đoạn văn' : (item.image_url ? 'Có hình ảnh' : 'Nhóm câu hỏi') }}
                </span>
                <span v-else-if="item.question_type === 'listening_group'">
                  {{ item.audio_url ? 'Có audio/video' : 'Nhóm nghe' }}
                </span>
                <span v-else-if="item.passage">Có ngữ cảnh</span>
                <span v-else>—</span>
              </td>

              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-2 flex-wrap">
                  <button
                    @click="openEdit(item)"
                    class="action-btn rounded-xl px-3 py-1.5 text-xs font-semibold"
                  >
                    Sửa
                  </button>

                  <button
                    @click="deleteQuestion(item.id)"
                    class="danger-btn rounded-xl px-3 py-1.5 text-xs font-semibold"
                  >
                    Xóa
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div
        v-if="modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5)"
      >
        <div
          class="w-full max-w-5xl rounded-[28px] p-6 space-y-5 overflow-y-auto max-h-[92vh]"
          style="background: var(--surface-strong); border: 1px solid var(--border)"
        >
          <div class="flex items-start justify-between gap-4">
            <div>
              <h2 class="text-xl font-semibold theme-text">
                {{ editing ? 'Sửa câu hỏi' : 'Thêm câu hỏi' }}
              </h2>
              <p class="mt-1 text-sm theme-text-muted">
                Hỗ trợ nhiều dạng câu hỏi tiếng Anh
              </p>
            </div>

            <button @click="modal = false" class="icon-btn" type="button">✕</button>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="form-group">
              <label class="form-label">Quiz</label>
              <select v-model="form.quiz_id" class="form-input">
                <option value="">Chọn quiz</option>
                <option v-for="q in quizzes" :key="q.id" :value="q.id">{{ q.title }}</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Loại câu hỏi</label>
              <select v-model="form.question_type" class="form-input" @change="handleTypeChange">
                <option v-for="type in questionTypes" :key="type.value" :value="type.value">
                  {{ type.label }}
                </option>
              </select>
            </div>
          </div>

          <!-- ====================== GROUP TYPES ====================== -->
          <template v-if="isGroupType(form.question_type)">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div class="form-group lg:col-span-2">
                <label class="form-label">Tiêu đề nhóm câu hỏi</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="form-input"
                  placeholder="Ví dụ: Grammar Practice / Listening Part 1 / Read the following passage..."
                />
              </div>

              <div class="form-group lg:col-span-2">
                <label class="form-label">Hướng dẫn</label>
                <textarea
                  v-model="form.instruction"
                  class="form-input"
                  rows="2"
                  placeholder="Ví dụ: Chọn đáp án đúng cho mỗi câu hỏi..."
                />
              </div>

              <div
                v-if="supportsPassage(form.question_type)"
                class="form-group lg:col-span-2"
              >
                <label class="form-label">Đoạn văn / ngữ cảnh</label>
                <textarea
                  v-model="form.passage"
                  class="form-input"
                  rows="6"
                  placeholder="Nhập đoạn văn, hội thoại, ngữ cảnh..."
                />
              </div>

              <div class="form-group">
                <label class="form-label">Hình ảnh</label>
                <UploadInput v-model="form.image_url" type="image" folder="materials" :show-url="true" />
              </div>

              <div v-if="form.question_type === 'listening_group'" class="form-group">
                <label class="form-label">Audio / Video</label>
                <UploadInput v-model="form.audio_url" type="audio" :show-url="true" />
              </div>

              <div
                v-if="form.question_type === 'reading_group'"
                class="form-group lg:col-span-2"
              >
                <label class="form-label">Ghi chú</label>
                <input
                  v-model="form.note"
                  type="text"
                  class="form-input"
                  placeholder="Ví dụ: Câu 1 đến câu 5"
                />
              </div>
            </div>

            <div class="space-y-4">
              <div class="flex items-center justify-between gap-3">
                <label class="form-label">Danh sách câu hỏi</label>
                <button @click="addGroupQuestion" class="mini-link-btn" type="button">
                  + Thêm câu hỏi
                </button>
              </div>

              <div
                v-for="(q, qIndex) in form.questions"
                :key="qIndex"
                class="rounded-[24px] p-4 space-y-4"
                style="background: var(--surface-muted); border: 1px solid var(--border)"
              >
                <div class="flex items-start justify-between gap-3">
                  <div class="font-semibold theme-text">Câu hỏi {{ qIndex + 1 }}</div>
                  <button
                    v-if="form.questions.length > 1"
                    @click="removeGroupQuestion(qIndex)"
                    class="danger-text-btn"
                    type="button"
                  >
                    Xóa câu hỏi
                  </button>
                </div>

                <div class="form-group">
                  <label class="form-label">Nội dung câu hỏi</label>
                  <textarea
                    v-model="q.question"
                    class="form-input"
                    rows="2"
                    placeholder="Nhập câu hỏi..."
                  />
                </div>

                <!-- multiple_choice_group / listening_group / reading_group -->
                <template v-if="usesAnswerOptions(form.question_type)">
                  <div class="space-y-2">
                    <div class="flex items-center justify-between gap-3">
                      <label class="form-label">Đáp án</label>
                      <button
                        v-if="q.answers.length < 8"
                        @click="addAnswer(qIndex)"
                        class="mini-link-btn"
                        type="button"
                      >
                        + Thêm đáp án
                      </button>
                    </div>

                    <div
                      v-for="(ans, aIndex) in q.answers"
                      :key="aIndex"
                      class="flex items-center gap-3"
                    >
                      <input
                        type="radio"
                        :name="`group-correct-${qIndex}`"
                        :checked="ans.is_correct"
                        @change="setSingleCorrect(qIndex, aIndex)"
                        class="shrink-0"
                        style="accent-color: var(--accent)"
                      />

                      <span class="text-xs font-bold theme-text-muted w-5 shrink-0">
                        {{ answerLabels[aIndex] || aIndex + 1 }}.
                      </span>

                      <input
                        v-model="ans.answer_text"
                        type="text"
                        class="form-input flex-1 text-sm"
                        :placeholder="`Đáp án ${answerLabels[aIndex] || aIndex + 1}`"
                      />

                      <button
                        v-if="q.answers.length > 2"
                        @click="removeAnswer(qIndex, aIndex)"
                        class="danger-text-btn"
                        type="button"
                      >
                        Xóa
                      </button>
                    </div>
                  </div>
                </template>

                <!-- fill_blank_group -->
                <template v-if="form.question_type === 'fill_blank_group'">
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="form-group">
                      <label class="form-label">Đáp án đúng</label>
                      <input
                        v-model="q.correct_text"
                        type="text"
                        class="form-input"
                        placeholder="Ví dụ: goes"
                      />
                    </div>

                    <div class="form-group">
                      <label class="form-label">Đáp án chấp nhận thêm</label>
                      <input
                        v-model="q.accepted_answers_text"
                        type="text"
                        class="form-input"
                        placeholder="goes, goes to school"
                      />
                    </div>
                  </div>
                </template>

                <!-- true_false_group -->
                <template v-if="form.question_type === 'true_false_group'">
                  <div class="space-y-3">
                    <label class="form-label">Đáp án đúng</label>
                    <div class="flex gap-3 flex-wrap">
                      <button
                        type="button"
                        class="chip"
                        :class="{ 'chip-active': q.correct_boolean === true }"
                        @click="q.correct_boolean = true"
                      >
                        Đúng
                      </button>
                      <button
                        type="button"
                        class="chip"
                        :class="{ 'chip-active': q.correct_boolean === false }"
                        @click="q.correct_boolean = false"
                      >
                        Sai
                      </button>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </template>

          <!-- ====================== WRITING ====================== -->
          <template v-if="form.question_type === 'writing'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div class="form-group lg:col-span-2">
                <label class="form-label">Đề bài</label>
                <textarea
                  v-model="form.writing_question"
                  class="form-input"
                  rows="4"
                  placeholder="Nhập đề bài viết..."
                />
              </div>

              <div class="form-group">
                <label class="form-label">Số từ tối thiểu</label>
                <input
                  v-model.number="form.writing_min_words"
                  type="number"
                  min="0"
                  class="form-input"
                />
              </div>

              <div class="form-group">
                <label class="form-label">Số từ tối đa</label>
                <input
                  v-model.number="form.writing_max_words"
                  type="number"
                  min="0"
                  class="form-input"
                />
              </div>

              <div class="form-group lg:col-span-2">
                <label class="form-label">Rubric / ghi chú chấm</label>
                <textarea
                  v-model="form.writing_rubric"
                  class="form-input"
                  rows="4"
                  placeholder="Ví dụ: chấm theo ngữ pháp, từ vựng, bố cục..."
                />
              </div>
            </div>
          </template>

          <!-- ====================== ORDERING ====================== -->
          <template v-if="form.question_type === 'ordering'">
            <div class="form-group">
              <label class="form-label">Câu hỏi / hướng dẫn</label>
              <textarea
                v-model="form.ordering_question"
                class="form-input"
                rows="3"
                placeholder="Ví dụ: Sắp xếp các từ sau thành câu đúng."
              />
            </div>

            <div class="space-y-3">
              <div class="flex items-center justify-between gap-3">
                <label class="form-label">Các mục để sắp xếp</label>
                <button @click="addOrderingItem" class="mini-link-btn" type="button">
                  + Thêm mục
                </button>
              </div>

              <div
                v-for="(item, index) in form.ordering_items"
                :key="index"
                class="rounded-2xl p-3 flex items-center gap-3"
                style="background: var(--surface-muted); border: 1px solid var(--border)"
              >
                <span class="text-xs font-semibold theme-text-muted w-8 shrink-0">
                  {{ index + 1 }}
                </span>

                <input
                  v-model="item.text"
                  type="text"
                  class="form-input flex-1 text-sm"
                  placeholder="Nhập từ / cụm từ / câu..."
                />

                <button
                  v-if="form.ordering_items.length > 2"
                  @click="removeOrderingItem(index)"
                  class="danger-text-btn"
                  type="button"
                >
                  Xóa
                </button>
              </div>
            </div>
          </template>

          <!-- ====================== MATCHING ====================== -->
          <template v-if="form.question_type === 'matching'">
            <div class="form-group">
              <label class="form-label">Câu hỏi / hướng dẫn</label>
              <textarea
                v-model="form.matching_question"
                class="form-input"
                rows="3"
                placeholder="Ví dụ: Nối từ tiếng Anh với nghĩa tiếng Việt."
              />
            </div>

            <div class="space-y-3">
              <div class="flex items-center justify-between gap-3">
                <label class="form-label">Các cặp nối</label>
                <button @click="addMatchingPair" class="mini-link-btn" type="button">
                  + Thêm cặp
                </button>
              </div>

              <div
                v-for="(pair, index) in form.matching_pairs"
                :key="index"
                class="rounded-2xl p-3 grid grid-cols-1 lg:grid-cols-[1fr_auto_1fr_auto] gap-3 items-center"
                style="background: var(--surface-muted); border: 1px solid var(--border)"
              >
                <input
                  v-model="pair.left"
                  type="text"
                  class="form-input text-sm"
                  placeholder="Vế trái"
                />
                <span class="theme-text-muted text-center">↔</span>
                <input
                  v-model="pair.right"
                  type="text"
                  class="form-input text-sm"
                  placeholder="Vế phải"
                />

                <button
                  v-if="form.matching_pairs.length > 1"
                  @click="removeMatchingPair(index)"
                  class="danger-text-btn"
                  type="button"
                >
                  Xóa
                </button>
              </div>
            </div>
          </template>

          <div
            v-if="error"
            class="rounded-2xl px-4 py-3 text-sm"
            style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)"
          >
            {{ error }}
          </div>

          <div class="flex gap-3 pt-1 flex-wrap">
            <button
              @click="saveQuestion"
              :disabled="saving"
              class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-60"
            >
              {{ saving ? 'Đang lưu...' : 'Lưu' }}
            </button>

            <button
              @click="modal = false"
              class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold"
              type="button"
            >
              Hủy
            </button>
          </div>
        </div>
      </div>

      <!-- Import modal -->
      <div
        v-if="importModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5)"
      >
        <div
          class="w-full max-w-3xl rounded-[28px] p-6 space-y-5 overflow-y-auto max-h-[92vh]"
          style="background: var(--surface-strong); border: 1px solid var(--border)"
        >
          <!-- Step 1: Upload -->
          <template v-if="importStep === 'upload'">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h2 class="text-xl font-semibold theme-text">Import câu hỏi</h2>
                <p class="mt-1 text-sm theme-text-muted">
                  Hỗ trợ JSON, CSV, TXT, DOCX. JSON là tốt nhất cho nhiều dạng câu hỏi.
                </p>
              </div>
              <button @click="closeImport" class="icon-btn" type="button">✕</button>
            </div>

            <div
              class="rounded-2xl p-4 space-y-3 text-xs"
              style="background: var(--surface-muted); border: 1px solid var(--border)"
            >
              <p class="font-semibold theme-text">Mẫu JSON:</p>
              <pre class="rounded-xl p-3 overflow-x-auto" style="background: var(--bg); color: var(--text)">{{ importJsonSample }}</pre>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
              <div class="form-group">
                <label class="form-label">Quiz đích</label>
                <select v-model="importForm.quiz_id" class="form-input">
                  <option value="">Chọn quiz</option>
                  <option v-for="q in quizzes" :key="q.id" :value="q.id">{{ q.title }}</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Loại mặc định</label>
                <select v-model="importForm.default_type" class="form-input">
                  <option value="">Tự lấy theo file</option>
                  <option v-for="type in questionTypes" :key="type.value" :value="type.value">
                    {{ type.label }}
                  </option>
                </select>
              </div>
            </div>

            <div
              class="drop-zone rounded-[20px] p-8 text-center cursor-pointer transition"
              :class="{ 'drop-zone-active': isDragging }"
              @click="fileInput?.click()"
              @dragover.prevent="isDragging = true"
              @dragleave="isDragging = false"
              @drop.prevent="onDrop"
            >
              <input
                ref="fileInput"
                type="file"
                accept=".json,.csv,.txt,.docx"
                class="hidden"
                @change="onFileChange"
              />
              <p class="text-3xl mb-3">📂</p>
              <p class="font-semibold theme-text">
                {{ importFile ? importFile.name : 'Kéo thả hoặc click để chọn file' }}
              </p>
              <p class="mt-1 text-xs theme-text-muted">
                DOCX: tự động nhận dạng câu hỏi và đáp án. JSON hợp nhất cho nhiều nhóm câu hỏi.
              </p>
            </div>

            <!-- JSON/CSV/TXT preview -->
            <div v-if="preview.length && !isDocxFile" class="space-y-2">
              <p class="text-sm font-semibold theme-text">Xem trước — {{ preview.length }} mục</p>
              <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                <div
                  v-for="(item, index) in preview"
                  :key="index"
                  class="rounded-2xl p-3 text-sm"
                  style="background: var(--surface-muted); border: 1px solid var(--border)"
                >
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="type-badge rounded-full px-2 py-0.5 text-[11px] font-semibold">
                      {{ typeLabel(item.question_type) }}
                    </span>
                    <span class="font-medium theme-text">{{ index + 1 }}. {{ previewTitle(item) }}</span>
                  </div>
                  <div v-if="item.answers?.length" class="mt-2 flex flex-wrap gap-1.5">
                    <span
                      v-for="(ans, ai) in item.answers"
                      :key="ai"
                      class="rounded-full px-2 py-0.5 text-xs"
                      :style="ans.is_correct
                        ? 'background: color-mix(in srgb, #22c55e 15%, var(--surface-muted)); color: #16a34a'
                        : 'background: var(--surface-strong); color: var(--text)'"
                    >
                      {{ ans.is_correct ? '✓ ' : '' }}{{ ans.answer_text }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- DOCX ready indicator -->
            <div
              v-if="isDocxFile && importFile"
              class="rounded-2xl px-4 py-3 text-sm flex items-center gap-3"
              style="background: color-mix(in srgb, var(--accent) 8%, var(--surface-muted)); border: 1px solid color-mix(in srgb, var(--accent) 20%, var(--border))"
            >
              <span class="text-xl">📄</span>
              <div>
                <p class="font-semibold theme-text">{{ importFile.name }}</p>
                <p class="text-xs theme-text-muted">Nhấn "Tiếp theo" để phân tích và chỉnh sửa câu hỏi trước khi lưu.</p>
              </div>
            </div>

            <div
              v-if="importError"
              class="rounded-2xl px-4 py-3 text-sm"
              style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)"
            >
              {{ importError }}
            </div>

            <div class="flex gap-3 pt-1 flex-wrap">
              <!-- DOCX: go to review step -->
              <button
                v-if="isDocxFile"
                @click="parseDocxAndReview"
                :disabled="importing || !importFile || !importForm.quiz_id"
                class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
              >
                {{ importing ? 'Đang phân tích...' : 'Tiếp theo →' }}
              </button>

              <!-- JSON/CSV/TXT: submit directly -->
              <button
                v-else
                @click="submitImport"
                :disabled="importing || !importFile || !importForm.quiz_id"
                class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
              >
                {{ importing ? 'Đang import...' : `Import${preview.length ? ` ${preview.length} mục` : ''}` }}
              </button>

              <button @click="closeImport" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold" type="button">
                Đóng
              </button>
            </div>
          </template>

          <!-- Step 2: Review DOCX questions -->
          <template v-if="importStep === 'review'">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h2 class="text-xl font-semibold theme-text">Kiểm tra câu hỏi</h2>
                <p class="mt-1 text-sm theme-text-muted">
                  Đã tìm thấy {{ docxQuestions.length }} câu hỏi. Chỉnh sửa nội dung và chọn đáp án đúng trước khi lưu.
                </p>
              </div>
              <button @click="closeImport" class="icon-btn" type="button">✕</button>
            </div>

            <!-- Validation summary -->
            <div
              v-if="docxValidationErrors.length"
              class="rounded-2xl px-4 py-3 text-sm"
              style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)"
            >
              ⚠ {{ docxValidationErrors.length }} câu hỏi chưa chọn đáp án đúng: câu {{ docxValidationErrors.join(', ') }}
            </div>

            <div class="space-y-4 max-h-[55vh] overflow-y-auto pr-1">
              <QuizQuestionItem
                v-for="(q, i) in docxQuestions"
                :key="i"
                v-model="docxQuestions[i]"
                :index="i"
                :removable="docxQuestions.length > 1"
                @remove="docxQuestions.splice(i, 1)"
              />
            </div>

            <div
              v-if="importError"
              class="rounded-2xl px-4 py-3 text-sm"
              style="background: color-mix(in srgb, var(--danger) 8%, var(--surface-muted)); color: var(--danger)"
            >
              {{ importError }}
            </div>

            <div
              v-if="importSuccess"
              class="rounded-2xl px-4 py-3 text-sm"
              style="background: color-mix(in srgb, #22c55e 10%, var(--surface-muted)); color: #16a34a"
            >
              {{ importSuccess }}
            </div>

            <div class="flex gap-3 pt-1 flex-wrap">
              <button
                @click="submitDocxImport"
                :disabled="importing || !!docxValidationErrors.length"
                class="submit-btn rounded-full px-5 py-2.5 text-sm font-semibold text-white disabled:opacity-50"
              >
                {{ importing ? 'Đang lưu...' : `Lưu ${docxQuestions.length} câu hỏi` }}
              </button>
              <button @click="importStep = 'upload'" class="cancel-btn rounded-full px-5 py-2.5 text-sm font-semibold" type="button">
                ← Quay lại
              </button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { questionService, quizService } from '@/services/teacher.service'
import QuizQuestionItem from '@/components/quiz/QuizQuestionItem.vue'
import UploadInput from '@/components/upload/UploadInput.vue'

const route = useRoute()

const questions = ref([])
const quizzes = ref([])
const loading = ref(true)
const search = ref('')
const quizFilter = ref(route.query.quiz_id ?? '')
const typeFilter = ref('')

const modal = ref(false)
const editing = ref(null)
const saving = ref(false)
const error = ref('')

const importModal = ref(false)
const importFile = ref(null)
const preview = ref([])
const importing = ref(false)
const importError = ref('')
const importSuccess = ref('')
const isDragging = ref(false)
const fileInput = ref(null)

// DOCX review step
const importStep = ref('upload') // 'upload' | 'review'
const docxQuestions = ref([])
const isDocxFile = computed(() => importFile.value?.name?.toLowerCase().endsWith('.docx'))
const docxValidationErrors = computed(() =>
  docxQuestions.value
    .map((q, i) => (!q.answers?.some(a => a.is_correct) ? i + 1 : null))
    .filter(Boolean)
)

const questionTypes = [
  { value: 'multiple_choice_group', label: 'Trắc nghiệm nhiều câu' },
  { value: 'fill_blank_group', label: 'Điền vào chỗ trống nhiều câu' },
  { value: 'true_false_group', label: 'Đúng / Sai nhiều câu' },
  { value: 'listening_group', label: 'Nghe nhiều câu' },
  { value: 'reading_group', label: 'Đọc hiểu nhiều câu' },
  { value: 'writing', label: 'Viết' },
  { value: 'ordering', label: 'Sắp xếp câu' },
  { value: 'matching', label: 'Nối cột' },
]

const answerLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']

const importForm = ref({
  quiz_id: '',
  default_type: '',
})

function defaultAnswers() {
  return [
    { answer_text: '', is_correct: true },
    { answer_text: '', is_correct: false },
    { answer_text: '', is_correct: false },
    { answer_text: '', is_correct: false },
  ]
}

function defaultQuestionItem(type = 'multiple_choice_group') {
  if (['multiple_choice_group', 'listening_group', 'reading_group'].includes(type)) {
    return {
      question: '',
      answers: defaultAnswers(),
    }
  }

  if (type === 'fill_blank_group') {
    return {
      question: '',
      correct_text: '',
      accepted_answers_text: '',
    }
  }

  if (type === 'true_false_group') {
    return {
      question: '',
      correct_boolean: true,
    }
  }

  return { question: '' }
}

function createDefaultForm() {
  return {
    quiz_id: quizFilter.value || '',
    question_type: 'multiple_choice_group',

    // group
    title: '',
    instruction: '',
    passage: '',
    image_url: '',
    audio_url: '',
    note: '',
    questions: [defaultQuestionItem('multiple_choice_group')],

    // writing
    writing_question: '',
    writing_min_words: null,
    writing_max_words: null,
    writing_rubric: '',

    // ordering
    ordering_question: '',
    ordering_items: [{ text: '' }, { text: '' }, { text: '' }],

    // matching
    matching_question: '',
    matching_pairs: [{ left: '', right: '' }],
  }
}

const form = ref(createDefaultForm())

const importJsonSample = `[
  {
    "question_type": "multiple_choice_group",
    "title": "Grammar Practice",
    "instruction": "Choose the best answer for each question.",
    "questions": [
      {
        "question": "What is the capital of France?",
        "answers": [
          { "answer_text": "London", "is_correct": false },
          { "answer_text": "Paris", "is_correct": true },
          { "answer_text": "Rome", "is_correct": false },
          { "answer_text": "Berlin", "is_correct": false }
        ]
      },
      {
        "question": "She ____ to school every day.",
        "answers": [
          { "answer_text": "go", "is_correct": false },
          { "answer_text": "goes", "is_correct": true },
          { "answer_text": "gone", "is_correct": false },
          { "answer_text": "going", "is_correct": false }
        ]
      }
    ]
  },
  {
    "question_type": "fill_blank_group",
    "title": "Fill in the blanks",
    "instruction": "Complete each sentence.",
    "questions": [
      {
        "question": "She ____ to school every day.",
        "correct_text": "goes",
        "accepted_answers": ["goes"]
      }
    ]
  },
  {
    "question_type": "reading_group",
    "title": "Read the following passage and choose the best answer.",
    "instruction": "Mark the letter A, B, C or D on your answer sheet.",
    "passage": "All holidays involve some element of risk...",
    "questions": [
      {
        "question": "Question 1",
        "answers": [
          { "answer_text": "A ...", "is_correct": false },
          { "answer_text": "B ...", "is_correct": true },
          { "answer_text": "C ...", "is_correct": false },
          { "answer_text": "D ...", "is_correct": false }
        ]
      }
    ]
  }
]`

async function load() {
  loading.value = true
  try {
    const [questionRes, quizRes] = await Promise.all([
      questionService.list(),
      quizService.list(),
    ])

    questions.value = questionRes.data?.data ?? questionRes.data ?? []
    quizzes.value = quizRes.data?.data ?? quizRes.data ?? []
  } finally {
    loading.value = false
  }
}

onMounted(load)

const filtered = computed(() => {
  return questions.value.filter((item) => {
    const keyword = search.value.trim().toLowerCase()
    const title = displayQuestionTitle(item).toLowerCase()
    const matchSearch = !keyword || title.includes(keyword)
    const matchQuiz = !quizFilter.value || String(item.quiz_id) === String(quizFilter.value)
    const matchType = !typeFilter.value || item.question_type === typeFilter.value
    return matchSearch && matchQuiz && matchType
  })
})

function isGroupType(type) {
  return type.endsWith('_group')
}

function usesAnswerOptions(type) {
  return ['multiple_choice_group', 'listening_group', 'reading_group'].includes(type)
}

function supportsPassage(type) {
  return ['multiple_choice_group', 'fill_blank_group', 'true_false_group', 'reading_group'].includes(type)
}

function displayQuestionTitle(item) {
  if (item.question_type?.endsWith('_group')) {
    return item.title || 'Nhóm câu hỏi'
  }

  if (item.question_type === 'writing') {
    return item.question || item.writing_question || 'Bài viết'
  }

  return item.question || '—'
}

function previewTitle(item) {
  return displayQuestionTitle(item)
}

function getGroupQuestionCount(item) {
  return item.questions?.length || item.sub_questions?.length || 0
}

function typeLabel(type) {
  return questionTypes.find((item) => item.value === type)?.label ?? type
}

function openAdd() {
  editing.value = null
  form.value = createDefaultForm()
  error.value = ''
  modal.value = true
}

function openEdit(item) {
  editing.value = item

  if (item.question_type?.endsWith('_group')) {
    const questionsData = item.questions || item.sub_questions || []

    form.value = {
      ...createDefaultForm(),
      quiz_id: item.quiz_id ?? '',
      question_type: item.question_type ?? 'multiple_choice_group',
      title: item.title ?? '',
      instruction: item.instruction ?? item.meta?.instruction ?? '',
      passage: item.passage ?? '',
      image_url: item.image_url ?? '',
      audio_url: item.audio_url ?? '',
      note: item.note ?? '',
      questions: Array.isArray(questionsData) && questionsData.length
        ? questionsData.map((q) => {
            if (['multiple_choice_group', 'listening_group', 'reading_group'].includes(item.question_type)) {
              return {
                question: q.question ?? '',
                answers: Array.isArray(q.answers) && q.answers.length
                  ? q.answers.map((ans) => ({
                      answer_text: ans.answer_text ?? '',
                      is_correct: !!ans.is_correct,
                    }))
                  : defaultAnswers(),
              }
            }

            if (item.question_type === 'fill_blank_group') {
              return {
                question: q.question ?? '',
                correct_text: q.correct_text ?? '',
                accepted_answers_text: Array.isArray(q.accepted_answers)
                  ? q.accepted_answers.join(', ')
                  : '',
              }
            }

            if (item.question_type === 'true_false_group') {
              return {
                question: q.question ?? '',
                correct_boolean: typeof q.correct_boolean === 'boolean' ? q.correct_boolean : true,
              }
            }

            return defaultQuestionItem(item.question_type)
          })
        : [defaultQuestionItem(item.question_type)],
    }

    error.value = ''
    modal.value = true
    return
  }

  if (item.question_type === 'writing') {
    form.value = {
      ...createDefaultForm(),
      quiz_id: item.quiz_id ?? '',
      question_type: 'writing',
      writing_question: item.question ?? '',
      writing_min_words: item.meta?.min_words ?? null,
      writing_max_words: item.meta?.max_words ?? null,
      writing_rubric: item.meta?.rubric ?? '',
    }

    error.value = ''
    modal.value = true
    return
  }

  if (item.question_type === 'ordering') {
    form.value = {
      ...createDefaultForm(),
      quiz_id: item.quiz_id ?? '',
      question_type: 'ordering',
      ordering_question: item.question ?? '',
      ordering_items: Array.isArray(item.meta?.ordering_items) && item.meta.ordering_items.length
        ? item.meta.ordering_items.map((x) => ({ text: x.text ?? '' }))
        : [{ text: '' }, { text: '' }, { text: '' }],
    }

    error.value = ''
    modal.value = true
    return
  }

  if (item.question_type === 'matching') {
    form.value = {
      ...createDefaultForm(),
      quiz_id: item.quiz_id ?? '',
      question_type: 'matching',
      matching_question: item.question ?? '',
      matching_pairs: Array.isArray(item.meta?.matching_pairs) && item.meta.matching_pairs.length
        ? item.meta.matching_pairs.map((x) => ({ left: x.left ?? '', right: x.right ?? '' }))
        : [{ left: '', right: '' }],
    }

    error.value = ''
    modal.value = true
  }
}

function handleTypeChange() {
  const quizId = form.value.quiz_id
  const type = form.value.question_type
  form.value = createDefaultForm()
  form.value.quiz_id = quizId
  form.value.question_type = type
  if (isGroupType(type)) {
    form.value.questions = [defaultQuestionItem(type)]
  }
}

function addGroupQuestion() {
  form.value.questions.push(defaultQuestionItem(form.value.question_type))
}

function removeGroupQuestion(index) {
  form.value.questions.splice(index, 1)
}

function addAnswer(questionIndex) {
  form.value.questions[questionIndex].answers.push({
    answer_text: '',
    is_correct: false,
  })
}

function removeAnswer(questionIndex, answerIndex) {
  const answers = form.value.questions[questionIndex].answers
  answers.splice(answerIndex, 1)

  if (!answers.some((a) => a.is_correct) && answers.length) {
    answers[0].is_correct = true
  }
}

function setSingleCorrect(questionIndex, answerIndex) {
  form.value.questions[questionIndex].answers.forEach((ans, i) => {
    ans.is_correct = i === answerIndex
  })
}

function addOrderingItem() {
  form.value.ordering_items.push({ text: '' })
}

function removeOrderingItem(index) {
  form.value.ordering_items.splice(index, 1)
}

function addMatchingPair() {
  form.value.matching_pairs.push({ left: '', right: '' })
}

function removeMatchingPair(index) {
  form.value.matching_pairs.splice(index, 1)
}

function buildPayload() {
  const type = form.value.question_type

  if (type.endsWith('_group')) {
    return {
      quiz_id: form.value.quiz_id,
      question_type: type,
      title: form.value.title?.trim() || '',
      instruction: form.value.instruction?.trim() || '',
      passage: form.value.passage?.trim() || '',
      image_url: form.value.image_url?.trim() || '',
      audio_url: form.value.audio_url?.trim() || '',
      note: form.value.note?.trim() || '',
      questions: form.value.questions.map((q) => {
        if (['multiple_choice_group', 'listening_group', 'reading_group'].includes(type)) {
          return {
            question: q.question?.trim() || '',
            answers: q.answers
              .map((a) => ({
                answer_text: a.answer_text?.trim() || '',
                is_correct: !!a.is_correct,
              }))
              .filter((a) => a.answer_text),
          }
        }

        if (type === 'fill_blank_group') {
          return {
            question: q.question?.trim() || '',
            correct_text: q.correct_text?.trim() || '',
            accepted_answers: String(q.accepted_answers_text || '')
              .split(',')
              .map((x) => x.trim())
              .filter(Boolean),
          }
        }

        if (type === 'true_false_group') {
          return {
            question: q.question?.trim() || '',
            correct_boolean: !!q.correct_boolean,
          }
        }

        return q
      }),
    }
  }

  if (type === 'writing') {
    return {
      quiz_id: form.value.quiz_id,
      question_type: 'writing',
      question: form.value.writing_question?.trim() || '',
      meta: {
        min_words: form.value.writing_min_words || null,
        max_words: form.value.writing_max_words || null,
        rubric: form.value.writing_rubric?.trim() || '',
      },
    }
  }

  if (type === 'ordering') {
    return {
      quiz_id: form.value.quiz_id,
      question_type: 'ordering',
      question: form.value.ordering_question?.trim() || '',
      meta: {
        ordering_items: form.value.ordering_items
          .map((item) => ({ text: String(item.text || '').trim() }))
          .filter((item) => item.text),
      },
    }
  }

  if (type === 'matching') {
    return {
      quiz_id: form.value.quiz_id,
      question_type: 'matching',
      question: form.value.matching_question?.trim() || '',
      meta: {
        matching_pairs: form.value.matching_pairs
          .map((pair) => ({
            left: String(pair.left || '').trim(),
            right: String(pair.right || '').trim(),
          }))
          .filter((pair) => pair.left && pair.right),
      },
    }
  }

  return null
}

function validatePayload(payload) {
  if (!payload.quiz_id) return 'Vui lòng chọn quiz.'

  if (payload.question_type?.endsWith('_group')) {
    if (!payload.title && !payload.passage && !payload.image_url && !payload.audio_url) {
      return 'Nhóm câu hỏi cần có tiêu đề, đoạn văn, hình ảnh hoặc audio.'
    }

    if (!Array.isArray(payload.questions) || !payload.questions.length) {
      return 'Phải có ít nhất 1 câu hỏi.'
    }

    for (let i = 0; i < payload.questions.length; i++) {
      const q = payload.questions[i]

      if (!q.question) {
        return `Câu hỏi ${i + 1} đang để trống.`
      }

      if (['multiple_choice_group', 'listening_group', 'reading_group'].includes(payload.question_type)) {
        if (!q.answers || q.answers.length < 2) {
          return `Câu hỏi ${i + 1} phải có ít nhất 2 đáp án.`
        }

        if (q.answers.filter((a) => a.is_correct).length !== 1) {
          return `Câu hỏi ${i + 1} phải có đúng 1 đáp án đúng.`
        }
      }

      if (payload.question_type === 'fill_blank_group' && !q.correct_text) {
        return `Câu hỏi ${i + 1} phải có đáp án đúng.`
      }
    }

    if (payload.question_type === 'listening_group' && !payload.audio_url) {
      return 'Phần nghe phải có URL audio hoặc video.'
    }

    return ''
  }

  if (payload.question_type === 'writing' && !payload.question) {
    return 'Vui lòng nhập đề bài viết.'
  }

  if (payload.question_type === 'ordering') {
    if (!payload.question) return 'Vui lòng nhập câu hỏi sắp xếp.'
    if ((payload.meta?.ordering_items || []).length < 2) {
      return 'Dạng sắp xếp cần ít nhất 2 mục.'
    }
  }

  if (payload.question_type === 'matching') {
    if (!payload.question) return 'Vui lòng nhập câu hỏi nối cột.'
    if ((payload.meta?.matching_pairs || []).length < 1) {
      return 'Dạng nối cột cần ít nhất 1 cặp.'
    }
  }

  return ''
}

async function saveQuestion() {
  saving.value = true
  error.value = ''

  try {
    const payload = buildPayload()
    const validationError = validatePayload(payload)

    if (validationError) {
      error.value = validationError
      return
    }

    if (editing.value) {
      await questionService.update(editing.value.id, payload)
    } else {
      await questionService.create(payload)
    }

    modal.value = false
    await load()
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Có lỗi xảy ra.'
  } finally {
    saving.value = false
  }
}

async function deleteQuestion(id) {
  if (!confirm('Xóa câu hỏi này?')) return
  await questionService.destroy(id)
  await load()
}

/* import */
function openImport() {
  importModal.value = true
  importFile.value = null
  preview.value = []
  importError.value = ''
  importSuccess.value = ''
  importStep.value = 'upload'
  docxQuestions.value = []
  importForm.value = {
    quiz_id: quizFilter.value || '',
    default_type: '',
  }
}

function closeImport() {
  importModal.value = false
}

function onDrop(e) {
  isDragging.value = false
  const file = e.dataTransfer?.files?.[0]
  if (file) processFile(file)
}

function onFileChange(e) {
  const file = e.target.files?.[0]
  if (file) processFile(file)
  e.target.value = ''
}

function processFile(file) {
  importFile.value = file
  importError.value = ''
  importSuccess.value = ''
  preview.value = []

  const ext = file.name.toLowerCase().split('.').pop()

  if (ext === 'docx') return

  const reader = new FileReader()
  reader.onload = (event) => {
    try {
      const content = event.target.result

      if (ext === 'json') {
        const parsed = JSON.parse(content)
        if (!Array.isArray(parsed)) throw new Error('JSON phải là mảng câu hỏi.')
        preview.value = parsed
      } else if (ext === 'txt') {
        throw new Error('TXT hiện chỉ phù hợp dạng đơn giản. Với nhóm câu hỏi nên dùng JSON.')
      } else if (ext === 'csv') {
        throw new Error('CSV hiện chỉ phù hợp dạng đơn giản. Với nhóm câu hỏi nên dùng JSON.')
      } else {
        throw new Error('Định dạng file không được hỗ trợ.')
      }

      if (!preview.value.length) {
        importError.value = 'Không tìm thấy câu hỏi hợp lệ trong file.'
      }
    } catch (err) {
      importError.value = err.message || 'Không thể đọc file.'
      importFile.value = null
      preview.value = []
    }
  }

  reader.readAsText(file, 'UTF-8')
}

async function submitImport() {
  if (!importFile.value || !importForm.value.quiz_id) return

  importing.value = true
  importError.value = ''
  importSuccess.value = ''

  try {
    const rows = preview.value.map((item) => ({
      ...item,
      quiz_id: importForm.value.quiz_id,
    }))

    const fd = new FormData()
    fd.append('quiz_id', importForm.value.quiz_id)
    fd.append(
      'file',
      new Blob([JSON.stringify(rows)], { type: 'application/json' }),
      'questions.json'
    )

    const res = await questionService.import(fd)
    importSuccess.value = res.data?.message ?? 'Import thành công.'
    preview.value = []
    importFile.value = null
    await load()
  } catch (e) {
    importError.value = e.response?.data?.message ?? 'Import thất bại.'
  } finally {
    importing.value = false
  }
}

async function parseDocxAndReview() {
  if (!importFile.value || !importForm.value.quiz_id) return

  importing.value = true
  importError.value = ''

  try {
    const fd = new FormData()
    fd.append('file', importFile.value)

    const res = await questionService.parseDocx(fd)
    const parsed = res.data?.questions ?? []

    if (!parsed.length) {
      importError.value = 'Không tìm thấy câu hỏi trong file DOCX.'
      return
    }

    docxQuestions.value = parsed.map((q) => ({
      question:      q.question ?? '',
      question_type: q.question_type ?? 'multiple_choice',
      answers:       (q.answers ?? []).map((a) => ({
        answer_text: a.answer_text ?? '',
        is_correct:  !!a.is_correct,
      })),
    }))

    importStep.value = 'review'
  } catch (e) {
    importError.value = e.response?.data?.message ?? 'Không thể phân tích file DOCX.'
  } finally {
    importing.value = false
  }
}

async function submitDocxImport() {
  if (!importForm.value.quiz_id || !docxQuestions.value.length) return
  if (docxValidationErrors.value.length) return

  importing.value = true
  importError.value = ''
  importSuccess.value = ''

  try {
    const res = await questionService.importDocx({
      quiz_id:   importForm.value.quiz_id,
      questions: docxQuestions.value,
    })

    importSuccess.value = res.data?.message ?? 'Import thành công.'
    await load()
    setTimeout(() => closeImport(), 1500)
  } catch (e) {
    importError.value = e.response?.data?.message ?? 'Import thất bại.'
  } finally {
    importing.value = false
  }
}
</script>

<style scoped>
.search-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}

.search-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
}

.table-head {
  background: var(--surface-muted);
}

.table-row:hover {
  background: var(--surface-muted);
}

.type-badge {
  background: color-mix(in srgb, var(--accent) 12%, var(--surface-muted));
  color: var(--accent);
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text);
}

.form-input {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 1rem;
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  outline: none;
  width: 100%;
}

.form-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--ring);
}

.action-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}

.action-btn:hover {
  background: var(--surface-strong);
}

.danger-btn {
  border: 1px solid color-mix(in srgb, var(--danger) 25%, var(--border));
  color: var(--danger);
  background: color-mix(in srgb, var(--danger) 6%, var(--surface-muted));
}

.danger-btn:hover {
  background: color-mix(in srgb, var(--danger) 10%, var(--surface-muted));
}

.submit-btn {
  background: var(--accent);
}

.submit-btn:hover {
  opacity: 0.9;
}

.cancel-btn,
.icon-btn {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
}

.icon-btn {
  width: 2.25rem;
  height: 2.25rem;
  border-radius: 999px;
}

.drop-zone {
  border: 2px dashed var(--border);
  background: var(--surface-muted);
}

.drop-zone:hover,
.drop-zone-active {
  border-color: var(--accent);
  background: color-mix(in srgb, var(--accent) 5%, var(--surface-muted));
}

.chip {
  border: 1px solid var(--border);
  background: var(--surface-muted);
  color: var(--text);
  border-radius: 999px;
  padding: 0.6rem 1rem;
  font-size: 0.875rem;
  font-weight: 600;
}

.chip-active {
  border-color: var(--accent);
  color: var(--accent);
  background: color-mix(in srgb, var(--accent) 10%, var(--surface-muted));
}

.mini-link-btn {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--accent);
}

.danger-text-btn {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--danger);
}
</style>