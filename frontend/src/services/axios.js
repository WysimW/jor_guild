import axios from 'axios';

const api = axios.create({
    baseURL: 'https://api.guild-jor.fr/api', // L'URL de votre API Symfony
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
