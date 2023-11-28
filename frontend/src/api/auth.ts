import axios from "@/plugins/axios";
import { UserAuth } from "@/types/userAuth";

export default {
    login: (data: UserAuth) => {
        return axios.post("/api/auth/login", data);
    },
    logout: () => {
        return axios.post("/api/auth/logout");
    },
};
