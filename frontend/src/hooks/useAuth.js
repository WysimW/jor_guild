import { useState } from 'react';
import api from '../services/axios'; // Importez votre instance axios

export const useAuth = () => {
    const [token, setToken] = useState(localStorage.getItem('token'));

    const login = async (email, password) => {
        try {
            const response = await api.post('/login_check', { email, password });
            localStorage.setItem('token', response.data.token);
            setToken(response.data.token);
        } catch (error) {
            console.error('Erreur lors de la connexion', error);
        }
    };

    const logout = () => {
        localStorage.removeItem('token');
        setToken(null);
    };

    return { token, login, logout };
};
