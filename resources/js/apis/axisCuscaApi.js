import axios from "axios";

const axisCuscaApi = axios.create({
    baseURL: "/api/axisCusca",
});

export default axisCuscaApi;
