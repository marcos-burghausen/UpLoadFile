import axios from "@/plugins/axios";
import type { UserRegister } from "@/types/userRegister";

export default {
    registerUser: (user: UserRegister) => {
        return axios.post("/api/create", user);
    },
};