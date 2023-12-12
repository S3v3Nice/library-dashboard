<script setup>
import "bootstrap-icons/font/bootstrap-icons.css";
import {onMounted, ref} from "vue";
import {Modal} from 'bootstrap';
import axios from 'axios';

const items = ref([])
const editedItem = ref({})
const addForm = ref(null)
const editForm = ref(null)
const deleteForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshItems()
  addForm.value = Modal.getOrCreateInstance('#addForm', {})
  editForm.value = Modal.getOrCreateInstance('#editForm', {})
  deleteForm.value = Modal.getOrCreateInstance('#confirmDeleteModal', {})
  document.getElementById('addForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshItems() {
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/users').then((response) => {
    items.value = response.data
  })
}

function showAddForm(item) {
  editedItem.value = {...item}
  addForm.value.show()
}

function submitAddItem() {
  axios.post(`/api/user`, editedItem.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshItems()
    editedItem.value = {}
    addForm.value.hide()
  })
}

function showEditForm(item) {
  editedItem.value = {...item}
  editForm.value.show()
}

function submitEditItem() {
  axios.put(`/api/user/${editedItem.value.id}`, editedItem.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshItems()
    editedItem.value = {}
    editForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteForm.value.show()
  editedItem.value = {id}
}

function confirmDelete() {
  axios.delete(`/api/user/${editedItem.value.id}`).then(() => {
    refreshItems()
    editedItem.value = {}
    deleteForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <!-- Кнопка для открытия формы добавления нового пользователя -->
  <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
    <i class="bi bi-plus"></i> Добавить
  </button>

  <!--  Табличная часть-->
  <table class="table mt-3">
    <thead class="thead-dark">
    <tr>
      <th scope="col">Email</th>
      <th scope="col">Имя</th>
      <th scope="col">Фамилия</th>
      <th scope="col">Действия</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="item in items" :key="item.id">
      <td>{{ item.email }}</td>
      <td>{{ item.first_name }}</td>
      <td>{{ item.last_name }}</td>
      <td>
        <button @click="showEditForm(item)" class="btn btn-sm btn-success me-2">
          <i class="bi bi-pencil-fill"></i>
        </button>
        <button @click="showDeleteForm(item.id)" class="btn btn-sm btn-danger">
          <i class="bi bi-trash-fill"></i>
        </button>
      </td>
    </tr>
    </tbody>
  </table>

  <!-- Модальное окно для добавления пользователя -->
  <div class="modal fade" id="addForm" tabindex="-1" aria-labelledby="addFormLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addFormLabel">Добавить пользователя</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitAddItem">
            <div class="mb-3">
              <label for="addFirstName" class="form-label">Имя</label>
              <input v-model="editedItem.first_name" type="text" class="form-control" id="addFirstName"
                     :class="{ 'is-invalid': 'first_name' in formErrors }">
              <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['first_name'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="addLastName" class="form-label">Фамилия</label>
              <input v-model="editedItem.last_name" type="text" class="form-control" id="addLastName"
                     :class="{ 'is-invalid': 'last_name' in formErrors }">
              <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['last_name'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="addEmail" class="form-label">Email</label>
              <input v-model="editedItem.email" type="email" class="form-control" id="addEmail"
                     :class="{ 'is-invalid': 'email' in formErrors }">
              <span v-if="'email' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['email'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="addPassword" class="form-label">Пароль</label>
              <input v-model="editedItem.password" type="password" class="form-control" id="addPassword"
                     :class="{ 'is-invalid': 'password' in formErrors }">
              <span v-if="'password' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['password'][0] }}
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

  <!-- Модальное окно для редактирования пользователя -->
  <div class="modal fade" id="editForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editFormLabel">Редактировать пользователя</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitEditItem">
            <div class="mb-3">
              <label for="editFirstName" class="form-label">Имя</label>
              <input v-model="editedItem.first_name" type="text" class="form-control" id="editFirstName"
                     :class="{ 'is-invalid': 'first_name' in formErrors }">
              <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['first_name'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="editLastName" class="form-label">Фамилия</label>
              <input v-model="editedItem.last_name" type="text" class="form-control" id="editLastName"
                     :class="{ 'is-invalid': 'last_name' in formErrors }">
              <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['last_name'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="editEmail" class="form-label">Email</label>
              <input v-model="editedItem.email" type="email" class="form-control" id="editEmail"
                     :class="{ 'is-invalid': 'email' in formErrors }">
              <span v-if="'email' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['email'][0] }}
              </span>
            </div>
            <div class="mb-3">
              <label for="editPassword" class="form-label">Пароль</label>
              <input v-model="editedItem.password" type="password" class="form-control" id="editPassword"
                     :class="{ 'is-invalid': 'password' in formErrors }">
              <span v-if="'password' in formErrors" class="invalid-feedback mb-3" role="alert">
                {{ formErrors['password'][0] }}
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

  <!-- Модальное окно для подтверждения удаления пользователя -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
       aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Вы уверены, что хотите удалить этого пользователя?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
          <button @click="confirmDelete" type="button" class="btn btn-danger">Удалить</button>
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

.table tbody+tbody {
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
