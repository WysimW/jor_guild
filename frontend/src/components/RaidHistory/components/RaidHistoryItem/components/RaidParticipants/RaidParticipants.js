import React from 'react';
import './RaidParticipants.css';

const RaidParticipants = ({ participants }) => {
    return (
        <div className="raid-participants">
            <h4>Participants ({participants.length}):</h4>
            <ul>
                {participants.map((participant) => (
                    <li key={participant.id}>
                        {participant.name} (User: {participant.user.username})
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default RaidParticipants;
