// src/components/Login.js
import React, { useState, useEffect } from 'react';
import api from '../../services/axios';
import { Link, useNavigate, useLocation } from 'react-router-dom'; // Importer Link et useNavigate
import './Auth.css'; // Importer le fichier de styles

const Login = () => {
  const [user, setUser] = useState(''); // Nom d'utilisateur ou email
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const navigate = useNavigate();
  const location = useLocation(); // Utiliser useLocation pour récupérer l'état


  useEffect(() => {
    if (location.state?.successMessage) {
        setMessage(location.state.successMessage);
    }
}, [location.state]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      const response = await api.post('/login_check', { username: user, password }); // Envoyer username
      localStorage.setItem('token', response.data.token); // Stocker le token JWT
      setMessage('Connexion réussie');
      navigate('/dashboard'); // Rediriger vers une page protégée après connexion
    } catch (error) {
      setMessage('Erreur lors de la connexion');
    }
  };

  return (
    <div className="login-container">
      <div className="auth_overlay"></div> {/* Overlay devant l'image */}
      <div className="login-wrapper">

        <h2>Connexion</h2>
        <form className="login-form" onSubmit={handleSubmit}>
          <label htmlFor="username">Email :</label>
          <input
            type="text"
            id="username"
            value={user}
            onChange={(e) => setUser(e.target.value)}
            required
          />
          <label htmlFor="password">Mot de passe :</label>
          <input
            type="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
          <button type="submit" className="login-button">Se connecter</button>
        </form>
        <div className="signup-links">
                    <Link to="/">Retour à l'accueil</Link>
                    <Link to="/signup">Pas encore inscrit ?</Link>
                </div>
      </div>
      {message && <p className="notification">{message}</p>}

    </div>
  );
};

export default Login;
