import { ref, onMounted, onBeforeUnmount } from 'vue'

/**
 * @param {string} text - full text to type
 * @param {object} options
 * @param {number} options.typeSpeed - ms per char when typing (default 60)
 * @param {number} options.deleteSpeed - ms per char when deleting (default 30)
 * @param {number} options.pause - ms to wait after fully typed (default 30000)
 * @param {number} options.startDelay - ms before starting (default 300)
 */
export function useTypewriter(text, { typeSpeed = 60, deleteSpeed = 30, pause = 30000, startDelay = 300 } = {}) {
  const displayed = ref('')
  let timer = null

  function run() {
    function typeStep() {
      if (displayed.value.length < text.length) {
        displayed.value = text.slice(0, displayed.value.length + 1)
        timer = setTimeout(typeStep, typeSpeed)
      } else {
        timer = setTimeout(deleteStep, pause)
      }
    }

    function deleteStep() {
      if (displayed.value.length > 0) {
        displayed.value = displayed.value.slice(0, -1)
        timer = setTimeout(deleteStep, deleteSpeed)
      } else {
        timer = setTimeout(typeStep, 400)
      }
    }

    timer = setTimeout(typeStep, startDelay)
  }

  onMounted(run)
  onBeforeUnmount(() => { if (timer) clearTimeout(timer) })

  return { displayed }
}
