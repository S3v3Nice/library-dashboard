<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const isLoading = ref(false)
const books = ref([])
const authors = ref([]);
const genres = ref([]);
const editedBook = ref({})
const addBookForm = ref(null)
const editBookForm = ref(null)
const deleteBookForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshBooks()
  loadAuthors()
  loadGenres()
  addBookForm.value = Modal.getOrCreateInstance('#addBookForm', {})
  editBookForm.value = Modal.getOrCreateInstance('#editBookForm', {})
  deleteBookForm.value = Modal.getOrCreateInstance('#confirmDeleteBookForm', {})
  document.getElementById('addBookForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editBookForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshBooks() {
  isLoading.value = true
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/books').then((response) => {
    books.value = response.data
    isLoading.value = false
  })
}

async function loadAuthors() {
  await axios.get('/api/authors').then((response) => {
    authors.value = response.data
  })
}

async function loadGenres() {
  await axios.get('/api/genres').then((response) => {
    genres.value = response.data
  })
}

function showAddForm() {
  editedBook.value = {}
  addBookForm.value.show()
}

function submitAddBook() {
  axios.post('/api/book', editedBook.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshBooks()
    editedBook.value = {}
    addBookForm.value.hide()
  })
}

function showEditForm(book) {
  editedBook.value = {...book}
  editBookForm.value.show()
}

function submitEditBook() {
  axios.put(`/api/book/${editedBook.value.id}`, editedBook.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshBooks()
    editedBook.value = {}
    editBookForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteBookForm.value.show()
  editedBook.value = {id}
}

function confirmDeleteBook() {
  axios.delete(`/api/book/${editedBook.value.id}`).then(() => {
    refreshBooks()
    editedBook.value = {}
    deleteBookForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <div>
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить книгу
    </button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Название</th>
        <th scope="col">Автор</th>
        <th scope="col">Год</th>
        <th scope="col">Жанр</th>
        <th scope="col">Кол-во</th>
        <th scope="col">Действия</th>
      </tr>
      </thead>
      <tbody>
      <tr v-if="isLoading">
        <td colspan="100%">
          <div class="d-flex justify-content-center align-items-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Загрузка...</span>
            </div>
          </div>
        </td>
      </tr>
      <tr v-else v-for="book in books" :key="book.id">
        <td>{{ book.name }}</td>
        <td>{{ `${book.author.last_name} ${book.author.first_name[0]}.${book.author.patronymic[0]}.`}}</td>
        <td>{{ book.year }}</td>
        <td>{{ book.genre.name }}</td>
        <td>{{ book.count }}</td>
        <td>
          <button @click="showEditForm(book)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(book.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Модальное окно для добавления книги -->
    <div class="modal fade" id="addBookForm" tabindex="-1" aria-labelledby="addBookFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addBookFormLabel">Добавить книгу</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddBook">
              <div class="mb-3">
                <label for="addBookName" class="form-label">Название</label>
                <input v-model="editedBook.name" type="text" class="form-control" id="addBookName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addBookAuthor" class="form-label">Автор</label>
                <select v-model="editedBook.author_id" class="form-select" id="addBookAuthor">
                  <option v-for="author in authors" :key="author.id" :value="author.id">{{ `${author.last_name} ${author.first_name[0]}.${author.patronymic[0]}.` }}</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="addBookYear" class="form-label">Год</label>
                <input v-model="editedBook.year" type="number" class="form-control" id="addBookYear"
                       :class="{ 'is-invalid': 'year' in formErrors }">
                <span v-if="'year' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['year'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addBookGenre" class="form-label">Жанр</label>
                <select v-model="editedBook.genre_id" class="form-select" id="addBookGenre">
                  <option v-for="genre in genres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="addBookCount" class="form-label">Количество</label>
                <input v-model="editedBook.count" type="number" class="form-control" id="addBookCount"
                       :class="{ 'is-invalid': 'count' in formErrors }">
                <span v-if="'count' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['count'][0] }}
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

    <!-- Модальное окно для редактирования книги -->
    <div class="modal fade" id="editBookForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать книгу</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditBook">
              <div class="mb-3">
                <label for="editBookName" class="form-label">Имя</label>
                <input v-model="editedBook.name" type="text" class="form-control" id="editBookName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editBookAuthor" class="form-label">Автор</label>
                <select v-model="editedBook.author_id" class="form-select" id="editBookAuthor">
                  <option v-for="author in authors" :key="author.id" :value="author.id">{{ `${author.last_name} ${author.first_name[0]}.${author.patronymic[0]}.` }}</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="editBookYear" class="form-label">Год</label>
                <input v-model="editedBook.year" type="number" class="form-control" id="editBookYear"
                       :class="{ 'is-invalid': 'year' in formErrors }">
                <span v-if="'year' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['year'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editBookGenre" class="form-label">Жанр</label>
                <select v-model="editedBook.genre_id" class="form-select" id="editBookGenre">
                  <option v-for="genre in genres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="editBookCount" class="form-label">Количество</label>
                <input v-model="editedBook.count" type="number" class="form-control" id="editBookCount"
                       :class="{ 'is-invalid': 'count' in formErrors }">
                <span v-if="'count' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['count'][0] }}
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

    <!-- Модальное окно для подтверждения удаления книги -->
    <div class="modal fade" id="confirmDeleteBookForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить эту книгу?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteBook" type="button" class="btn btn-danger">Удалить</button>
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

.table .btn {
  margin-right: 2px;
}

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

.modal-footer .btn {
  margin-right: 5px;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
  border: 0.25rem solid rgba(0, 0, 0, 0.125);
  border-right: 0.25rem solid #709CFF;
  border-radius: 50%;
  animation: spin 0.75s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
