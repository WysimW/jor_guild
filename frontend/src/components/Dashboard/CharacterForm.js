import React, { useState, useEffect } from 'react';
import axios from '../../services/axios';

const CharacterForm = ({ refreshCharacters }) => {
    const [name, setName] = useState('');
    const [classeId, setClasseId] = useState('');
    const [classes, setClasses] = useState([]);
    const [message, setMessage] = useState('');

    // Récupérer la liste des classes depuis l'API
    useEffect(() => {
        const fetchClasses = async () => {
            try {
                const response = await axios.get('/classes');
                setClasses(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des classes :', error);
            }
        };

        fetchClasses();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post('/character/create', {
                name: name,
                classe_id: classeId,
            });
            setMessage('Personnage créé avec succès !');
            refreshCharacters(); // Rafraîchir la liste des personnages après création

            // Réinitialiser le formulaire
            setName('');
            setClasseId('');

            // Réinitialiser le message après 3 secondes
            setTimeout(() => {
                setMessage('');
            }, 3000);
        } catch (error) {
            console.error('Erreur lors de la création du personnage :', error);
            setMessage('Erreur lors de la création du personnage.');
        }
    };

    return (
        <div className="character-form">
            <h3>Créer un personnage</h3>
            <form onSubmit={handleSubmit}>
                <label htmlFor="name">Nom du personnage :</label>
                <input
                    type="text"
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                />

                <label htmlFor="classe">Classe :</label>
                <select
                    id="classe"
                    value={classeId}
                    onChange={(e) => setClasseId(e.target.value)}
                    required
                >
                    <option value="">Sélectionner une classe</option>
                    {classes.length > 0 && classes.map((classe) => (
                        <option key={classe.id} value={classe.id}>
                            {classe.name}
                        </option>
                    ))}
                </select>

                <button type="submit">Créer</button>
            </form>
            {message && <p className="notification">{message}</p>}
        </div>
    );
};

export default CharacterForm;
