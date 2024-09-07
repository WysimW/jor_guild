import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from '../services/axios';
import '../styles/RaidList.css';
import '../styles/Home.css';


const RaidList = () => {
    const [raids, setRaids] = useState([]);
    const [characters, setCharacters] = useState([]); // Stocker les personnages de l'utilisateur
    const [selectedCharacter, setSelectedCharacter] = useState(''); // Stocker le personnage sélectionné
    const [message, setMessage] = useState('');

    useEffect(() => {
        const fetchRaids = async () => {
            try {
                const response = await axios.get('/raids'); // Récupérer la liste des raids depuis l'API
                setRaids(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des raids:', error);
            }
        };
        fetchRaids();
    }, []);

     // Récupérer la liste des personnages de l'utilisateur
     useEffect(() => {
        const fetchCharacters = async () => {
            try {
                const response = await axios.get('/characters/list'); // Endpoint pour récupérer les personnages
                setCharacters(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des personnages :', error);
            }
        };
        fetchCharacters();
    }, []);

    const handleRegister = async (raidId) => {
        if (!selectedCharacter) {
            setMessage('Veuillez sélectionner un personnage pour vous inscrire.');
            return;
        }

        try {
            const response = await axios.post('/raid/register', {
                raid_id: raidId,
                character_id: selectedCharacter, // Utiliser l'ID du personnage sélectionné
            });
            setMessage('Inscription réussie !');
        } catch (error) {
            console.error('Erreur lors de l\'inscription :', error.response);
            setMessage('Erreur lors de l\'inscription.');
        }
    };
    return (
        <div className="raid-list">
            <h2>Liste des Raids</h2>
                        {/* Liste déroulante pour sélectionner un personnage */}
                        <div>
                <label htmlFor="characterSelect">Sélectionnez un personnage :</label>
                <select
                    id="characterSelect"
                    value={selectedCharacter}
                    onChange={(e) => setSelectedCharacter(e.target.value)}
                >
                    <option value="">-- Sélectionnez un personnage --</option>
                    {characters.map((character) => (
                        <option key={character.id} value={character.id}>
                            {character.name} - {character.raidRoles.map(role => role.name).join(', ')}
                        </option>
                    ))}
                </select>
            </div>
            {raids.length > 0 ? (
                <ul>
                    {raids.map((raid) => (
                        <li key={raid.id}>
                            <h3>{raid.title}</h3>
                            <p>{raid.description}</p>
                            <p>Date: {new Date(raid.date).toLocaleDateString()}</p>

                            <Link to={`/raid/${raid.id}`}>Voir les inscrits</Link>
                            <button onClick={() => handleRegister(raid.id)}>
                                S'inscrire
                            </button>
                        </li>
                    ))}
                </ul>
            ) : (
                <p>Aucun raid disponible pour le moment.</p>
            )}
                        {message && <p>{message}</p>}

        </div>
    );
};

export default RaidList;
