import axios from "axios";

const yearApi = axios.create({
    baseURL: "/api/year",
});

export default yearApi;
