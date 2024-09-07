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
            Plongez dans les ténèbres et affrontez la plus grande menace qu'Azeroth ait jamais connue. En cette période de guerre intérieure, les héros doivent trouver leur force la plus profonde pour survivre.
          </p>

          <div className="home-sections">
            <div className="home-section">
              <h3>Les Secrets d'Azeroth</h3>
              <p>
                Explorez des territoires inconnus et découvrez les secrets cachés de ce monde déchiré.
              </p>
            </div>

            <div className="home-section">
              <h3>Nouvelle Puissance</h3>
              <p>
                Maîtrisez les nouvelles capacités et choisissez votre camp dans cette lutte pour la survie d'Azeroth.
              </p>
            </div>
          </div>

          <div className="home-cta">
            <Link to="/raids">
              <button className="cta-button">Voir les Raids</button>
            </Link>
            <Link to="/signup">
              <button className="cta-button">Rejoindre la Guilde</button>
            </Link>
          </div>
        </div>
      </div>
    );
};

export default Home;
