// Importaciones
import Vue from 'vue'
import VueRouter from 'vue-router'
import homeComponent from '../pages/home/homeComponent.vue'
import createEventComponent from '../pages/event/createEventComponent.vue'
import anEventComponent from '../pages/event/anEventComponent.vue'
import indexQAComponent from '../pages/question_answer/indexQAComponent.vue'
import indexQADeniedComponent from '../pages/report/indexQADeniedComponent.vue'
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
    },
    {
        path: '/event/:id',
        name: 'AnEvent',
        component: anEventComponent,
        props: true
    },
    {
        path: '/question-answer',
        name: 'QA',
        component: indexQAComponent,
        props: true
    },
    {
        path: '/report',
        name: 'Report',
        component: indexQADeniedComponent,
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