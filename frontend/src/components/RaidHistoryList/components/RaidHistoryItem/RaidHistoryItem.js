import React, { useState } from 'react';
import './RaidHistoryItem.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';
import RaidParticipantsList from 'components/RaidParticipantsList/RaidParticipantsList'; // Importer le nouveau sous-composant

const RaidHistoryItem = ({ raid }) => {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    console.log(raid)
    // Fonction pour trier les participants par rôle
    const sortedByRole = raid.inscriptions.reduce((acc, inscription) => {
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

    return (
        <li className="raid-history-list__item">
            <div className="raid-history-list__header" onClick={toggleDropdown}>
                <h3 className="raid-history-list__title">{raid.title} - {new Date(raid.date).toLocaleDateString()}</h3>
                <FontAwesomeIcon
                    icon={faChevronDown}
                    className={`raid-history-list__icon ${isOpen ? 'raid-history-list__icon--open' : ''}`}
                />
            </div>

            <div className={`raid-history-list__details ${isOpen ? 'raid-history-list__details--open' : ''}`}>
                <p className="raid-history-list__description">{raid.description}</p>
                <p className="raid-history-list__bosses">
                    <strong>Boss tombés :</strong>
                    {Array.isArray(raid.bossesDown) && raid.bossesDown.length > 0
                        ? raid.bossesDown.join(', ')
                        : 'Aucun boss tombé'}
                </p>

                <RaidParticipantsList sortedByRole={sortedByRole} /> {/* Passer sortedParticipantsByRole ici */}

                {raid.logsLink && (
                    <p className="raid-history-list__logs"><a href={raid.logsLink} target="_blank" rel="noopener noreferrer">Voir les logs du raid</a></p>
                )}
            </div>
        </li>
    );
};

export default RaidHistoryItem;
