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
          <div className="logo">
              <img src="/images/guilde-logo.png" alt="Logo de la guilde" className="guild-logo" />
              <h1>Guilde JOR</h1>
          </div>
          <nav className="nav-links">
              <Link to="/">Accueil</Link>
              <Link to="/raids">Raids</Link> {/* Lien vers la page des raids */}
              <Link to="/raid/calendar">Calendrier</Link> {/* Lien vers la page des raids */}

              {token ? ( // Si l'utilisateur est connecté
                  <>
                      <Link to="/dashboard" className="nav-icon"> {/* Remplacer Dashboard par Profil */}
                          <FontAwesomeIcon icon={faUser} /> Profil
                      </Link>
                      <button className="nav-icon logout-button" onClick={handleLogout}>
                          <FontAwesomeIcon icon={faSignOutAlt} /> {/* Icône pour déconnexion */}
                      </button>
                  </>
              ) : ( // Si l'utilisateur n'est pas connecté
                  <>
                      <Link to="/signup">S'inscrire</Link>
                      <Link to="/login">Se connecter</Link>
                  </>
              )}
          </nav>
      </header>
  );
};

export default Header;