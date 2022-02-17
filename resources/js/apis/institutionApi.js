import axios from "axios";

const institutionApi = axios.create({
    baseURL: "/api/institution",
});

export default institutionApi;
