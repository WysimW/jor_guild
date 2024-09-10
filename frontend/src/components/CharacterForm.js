import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import '../styles/Dashboard.css'; // Importer le fichier de styles

const CharacterForm = () => {
    const [name, setName] = useState('');
    const [classeId, setClasseId] = useState(''); // Stocker l'ID de la classe sélectionnée
    const [classes, setClasses] = useState([]); // Stocker la liste des classes
    const [message, setMessage] = useState('');

    // Récupérer la liste des classes depuis l'API
    useEffect(() => {
        const fetchClasses = async () => {
            try {
                const response = await axios.get('/classes'); // Remplacer '/roles' par '/classes' pour récupérer les classes
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
            const response = await axios.post('/character/create', {
                name: name,
                classe_id: classeId, // Envoyer l'ID de la classe sélectionnée
            });
            setMessage('Personnage créé avec succès !');
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
                    {classes.length > 0 && classes.map((classe, index) => (
                        <option key={classe.id || index} value={classe.id}>
                            {classe.name}
                        </option>
                    ))}
                </select>

                <button type="submit">Créer</button>
            </form>
            {message && <p>{message}</p>}
        </div>
    );
};

export default CharacterForm;
