import axios from "axios";

const trackingObservationCuscaApi = axios.create({
    baseURL: "/api/trackingObservationCusca",
});

export default trackingObservationCuscaApi;
