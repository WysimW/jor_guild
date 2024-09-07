import React from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import { useAuth } from './hooks/useAuth';
import './App.css';  // Import the reset CSS first
import Home from './pages/Home';
import SignUp from './pages/SignUp';
import Login from './pages/Login';
import Dashboard from './pages/Dashboard'; // Une page pour l'utilisateur connecté
import Header from './components/Header';
import Footer from './components/Footer';
import RaidList from './pages/RaidList'; // Page pour afficher la liste des raids
import RaidDetails from './pages/RaidDetails'; // Page pour afficher les détails d'un raid
import RaidCalendar from './pages/RaidCalendar'; // Page pour afficher les détails d'un raid


const App = () => {
    const { token } = useAuth();

    // Route privée qui redirige vers /login si l'utilisateur n'est pas connecté
    const PrivateRoute = ({ children }) => {
        // Si le token JWT n'est pas présent, rediriger vers /login
        const token = localStorage.getItem('token');
        return token ? children : <Navigate to="/login" />;
      };
      

    return (
        <Router>
            <Header />
            <div className="App">
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/signup" element={<SignUp />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/raids" element={<RaidList />} /> {/* Route vers la liste des raids */}
                    <Route path="/raid/:id" element={<RaidDetails />} /> {/* Route vers les détails d'un raid */}
                    <Route path="/raid/calendar" element={<RaidCalendar />} /> {/* Route vers les détails d'un raid */}

                    {/* Route protégée pour le Dashboard */}
          <Route path="/dashboard" element={<PrivateRoute><Dashboard /></PrivateRoute>} />
                </Routes>
            </div>
            <Footer />
        </Router>
    );
};

export default App;
