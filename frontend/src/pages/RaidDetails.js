import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import { useParams } from 'react-router-dom';
import tippy from 'tippy.js'; // Importer tippy.js pour les infobulles
import 'tippy.js/dist/tippy.css'; // Importer les styles de tippy.js
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

    useEffect(() => {
        // Initialiser tippy.js après le rendu des éléments de personnage
        if (raidDetails) {
            tippy('.character-badge', {
                content(reference) {
                    return reference.getAttribute('data-tippy-content');
                },
                theme: 'light',
            });
        }
    }, [raidDetails]);

    if (!raidDetails) {
        return <p>Chargement des détails du raid...</p>;
    }

    // Définir les priorités de rôle
    const rolePriority = {
        Tank: 1,
        Heal: 2,
        DPS: 3,
        'Aucun rôle': 4 // Pour les cas sans rôle attribué
    };

    // Trier les inscriptions par rôle en utilisant la spécialisation et la priorité des rôles
    const sortedByRole = raidDetails.inscriptions.reduce((acc, inscription) => {
        const specialization = inscription.registredCharacter.specializations[0]; // Prendre la première spécialisation
        const role = specialization?.role?.name || 'Aucun rôle'; // Récupérer le nom du rôle de la spécialisation
        if (!acc[role]) {
            acc[role] = [];
        }
        acc[role].push({
            character: inscription.registredCharacter,
            specialization: specialization?.name || 'Aucune spécialisation',
        });
        return acc;
    }, {});

    // Classer les rôles selon leur priorité (Tank > Heal > DPS)
    const sortedRoles = Object.keys(sortedByRole).sort((a, b) => rolePriority[a] - rolePriority[b]);

    return (
        <div className="raid-details-container">
            <div className="overlay"></div> {/* Overlay devant l'image */}
            <div className="raid-details">
                <h2 className="raid-details__title">{raidDetails.title}</h2>
                <p className="raid-details__description">{raidDetails.description}</p>
                <p className="raid-details__date">
                    <span className="raid-details__date--formatted">
                        {new Intl.DateTimeFormat("fr-FR", {
                            weekday: "long",
                            day: "numeric",
                            month: "long"
                        }).format(new Date(raidDetails.date))}
                    </span> 
                    à {new Intl.DateTimeFormat("fr-FR", {
                        hour: "2-digit",
                        minute: "2-digit"
                    }).format(new Date(raidDetails.date))}
                </p>

                <h3 className="raid-details__subheading">Inscriptions</h3>
                <div className="raid-details__grid">
                    {sortedRoles.map((role) => (
                        <div key={role} className="raid-details__role-column">
                            <h4 className="raid-details__role-title">{role}</h4>
                            <ul>
                                {sortedByRole[role].map(({ character, specialization }) => (
                                    <li 
                                        key={character.id} 
                                        className={`character-badge character-${character.classe.name.toLowerCase()}`} 
                                        data-tippy-content={`Classe: ${character.classe.name}, Spécialisation: ${specialization}`} // Utiliser l'attribut data-tippy-content pour afficher la classe et la spécialisation
                                    >
                                        <span className="character-badge__name">
                                            {character.name}
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default RaidDetails;
