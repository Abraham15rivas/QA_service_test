<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align" :class="id = getIdUser"> ¡ Mis preguntas ! </h3>
                <div v-if="validate">
                    <md-table class="mt mb">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Fecha</md-table-head>
                            <md-table-head>Hora</md-table-head>
                            <md-table-head>Pregunta</md-table-head>
                            <md-table-head>Status</md-table-head>
                            <md-table-head>Participante</md-table-head>
                            <md-table-head>Evento</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(question, index) in my_questions" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ question.created_at.slice(0, 10) }}</md-table-cell>
                            <md-table-cell>{{ question.created_at.slice(11, 19) }}</md-table-cell>
                            <md-table-cell>{{ question.content }}</md-table-cell>
                            <md-table-cell>{{ question.status }}</md-table-cell>
                            <md-table-cell>{{ JSON.parse(question.participant_data).email }}</md-table-cell>
                            <md-table-cell>{{ question.event.title }}</md-table-cell>
                        </md-table-row>
                    </md-table>
                </div>
                <h4 class="text-center" v-else>No hay datos para mostrar</h4>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    data() {
        return {
            my_questions: [],
            validate: false,
            id: null
        }
    },
    methods: {
        async getMyQuestions() {
            try {
                let url = `/user/questions/${this.id}`
                const responses = await axios.get(url)
                if(responses.data) {
                    this.my_questions = responses.data
                    if(this.my_questions.length > 0) {
                        this.validate = true
                    }
                }
            } catch (error) {
                console.log(error)
            }
        },
    },
    computed: {
        ...mapGetters(['getIdUser'])
    },
    mounted() {
        setTimeout(() => this.getMyQuestions(), 1000)
    }
}
</script>