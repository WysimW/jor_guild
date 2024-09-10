import React, { useEffect, useState } from 'react';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // Importer les styles de tippy.js
import '../styles/Dashboard.css'; // Importer le fichier de styles

const CharacterList = ({ characters }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [charactersPerPage] = useState(5);

    // Initialiser tippy après que les personnages soient rendus
    useEffect(() => {
        if (characters.length > 0) {
            tippy('.character-font', {
                content(reference) {
                    return reference.getAttribute('data-tippy-content');
                },
                theme: 'light',
            });
        }
    }, [characters]);

    const slugify = (text) => {
        return text
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    };

    // Pagination logic
    const indexOfLastCharacter = currentPage * charactersPerPage;
    const indexOfFirstCharacter = indexOfLastCharacter - charactersPerPage;
    const currentCharacters = characters.slice(indexOfFirstCharacter, indexOfLastCharacter);

    const totalPages = Math.ceil(characters.length / charactersPerPage);

    const handlePageClick = (pageNumber) => {
        setCurrentPage(pageNumber);
    };

    return (
        <div className="character-list">
            <h3>Liste de personnages</h3>
            {characters.length > 0 ? (
                <>
                    <ul className="character-list-items">
                        {currentCharacters.map((character) => (
                            <li
                                key={character.id}
                                className={`character-font character-${slugify(character.classe.name)}-font`}
                                data-tippy-content={`Classe: ${character.classe.name}`}
                            >
                                {character.name}
                            </li>
                        ))}
                    </ul>
                </>
            ) : (
                <p>Vous n'avez pas encore de personnages.</p>
            )}

            {totalPages > 1 && (
                <div className="pagination">
                    {Array.from({ length: totalPages }, (_, index) => (
                        <button
                            key={index + 1}
                            onClick={() => handlePageClick(index + 1)}
                            className={`pagination-number ${currentPage === index + 1 ? 'active' : ''}`}
                        >
                            {index + 1}
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
};

export default CharacterList;
