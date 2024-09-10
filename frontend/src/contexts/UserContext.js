// UserContext.js
import React, { createContext, useState, useEffect } from 'react';
import axios from '../services/axios'; // Remplacer par le bon chemin d'axios

export const UserContext = createContext();

export const UserProvider = ({ children }) => {
    const [user, setUser] = useState(null);

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const response = await axios.get('/user/me'); // Remplacer par le bon endpoint
                setUser(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des informations utilisateur:', error);
            }
        };

        fetchUser();
    }, []);

    return (
        <UserContext.Provider value={user}>
            {children}
        </UserContext.Provider>
    );
};
