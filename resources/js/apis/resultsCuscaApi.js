import axios from "axios";

const resultsCuscaApi = axios.create({
    baseURL: "/api/resultsCusca",
});

export default resultsCuscaApi;
