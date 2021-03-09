require('./bootstrap');
// Asignar Vue al objeto de ventana, para que este disponible en toda la app
window.Vue = require('vue').default;
// Importar librerías y ficheros necesarios
import store from './store'
import router from './router'
import VueMaterial from 'vue-material'
import { MdButton, MdContent, MdTabs } from 'vue-material/dist/components'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'
// Componentes de Materialize
Vue.use(MdButton)
Vue.use(MdContent)
Vue.use(MdTabs)
Vue.use(VueMaterial)
// Cargar componentes
Vue.component('header-component', require('./components/header/headerComponent.vue').default);
Vue.component('all-event-component', require('./pages/event/allEventComponent').default);
Vue.component('an-event-component', require('./pages/event/anEventComponent').default);
// Instancia de vue
const app = new Vue({
    el: '#app',
    router,
    store
});