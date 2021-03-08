// Importaciones
import Vue from 'vue'
import VueRouter from 'vue-router'
import homeComponent from '../pages/home/homeComponent.vue'
import createEventComponent from '../pages/event/createEventComponent.vue'
// asignar
Vue.use(VueRouter)
// rutas
const routes = [
    {
        path: '/',
        name: 'Raiz',
        component: homeComponent,
        props: true
    },
    {
        path: '/home',
        name: 'Home',
        component: homeComponent,
        props: true
    },
    {
        path: '/event',
        name: 'Event',
        component: createEventComponent,
        props: true
    }
]
// instancia
const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})
// exportar objeto
export default router