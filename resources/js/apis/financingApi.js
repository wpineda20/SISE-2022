import axios from "axios";

const financingApi = axios.create({
    baseURL: "/api/financing",
});

export default financingApi;
