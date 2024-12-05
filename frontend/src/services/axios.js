import axios from 'axios';

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL, // Utilisation de la variable d'environnement
    headers: {
        'Content-Type': 'application/json',
    },
});

// Intercepter les requêtes pour ajouter le token JWT si disponible
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
