<template>
  <div style="background:#faf8f4;min-height:100vh;padding:24px 24px 64px;">
    <div style="max-width:1280px;margin:0 auto;">
      <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;margin-bottom:28px;">
        <div>
          <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#1a2540;margin:0 0 4px;">My Vocabulary</h1>
          <p style="font-size:13px;color:#9ca3af;margin:0;">{{ words.length }} words learned</p>
        </div>
        <!-- View toggle -->
        <div style="display:flex;border:1px solid #ede9e2;border-radius:8px;overflow:hidden;background:#fff;">
          <button v-for="t in viewTabs" :key="t.value" @click="viewMode = t.value"
            :style="`padding:8px 16px;font-size:12px;font-weight:600;border:none;cursor:pointer;font-family:inherit;transition:background 0.15s,color 0.15s;background:${viewMode===t.value?'#1a2540':'transparent'};color:${viewMode===t.value?'#fff':'#6b7280'};`"
          >{{ t.label }}</button>
        </div>
      </div>

      <!-- Filters -->
      <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
        <div style="position:relative;flex:1;max-width:320px;">
          <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input v-model="search" type="text" placeholder="Search vocabulary..."
            style="width:100%;padding:10px 12px 10px 36px;border:1px solid #ede9e2;border-radius:8px;font-size:14px;color:#1a2540;background:#fff;outline:none;font-family:inherit;box-sizing:border-box;"
            @focus="e=>e.currentTarget.style.borderColor='#1a2540'"
            @blur="e=>e.currentTarget.style.borderColor='#ede9e2'"
          />
        </div>
        <select v-model="filterLevel"
          style="padding:10px 12px;border:1px solid #ede9e2;border-radius:8px;font-size:13px;color:#1a2540;background:#fff;outline:none;cursor:pointer;font-family:inherit;"
        >
          <option value="">All Levels</option>
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <!-- Flashcard view -->
      <div v-if="viewMode === 'flashcard'" style="max-width:480px;margin:0 auto;">
        <div v-if="filteredWords.length" style="text-align:center;margin-bottom:12px;">
          <span style="font-size:13px;color:#9ca3af;">{{ currentCardIndex + 1 }}/{{ filteredWords.length }}</span>
        </div>
        <div v-if="currentCard"
          style="background:#fff;border:1px solid #ede9e2;border-radius:16px;padding:48px 32px;text-align:center;cursor:pointer;min-height:220px;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:box-shadow 0.2s;"
          @click="flipped = !flipped"
          @mouseenter="e=>e.currentTarget.style.boxShadow='0 8px 24px rgba(26,37,64,0.1)'"
          @mouseleave="e=>e.currentTarget.style.boxShadow='none'"
        >
          <div v-if="!flipped">
            <p style="font-family:'Playfair Display',serif;font-size:36px;font-weight:700;color:#1a2540;margin:0 0 10px;">{{ currentCard.word }}</p>
            <p style="font-size:14px;color:#9ca3af;margin:0 0 16px;">{{ currentCard.pronunciation }}</p>
            <p style="font-size:12px;color:#c9c9c9;margin:0;">Click to reveal meaning</p>
          </div>
          <div v-else>
            <p style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:#c9a84c;margin:0 0 10px;">{{ currentCard.meaning }}</p>
            <p style="font-size:14px;color:#6b7280;font-style:italic;margin:0 0 16px;">{{ currentCard.example }}</p>
            <span :style="`font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:${currentCard.level==='easy'?'#dcfce7':currentCard.level==='hard'?'#fee2e2':'#fef9c3'};color:${currentCard.level==='easy'?'#16a34a':currentCard.level==='hard'?'#dc2626':'#ca8a04'};`">
              {{ currentCard.level }}
            </span>
          </div>
        </div>
        <div style="display:flex;justify-content:center;gap:12px;margin-top:20px;">
          <button @click="prevCard" :disabled="currentCardIndex === 0"
            style="padding:10px 20px;background:#fff;color:#1a2540;font-size:13px;font-weight:600;border:1px solid #ede9e2;border-radius:8px;cursor:pointer;font-family:inherit;transition:background 0.15s;"
            @mouseenter="e=>e.currentTarget.style.background='#faf8f4'"
            @mouseleave="e=>e.currentTarget.style.background='#fff'"
          >← Prev</button>
          <button @click="flipped = !flipped"
            style="padding:10px 20px;background:#faf8f4;color:#1a2540;font-size:13px;font-weight:600;border:1px solid #ede9e2;border-radius:8px;cursor:pointer;font-family:inherit;"
          >Flip</button>
          <button @click="nextCard" :disabled="currentCardIndex >= filteredWords.length - 1"
            style="padding:10px 20px;background:#1a2540;color:#fff;font-size:13px;font-weight:600;border:none;border-radius:8px;cursor:pointer;font-family:inherit;transition:opacity 0.15s;"
            @mouseenter="e=>e.currentTarget.style.opacity='0.88'"
            @mouseleave="e=>e.currentTarget.style.opacity='1'"
          >Next →</button>
        </div>
      </div>

      <!-- List view -->
      <div v-else>
        <div v-if="filteredWords.length" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
          <div v-for="w in filteredWords" :key="w.id"
            style="background:#fff;border:1px solid #ede9e2;border-radius:12px;padding:20px;transition:box-shadow 0.2s,transform 0.2s;"
            @mouseenter="e=>{e.currentTarget.style.boxShadow='0 6px 20px rgba(26,37,64,0.08)';e.currentTarget.style.transform='translateY(-2px)'}"
            @mouseleave="e=>{e.currentTarget.style.boxShadow='none';e.currentTarget.style.transform='none'}"
          >
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
              <div>
                <p style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#1a2540;margin:0 0 2px;">{{ w.word }}</p>
                <p style="font-size:11px;color:#9ca3af;margin:0;">{{ w.pronunciation }}</p>
              </div>
              <span :style="`font-size:10px;font-weight:600;padding:3px 8px;border-radius:20px;flex-shrink:0;background:${w.level==='easy'?'#dcfce7':w.level==='hard'?'#fee2e2':'#fef9c3'};color:${w.level==='easy'?'#16a34a':w.level==='hard'?'#dc2626':'#ca8a04'};`">
                {{ w.level }}
              </span>
            </div>
            <p style="font-size:14px;font-weight:600;color:#c9a84c;margin:0 0 6px;">{{ w.meaning }}</p>
            <p style="font-size:12px;color:#6b7280;font-style:italic;line-height:1.6;margin:0;">{{ w.example }}</p>
          </div>
        </div>
        <div v-else style="text-align:center;padding:64px 24px;">
          <div style="font-size:36px;margin-bottom:12px;">📝</div>
          <p style="font-size:15px;font-weight:600;color:#1a2540;margin:0 0 6px;">No vocabulary found</p>
          <p style="font-size:13px;color:#9ca3af;margin:0;">Try changing your filters or search term.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const viewMode    = ref('list')
