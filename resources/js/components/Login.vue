<script setup>
import Header from "./Header.vue";
import {ref} from "vue";
import router from "../router";
import {useStore} from "vuex";

const store = useStore();

const loginData = ref({
  email: '',
  password: '',
  remember: false
})
const errors = ref({})

async function login() {
  await axios.get('/sanctum/csrf-cookie')
  await axios.post('/login', loginData.value).then((response) => {
    if (!response.data.success) {
      errors.value = response.data.errors
      return
    }

    store.dispatch('auth/fetch').then(() => {
      router.push({name: 'home'})
    })
  })
}

</script>

<template>
  <Header></Header>

  <div class="container login-container">
    <main class="form-signin w-50 m-auto">
      <form @submit.prevent="login">
        <h1 class="h3 mb-3 fw-normal">Вход</h1>

        <div class="form-floating">
          <input v-model="loginData.email" name="email" type="email" class="form-control mb-2"
                 :class="{ 'is-invalid': 'email' in errors }"
                 id="floatingInput">
          <label for="floatingInput">Адрес электронной почты</label>
          <span v-if="'email' in errors" class="invalid-feedback mb-3" role="alert">
            {{ errors['email'][0] }}
          </span>
        </div>

        <div class="form-floating">
          <input v-model="loginData.password" name="password" type="password" class="form-control mb-2"
                 :class="{ 'is-invalid': 'password' in errors }"
                 id="floatingPassword">
          <label for="floatingPassword">Пароль</label>
          <span v-if="'password' in errors" class="invalid-feedback mb-3" role="alert">
            {{ errors['password'][0] }}
          </span>
        </div>

        <div class="checkbox mb-3">
          <label>
            <input v-model="loginData.remember" name="remember" type="checkbox"> Запомнить
          </label>
        </div>

        <button class="w-100 btn btn-lg btn-primary mb-2">Войти</button>
      </form>
    </main>
  </div>
</template>

<style scoped>

</style>