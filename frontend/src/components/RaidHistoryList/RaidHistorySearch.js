// src/components/RaidHistorySearch/RaidHistorySearch.js

import React, { useState } from 'react';
import './RaidHistorySearch.css';

const RaidHistorySearch = ({ onSearch }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [difficulty, setDifficulty] = useState('');
    const [boss, setBoss] = useState('');
    const [sort, setSort] = useState('date');
    const [order, setOrder] = useState('desc');

    const handleSearch = (e) => {
        e.preventDefault();
        onSearch({ searchTerm, difficulty, boss, sort, order });
    };

    return (
        <form className="raid-history-search" onSubmit={handleSearch}>
            <input
                type="text"
                placeholder="Rechercher..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
            />

            <select value={difficulty} onChange={(e) => setDifficulty(e.target.value)}>
                <option value="">Toutes difficultés</option>
                <option value="Normal">Normal</option>
                <option value="Héroïque">Héroïque</option>
                <option value="Mythique">Mythique</option>
            </select>

            <input
                type="text"
                placeholder="Boss..."
                value={boss}
                onChange={(e) => setBoss(e.target.value)}
            />

            <select value={sort} onChange={(e) => setSort(e.target.value)}>
                <option value="date">Date</option>
                <option value="title">Titre</option>
            </select>

            <select value={order} onChange={(e) => setOrder(e.target.value)}>
                <option value="desc">Décroissant</option>
                <option value="asc">Croissant</option>
            </select>

            <button type="submit">Rechercher</button>
        </form>
    );
};

export default RaidHistorySearch;