const search      = ref('')
const filterLevel = ref('')
const flipped     = ref(false)
const currentCardIndex = ref(0)

const viewTabs = [
  { value: 'list',      label: 'List' },
  { value: 'flashcard', label: 'Flashcard' },
]

const words = ref([
  { id: 1, word: 'Eloquent',     pronunciation: '/ˈɛl.ə.kwənt/',      meaning: 'Fluent and persuasive in speaking', example: 'She gave an eloquent speech.',       level: 'medium' },
  { id: 2, word: 'Perseverance', pronunciation: '/ˌpɜː.sɪˈvɪər.əns/', meaning: 'Persistence despite difficulty',    example: 'Success requires perseverance.',    level: 'hard' },
  { id: 3, word: 'Ambiguous',    pronunciation: '/æmˈbɪɡ.ju.əs/',      meaning: 'Open to more than one meaning',    example: 'The instructions were ambiguous.', level: 'hard' },
  { id: 4, word: 'Diligent',     pronunciation: '/ˈdɪl.ɪ.dʒənt/',      meaning: 'Hardworking and careful',          example: 'She is a diligent student.',        level: 'easy' },
  { id: 5, word: 'Innovative',   pronunciation: '/ˈɪn.ə.veɪ.tɪv/',     meaning: 'Introducing new ideas',            example: 'An innovative approach to learning.', level: 'medium' },
  { id: 6, word: 'Resilient',    pronunciation: '/rɪˈzɪl.i.ənt/',      meaning: 'Able to recover quickly',          example: 'Children are remarkably resilient.', level: 'hard' },
])

const filteredWords = computed(() => {
  let result = words.value
  if (search.value) result = result.filter(w =>
    w.word.toLowerCase().includes(search.value.toLowerCase()) ||
    w.meaning.toLowerCase().includes(search.value.toLowerCase())
  )
  if (filterLevel.value) result = result.filter(w => w.level === filterLevel.value)
  return result
})

const currentCard = computed(() => filteredWords.value[currentCardIndex.value])

function nextCard() { if (currentCardIndex.value < filteredWords.value.length - 1) { currentCardIndex.value++; flipped.value = false } }
function prevCard() { if (currentCardIndex.value > 0) { currentCardIndex.value--; flipped.value = false } }
</script>
