<script setup>
import Header from "./Header.vue";
import "bootstrap-icons/font/bootstrap-icons.css";
import {useRoute} from "vue-router";
import {computed} from "vue";

const sections = [
  {
    name: 'Пользователи',
    icon: 'bi bi-person',
    route: 'users',
  },
  {
    name: 'Авторы',
    icon: 'bi bi-person',
    route: 'authors',
  },
  {
    name: 'Жанры',
    icon: 'bi bi-person',
    route: 'genres',
  },
  {
    name: 'Книги',
    icon: 'bi bi-book',
    route: 'books',
  },
  {
    name: 'Должности',
    icon: 'bi bi-briefcase',
    route: 'job-positions',
  },
  {
    name: 'Сотрудники',
    icon: 'bi bi-person-check',
    route: 'employees',
  },
  {
    name: 'Читатели',
    icon: 'bi bi-person-check-fill',
    route: 'readers',
  },
]

const currentRoute = computed(() => useRoute().name);
</script>

<template>
  <div>
    <Header></Header>
  </div>
  <div class="container-lg">
    <div class="row">
      <nav id="sidebar" class="col-md-3">
        <div class="sidebar-header">
          <h3>Управление</h3>
        </div>

        <ul class="list-unstyled components">
          <li v-for="(section, index) in sections" :key="index">
            <router-link :to="{ name: section.route }">
              <button
                  class="btn btn-block text-left"
                  :class="{ active: currentRoute === section.route }"
              >
                <i :class="section.icon"></i>
                <span class="ms-2">{{ section.name }}</span>
              </button>
            </router-link>
          </li>
        </ul>
      </nav>
      <div class="col-md-9">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<style scoped>
#sidebar {
  height: 100%;
  padding-top: 20px;
}

.sidebar-header {
  text-align: left;
  margin-bottom: 20px;
}

.components {
  padding: 0;
}

.btn-block {
  width: 100%;
  height: 60px;
}

.text-left {
  text-align: left !important;
}

.btn-block:hover {
  background-color: #407bff;
  color: #fff;
}

.active {
  background-color: #407bff;
  color: #fff;
}
</style>
