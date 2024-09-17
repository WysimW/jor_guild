// hooks/useAuth.js
import { useState, useEffect } from 'react';
import {jwtDecode} from 'jwt-decode'; // Assurez-vous que jwtDecode est importé correctement
import api from 'services/axios';

export const useAuth = () => {
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [expirationTime, setExpirationTime] = useState(null);
    const [showTimeoutPopup, setShowTimeoutPopup] = useState(false);

    useEffect(() => {
        const storedToken = localStorage.getItem('token');
        if (storedToken) {
            try {
                const decodedToken = jwtDecode(storedToken);
                const expiration = decodedToken.exp * 1000;
                setExpirationTime(expiration);

                // Si le token est déjà expiré, déclencher l'expiration
                if (Date.now() >= expiration) {
                    handleTokenExpiry();
                }
            } catch (error) {
                console.error('Erreur lors du décodage du token:', error);
                logout();
            }
        }
    }, []);

    const login = async (email, password) => {
        try {
            const response = await api.post('/login_check', { email, password });
            const newToken = response.data.token;
            localStorage.setItem('token', newToken);
            setToken(newToken);

            const decodedToken = jwtDecode(newToken);
            const expiration = decodedToken.exp * 1000;
            setExpirationTime(expiration);
        } catch (error) {
            console.error('Erreur lors de la connexion', error);
        }
    };

    const logout = () => {
        localStorage.removeItem('token');
        setToken(null);
        setExpirationTime(null);
    };

    const handleTokenExpiry = () => {
        setShowTimeoutPopup(true);
    };

    useEffect(() => {
        if (!expirationTime) return;

        const timeRemaining = expirationTime - Date.now();
        if (timeRemaining <= 0) {
            handleTokenExpiry();
        } else {
            const timeoutId = setTimeout(() => {
                handleTokenExpiry();
            }, timeRemaining);

            return () => clearTimeout(timeoutId);
        }
    }, [expirationTime]);

    return { token, login, logout, showTimeoutPopup, setShowTimeoutPopup };
};