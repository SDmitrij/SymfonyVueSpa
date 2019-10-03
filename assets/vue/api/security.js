import axios from "axios";

export default {
    login(login, password) {
        return axios.post("/api/security/login", {
            username: login,
            password: password
        });
    },
    register(login, password) {
        return axios.post("/api/base_user", {
            username: login,
            password: password
        });
    }
}