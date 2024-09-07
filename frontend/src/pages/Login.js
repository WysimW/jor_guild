// src/components/Login.js
import React, { useState } from 'react';
import api from '../services/axios';
import { useNavigate } from 'react-router-dom';
import '../styles/Auth.css'; // Importer le fichier de styles

const Login = () => {
  const [user, setUser] = useState(''); // Nom d'utilisateur ou email
  const [password, setPassword] = useState('');
  const [message, setMessage] = useState('');
  const navigate = useNavigate();

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
      <h2>Connexion</h2>
      <form className="login-form" onSubmit={handleSubmit}>
        <label htmlFor="username">Email ou Nom d'utilisateur :</label>
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
      {message && <p className="login-message">{message}</p>}
    </div>
  );
};

export default Login;
