import axios from "axios";

const strategyCuscaApi = axios.create({
    baseURL: "/api/strategyCusca",
});

export default strategyCuscaApi;