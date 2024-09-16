import React, { useContext, useState, useEffect } from 'react';
import CharacterForm from 'components/Dashboard/CharacterForm';
import CharacterList from 'components/Dashboard/CharacterList';
import Profession from 'components/Dashboard/Profession';
import Tabs from 'components/Tabs/Tabs';
import { UserContext } from 'contexts/UserContext'; // Importer le contexte utilisateur
import axios from 'services/axios';
import './Dashboard.css'; // Importer le fichier de styles

const Dashboard = () => {
    const user = useContext(UserContext); // Récupérer les informations de l'utilisateur
    const [characters, setCharacters] = useState([]); // État pour stocker les personnages

    // Fonction pour récupérer les personnages
    const fetchCharacters = async () => {
        try {
            const response = await axios.get('/characters/list');
            setCharacters(response.data);
        } catch (error) {
            console.error('Erreur lors de la récupération des personnages :', error);
        }
    };

    // Charger les personnages au montage du composant
    useEffect(() => {
        fetchCharacters();
    }, []);

    return (
        <div className="dashboard">
            <div className="overlay"></div>

            <div className="dashboard-content">
                {/* Affichage du message de bienvenue avec le pseudo de l'utilisateur */}
                <h2>Bienvenue {user ? user.pseudo : 'Utilisateur'}</h2>

                <Tabs>
                    <div label="Personnages" className="character-tabs-container">
                        {/* Passer les personnages et la fonction de mise à jour aux composants enfants */}
                        <CharacterList characters={characters} refreshCharacters={fetchCharacters}/>
                        <CharacterForm refreshCharacters={fetchCharacters} />
                    </div>
                    <div label="Mes Métiers">
                        {/* Ajouter ici la gestion des raids */}
                        <Profession />
                    </div>
                </Tabs>
            </div>
        </div>
    );
};

export default Dashboard;
