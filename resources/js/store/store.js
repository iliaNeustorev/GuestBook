import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        user: {},
        checkIsAdmin: false,
    },
    getters: {},
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setcheckIsAdmin(state, payload) {
            state.checkIsAdmin = payload;
        },
    },
    actions: {
        getUser(context, payload) {
            context.commit("setUser", payload);
        },
        changecheckIsAdmin(context, payload) {
            context.commit("setcheckIsAdmin", payload);
        },
        getcheckIsAdmin(context, payload) {
            context.commit("setcheckIsAdmin", payload);
        },
    },
});

export default store;
