import axios from "axios";

const directionApi = axios.create({
    baseURL: "/api/direction",
});

export default directionApi;
