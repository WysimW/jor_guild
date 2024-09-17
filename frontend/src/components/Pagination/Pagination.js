// src/components/Pagination/Pagination.js

import React from 'react';
import './Pagination.css';

const Pagination = ({ total, page, limit, onPageChange }) => {
    const totalPages = Math.ceil(total / limit);

    const pages = [];
    for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
    }

    return (
        <div className="pagination">
            {pages.map((pageNumber) => (
                <button
                    key={pageNumber}
                    onClick={() => onPageChange(pageNumber)}
                    className={pageNumber === page ? 'active' : ''}
                >
                    {pageNumber}
                </button>
            ))}
        </div>
    );
};

export default Pagination;
