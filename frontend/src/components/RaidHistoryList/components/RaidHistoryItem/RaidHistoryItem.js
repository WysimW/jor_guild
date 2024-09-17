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

    const renderLinks = () => {
        const { links } = raid;
        if (!links) return null;
    
        const linkElements = [];
    
        if (links.warcraftLogs) {
            linkElements.push(
                <a href={links.warcraftLogs} target="_blank" rel="noopener noreferrer" key="warcraftLogs">
                    Warcraft Logs
                </a>
            );
        }
    
        if (links.wowAnalyzer) {
            linkElements.push(
                <a href={links.wowAnalyzer} target="_blank" rel="noopener noreferrer" key="wowAnalyzer">
                    WoW Analyzer
                </a>
            );
        }
    
        if (links.wipeFest) {
            linkElements.push(
                <a href={links.wipeFest} target="_blank" rel="noopener noreferrer" key="wipeFest">
                    WipeFest
                </a>
            );
        }
    
        if (linkElements.length === 0) return null;
    
        return (
            <p className="raid-history-list__links">
                {linkElements.reduce((prev, curr) => [prev, ' | ', curr])}
            </p>
        );
    };
    

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
    <strong>Boss tombés :</strong>{' '}
    {Array.isArray(raid.downedBosses) && raid.downedBosses.length > 0
        ? raid.downedBosses.map(boss => boss.name).join(', ')
        : 'Aucun boss tombé'}
</p>


                <RaidParticipantsList sortedByRole={sortedByRole} /> {/* Passer sortedParticipantsByRole ici */}

                {renderLinks()}

            </div>
        </li>
    );
};

export default RaidHistoryItem;
