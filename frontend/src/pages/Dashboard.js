import React from 'react';
import CharacterForm from '../components/CharacterForm';
import CharacterList from '../components/CharacterList';

import RaidList from '../components/RaidList';
import Tabs from '../components/Tabs'; // Importer le composant des onglets
import '../styles/Dashboard.css'; // Importer le fichier de styles

const Dashboard = () => {
  return (
      <div className="dashboard">
          <div className="overlay"></div>

          <div className="dashboard-content">
              <h2>Bienvenue dans le Tableau de Bord</h2>
              <p>Gérez vos personnages et inscrivez-vous aux raids !</p>
              <Tabs>
                  <div label="Personnages">
                      {/* Ajouter ici la gestion des personnages */}
                      <CharacterList />
                      <CharacterForm />
                      {/* Ici, tu pourrais aussi afficher la liste des personnages */}
                  </div>
                  <div label="Raids">
                      {/* Ajouter ici la gestion des raids */}
                      <RaidList />
                  </div>
              </Tabs>
          </div>
      </div>
  );
};

export default Dashboard;
