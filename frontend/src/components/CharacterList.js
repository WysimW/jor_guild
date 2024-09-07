import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import '../styles/Dashboard.css'; // Importer le fichier de styles

const CharacterList = () => {
    const [characters, setCharacters] = useState([]);

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

    console.log(characters);

    return (
        <div className="character-list">
            <h3>Liste de vos personnages</h3>
            {characters.length > 0 ? (
                <ul>
                    {characters.map((character) => (
                        <li key={character.id}>
                            {character.name} - 
                            {character.raidRoles.length > 0 ? (
                                character.raidRoles.map((role, index) => (
                                    <span key={role.id}>
                                        {role.name}{index < character.raidRoles.length - 1 ? ', ' : ''}
                                    </span>
                                ))
                            ) : (
                                <span>Aucun rôle</span>
                            )}
                        </li>
                    ))}
                </ul>
            ) : (
                <p>Vous n'avez pas encore de personnages.</p>
            )}
        </div>
    );
};

export default CharacterList;
