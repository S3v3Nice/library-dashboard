<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const isLoading = ref(false)
const jobPositions = ref([])
const editedJobPosition = ref({})
const addJobPositionForm = ref(null)
const editJobPositionForm = ref(null)
const deleteJobPositionForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshJobPositions()
  addJobPositionForm.value = Modal.getOrCreateInstance('#addJobPositionForm', {})
  editJobPositionForm.value = Modal.getOrCreateInstance('#editJobPositionForm', {})
  deleteJobPositionForm.value = Modal.getOrCreateInstance('#confirmDeleteJobPositionForm', {})
  document.getElementById('addJobPositionForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editJobPositionForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshJobPositions() {
  isLoading.value = true
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/job-positions').then((response) => {
    jobPositions.value = response.data
    isLoading.value = false
  })
}

function showAddForm() {
  editedJobPosition.value = {}
  addJobPositionForm.value.show()
}

function submitAddJobPosition() {
  axios.post('/api/job-position', editedJobPosition.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshJobPositions()
    editedJobPosition.value = {}
    addJobPositionForm.value.hide()
  })
}

function showEditForm(jobPosition) {
  editedJobPosition.value = {...jobPosition}
  editJobPositionForm.value.show()
}

function submitEditJobPosition() {
  axios.put(`/api/job-position/${editedJobPosition.value.id}`, editedJobPosition.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshJobPositions()
    editedJobPosition.value = {}
    editJobPositionForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteJobPositionForm.value.show()
  editedJobPosition.value = {id}
}

function confirmDeleteJobPosition() {
  axios.delete(`/api/job-position/${editedJobPosition.value.id}`).then(() => {
    refreshJobPositions()
    editedJobPosition.value = {}
    deleteJobPositionForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <div>
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить должность</button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Название</th>
        <th scope="col">Оклад</th>
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
      <tr v-else v-for="jobPosition in jobPositions" :key="jobPosition.id">
        <td>{{ jobPosition.name }}</td>
        <td>{{ jobPosition.salary }}</td>
        <td>
          <button @click="showEditForm(jobPosition)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(jobPosition.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Модальное окно для добавления должности -->
    <div class="modal fade" id="addJobPositionForm" tabindex="-1" aria-labelledby="addJobPositionFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addJobPositionFormLabel">Добавить должность</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddJobPosition">
              <div class="mb-3">
                <label for="addJobPositionName" class="form-label">Название</label>
                <input v-model="editedJobPosition.name" type="text" class="form-control" id="addJobPositionName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addJobPositionSalary" class="form-label">Оклад</label>
                <input v-model="editedJobPosition.salary" type="number" step="any" class="form-control" id="addJobPositionSalary"
                       :class="{ 'is-invalid': 'salary' in formErrors }">
                <span v-if="'salary' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['salary'][0] }}
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

    <!-- Модальное окно для редактирования должности -->
    <div class="modal fade" id="editJobPositionForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать должность</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditJobPosition">
              <div class="mb-3">
                <label for="editJobPositionName" class="form-label">Имя</label>
                <input v-model="editedJobPosition.name" type="text" class="form-control" id="editJobPositionName"
                       :class="{ 'is-invalid': 'name' in formErrors }">
                <span v-if="'name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editJobPositionSalary" class="form-label">Оклад</label>
                <input v-model="editedJobPosition.salary" type="number" step="any" class="form-control" id="editJobPositionSalary"
                       :class="{ 'is-invalid': 'salary' in formErrors }">
                <span v-if="'salary' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['salary'][0] }}
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

    <!-- Модальное окно для подтверждения удаления должности -->
    <div class="modal fade" id="confirmDeleteJobPositionForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить эту должность
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteJobPosition" type="button" class="btn btn-danger">Удалить</button>
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
