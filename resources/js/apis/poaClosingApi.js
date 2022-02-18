import axios from "axios";

const poaClosingApi = axios.create({
    baseURL: "/api/poaClosing",
});

export default poaClosingApi;
