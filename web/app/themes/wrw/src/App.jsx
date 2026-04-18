import React, { useMemo } from 'react';
import { ThemeProvider, createTheme, responsiveFontSizes, CssBaseline } from '@mui/material';
import Layout from './components/Layout.jsx';
import Home from './pages/Home.jsx';
import Events from './pages/Events.jsx';
import Team from './pages/Team.jsx';
import Rules from './pages/Rules.jsx';
import Gallery from './pages/Gallery.jsx';
import Announcements from './pages/Announcements.jsx';
import Sponsoren from './pages/Sponsoren.jsx';
import AdminOverview from './pages/AdminOverview.jsx';
import Profile from './pages/Profile.jsx';

export default function App({ wpData }) {
    const darkTheme = useMemo(() => responsiveFontSizes(createTheme({
        palette: {
            mode: 'dark',
            background: {
                default: '#0c0f0f',
                paper: '#0c0f0f'
            },
            primary: {
                main: '#b4c3c0'
            },
            secondary: {
                main: '#4a565b'
            },
            text: {
                primary: '#eef2f1',
                secondary: '#8d9fa3'
            },
            divider: '#74808f'
        },
        typography: {
            fontFamily: '"Poppins", "Roboto", "Helvetica", "Arial", sans-serif',
            h1: { fontSize: '3.5rem', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1px' },
            h2: { fontSize: '2.5rem', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1px' },
            h3: { fontSize: '2rem', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '1px' },
            h4: { fontSize: '1.5rem', fontWeight: 700 },
            button: { fontWeight: 700, textTransform: 'uppercase' },
        },
        shape: {
            borderRadius: 8
        }
    })), []);

    const renderPage = () => {
        switch (wpData.page) {
            case 'home':
                return <Home wpData={wpData} />;
            case 'events':
                return <Events wpData={wpData} />;
            case 'team':
                return <Team wpData={wpData} />;
            case 'regeln':
                return <Rules wpData={wpData} />;
            case 'galerie':
                return <Gallery wpData={wpData} />;
            case 'announcements':
                return <Announcements wpData={wpData} />;
            case 'sponsoren':
                return <Sponsoren wpData={wpData} />;
            case 'admin-overview':
                return <AdminOverview wpData={wpData} />;
            case 'profil':
                return <Profile wpData={wpData} />;
            case 'forum':
                // Forum is an external plugin page — show placeholder
                return <div style={{ padding: '4rem', textAlign: 'center', color: '#8d9fa3' }}>Weiterleitung zum Forum...</div>;
            default:
                return <div>Unhandled WordPress page context: ({wpData.page})</div>;
        }
    };

    return (
        <ThemeProvider theme={darkTheme}>
            <CssBaseline />
            <Layout wpData={wpData} renderMainArea={wpData.page !== 'forum'}>
                {renderPage()}
            </Layout>
        </ThemeProvider>
    );
}
