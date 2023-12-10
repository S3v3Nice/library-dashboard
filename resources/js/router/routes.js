import Dashboard from "../components/Dashboard.vue";
import Login from "../components/Login.vue";
import NotFound from "../components/NotFound.vue";
import Users from "../components/dashboard-sections/Users.vue";
import Authors from "../components/dashboard-sections/Authors.vue";
import Genres from "../components/dashboard-sections/Genres.vue";
import Books from "../components/dashboard-sections/Books.vue";
import JobPositions from "../components/dashboard-sections/JobPositions.vue";
import Employees from "../components/dashboard-sections/Employees.vue";
import Readers from "../components/dashboard-sections/Readers.vue";

function authenticated(to, from, next) {

}

function guest(to, from, next) {

}

export default [
    {
        path: '/',
        name: 'home',
        component: Dashboard,
        meta:
            {
                title: 'Управление',
                authenticated: true,
            },
        children:
            [
                {
                    path: '/users',
                    name: 'users',
                    component: Users,
                    meta:
                        {
                            title: 'Пользователи',
                            authenticated: true,
                        }
                },
                {
                    path: '/authors',
                    name: 'authors',
                    component: Authors,
                    meta:
                        {
                            title: 'Авторы',
                            authenticated: true,
                        }
                },
                {
                    path: '/genres',
                    name: 'genres',
                    component: Genres,
                    meta:
                        {
                            title: 'Жанры',
                            authenticated: true,
                        }
                },
                {
                    path: '/books',
                    name: 'books',
                    component: Books,
                    meta:
                        {
                            title: 'Книги',
                            authenticated: true,
                        }
                },
                {
                    path: '/job-positions',
                    name: 'job-positions',
                    component: JobPositions,
                    meta:
                        {
                            title: 'Должности',
                            authenticated: true,
                        }
                },
                {
                    path: '/employees',
                    name: 'employees',
                    component: Employees,
                    meta:
                        {
                            title: 'Сотрудники',
                            authenticated: true,
                        }
                },
                {
                    path: '/readers',
                    name: 'readers',
                    component: Readers,
                    meta:
                        {
                            title: 'Читатели',
                            authenticated: true,
                        }
                },
            ],
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta:
            {
                title: 'Вход в аккаунт',
                guest: true,
            }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound,
        meta:
            {
                title: 'Не найдено',
            }
    },
]
