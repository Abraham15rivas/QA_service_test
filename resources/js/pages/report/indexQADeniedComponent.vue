<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> ¡ Reporte de preguntas denegadas ! </h3>
                <div v-if="questions.length > 0">
                    <md-table class="mt mb">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Fecha</md-table-head>
                            <md-table-head>Pregunta</md-table-head>
                            <md-table-head>Status</md-table-head>
                            <md-table-head>Participante</md-table-head>
                            <md-table-head>Evento</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(question, index) in questions" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ question.created_at.slice(0, 10) }}</md-table-cell>
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
export default {
    data() {
        return {
            questions: []
        }
    },
    methods: {
        async getAllQuestions() {
            try {
                let url = `/questions/deneid`
                const responses = await axios.get(url)
                if(responses.data) {
                    this.questions = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        this.getAllQuestions()
    }
}
</script>