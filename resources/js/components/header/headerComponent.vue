<template>
  <div>
    <header>
      <div class="navbar-fixed" style="height: 95px">
        <nav class="navbar blue darken-1" style="height: 95px">
          <div class="nav-wrapper aling-v">
            <div class="linea">
              <a
                data-target="slide-out"
                class="sidenav-trigger left"
                v-bind:class="{ 'button-collapse': sidebar }"
                v-on:click="collapse()"
              >
                <i class="material-icons white-text">menu</i>
              </a>
            </div>
            <div class="brand-logo linea">
              <img
                class="circle-nav"
                src="images/default.png"
                alt=""
              />
            </div>            
          </div>
        </nav>
      </div>
      <ul
        class="sidenav sidenav-fixed"
        v-bind:class="{ 'sidenav-collapse': sidebar }"
        id="slide-out"
      >
        <li>
          <div class="logo-container" style="padding:20px">
            <img src="images/logo.png" class="logo-sidebar" />
          </div>
        </li>
        <li>
          <ul class="collapsible collapsible-accordion">
            <li class="waves-effect">
              <h5 class="black-text name center">Menú</h5>
            </li>
            <li class="waves-effect">
              <router-link 
                to="/" 
                class="collapsible-header" 
                tabindex="0"
              >
                Inicio<i class="material-icons">home</i>
              </router-link>
            </li>
            <li class="waves-effect" v-if="getRoleUser == 2">
              <router-link
                to="/my-question"
                class="collapsible-header"
                tabindex="0"
              >
                Mis preguntas<i class="material-icons">person</i>
              </router-link>
            </li>
            <li class="waves-effect" v-if="getRoleUser == 1">
              <router-link
                to="/event"
                class="collapsible-header"
                tabindex="0"
              >
                Eventos<i class="material-icons">event_note</i>
              </router-link>
            </li>
            <li class="waves-effect" v-if="getRoleUser == 1">
              <router-link
                to="/question-answer"
                class="collapsible-header"
                tabindex="0"
              >
                Q&A<i class="material-icons">question_answer</i>
              </router-link>
            </li>
            <li class="waves-effect" v-if="getRoleUser == 1">
              <router-link
                to="/report"
                class="collapsible-header"
                tabindex="0"
              >
                Reportes<i class="material-icons">description</i>
              </router-link>
            </li>
            <li class="waves-effect" @click="logout">
              <router-link
                to="/"
                class="collapsible-header"
                tabindex="0"
              >
                Cerrar sesión<i class="material-icons">logout</i>
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </header>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  props: ['route'],
  name: "Header",
  data() {
    return {
      sidebar: false,
    };
  },
  methods: {
    collapse() {
      this.sidebar = !this.sidebar;
    },
    async logout() {
      try {
        const url = `${ this.route }`
        await axios.post(url)
        console.log('cerrado correctamente')
        window.location.href = "/"
      } catch (error) {
        console.log(error)
      }
    },
    ...mapActions(['getUser']),
  },
  computed: {
    ...mapGetters(['getRoleUser'])
  },
  mounted() {
    this.getUser()
  },
};
</script>

<style scoped>
.sidenav .logo-container {
  padding: 0 16px;
  height: 94px;
  line-height: 64px;
  font-size: 24px;
  box-sizing: content-box;
}

.sidenav .collapsible > li.waves-effect {
  display: block;
}

.sidenav .collapsible li,
.sidenav.fixed .collapsible li {
  padding: 0;
}


.logo-sidebar {
  height: 110px;
  margin: 0 auto;
  display: block;
}

.circle-nav {
  width: 50px;
  height: 50px;
  border: solid 1px;
  border-radius: 50%;
}

.brand-logo {
  position: fixed;
  right: 10px;
}

.aling-v {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

@media (max-width: 992px) {
  .sidenav-collapse {
    transform: translateX(0%);
    -webkit-transition: transform 300ms linear;
    -moz-transition: transform 300ms linear;
    -ms-transition: transform 300ms linear;
    -o-transition: transform 300ms linear;
    transition: transform 300ms linear;
  }

  .button-collapse {
    margin-left: 312px;
    -webkit-transition: margin-left 300ms linear;
    -moz-transition: margin-left 300ms linear;
    -ms-transition: margin-left 300ms linear;
    -o-transition: margin-left 300ms linear;
    transition: margin-left 300ms linear;
  }
}
</style>