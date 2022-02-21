import axios from "axios";

const programmaticObjective = axios.create({
    baseURL: "/api/programmaticObjective",
});

export default programmaticObjective;