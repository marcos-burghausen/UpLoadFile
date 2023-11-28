import axios from "@/plugins/axios";

export default {
    qtdMov: () => {
        return axios.get("/api/quantidade-movimentacao");
    },
    vlrMov: () => {
        return axios.get("/api/valor-movimentacao");
    },
    rx1Px1: () => {
        return axios.get("/api/rx1-px1-movimentacao");
    },
    coopAgencia: () => {
        return axios.get("/api/coop-agencia");
    },
    relacaoCdtDbt: () => {
        return axios.get("/api/relacao-credito-debito");
    },
};
