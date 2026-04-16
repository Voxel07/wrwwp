import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App.jsx';

const rootElement = document.getElementById('root');
if (rootElement) {
    const wpData = window.__WP_DATA__ || { page: 'unknown' };
    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <App wpData={wpData} />
        </React.StrictMode>
    );
}
