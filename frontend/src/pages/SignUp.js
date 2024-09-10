import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom'; // Importer Link et useNavigate
import axios from '../services/axios';
import '../styles/Auth.css'; // Importer le fichier de styles

const SignUp = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');
    const [pseudo, setPseudo] = useState('');
    const navigate = useNavigate(); // Utiliser useNavigate pour rediriger

    const handleSubmit = async (event) => {
        event.preventDefault();
        try {
            const response = await axios.post('/register', { email, password, pseudo });
            // Si l'inscription est réussie, rediriger vers la page de connexion avec un message de succès
            navigate('/login', { state: { successMessage: 'Inscription réussie, veuillez vous connecter.' } });
        } catch (error) {
            setMessage('Erreur lors de l\'inscription : ' + error.response?.data?.message || 'Une erreur est survenue');
        }
    };

    return (
        <div className="signup-container">
             <div className="auth_overlay"></div> {/* Overlay devant l'image */}
            <div className="signup-wrapper">
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
                    <label htmlFor="pseudo">Pseudo :</label>
                    <input
                        type="text"
                        id="pseudo"
                        value={pseudo}
                        onChange={(e) => setPseudo(e.target.value)}
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

                {/* Liens vers la page d'accueil et la page de connexion */}
                <div className="signup-links">
                    <Link to="/">Retour à l'accueil</Link>
                    <Link to="/login">Déjà inscrit ? Se connecter</Link>
                </div>
            </div>
            {message && <p className="notification">{message}</p>}
        </div>
    );
};

export default SignUp;
