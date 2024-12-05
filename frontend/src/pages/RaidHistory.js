import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import RaidHistorySearch from 'components/RaidHistory/RaidHistorySearch';
import RaidHistoryList from 'components/RaidHistory/RaidHistoryList';
import Pagination from 'components/Pagination/Pagination';

import './RaidHistory.css';

const RaidHistory = () => {
    const [raids, setRaids] = useState([]);
    const [total, setTotal] = useState(0);
    const [page, setPage] = useState(1);
    const [limit] = useState(10);
    const [searchParams, setSearchParams] = useState({});
    const [debouncedSearchParams, setDebouncedSearchParams] = useState({});
    const [loading, setLoading] = useState(false);

    const fetchRaids = async () => {
        setLoading(true);
        try {
            const response = await axios.get('/raids/history', {
                params: {
                    ...debouncedSearchParams,
                    page: page,
                    limit: limit,
                },
            });
            setRaids(response.data.data);
            setTotal(response.data.total);
        } catch (error) {
            console.error('Erreur lors du chargement des raids :', error);
        } finally {
            setLoading(false);
        }
    };

    // Débonçage des paramètres de recherche
    useEffect(() => {
        const handler = setTimeout(() => {
            setDebouncedSearchParams(searchParams);
        }, 500);

        return () => {
            clearTimeout(handler);
        };
    }, [searchParams]);

    // Appel de fetchRaids lorsque les paramètres débouncés ou la page changent
    useEffect(() => {
        fetchRaids();
    }, [debouncedSearchParams, page]);

    const handlePageChange = (newPage) => {
        setPage(newPage);
    };

    const handleSearch = (params) => {
        const hasChanged = JSON.stringify(params) !== JSON.stringify(searchParams);

        if (hasChanged) {
            setPage(1);
            setSearchParams(params);
        }
    };

    return (
        <div className="raid-history-container">
            <div className="raid-history-wrapper">
                <h2>Historique des Raids</h2>
                <RaidHistorySearch onSearch={handleSearch} />
                <RaidHistoryList raids={raids} />
                <Pagination
                    total={total}
                    page={page}
                    limit={limit}
                    onPageChange={handlePageChange}
                />
            </div>
        </div>
    );
};

export default RaidHistory;
