import axios from "axios";

const organizationalUnitApi = axios.create({
    baseURL: "/api/organizationalUnit",
});

export default organizationalUnitApi;
