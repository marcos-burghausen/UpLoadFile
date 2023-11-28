import axios from "@/plugins/axios";

export default {
    fileUpload: (file: FormData) => {
        return axios.post("/file-upload", file, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
    upload: (file: FormData) => {
        return axios.post("/upload", file, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
};
