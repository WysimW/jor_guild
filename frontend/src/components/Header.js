import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth'; // Utiliser le hook useAuth pour gérer l'authentification
import '../styles/Header.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'; // Importer FontAwesomeIcon
import { faSignOutAlt, faUser } from '@fortawesome/free-solid-svg-icons'; // Importer les icônes

const Header = () => {
  const { token, logout } = useAuth(); // Récupérer le token et la fonction logout
  const navigate = useNavigate();

  const handleLogout = () => {
      logout(); // Déconnecter l'utilisateur
      navigate('/'); // Rediriger vers l'accueil après déconnexion
  };

  return (
    <header className="header">
        <nav className="nav-links nav-left">
            <Link className='nav-links--items' to="/">Accueil</Link>
            <Link className='nav-links--items' to="/raids">Raids</Link>
            <Link className='nav-links--items' to="/raid/calendar">Calendrier</Link>
        </nav>

        <div className="logo">
            <img src="/images/guilde-logo.png" alt="Logo de la guilde" className="guild-logo" />
        </div>

        <nav className="nav-links nav-right">
            {token ? ( // Si l'utilisateur est connecté
                <>
                    <Link to="/dashboard" className="nav-icon nav-links--items">
                         Profil
                    </Link>
                    <Link className='nav-links--items'  to="/" onClick={handleLogout}>Se déconnecter</Link>

                </>
            ) : (
                <>
                    <Link className='nav-links--items'  to="/signup">S'inscrire</Link>
                    <Link className='nav-links--items' to="/login">Se connecter</Link>
                </>
            )}
        </nav>
    </header>
);

};

export default Header;