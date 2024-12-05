import React from 'react';
import RaidHistoryItem from './components/RaidHistoryItem/RaidHistoryItem'; // Sous-composant pour chaque raid
import './/RaidHistoryList.css';

const RaidHistoryList = ({ raids }) => {
    return (
        <ul className="raid-history-list">
            {raids.map((raid) => (
                <RaidHistoryItem key={raid.id} raid={raid} />
            ))}
        </ul>
    );
};

export default RaidHistoryList;
