<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const authors = ref([])
const editedAuthor = ref({})
const addAuthorForm = ref(null)
const editAuthorForm = ref(null)
const deleteAuthorForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshAuthors()
  addAuthorForm.value = Modal.getOrCreateInstance('#addAuthorForm', {})
  editAuthorForm.value = Modal.getOrCreateInstance('#editAuthorForm', {})
  deleteAuthorForm.value = Modal.getOrCreateInstance('#confirmDeleteAuthorForm', {})
  document.getElementById('addAuthorForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editAuthorForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshAuthors() {
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/authors').then((response) => {
    authors.value = response.data
  })
}

function showAddForm() {
  editedAuthor.value = {}
  addAuthorForm.value.show()
}

function submitAddAuthor() {
  axios.post('/api/author', editedAuthor.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshAuthors()
    editedAuthor.value = {}
    addAuthorForm.value.hide()
  })
}

function showEditForm(author) {
  editedAuthor.value = {...author}
  editAuthorForm.value.show()
}

function submitEditAuthor() {
  axios.put(`/api/author/${editedAuthor.value.id}`, editedAuthor.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshAuthors()
    editedAuthor.value = {}
    editAuthorForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteAuthorForm.value.show()
  editedAuthor.value = {id}
}

function confirmDeleteAuthor() {
  axios.delete(`/api/author/${editedAuthor.value.id}`).then(() => {
    refreshAuthors()
    editedAuthor.value = {}
    deleteAuthorForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <div>
    <!-- Кнопка для открытия формы добавления нового автора -->
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить автора
    </button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Отчество</th>
        <th scope="col">Действия</th>
      </tr>
      </thead>
      <tbody>
      <!-- Рендеринг данных об авторах -->
      <tr v-for="author in authors" :key="author.id">
        <td>{{ author.first_name }}</td>
        <td>{{ author.last_name }}</td>
        <td>{{ author.patronymic }}</td>
        <td>
          <button @click="showEditForm(author)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(author.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      <!-- Конец рендеринга данных -->
      </tbody>
    </table>

    <!-- Модальное окно для добавления автора -->
    <div class="modal fade" id="addAuthorForm" tabindex="-1" aria-labelledby="addAuthorFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addAuthorFormLabel">Добавить автора</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddAuthor">
              <div class="mb-3">
                <label for="addAuthorFirstName" class="form-label">Имя</label>
                <input v-model="editedAuthor.first_name" type="text" class="form-control" id="addAuthorFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>
              <div class="mb-3">
                <label for="addAuthorLastName" class="form-label">Фамилия</label>
                <input v-model="editedAuthor.last_name" type="text" class="form-control" id="addAuthorLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>
              <div class="mb-3">
                <label for="addAuthorMiddleName" class="form-label">Отчество</label>
                <input v-model="editedAuthor.patronymic" type="text" class="form-control" id="addAuthorMiddleName"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
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

    <!-- Модальное окно для редактирования автора -->
    <div class="modal fade" id="editAuthorForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать автора</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditAuthor">
              <div class="mb-3">
                <label for="editAuthorFirstName" class="form-label">Имя</label>
                <input v-model="editedAuthor.first_name" type="text" class="form-control" id="editAuthorFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>
              <div class="mb-3">
                <label for="editAuthorLastName" class="form-label">Фамилия</label>
                <input v-model="editedAuthor.last_name" type="text" class="form-control" id="editAuthorLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>
              <div class="mb-3">
                <label for="editAuthorMiddleName" class="form-label">Отчество</label>
                <input v-model="editedAuthor.patronymic" type="text" class="form-control" id="editAuthorMiddleName"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
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

    <!-- Модальное окно для подтверждения удаления автора -->
    <div class="modal fade" id="confirmDeleteAuthorForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить этого автора?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteAuthor" type="button" class="btn btn-danger">Удалить</button>
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
