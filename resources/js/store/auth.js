import axios from 'axios'

export default {
    namespaced: true,
    state: {
        isAuthenticated: false,
        user: {}
    },
    getters: {
        isAuthenticated(state) {
            return state.isAuthenticated
        },
        user(state) {
            return state.user
        }
    },
    mutations: {
        setAuthenticated(state, value) {
            state.isAuthenticated = value
        },
        setUser(state, value) {
            state.user = value
        }
    },
    actions: {
        async fetch({commit, dispatch}) {
            await axios.get('/api/user').then(({data}) => {
                if (Object.keys(data).length === 0) {
                    dispatch('reset')
                    return
                }

                commit('setAuthenticated', true)
                commit('setUser', data)
            }).catch(() => {
                dispatch('reset')
            })
        },

        reset({commit}) {
            commit('setAuthenticated', false)
            commit('setUser', {})
        }
    }
}
