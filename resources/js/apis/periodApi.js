import axios from "axios";

const periodApi = axios.create({
    baseURL: "/api/period",
});

export default periodApi;
