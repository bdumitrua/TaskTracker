import axios from "axios";

// TODO
// Брать из env
axios.defaults.baseURL = "http://localhost:8000";
axios.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("access_token");
