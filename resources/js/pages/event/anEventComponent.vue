<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt mb">
                <div class="card">
                    <div class="card-header text-center backcolor">
                        <h5>Evento: ¡ {{ event_selected ? event_selected.title : ''}} !</h5>
                    </div>
                    <div class="card-body">
                        <h5 v-text="`Título: ${ event_selected ? event_selected.title : '' }`"></h5>
                        <p v-text="`Subtitulo: ${ event_selected ? event_selected.slug : '' }`"></p>
                        <p v-text="`Fecha de creación: ${ event_selected ? event_selected.created_at.slice(0, 10) : '' }`"></p>
                        <span id="embedA">
                            <iframe
                                class="mt altura"
                                width="100%" 
                                height="350px"
                                src="" 
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </span>
                        <p v-text="`Descripción: ${ event_selected ? event_selected.description : '' }`"></p>
                        <span v-if="event_selected != undefined && event_selected.qa">
                            <hr>
                            <p>Preguntas:</p>
                            <div v-if="questions.length > 0">
                                <ul>
                                    <li v-for="(question_approved, index) of questions" :key="index">
                                        <p v-text="`Autor: ${JSON.parse(question_approved.participant_data).email}`"></p>
                                        <p v-text="`Fecha: ${question_approved.created_at.slice(0, 10)}`"></p>
                                        <p v-text="`Hora: ${question_approved.created_at.slice(11, 19)}`"></p>
                                        <p v-text="`Pregunta: ${question_approved.content}`"></p>
                                        <span v-if="question_approved.answer != null">
                                            <div class="text-right">
                                                <h6>Respuesta del moderador:</h6>
                                                <blockquote v-text="question_approved.answer.content"></blockquote>
                                            </div>
                                        </span>
                                        <hr>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="text-center">
                                <span >
                                    <b>No hay preguntas para mostrar</b>
                                </span>
                            </div>
                            <hr>
                            <form id="form" @submit.prevent="validateQuestion">
                                <label for="question">Preguntar:</label>
                                <textarea class="browser-default" id="question" cols="30" rows="10" v-model="question"></textarea>
                                <div class="text-right mt">
                                    <button type="submit" class="btn green">Preguntar</button>
                                </div>
                            </form>
                        </span>
                        <hr>
                        <router-link to="/">
                            <button 
                                class="btn btn center"
                                @click="redirect"
                            >Volver</button>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
import { mapGetters } from 'vuex'
export default {
    props: ['event', 'user'],
    data() {
        return {
            url_video: null,
            event_selected: this.event,
            question: null,
            questions: [],
            email_participant: null,
            id: null
        }
    },
    methods: {
        validateQuestion() {
            if(this.question) {
                if(this.event_selected.user_id == this.getIdUser) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '¡No puedes preguntar en un evento posteada por tí!',
                    })
                    this.question = null
                }
                if(this.user != undefined && this.user == 'guest') {
                    this.setWithEmail()                   
                }
                if(this.getIdUser != null && this.getEmailUser != null) {
                    if(this.event_selected.user_id != this.getIdUser) {
                        this.setQuestion(this.getEmailUser, this.getIdUser)
                    }
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Oops...',
                    text: '¡No puedes estar vacío!',
                })
                this.question = null
            }
        },
        async setWithEmail() {
            const { value: email } = await Swal.fire({
                title: '¡ Hey primero considerar lo siguiente !',
                html: ` <p>¡Debes registrate, iniciar sesión o enviar tu pregunta con tú correo!</p>
                        <br>
                        <a href="/login">Login</a>
                        <br>
                        <a href="/register">Registrate</a>`,
                input: 'email',
                inputLabel: 'Tu correo electrónico',
                inputPlaceholder: 'Ingrese su dirección de correo electrónico'
            })
            if (email) {
                Swal.fire(`Ingresó email: ${email}`)
                this.email_participant = email
                this.setQuestion(email, null)
            }
        },
        async setQuestion(email, id) {
            try {
                let url = `/question/new`
                const params = {
                    content: this.question,
                    participant_data: {
                        email,
                        user_id: id
                    },
                    event_id: this.event_selected.id
                }
                const responses = await axios.post(url, params)
                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'info',
                        title: 'Pregunta enviada correctamente, está en revisón por el moderador una vez aprobada y respondida se mostrará en la lista ',
                        showConfirmButton: false,
                        timer: 5000
                    })
                    this.question = null
                    this.id = null
                    this.getQuestions()
                }
            } catch (error) {
                console.log(error)
            }
        },
        redirect() {
            if(this.user != undefined && this.user == 'guest') {
                window.location.href = `/`
            }
        },
        setUrlVideo() {
            this.url_video = this.event_selected != undefined ? this.event_selected.video : ''
        },
        urlIframe() {
            const iframe = document.getElementById('embedA').children[0]
            iframe.src = this.url_video.replace("watch?v=", "embed/")
        },
        async getQuestions() {
            try {
                let url = `/questions/approved/${this.event_selected.id}`
                const responses = await axios.get(url)
                if(responses.data) {
                    this.questions = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async getEvent() {
            try {
                let url = `/event/${ this.$route.params.id }`
                const responses = await axios.get(url)
                if(responses.data) {
                    this.event_selected = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        if(!this.event) {
            this.getEvent()
        }
        setTimeout(() => {
            this.setUrlVideo()
            this.urlIframe()
        }, 1000)
        if(this.event_selected != undefined && this.event_selected.qa) {
            this.getQuestions()
        }
    },
    computed: {
        ...mapGetters(['getRoleUser', 'getIdUser', 'getEmailUser'])
    }
}
</script>

<style scoped>
.altura {
    height: 350px !important;
}
</style>