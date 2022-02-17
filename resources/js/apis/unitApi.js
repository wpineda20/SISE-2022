import axios from "axios";

const unitApi = axios.create({
    baseURL: "/api/unit",
});

export default unitApi;
