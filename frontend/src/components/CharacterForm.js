import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import '../styles/Dashboard.css'; // Importer le fichier de styles

const CharacterForm = () => {
    const [name, setName] = useState('');
    const [roleId, setRoleId] = useState(''); // Stocker l'ID du rôle sélectionné
    const [roles, setRoles] = useState([]); // Stocker la liste des rôles
    const [message, setMessage] = useState('');

    // Récupérer la liste des rôles depuis l'API
    useEffect(() => {
        const fetchRoles = async () => {
            try {
                const response = await axios.get('/roles');
                setRoles(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des rôles :', error);
            }
        };

        fetchRoles();
    }, []);

    console.log(roles)

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('/character/create', {
                name: name,
                role_id: roleId, // Envoyer l'ID du rôle sélectionné
            });
            setMessage('Personnage créé avec succès !');
        } catch (error) {
            console.error('Erreur lors de la création du personnage :', error);
            setMessage('Erreur lors de la création du personnage.');
        }
    };

    return (
        <div className="character-form">
            <h2>Créer un personnage</h2>
            <form onSubmit={handleSubmit}>
                <label htmlFor="name">Nom du personnage :</label>
                <input
                    type="text"
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                />

                <label htmlFor="role">Rôle :</label>
                <select
                    id="role"
                    value={roleId}
                    onChange={(e) => setRoleId(e.target.value)}
                    required
                >
                    <option value="">Sélectionner un rôle</option>
                    {roles.length > 0 && roles.map((role, index) => (
                        <option key={role.id || index} value={role.id}> {/* Ajout d'une clé alternative */}
                            {role.name}
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
