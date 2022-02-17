import axios from "axios";

const monthlyClosingApi = axios.create({
    baseURL: "/api/monthlyClosing",
});

export default monthlyClosingApi;
