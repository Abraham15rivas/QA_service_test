<template>
    <div class="padding">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align" > Welcome ! {{ getNameUser ? getNameUser : "" }} </h3>
            </div>
        </div>
        <div class="row">
            <div class="col m12 s12">
                <all-event-component />
            </div>
        </div>  
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    name: "Home",
    computed: {
        ...mapGetters(['getNameUser'])
    }
}
</script>

<style scoped>

.padding{
    padding-left: 50px;
    padding-right: 50px;
    padding-bottom: 100px;
    font-family: "Nunito", Times, serif;
}
.imagen-portada{ width: 400px; }
.section-imagen{
    display: flex;
    align-items: center;
    height: 40vh;
}
</style>
