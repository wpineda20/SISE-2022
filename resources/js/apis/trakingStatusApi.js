import axios from "axios";

const trakingStatusApi = axios.create({
    baseURL: "/api/trakingStatus",
});

export default trakingStatusApi;
