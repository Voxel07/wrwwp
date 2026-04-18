import React, { useState } from 'react';
import { Box, Container, Typography, Button } from '@mui/material';
import AddPhotoAlternateIcon from '@mui/icons-material/AddPhotoAlternate';

export default function Gallery({ wpData }) {
    const { isLoggedIn, proxyUrl, dropUrl } = wpData;
    const [showUpload, setShowUpload] = useState(false);

    return (
        <Box sx={{ py: { xs: 3, md: 6 } }}>
            <Container maxWidth="xl">
                <Typography variant="h3" color="primary" align="center" gutterBottom>
                    Einsatz-Galerie
                </Typography>
                <Typography variant="subtitle1" color="text.secondary" align="center" sx={{ mb: 4, maxWidth: 800, mx: 'auto' }}>
                    Impressionen und Bilder unserer vergangenen Operationen in einer großen Sammlung.
                </Typography>

                <Box sx={{ textAlign: 'center', mb: 4 }}>
                    {isLoggedIn ? (
                        <Button
                            variant="contained"
                            color="secondary"
                            startIcon={<AddPhotoAlternateIcon />}
                            onClick={() => setShowUpload(!showUpload)}
                        >
                            Neue Bilder Hochladen
                        </Button>
                    ) : (
                        <Typography variant="body2" color="text.secondary" sx={{ fontStyle: 'italic', border: 1, borderColor: 'divider', p: 1.5, display: 'inline-block', borderRadius: 1, bgcolor: 'rgba(0,0,0,0.2)' }}>
                            🔒 Bitte im Forum / per OpenID einloggen, um Bilder hochzuladen.
                        </Typography>
                    )}
                </Box>

                {isLoggedIn && showUpload && (
                    <Box sx={{ width: '100%', height: 500, borderRadius: 2, overflow: 'hidden', border: 2, borderColor: 'primary.main', borderStyle: 'dashed', bgcolor: 'background.paper', mb: 6 }}>
                        <iframe
                            src={dropUrl}
                            style={{ width: '100%', height: '100%', border: 'none' }}
                            allowFullScreen
                            title="Immich Drop Upload"
                        />
                    </Box>
                )}

                <Typography variant="h4" sx={{ borderBottom: 2, borderColor: 'primary.main', pb: 1, mt: 4, mb: 3 }}>
                    Galerie
                </Typography>

                <Box sx={{ width: '100%', height: '80vh', borderRadius: 2, overflow: 'hidden', border: 1, borderColor: 'divider', bgcolor: 'background.paper', boxShadow: 3 }}>
                    <iframe
                        src={proxyUrl}
                        style={{ width: '100%', height: '100%', border: 'none' }}
                        allowFullScreen
                        title="Immich Public Proxy Gallery"
                    />
                </Box>
            </Container>
        </Box>
    );
}
