import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        counter: 1000,
        deleteModalObj: {
            showDeleteModal: false,
            deleteUrl : 'app/delete_tag',
            data: null,
            deleteIndex: -1,
            isDeleted: false
        },
        user: false,
        userPermission: null

    },
    getters: {
        getCounter(state) {
            return state.counter
        },
        getDeleteModalObj(state) {
            return state.deleteModalObj
        },
        getUserPermission(state) {
            return state.userPermission
        }
    },
    mutations: {
        setDeleteModal(state, data) {
            const deleteModalObj = {
                showDeleteModal: false,
                deleteUrl : 'app/delete_tag',
                data: data,
                deleteIndex: -1,
                isDeleted: data,
            }
            state.deleteModalObj = deleteModalObj

        },
        setDeletingModalObj(state,data) {
            state.deleteModalObj = data;
        },
        setUpdateUser(state, data) {
            state.user = data
        },
        setUserPermission(state, data) {
           state.userPermission = data
        }
    },
    actions: {

    }
})


