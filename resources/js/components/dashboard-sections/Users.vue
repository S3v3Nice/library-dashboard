<script setup>
import "bootstrap-icons/font/bootstrap-icons.css";
import { ref } from "vue";

const items = ref([]);

axios.get('/api/users').then((response) => {
  items.value = response.data
})

const editItem = (item) => {
  // Логика для редактирования записи
  console.log("Edit item:", item);
};

const deleteItem = (itemId) => {
  // Логика для удаления записи
  console.log("Delete item:", itemId);
};
</script>

<template>
  <table class="table">
    <thead>
    <tr>
      <th>Email</th>
      <th>Имя</th>
      <th>Фамилия</th>
      <th>Отчество</th>
      <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in items" :key="item.id">
      <td>{{ item.email }}</td>
      <td>{{ item.first_name }}</td>
      <td>{{ item.last_name }}</td>
      <td>{{ item.patronymic }}</td>
      <td>
        <button @click="editItem(item)" class="btn btn-sm btn-success me-2">
          <i class="bi bi-pencil-fill"></i> <!-- Заполненная иконка для редактирования -->
        </button>
        <button @click="deleteItem(item.id)" class="btn btn-sm btn-danger">
          <i class="bi bi-trash-fill"></i> <!-- Заполненная иконка для удаления -->
        </button>
      </td>
    </tr>
    </tbody>
  </table>
</template>

<style scoped>

</style>
