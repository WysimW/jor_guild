// components/AuthWrapper.js
import React, { useEffect } from 'react';
import { useAuth } from 'hooks/useAuth';
import { useNavigate } from 'react-router-dom';
import './AuthWrapper.css'; // Importer les styles du popup

const AuthWrapper = ({ children }) => {
    const { showTimeoutPopup, setShowTimeoutPopup, logout } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (showTimeoutPopup) {
            // Afficher le popup plus longtemps avant la redirection (ici 5 secondes)
            const timer = setTimeout(() => {
                setShowTimeoutPopup(false);
                logout();
                navigate('/login');
            }, 5000); // Le popup reste visible pendant 5 secondes avant redirection

            return () => clearTimeout(timer);
        }
    }, [showTimeoutPopup, navigate, logout, setShowTimeoutPopup]);

    return (
        <>
            {showTimeoutPopup && (
                <div className="timeout-popup">
                    <div className="timeout-popup__content">
                        <h3>Session expirée</h3>
                        <p>Votre session a expiré. Vous allez être redirigé vers la page de connexion dans quelques instants.</p>
                    </div>
                </div>
            )}
            {children}
        </>
    );
};

export default AuthWrapper;