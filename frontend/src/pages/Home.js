// src/components/Home.js
import React from 'react';
import { Link } from 'react-router-dom';

import '../styles/Home.css';

const Home = () => {
    return (
      <div className="home">
        <div className="overlay"></div> {/* Overlay devant l'image */}
        <div className="home-content">
          <h2>Bienvenue dans la Guilde JOR</h2>
          <p>
            Explorez Azeroth avec notre guilde. Que vous soyez un héros de la Horde ou de l'Alliance, une aventure épique vous attend.
          </p>
          <div className="home-buttons">
            <Link to="/signup">
              <button className="home-button">S'inscrire</button>
            </Link>
            <Link to="/login">
              <button className="home-button">Se connecter</button>
            </Link>
          </div>
        </div>
      </div>
    );
  };

export default Home;
