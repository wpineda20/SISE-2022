import axios from "axios";

const actionsCuscaApi = axios.create({
    baseURL: "/api/actionsCusca",
});

export default actionsCuscaApi;