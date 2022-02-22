import axios from "axios";

const programmaticObjectiveApi = axios.create({
    baseURL: "/api/programmaticObjective",
});

export default programmaticObjectiveApi;