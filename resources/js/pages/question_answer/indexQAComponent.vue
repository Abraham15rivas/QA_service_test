<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> Manage Questions ! </h3>
                <div v-if="questions.length > 0">
                    <md-table class="mt mb">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Date</md-table-head>
                            <md-table-head>Time</md-table-head>
                            <md-table-head>Question</md-table-head>
                            <md-table-head>Status</md-table-head>
                            <md-table-head>Participant</md-table-head>
                            <md-table-head>Event</md-table-head>
                            <md-table-head>Actions</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(question, index) in questions" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ question.created_at.slice(0, 10) }}</md-table-cell>
                            <md-table-cell>{{ question.created_at.slice(11, 19) }}</md-table-cell>
                            <md-table-cell>{{ question.content }}</md-table-cell>
                            <md-table-cell>{{ question.status }}</md-table-cell>
                            <md-table-cell>{{ JSON.parse(question.participant_data).email }}</md-table-cell>
                            <md-table-cell>{{ question.event.title }}</md-table-cell>
                            <md-table-cell v-if="question.status == 'revision'">
                                <button class="btn btn-small green" @click="approvedQuestion(question.id, question.content)">Pass</button>
                                <button class="btn btn-small red" @click="deneidQuestion(question.id)">Deny</button>
                            </md-table-cell>
                            <md-table-cell v-else>
                                <button 
                                    :class="[question.status == 'approved' ? 'btn btn-small green disabled' : 'btn btn-small red disabled']" 
                                    v-text="question.status == 'approved' ? 'Approved' : 'Denied'"
                                ></button>
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                </div>
                <h4 class="text-center" v-else>No data to display</h4>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
export default {
    data() {
        return {
            questions: []
        }
    },
    methods: {
        async getAllQuestions() {
            try {
                let url = `/moderator/questions/all`
                const responses = await axios.get(url)
                if(responses.data) {
                    this.questions = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async deneidQuestion(id) {
            try {
                let url = `/moderator/question/deneid`
                const params = {
                    id
                }
                const responses = await axios.post(url, params)
                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pregunta denegada correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getAllQuestions()
                }
            } catch (error) {
                console.log(error)
            }
        },
        async setAnswerQuestion(content, question_id) {
            try {
                let url = `/moderator/answer/approved`
                const params = {
                    content,
                    question_id
                }
                const responses = await axios.post(url, params)
                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Pregunta respondida correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getAllQuestions()
                }
            } catch (error) {
                console.log(error)
            }
        },
        async approvedQuestion(question_id, content) {
            const { value: response } = await Swal.fire({
                title: '¡ Responder a pregunta !',
                html: `<p>${content}</p>`,
                input: 'text',
                inputLabel: 'Tu respuesta: ',
                inputPlaceholder: 'Ingrese una respuesta aquí'
            })
            if (response) {
                Swal.fire(`Ingresó esta respuesta: ${response}`)
                this.setAnswerQuestion(response, question_id)
            }
        },
    },
    mounted() {
        this.getAllQuestions()
    }
}
</script>