<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 mt mb">
                <div class="card">
                    <div class="card-header text-center backcolor">
                        <h5>Lista de eventos</h5>
                    </div>

                    <div class="card-body">
                        <ul v-if="events != null && events.length > 0">
                            <li v-for="(event, index) of events" :key="index">
                                <p v-text="event.title"></p>
                                <p v-text="event.slug"></p>
                                <p v-text="'Miniatura'"></p>
                                <img :src="`/storage/${ event.image }`" alt="" width="20%">
                                <button @click="redirect(event.id, index)" class="btn btn-success">Ver</button>
                                <br>
                                <hr>
                            </li>
                        </ul>
                        <h5 v-else>
                            <b>No hay eventos para mostrar</b>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            events: []
        }
    },
    methods: {
        redirect(id, index) {
            if(this.user != undefined && this.user == 'guest') {
                let anchor = document.createElement('a')
                anchor.href = `/event/${id}/?guest=true`
                anchor.click()
            } else {
                this.$router.push({ 
                name: 'AnEvent', 
                params: {
                        id,
                        event: this.events[index]
                    }
                })
            }
        },
        async getEvents() {
            try {
                let url = `/all/events`
                const responses = await axios.get(url);
                if(responses.data) {
                    this.events = responses.data
                }                
            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted() {
        this.getEvents()
    }
}
</script>