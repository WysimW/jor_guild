import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import { useParams } from 'react-router-dom';
import '../styles/RaidDetails.css';

const RaidDetails = () => {
    const { id } = useParams(); // Récupérer l'ID du raid depuis l'URL
    const [raidDetails, setRaidDetails] = useState(null);

    useEffect(() => {
        const fetchRaidDetails = async () => {
            try {
                const response = await axios.get(`/raid/${id}/details`); // Récupérer les détails du raid depuis l'API
                setRaidDetails(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération des détails du raid:', error);
            }
        };
        fetchRaidDetails();
    }, [id]);

    if (!raidDetails) {
        return <p>Chargement des détails du raid...</p>;
    }

    // Trier les inscrits par rôle
    const sortedByRole = raidDetails.inscriptions.reduce((acc, inscription) => {
        const role = inscription.registredCharacter.raidRoles[0]?.name || 'Aucun rôle';
        if (!acc[role]) {
            acc[role] = [];
        }
        acc[role].push(inscription.registredCharacter);
        return acc;
    }, {});

    return (
        <div className="raid-details">
            <h2>Détails du Raid: {raidDetails.title}</h2>
            <p>{raidDetails.description}</p>

            <h3>Inscriptions triées par rôle</h3>
            {Object.keys(sortedByRole).map((role) => (
                <div key={role}>
                    <h4>{role}</h4>
                    <ul>
                        {sortedByRole[role].map((character) => (
                            <li key={character.id}>{character.name}</li>
                        ))}
                    </ul>
                </div>
            ))}
        </div>
    );
};

export default RaidDetails;
