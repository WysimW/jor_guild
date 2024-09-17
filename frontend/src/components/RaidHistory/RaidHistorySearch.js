import React, { useState, useEffect, useRef } from 'react';
import './RaidHistorySearch.css';

const RaidHistorySearch = ({ onSearch }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [difficulty, setDifficulty] = useState('');
    const [boss, setBoss] = useState('');
    const [sort, setSort] = useState('date');
    const [order, setOrder] = useState('desc');

    const prevSearchParamsRef = useRef({
        searchTerm,
        difficulty,
        boss,
        sort,
        order,
    });

    useEffect(() => {
        const newSearchParams = {
            searchTerm,
            difficulty,
            boss,
            sort,
            order,
        };

        // Comparer les nouveaux paramètres avec les anciens
        const prevSearchParams = prevSearchParamsRef.current;

        const hasChanged = JSON.stringify(newSearchParams) !== JSON.stringify(prevSearchParams);

        if (hasChanged) {
            onSearch(newSearchParams);
            prevSearchParamsRef.current = newSearchParams;
        }
    }, [searchTerm, difficulty, boss, sort, order, onSearch]);

    return (
        <div className="raid-history-search">
            <input
                type="text"
                placeholder="Rechercher un raid..."
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
                placeholder="Rechercher un boss..."
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
        </div>
    );
};

export default RaidHistorySearch;
