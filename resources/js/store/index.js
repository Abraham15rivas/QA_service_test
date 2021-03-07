import axios from 'axios'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state:{
        user: null
    },
    mutations:{
        setUser(state, payload) {
            state.user = payload
        }
    },
    actions:{
        async getUser (context) {
            try {
                let url = `/user`
                const responses = await axios.get(url);
                if(responses.data) {
                    const user = responses.data
                    context.commit('setUser', user);
                }                
            } catch (error) {
                console.log(error)
            }
        }
    },
    getters: {
        getRoleUser(state) {
            if(state.user) {
                return state.user.role_id
            }
        },
        getNameUser(state) {
            if(state.user) {
                return state.user.name
            }
        }
    },
    modules:{}
})