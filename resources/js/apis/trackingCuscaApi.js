import axios from "axios";

const trackingCuscaApi = axios.create({
    baseURL: "/api/trackingCusca",
});

export default trackingCuscaApi;
