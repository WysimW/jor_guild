import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import axios from "../services/axios";
import "../styles/RaidList.css";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus, faTimes } from "@fortawesome/free-solid-svg-icons";

const RaidList = () => {
  const [raids, setRaids] = useState([]);
  const [characters, setCharacters] = useState([]); // Stocker les personnages de l'utilisateur
  const [specializations, setSpecializations] = useState([]); // Stocker les spécialisations du personnage
  const [selectedCharacter, setSelectedCharacter] = useState(""); // Stocker le personnage sélectionné
  const [selectedSpecialization, setSelectedSpecialization] = useState(""); // Stocker la spécialisation sélectionnée
  const [selectedCharacterDetails, setSelectedCharacterDetails] = useState(
    null
  ); // Stocker les détails du personnage
  const [message, setMessage] = useState("");
  const [showPopup, setShowPopup] = useState(false); // Gérer l'état du popup d'inscription
  const [notification, setNotification] = useState(""); // Gérer les notifications
  const [selectedRaid, setSelectedRaid] = useState(null); // Stocker l'ID du raid sélectionné

  useEffect(() => {
    const fetchRaids = async () => {
      try {
        const response = await axios.get("/raids"); // Récupérer la liste des raids
        setRaids(response.data);
      } catch (error) {
        console.error("Erreur lors de la récupération des raids:", error);
      }
    };
    fetchRaids();
  }, []);

  useEffect(() => {
    const fetchCharacters = async () => {
      try {
        const response = await axios.get("/characters/list"); // Récupérer les personnages de l'utilisateur
        setCharacters(response.data);
      } catch (error) {
        console.error(
          "Erreur lors de la récupération des personnages :",
          error
        );
      }
    };
    fetchCharacters();
  }, []);

  // Récupérer les spécialisations d'un personnage
  const fetchSpecializations = async characterId => {
    try {
      const response = await axios.get(
        `/characters/${characterId}/specializations`
      );
      setSpecializations(response.data);
    } catch (error) {
      console.error(
        "Erreur lors de la récupération des spécialisations :",
        error
      );
    }
  };

  // Afficher le popup avec les spécialisations après avoir cliqué sur "S'inscrire"
  const handleRegisterClick = (characterId, raidId) => {
    if (!characterId) {
      setNotification(
        "Veuillez sélectionner un personnage avant de vous inscrire."
      );
      return;
    }

    const character = characters.find(
      char => char.id === parseInt(characterId)
    ); // Parse l'ID pour être sûr
    setSelectedCharacterDetails(character);
    fetchSpecializations(characterId); // Récupérer les spécialisations pour ce personnage
    setSelectedRaid(raidId); // Stocker l'ID du raid
    setShowPopup(true); // Ouvrir le popup
  };

  const handleRegister = async () => {
    if (!selectedSpecialization) {
      setNotification(
        "Veuillez sélectionner une spécialisation pour vous inscrire."
      );
      return;
    }

    try {
      const response = await axios.post("/raid/register", {
        raid_id: selectedRaid,
        character_id: selectedCharacterDetails.id,
        specialization_id: selectedSpecialization // Envoyer l'ID de la spécialisation
      });
      setNotification("Inscription réussie au raid !");
      setShowPopup(false); // Fermer le popup après l'inscription
    } catch (error) {
      console.error("Erreur lors de l'inscription :", error.response);
      setNotification("Erreur lors de l'inscription.");
    }
  };

  // Fermer le popup en cliquant à l'extérieur
  const handleOutsideClick = event => {
    if (event.target.classList.contains("popup")) {
      setShowPopup(false);
    }
  };

  // Fonction pour effacer la notification après quelques secondes
  useEffect(
    () => {
      if (notification) {
        const timer = setTimeout(() => {
          setNotification("");
        }, 3000); // Délai de 3 secondes avant de masquer la notification
        return () => clearTimeout(timer);
      }
    },
    [notification]
  );

  return (
    <div className="raid-list-wrapper">
      <div className="raid-list-wrapper--overlay" />{" "}
      {/* Overlay devant l'image */}
      <div className="raid-list">
        <div className="raid-list__content">
          <h2 className="raid-list__title">Liste des Raids</h2>

          {/* Notification */}
          {notification &&
            <div className="notification">
              {notification}
            </div>}

          {/* Sélection du personnage */}
          <div className="raid-list__character-select">
            <label htmlFor="characterSelect">
            </label>
            <select
              id="characterSelect"
              value={selectedCharacter}
              onChange={e => setSelectedCharacter(e.target.value)}
            >
              <option value="">Sélectionnez un personnage</option>
              {characters.map(character =>
                <option key={character.id} value={character.id}>
                  {character.name} - {character.classe.name}
                </option>
              )}
            </select>
          </div>

          {/* Liste des raids */}
          {raids.length > 0
            ? <ul className="raid-list__items">
                {raids.map(raid =>
                  <li className="raid-list__item" key={raid.id}>
                    <h3 className="raid-list__item-title">
                      {raid.title}
                    </h3>
                    <p className="raid-list__item-description">
                      {raid.description}
                    </p>
                    <p className="raid-list__item-date">
                      <span className="raid-list__item-date--dateformat">
                        {new Intl.DateTimeFormat("fr-FR", {
                          weekday: "long", // Jour de la semaine
                          day: "numeric", // Jour du mois
                          month: "long"
                        }).format(new Date(raid.date))} </span>à 21h
                      
                    </p>
                    <div className="raid-list__item-links">
                      <button
                        onClick={() =>
                          handleRegisterClick(selectedCharacter, raid.id)}
                        className="raid-list__item-link"
                      >
                        S'inscrire
                      </button>
                      <Link to={`/raid/${raid.id}`}>
                        <button className="raid-list__item-link">
                          Voir les inscrits
                        </button>
                      </Link>
                    </div>
                  </li>
                )}
              </ul>
            : <p className="raid-list__no-raids">
                Aucun raid disponible pour le moment.
              </p>}

          {/* Popup de sélection des spécialisations */}
          {showPopup &&
            selectedCharacterDetails &&
            <div className="popup" onClick={handleOutsideClick}>
              <div className="popup__content">
                <button
                  className="popup__close-btn"
                  onClick={() => setShowPopup(false)}
                >
                  <FontAwesomeIcon icon={faTimes} />
                </button>
                <h3>
                  Inscription au raid pour {selectedCharacterDetails.name}
                </h3>

                <label htmlFor="specializationSelect">Spécialisation :</label>
                <select
                  id="specializationSelect"
                  value={selectedSpecialization}
                  onChange={e => setSelectedSpecialization(e.target.value)}
                >
                  <option value="">Sélectionnez une spécialisation</option>
                  {specializations.map(specialization =>
                    <option key={specialization.id} value={specialization.id}>
                      {specialization.name}
                    </option>
                  )}
                </select>

                <button onClick={handleRegister} className="popup--confirm-btn">
                  Confirmer
                </button>
              </div>
            </div>}

          {message &&
            <p className="raid-list__message">
              {message}
            </p>}
        </div>
      </div>
    </div>
  );
};

export default RaidList;
