// Pagination.js

import React from 'react';
import './Pagination.css';

const Pagination = ({ total, page, limit, onPageChange }) => {
    const totalPages = Math.ceil(total / limit);

    if (totalPages <= 1) return null;

    const pageNumbers = [];
    for (let i = 1; i <= totalPages; i++) {
        pageNumbers.push(i);
    }

    return (
        <div className="pagination">
            {pageNumbers.map((number) => (
                <button
                    key={number}
                    onClick={() => onPageChange(number)}
                    className={`pagination-number ${page === number ? 'active' : ''}`}
                >
                    {number}
                </button>
            ))}
        </div>
    );
};


export default Pagination;
