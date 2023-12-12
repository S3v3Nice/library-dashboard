<script setup>
import {useStore} from "vuex";
import {computed} from "vue";
import router from "../router/index.js";

const store = useStore()

const isAuthenticated = computed(() => {
  return store.getters["auth/isAuthenticated"]
})
const user = computed(() => {
  return store.getters["auth/user"]
})

async function logout() {
  await axios.post('/logout').then(() => {
    store.dispatch('auth/reset')
    router.push({name: 'login'})
  }).catch(({response: {data}}) => {
    alert(data.message)
  })
}
</script>

<template>
    <div class="container-lg">
      <header
          class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <router-link :to="{ name: 'home' }">
          <img class="logo" src="/public/images/logo.png" height="45" alt="Logo"/>
        </router-link>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navs">
        </ul>

        <div v-if="!isAuthenticated" class="col-md-3 text-end">
          <router-link :to="{ name: 'login' }" type="button" class="btn btn-outline-primary me-2">Войти</router-link>
        </div>
        <a v-else id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
           aria-haspopup="true" aria-expanded="false">
          {{ user.email }}
        </a>

        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
<!--          <router-link :to="{ name: 'profile' }" class="dropdown-item">-->
<!--            Личный кабинет-->
<!--          </router-link>-->
          <a type="button" class="dropdown-item" @click="logout">
            Выйти
          </a>
        </div>
      </header>
    </div>
</template>

<style scoped>

</style>