import $axios from '../api.js'

const state = () => ({
    loading: false,
    couriers: [],
    page: 1,
    id: '',
    courier: {
        name: '',
        email: '',
        password: '',
        reference_pool_pallet_id: '',
        reference_transporter_id: '',
    }
})

const mutations = {
    isLoading (state) {
        state.loading = true
      },
      doneLoading (state) {
        state.loading = false
      },
    ASSIGN_FORM(state, payload) {
        state.courier = payload
    },
    ASSIGN_DATA(state, payload) {
        state.couriers = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    }
}

const actions = {
    getCouriers({ commit, state }, payload) {
        commit('isLoading')
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/couriers?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            }).finally(() => {
                commit('doneLoading')
            })
        })
    },
    submitCourier({ dispatch, commit }, payload) {
        commit('isLoading')
        return new Promise((resolve, reject) => {
            $axios.post(`/couriers`, payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                dispatch('getCouriers').then(() => {
                    resolve(response.data)
                })
            })
            .catch((error) => {
                alert(error.response.data.message)
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            }).finally(() => {
                commit('doneLoading')
            })
        })
    },
    editCourier({ commit }, payload) {
        commit('isLoading')
        return new Promise((resolve, reject) => {
            $axios.get(`/couriers/${payload}/edit`)
            .then((response) => {
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            }).finally(() => {
                commit('doneLoading')
            })
        })
    },
    updateCourier({ state, commit }, payload) {
        commit('isLoading')
        return new Promise((resolve, reject) => {
            $axios.post(`/couriers/${state.id}`, payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                resolve(response.data)
            }).catch((error) => {
                alert(error.response.data.message)
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            }).finally(() => {
                commit('doneLoading')
            })
        })
    } ,
    removeCourier({ dispatch, commit }, payload) {
        commit('isLoading')
        return new Promise((resolve, reject) => {
            $axios.delete(`/couriers/${payload}`)
            .then((response) => {
                dispatch('getCouriers').then(() => resolve(response.data))
            }).finally(() => {
                commit('doneLoading')
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
