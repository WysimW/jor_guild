import React from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth'; // Utiliser le hook useAuth pour gérer l'authentification
import NavLinks from './components/NavLinks/NavLinks';
import Logo from './components/Logo/Logo';
import UserMenu from './components/UserMenu/UserMenu';
import './Header.css';

const Header = () => {
    const { token, logout } = useAuth(); // Récupérer le token et la fonction logout
    const navigate = useNavigate();

    const handleLogout = () => {
        logout(); // Déconnecter l'utilisateur
        navigate('/'); // Rediriger vers l'accueil après déconnexion
    };

    return (
        <header className="header">
            <NavLinks token={token} handleLogout={handleLogout} /> {/* Gérer les liens */}
            <Logo /> {/* Affichage du logo */}
            <UserMenu token={token} handleLogout={handleLogout} /> {/* Gestion du menu utilisateur */}
        </header>
    );
};

export default Header;
