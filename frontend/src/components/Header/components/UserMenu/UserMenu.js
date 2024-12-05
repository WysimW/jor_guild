import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../../../hooks/useAuth'; // Utiliser le hook useAuth pour gérer l'authentification
import '../../Header.css';

const UserMenu = () => {
    const { token, logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = () => {
        logout();
        navigate('/');
    };

    return (
        <div className='nav-links'>
            {token ? (
                <>
                    <Link to="/dashboard" className="nav-icon nav-links--items">
                        Profil
                    </Link>
                    <Link className="nav-links--items" to="/" onClick={handleLogout}>
                        Se déconnecter
                    </Link>
                </>
            ) : (
                <>
                    <Link className="nav-links--items" to="/signup">
                        S'inscrire
                    </Link>
                    <Link className="nav-links--items" to="/login">
                        Se connecter
                    </Link>
                </>
            )}
        </div>
    );
};

export default UserMenu;
