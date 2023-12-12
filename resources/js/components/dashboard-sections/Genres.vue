<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const genres = ref([])
const editedGenre = ref({})
const addGenreForm = ref(null)
const editGenreForm = ref(null)
const deleteGenreForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshGenres()
  addGenreForm.value = Modal.getOrCreateInstance('#addGenreForm', {})
  editGenreForm.value = Modal.getOrCreateInstance('#editGenreForm', {})
  deleteGenreForm.value = Modal.getOrCreateInstance('#confirmDeleteGenreForm', {})
  document.getElementById('addGenreForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editGenreForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshGenres() {
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/genres').then((response) => {
    genres.value = response.data
  })
}

function showAddForm() {
  editedGenre.value = {}
  addGenreForm.value.show()
}

function submitAddGenre() {
  axios.post('/api/genre', editedGenre.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshGenres()
    editedGenre.value = {}
    addGenreForm.value.hide()
  })
}

function showEditForm(genre) {
  editedGenre.value = {...genre}
  editGenreForm.value.show()
}

function submitEditGenre() {
  axios.put(`/api/genre/${editedGenre.value.id}`, editedGenre.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshGenres()
    editedGenre.value = {}
    editGenreForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteGenreForm.value.show()
  editedGenre.value = {id}
}

function confirmDeleteGenre() {
  axios.delete(`/api/genre/${editedGenre.value.id}`).then(() => {
    refreshGenres()
    editedGenre.value = {}
    deleteGenreForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <div>
    <!-- Кнопка для открытия формы добавления нового жанра книги -->
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить жанр
    </button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Название</th>
        <th scope="col">Действия</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="genre in genres" :key="genre.id">
        <td>{{ genre.name }}</td>
        <td>
          <button @click="showEditForm(genre)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(genre.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Модальное окно для добавления жанра -->
    <div class="modal fade" id="addGenreForm" tabindex="-1" aria-labelledby="addGenreFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addGenreFormLabel">Добавить жанр</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddGenre">
              <div class="mb-3">
                <label for="addGenreName" class="form-label">Имя</label>
                <input v-model="editedGenre.name" type="text" class="form-control" id="addGenreName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Добавить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Модальное окно для редактирования жанра -->
    <div class="modal fade" id="editGenreForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать жанр</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditGenre">
              <div class="mb-3">
                <label for="editGenreName" class="form-label">Имя</label>
                <input v-model="editedGenre.name" type="text" class="form-control" id="editGenreName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Модальное окно для подтверждения удаления жанра -->
    <div class="modal fade" id="confirmDeleteGenreForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить этот жанр книги?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteGenre" type="button" class="btn btn-danger">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
  border-collapse: collapse;
  border-spacing: 0;
}

.table th,
.table td {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table thead th {
  vertical-align: bottom;
  border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
  border-top: 2px solid #dee2e6;
}

.table .thead-dark th {
  color: #fff;
  background-color: #709CFF;
}

/* Дополнительные стили для кнопок внутри таблицы */
.table .btn {
  margin-right: 2px;
}

/* Стили для модальных окон (подстройте под свой дизайн) */
.modal-content {
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.125);
  border-radius: 0.25rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  padding: 1rem;
  border-top: 1px solid #dee2e6;
}

/* Дополнительные стили для кнопок внутри модальных окон */
.modal-footer .btn {
  margin-right: 5px;
}
</style>
