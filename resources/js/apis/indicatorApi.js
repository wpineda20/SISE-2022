import axios from "axios";

const indicatorApi = axios.create({
    baseURL: "/api/indicator",
});

export default indicatorApi;
