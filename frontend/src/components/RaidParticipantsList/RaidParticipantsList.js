import React, { useEffect } from 'react';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import './RaidParticipantsList.css'; // Ajouter un fichier CSS dédié si nécessaire

const RaidParticipantsList = ({ sortedByRole }) => {
    const rolePriority = {
        'Tank': 1,
        'Heal': 2,
        'Melee DPS': 4,
        'Ranged DPS': 5,
        'Support': 3,
        'Aucun rôle': 6
    };

    // Trier les rôles selon leur priorité (Tank > Heal > DPS)
    const sortedRoles = Object.keys(sortedByRole).sort((a, b) => rolePriority[a] - rolePriority[b]);

    // Initialiser tippy.js après le rendu des éléments de personnage
    useEffect(() => {
        tippy('.character-badge', {
            content(reference) {
                return reference.getAttribute('data-tippy-content');
            },
            theme: 'light',
        });
    }, [sortedByRole]); // Ce hook sera toujours appelé si les participants changent

    const slugify = (text) => {
        return text
            .toString()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    };

    return (
        <div className="raid-participants-list">
            {sortedRoles.map((role) => (
                <div key={role} className="raid-participants-list__role-column">
                    <h4 className="raid-participants-list__role-title">
                        {role} ({sortedByRole[role].length}) {/* Afficher le nombre de personnages */}
                    </h4>
                    <ul>
                        {sortedByRole[role].map(({ character, specialization }) => (
                            <li
                                key={character.id}
                                className={`character-badge character-${slugify(character.classe.name)}`}
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
    );
};

export default RaidParticipantsList;
