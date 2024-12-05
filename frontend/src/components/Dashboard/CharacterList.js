import React, { useEffect, useState } from 'react';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // Importer les styles de tippy.js
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons'; // Icône de suppression
import axios from '../../services/axios';

const CharacterList = ({ characters, refreshCharacters }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [charactersPerPage] = useState(5);
    const [message, setMessage] = useState(''); // Ajout de l'état pour le message

    // Initialiser tippy après que les personnages soient rendus
    useEffect(() => {
        if (characters.length > 0) {
            tippy('.character-tippy', {
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

    const handleDeleteCharacter = async (characterId) => {
        try {
            await axios.delete(`/characters/${characterId}`); // Suppression via API
            refreshCharacters(); // Rafraîchir la liste des personnages après suppression
            setMessage('Personnage supprimé avec succès !'); // Définir le message de succès
        } catch (error) {
            setMessage('Erreur lors de la suppression du personnage.'); // Définir le message d'erreur
            console.error('Erreur lors de la suppression du personnage :', error);
        }

        // Effacer le message après 3 secondes
        setTimeout(() => {
            setMessage('');
        }, 3000);
    };

    return (
        <div className="character-list">
            <h3>Liste de personnages</h3>
            {/* Notification */}
            {message && <p className="notification">{message}
                </p>} {/* Afficher le message */}

            {characters.length > 0 ? (
                <ul className="character-list-items">
                    {currentCharacters.map((character) => (
                        <li key={character.id} className={`character-font character-${slugify(character.classe.name)}-font`}>
                            <span 
                            key={character.id} 
                            className='character-tippy'
                            data-tippy-content={`Classe: ${character.classe.name}`}>
                                {character.name}
                            </span>
                            <button
                                className="delete-button"
                                onClick={() => handleDeleteCharacter(character.id)}
                                aria-label="Supprimer le personnage"
                            >
                                <FontAwesomeIcon icon={faTrashAlt} />
                            </button>
                        </li>
                    ))}
                </ul>
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
