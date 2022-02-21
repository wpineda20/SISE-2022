import axios from "axios";

const axisCuscatlanApi = axios.create({
    baseURL: "/api/axisCuscatlan",
});

export default axisCuscatlanApi;
