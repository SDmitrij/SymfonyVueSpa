import SecurityAPI from "../api/security";

const AUTHENTICATING                    = "AUTHENTICATING",
      AUTHENTICATING_SUCCESS            = "AUTHENTICATING_SUCCESS",
      AUTHENTICATING_ERROR              = "AUTHENTICATING_ERROR",
      REGISTRATION                      = "REGISTRATION",
      REGISTRATION_SUCCESS              = "REGISTRATION_SUCCESS",
      REGISTRATION_ERROR                = "REGISTRATION_ERROR",
      PROVIDING_DATA_ON_REFRESH_SUCCESS = "PROVIDING_DATA_ON_REFRESH_SUCCESS";

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        isAuthenticated: false,
        user: null
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        hasError(state) {
            return state.error !== null;
        },
        error(state) {
            return state.error;
        },
        isAuthenticated(state) {
            return state.isAuthenticated;
        },
        hasRole(state) {
            return role => {
                return state.user.roles.indexOf(role) !== -1;
            }
        }
    },
    mutations: {
        [AUTHENTICATING](state) {
            state.isLoading = true;
            state.error = null;
            state.isAuthenticated = false;
            state.user = null;
        },
        [AUTHENTICATING_SUCCESS](state, user) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = true;
            state.user = user;
        },
        [AUTHENTICATING_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.isAuthenticated = false;
            state.user = null;
        },
        [REGISTRATION](state) {
            state.isLoading = true;
            state.error = null;
            state.isAuthenticated = false;
            state.user = null;
        },
        [REGISTRATION_SUCCESS](state, user) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = true;
            state.user = user;
        },
        [REGISTRATION_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.isAuthenticated = false;
            state.user = null;
        },
        [PROVIDING_DATA_ON_REFRESH_SUCCESS](state, payload) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = payload.isAuthenticated;
            state.user = payload.user;
        }
    },
    actions: {
        async login({commit}, payload) {
            commit(AUTHENTICATING);
            try {
                let response = await SecurityAPI.login(payload.login, payload.password);
                commit(AUTHENTICATING_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(AUTHENTICATING_ERROR, error);
                return null;
            }
        },
        async register({commit}, payload) {
          commit(REGISTRATION);
          try {
              let response = await SecurityAPI.register(payload.login, payload.password);
              commit(REGISTRATION_SUCCESS, response.data);
              return response.data;
          } catch (error) {
              commit(REGISTRATION_ERROR, error);
              return null
          }
        },
        onRefresh({commit}, payload) {
            commit(PROVIDING_DATA_ON_REFRESH_SUCCESS, payload);
        }
    }
}