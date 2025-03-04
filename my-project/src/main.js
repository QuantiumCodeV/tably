import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

// Настройка axios
axios.defaults.baseURL = 'http://127.0.0.1:8000/api'

createApp(App)
  .use(router)
  .use(store)
  .mount('#app')
