import React, { useState, useEffect } from 'react';
import './RaidHistorySearch.css';

const RaidHistorySearch = ({ onSearch }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [difficulty, setDifficulty] = useState('');
    const [boss, setBoss] = useState('');
    const [sort, setSort] = useState('date');
    const [order, setOrder] = useState('desc');

    // State pour gérer le débonçage
    const [debouncedSearchParams, setDebouncedSearchParams] = useState({});

    // useEffect pour gérer le débonçage
    useEffect(() => {
        const handler = setTimeout(() => {
            setDebouncedSearchParams({
                searchTerm,
                difficulty,
                boss,
                sort,
                order,
            });
        }, 500); // Délai de 500ms

        // Nettoie le timeout si les valeurs changent avant la fin du délai
        return () => {
            clearTimeout(handler);
        };
    }, [searchTerm, difficulty, boss, sort, order]);

    // useEffect pour déclencher la recherche lorsque les paramètres débouncés changent
    useEffect(() => {
        onSearch(debouncedSearchParams);
    }, [debouncedSearchParams, onSearch]);

    return (
        <form className="raid-history-search">
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
        </form>
    );
};

export default RaidHistorySearch;
