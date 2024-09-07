import React, { useState } from 'react';
import axios from '../services/axios';
import '../styles/Auth.css'; // Importer le fichier de styles


const SignUp = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/register', { email, password });
            setMessage(response.data.message);
        } catch (error) {
            setMessage('Erreur lors de l\'inscription : ' + error.response.data.message);
        }
    };


    return (
        <div className="signup-container">
          <h2>Inscription</h2>
          <form className="signup-form" onSubmit={handleSubmit}>
            <label htmlFor="email">Email :</label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
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
            <button type="submit" className="signup-button">S'inscrire</button>
          </form>
          {message && <p className="signup-message">{message}</p>}
        </div>
      );
    };

export default SignUp;
