import React, { useState, useEffect } from 'react';
import axios from '../services/axios';
import RaidHistorySearch from 'components/RaidHistoryList/RaidHistorySearch';
import RaidHistoryList from 'components/RaidHistoryList/RaidHistoryList';
import Pagination from 'components/Pagination/Pagination';

import './RaidHistory.css';

const RaidHistory = () => {
    const [raids, setRaids] = useState([]);
    const [total, setTotal] = useState(0);
    const [page, setPage] = useState(1);
    const [limit] = useState(10);
    const [searchParams, setSearchParams] = useState({});


    const fetchRaids = async (params = {}) => {
        try {
            const response = await axios.get('/raids/history', {
                params: {
                    ...params,
                    page,
                    limit,
                },
            });
            setRaids(response.data.data);
            setTotal(response.data.total);
        } catch (error) {
            console.error(error);
        }
    };

    console.log(raids)

    useEffect(() => {
        fetchRaids(searchParams);
    }, [page, searchParams]);

    const handleSearch = (params) => {
        setPage(1); // Réinitialiser la page à 1 lors d'une nouvelle recherche
        setSearchParams(params);
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
                onPageChange={(newPage) => setPage(newPage)}
            />
            </div>
        </div>
    );
};

export default RaidHistory;
