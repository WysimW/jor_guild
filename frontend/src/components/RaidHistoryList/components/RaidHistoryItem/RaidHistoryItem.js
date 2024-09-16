import React from 'react';
import RaidParticipants from './components/RaidParticipants/RaidParticipants'; // Sous-composant pour les participants
import './RaidHistoryItem.css';

const RaidHistoryItem = ({ raid }) => {
    return (
        <li className="raid-history-item">
            <h3>{raid.title} - {new Date(raid.date).toLocaleDateString()}</h3>
            <p>{raid.description}</p>
            <p><strong>Boss tombés :</strong> {raid.bossesDown.length > 0 ? raid.bossesDown.join(', ') : 'Aucun boss tombé'}</p>
            <p><strong>Durée :</strong> {raid.duration || 'Non spécifiée'}</p>
            <RaidParticipants participants={raid.registeredCharacters} />
            {raid.logsLink && (
                <p><a href={raid.logsLink} target="_blank" rel="noopener noreferrer">Voir les logs du raid</a></p>
            )}
        </li>
    );
};

export default RaidHistoryItem;
