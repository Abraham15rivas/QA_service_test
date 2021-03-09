<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> ¡ Gestionar eventos ! </h3>
                <a v-if="!form" class="right-align btn-floating btn-large waves-effect waves-light green" @click="form = !form">
                    <i class="material-icons">add</i>
                </a>
                <a v-else class="right-align btn-floating btn-large waves-effect waves-light red" @click="form = !form; clear()">
                    <i class="material-icons">cancel</i>
                </a>
                <div class="mt mb" v-if="form">
                    <form id="form" @submit.prevent="setEvent" enctype="multipart/form-data">
                        <label for="title">Título</label>
                        <input type="text" id="title" v-model="title" class="form-control" required>
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" v-model="slug" class="form-control" required>
                        <label for="description">Descripcción</label>
                        <textarea class="browser-default" id="despcrition" cols="30" rows="10" v-model="description"></textarea>
                        <md-field>
                            <label>Miniatura</label>
                            <md-file @change="onFileUpload" accept="image/*" required/>
                        </md-field>
                        <label v-if="image" for="description">Vista previa</label>
                        <img v-if="image" :src="preview" alt="vista previa" width="10%">
                        <br>
                        <label for="video">Url Vídeo</label>
                        <input type="url" id="video" v-model="video" class="form-control" required>
                        <label for="category">Opciones</label>
                        <div>
                            <md-checkbox v-model="qa">Permitir preguntas</md-checkbox>
                            <md-checkbox v-model="status">Publicar</md-checkbox>
                        </div>
                        <button class="btn center-align green" v-if="form && !edit">Enviar</button>
                    </form>
                    <button type="buttton" class="btn center-align green" v-if="edit" @click="putEvent">Guardar</button>
                </div>
                <div v-else>
                    <md-table class="mt mb" v-if="events.length > 0">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Titulo</md-table-head>
                            <md-table-head>Slug</md-table-head>
                            <md-table-head>Descripcción</md-table-head>
                            <md-table-head>Miniatura</md-table-head>
                            <md-table-head>Vídeo</md-table-head>
                            <md-table-head>Permitir preguntas</md-table-head>
                            <md-table-head>Publicar</md-table-head>
                            <md-table-head>Acciones</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(event, index) in events" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ event.title }}</md-table-cell>
                            <md-table-cell>{{ event.slug }}</md-table-cell>
                            <md-table-cell>{{ event.description }}</md-table-cell>
                            <md-table-cell>
                                <img v-if="event.image" :src="`storage/${ event.image }`" alt="vista previa">
                            </md-table-cell>
                            <md-table-cell>{{ event.video }}</md-table-cell>
                            <md-table-cell>{{ event.qa ? 'Si' : 'No' }}</md-table-cell>
                            <md-table-cell>{{ event.status? 'Si' : 'No' }}</md-table-cell>
                            <md-table-cell>
                                <button class="btn btn-small blue" @click="editEvent(index)">Editar</button>
                                <button class="btn btn-small red" @click="delEvent(event.id)">Eliminar</button>
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                    <h4 class="text-center" v-else>No hay datos para mostrar</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
export default {
    data() {
        return {
            form: false,
            edit: false,
            events: [],
            id: null,
            title: null,
            slug: null,
            description: null,
            image: null,
            video: null,
            qa: false,
            status: false,
            lastFile: null
        }
    },
    watch: {
        qa() {
            if(this.qa == null) {
                this.qa = false
            }
        },
        status() {
            if(this.status == null) {
                this.status = false
            }
        }
    },
    methods: {
        onFileUpload(evt) {
            this.image = evt.target.files[0]
            this.lastFile = URL.createObjectURL(this.image)
        },
        editEvent(index) {
            let data = this.events[index]
            this.edit = true
            this.form = true
            this.id = data.id
            this.title = data.title
            this.slug = data.slug
            this.description = data.description
            this.image = data.image
            this.video = data.video
            this.qa = Boolean(data.qa)
            this.status = Boolean(data.status)
            this.lastFile = data.image
        },
        clear() {
            this.form = false
            this.edit = false
            this.id = null
            this.title = null
            this.slug = null
            this.description = null
            this.image = null
            this.video = null
            this.qa = false
            this.status = false
            this.lastFile = null
        },
        async getEvents() {
            try {
                let url = `/moderator/events`
                const response = await axios.get(url)
                if (response.data) {
                    this.events = response.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async setEvent() {
            try {
                let url = `/moderator/events/store`
                let params = new FormData(document.getElementById("form"))
                params.append("title", this.title)
                params.append("slug", this.slug)
                params.append("description", this.description)
                params.append("image", this.image)
                params.append("video", this.video)
                params.append("qa", Boolean(this.qa))
                params.append("status", Boolean(this.status))

                const response = await axios.post(url, params)
                if (response.data) {
                    this.getEvents()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Evento guardado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }                
            } catch (error) {
                console.log(error)
            }
        },
        async putEvent() {
            try {
                let url = `/moderator/events/${this.id}/update`
                let params = new FormData(document.getElementById("form"))
                params.append("title", this.title)
                params.append("slug", this.slug)
                params.append("description", this.description)
                params.append("image", this.image)
                params.append("video", this.video)
                params.append("qa", Boolean(this.qa))
                params.append("status", Boolean(this.status))

                const response = await axios.post(url, params)
                if (response.data) {
                    this.getEvents()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Evento actualizado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }                
            } catch (error) {
                console.log(error)
            }
        },
        async delEvent(id) {
            try {
                let url = `/moderator/events/${id}/delete`
                const response = await axios.delete(url)
                if (response.data) {
                    this.getEvents()
                    this.clear()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Evento eliminado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } 
            } catch (error) {
                console.log(error)
            }
        }
    },
    computed: {
        preview() {
            if(this.edit) {
                if(this.lastFile.startsWith('blob')) {
                    return this.lastFile
                } else {
                    return `storage/${ this.lastFile }`
                }
            } else {
                return this.lastFile
            }
        }
    },
    mounted() {
        this.getEvents()
    }
}
</script>