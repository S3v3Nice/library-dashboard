<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const isLoading = ref(false)
const readers = ref([])
const editedReader = ref({})
const addReaderForm = ref(null)
const editReaderForm = ref(null)
const deleteReaderForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshReaders()
  addReaderForm.value = Modal.getOrCreateInstance('#addReaderForm', {})
  editReaderForm.value = Modal.getOrCreateInstance('#editReaderForm', {})
  deleteReaderForm.value = Modal.getOrCreateInstance('#confirmDeleteReaderForm', {})
  document.getElementById('addReaderForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editReaderForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshReaders() {
  isLoading.value = true
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/readers').then((response) => {
    readers.value = response.data
    isLoading.value = false
  })
}

function showAddForm() {
  editedReader.value = {}
  addReaderForm.value.show()
}

function submitAddReader() {
  axios.post('/api/reader', editedReader.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshReaders()
    editedReader.value = {}
    addReaderForm.value.hide()
  })
}

function showEditForm(reader) {
  editedReader.value = {...reader}
  editReaderForm.value.show()
}

function submitEditReader() {
  axios.put(`/api/reader/${editedReader.value.id}`, editedReader.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshReaders()
    editedReader.value = {}
    editReaderForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteReaderForm.value.show()
  editedReader.value = {id}
}

function confirmDeleteReader() {
  axios.delete(`/api/reader/${editedReader.value.id}`).then(() => {
    refreshReaders()
    editedReader.value = {}
    deleteReaderForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}

function formatDate(dateString) {
  const options = { day: 'numeric', month: 'numeric', year: 'numeric' };
  const date = new Date(dateString);
  return date.toLocaleDateString('ru-RU', options);
}
</script>

<template>
  <div>
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить читателя
    </button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Отчество</th>
        <th scope="col">Выдача чит. билета</th>
        <th scope="col">Истечение чит. билета</th>
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
      <tr v-else v-for="reader in readers" :key="reader.id">
        <td>{{ reader.first_name }}</td>
        <td>{{ reader.last_name }}</td>
        <td>{{ reader.patronymic }}</td>
        <td>{{ formatDate(reader.library_card_issue_date) }}</td>
        <td>{{ formatDate(reader.library_card_expiry_date) ?? '-' }}</td>
        <td>
          <button @click="showEditForm(reader)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(reader.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Модальное окно для добавления читателя -->
    <div class="modal fade" id="addReaderForm" tabindex="-1" aria-labelledby="addReaderFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addReaderFormLabel">Добавить читателя</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddReader">
              <div class="mb-3">
                <label for="addReaderFirstName" class="form-label">Имя</label>
                <input v-model="editedReader.first_name" type="text" class="form-control" id="addReaderFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addReaderLastName" class="form-label">Фамилия</label>
                <input v-model="editedReader.last_name" type="text" class="form-control" id="addReaderLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addReaderMiddleName" class="form-label">Отчество</label>
                <input v-model="editedReader.patronymic" type="text" class="form-control" id="addReaderMiddleName"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addReaderIssueDate" class="form-label">Дата выдачи читательского билета</label>
                <input v-model="editedReader.library_card_issue_date" type="date" class="form-control" id="addReaderIssueDate"
                       :class="{ 'is-invalid': 'library_card_issue_date' in formErrors }">
                <span v-if="'library_card_issue_date' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['library_card_issue_date'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addReaderExpiryDate" class="form-label">Срок годности читательского билета</label>
                <input v-model="editedReader.library_card_expiry_date" type="date" class="form-control" id="addReaderExpiryDate"
                       :class="{ 'is-invalid': 'library_card_expiry_date' in formErrors }">
                <span v-if="'library_card_expiry_date' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['library_card_expiry_date'][0] }}
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

    <!-- Модальное окно для редактирования читателя -->
    <div class="modal fade" id="editReaderForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать читателя</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditReader">
              <div class="mb-3">
                <label for="editReaderFirstName" class="form-label">Имя</label>
                <input v-model="editedReader.first_name" type="text" class="form-control" id="editReaderFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editReaderLastName" class="form-label">Фамилия</label>
                <input v-model="editedReader.last_name" type="text" class="form-control" id="editReaderLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editReaderMiddleName" class="form-label">Отчество</label>
                <input v-model="editedReader.patronymic" type="text" class="form-control" id="editReaderMiddleName"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editReaderIssueDate" class="form-label">Дата выдачи читательского билета</label>
                <input v-model="editedReader.library_card_issue_date" type="date" class="form-control" id="editReaderIssueDate"
                       :class="{ 'is-invalid': 'library_card_issue_date' in formErrors }">
                <span v-if="'library_card_issue_date' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['library_card_issue_date'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editReaderExpiryDate" class="form-label">Срок годности читательского билета</label>
                <input v-model="editedReader.library_card_expiry_date" type="date" class="form-control" id="editReaderExpiryDate"
                       :class="{ 'is-invalid': 'library_card_expiry_date' in formErrors }">
                <span v-if="'library_card_expiry_date' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['library_card_expiry_date'][0] }}
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

    <!-- Модальное окно для подтверждения удаления читателя -->
    <div class="modal fade" id="confirmDeleteReaderForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить этого читателя?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteReader" type="button" class="btn btn-danger">Удалить</button>
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
