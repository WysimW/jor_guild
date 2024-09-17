import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import RaidHistoryList from '../components/RaidHistoryList/RaidHistoryList'; // Sous-composant pour la liste de raids
import './RaidHistory.css';

const RaidHistory = () => {
    const [raids, setRaids] = useState([]);

    useEffect(() => {
        const fetchRaidHistory = async () => {
            try {
                const response = await axios.get('/raids/history');
                setRaids(response.data);
            } catch (error) {
                console.error('Erreur lors de la récupération de l\'historique des raids:', error);
            }
        };

        fetchRaidHistory();
    }, []);

    return (
        <div className="raid-history-container">
            <div className="raid-history-wrapper">
            <h2>Historique des Raids</h2>
            {raids.length > 0 ? (
                <RaidHistoryList raids={raids} />
            ) : (
                <p>Aucun raid archivé pour le moment.</p>
            )}
            </div>
        </div>
    );
};

export default RaidHistory;
