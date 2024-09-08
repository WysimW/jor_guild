import React from 'react';
import { Link } from 'react-router-dom';
import '../styles/Home.css';

const Home = () => {
    return (
      <div className="home">
        <div className="overlay"></div> {/* Overlay devant l'image */}
        <div className="home-content">
          <h2>The War Within</h2>
          <p className="intro">
          Bienvenue dans la Guilde JOR ! Rejoignez une communauté de héros unis par l’aventure et la camaraderie. Que vous soyez un vétéran aguerri ou un nouveau venu, ensemble, nous conquérons Azeroth et relevons chaque défi.
          </p>


          <div className="home-cta">
            <Link to="/raids">
              <button className="cta-button">Voir les Raids</button>
            </Link>

          </div>
        </div>
      </div>
    );
};

export default Home;
