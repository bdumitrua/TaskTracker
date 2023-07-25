import axios from "axios";

// TODO
// Брать из env
const axiosInstance = axios.create({
    baseURL: "http://tasktracker.buy/api",
});

axiosInstance.defaults.headers.common["Authorization"] =
    "Bearer " + localStorage.getItem("access_token");

axiosInstance.interceptors.request.use(
    (config) => {
        // Получение токена из localStorage
        const token = localStorage.getItem("access_token");

        // Если токен есть, добавляем его в заголовок запроса
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Создаем перехватчик ответов
axiosInstance.interceptors.response.use(null, async (error) => {
    if (error.config && error.response && error.response.status === 401) {
        // Сохраняем оригинальный запрос
        const originalRequest = error.config;

        try {
            // Получаем новый токен
            const response = await axiosInstance.post("/auth/refresh-token");

            // Обновляем токен в хранилище
            localStorage.setItem("access_token", response.data.access_token);

            // Обновляем токен в заголовке авторизации
            axiosInstance.defaults.headers.common["Authorization"] =
                "Bearer " + localStorage.getItem("access_token");

            // Повторяем оригинальный запрос с новым токеном
            return axiosInstance(originalRequest);
        } catch (err) {
            console.error(err);
        }
    }

    return Promise.reject(error);
});

export default axiosInstance;
