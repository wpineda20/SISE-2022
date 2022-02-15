import axios from "axios";

const addressApi = axios.create({
    baseURL: "/api/address",
});

export default addressApi;
