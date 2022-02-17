import axios from "axios";

const monthApi = axios.create({
    baseURL: "/api/month",
});

export default monthApi;
