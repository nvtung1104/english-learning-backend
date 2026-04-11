import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import { storageUrl } from './utils/url'
import './style.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Global helper: {{ $storage('/storage/...') }} or :src="$storage(url)"
app.config.globalProperties.$storage = storageUrl

app.mount('#app')