import React, { useContext } from 'react';
import CharacterForm from '../components/CharacterForm';
import CharacterList from '../components/CharacterList';
import RaidList from '../components/RaidList';
import Tabs from '../components/Tabs';
import { UserContext } from '../contexts/UserContext'; // Importer le contexte utilisateur
import '../styles/Dashboard.css'; // Importer le fichier de styles

const Dashboard = () => {
    const user = useContext(UserContext); // Récupérer les informations de l'utilisateur

    return (
        <div className="dashboard">
            <div className="overlay"></div>

            <div className="dashboard-content">
                {/* Affichage du message de bienvenue avec le pseudo de l'utilisateur */}
                <h2>Bienvenue {user ? user.pseudo : 'Utilisateur'}</h2>

                <Tabs>
                    <div label="Personnages" className='character-tabs-container'>
                        {/* Ajouter ici la gestion des personnages */}
                        <CharacterList />
                        <CharacterForm />
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
