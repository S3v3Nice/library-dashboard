<script setup>
import {onMounted, ref} from 'vue';
import {Modal} from 'bootstrap';
import axios from 'axios';

const isLoading = ref(false)
const employees = ref([])
const jobPositions = ref([]);
const genres = ref([]);
const editedEmployee = ref({})
const addEmployeeForm = ref(null)
const editEmployeeForm = ref(null)
const deleteEmployeeForm = ref(null)
const formErrors = ref([])

onMounted(() => {
  refreshEmployees()
  loadJobPositions()
  addEmployeeForm.value = Modal.getOrCreateInstance('#addEmployeeForm', {})
  editEmployeeForm.value = Modal.getOrCreateInstance('#editEmployeeForm', {})
  deleteEmployeeForm.value = Modal.getOrCreateInstance('#confirmDeleteEmployeeForm', {})
  document.getElementById('addEmployeeForm').addEventListener('hidden.bs.modal', clearFormErrors)
  document.getElementById('editEmployeeForm').addEventListener('hidden.bs.modal', clearFormErrors)
})

async function refreshEmployees() {
  isLoading.value = true
  await axios.get('/sanctum/csrf-cookie')
  await axios.get('/api/employees').then((response) => {
    employees.value = response.data
    isLoading.value = false
  })
}

async function loadJobPositions() {
  await axios.get('/api/job-positions').then((response) => {
    jobPositions.value = response.data
  })
}

function showAddForm() {
  editedEmployee.value = {}
  addEmployeeForm.value.show()
}

function submitAddEmployee() {
  axios.post('/api/employee', editedEmployee.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshEmployees()
    editedEmployee.value = {}
    addEmployeeForm.value.hide()
  })
}

function showEditForm(employee) {
  editedEmployee.value = {...employee}
  editEmployeeForm.value.show()
}

function submitEditEmployee() {
  axios.put(`/api/employee/${editedEmployee.value.id}`, editedEmployee.value).then((response) => {
    if (!response.data.success) {
      formErrors.value = response.data.errors
      return
    }

    refreshEmployees()
    editedEmployee.value = {}
    editEmployeeForm.value.hide()
  })
}

function showDeleteForm(id) {
  deleteEmployeeForm.value.show()
  editedEmployee.value = {id}
}

function confirmDeleteEmployee() {
  axios.delete(`/api/employee/${editedEmployee.value.id}`).then(() => {
    refreshEmployees()
    editedEmployee.value = {}
    deleteEmployeeForm.value.hide()
  })
}

function clearFormErrors() {
  formErrors.value = []
}
</script>

<template>
  <div>
    <button @click="showAddForm" class="btn btn-primary mb-3 float-end">
      <i class="bi bi-plus"></i> Добавить сотрудника
    </button>

    <!--  Табличная часть-->
    <table class="table mt-3">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col">Отчество</th>
        <th scope="col">Должность</th>
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
      <tr v-else v-for="employee in employees" :key="employee.id">
        <td>{{ employee.first_name }}</td>
        <td>{{ employee.last_name }}</td>
        <td>{{ employee.patronymic }}</td>
        <td>{{ employee.position.name }}</td>
        <td>
          <button @click="showEditForm(employee)" class="btn btn-sm btn-success me-2">
            <i class="bi bi-pencil-fill"></i>
          </button>
          <button @click="showDeleteForm(employee.id)" class="btn btn-sm btn-danger">
            <i class="bi bi-trash-fill"></i>
          </button>
        </td>
      </tr>
      </tbody>
    </table>

    <!-- Модальное окно для добавления сотрудника -->
    <div class="modal fade" id="addEmployeeForm" tabindex="-1" aria-labelledby="addEmployeeFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addEmployeeFormLabel">Добавить сотрудника</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitAddEmployee">
              <div class="mb-3">
                <label for="addEmployeeFirstName" class="form-label">Имя</label>
                <input v-model="editedEmployee.first_name" type="text" class="form-control" id="addEmployeeFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addEmployeeLastName" class="form-label">Фамилия</label>
                <input v-model="editedEmployee.last_name" type="text" class="form-control" id="addEmployeeLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addEmployeePatronymic" class="form-label">Отчество</label>
                <input v-model="editedEmployee.patronymic" type="text" class="form-control" id="addEmployeePatronymic"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="addEmployeePosition" class="form-label">Должность</label>
                <select v-model="editedEmployee.position_id" class="form-select" id="addEmployeePosition">
                  <option v-for="position in jobPositions" :key="position.id" :value="position.id">{{ position.name }}</option>
                </select>
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

    <!-- Модальное окно для редактирования сотрудника -->
    <div class="modal fade" id="editEmployeeForm" tabindex="-1" aria-labelledby="editFormLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editFormLabel">Редактировать сотрудника</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitEditEmployee">
              <div class="mb-3">
                <label for="editEmployeeFirstName" class="form-label">Имя</label>
                <input v-model="editedEmployee.first_name" type="text" class="form-control" id="editEmployeeFirstName"
                       :class="{ 'is-invalid': 'first_name' in formErrors }">
                <span v-if="'first_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['first_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editEmployeeLastName" class="form-label">Фамилия</label>
                <input v-model="editedEmployee.last_name" type="text" class="form-control" id="editEmployeeLastName"
                       :class="{ 'is-invalid': 'last_name' in formErrors }">
                <span v-if="'last_name' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['last_name'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editEmployeePatronymic" class="form-label">Отчество</label>
                <input v-model="editedEmployee.patronymic" type="text" class="form-control" id="editEmployeePatronymic"
                       :class="{ 'is-invalid': 'patronymic' in formErrors }">
                <span v-if="'patronymic' in formErrors" class="invalid-feedback mb-3" role="alert">
                  {{ formErrors['patronymic'][0] }}
                </span>
              </div>

              <div class="mb-3">
                <label for="editEmployeePosition" class="form-label">Должность</label>
                <select v-model="editedEmployee.position_id" class="form-select" id="editEmployeePosition">
                  <option v-for="position in jobPositions" :key="position.id" :value="position.id">{{ position.name }}</option>
                </select>
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

    <!-- Модальное окно для подтверждения удаления сотрудника -->
    <div class="modal fade" id="confirmDeleteEmployeeForm" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Подтверждение удаления</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить этого сотрудника?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
            <button @click="confirmDeleteEmployee" type="button" class="btn btn-danger">Удалить</button>
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
