import React from 'react';
import { Box, Container, Typography, Grid, Card, CardContent, Divider } from '@mui/material';
import HandshakeIcon from '@mui/icons-material/Handshake';

const SPONSORS = [
    { name: 'TACTICAL', accent: 'GEAR', color: 'primary.main' },
    { name: 'BB', accent: 'AMMO', color: 'secondary.main' },
    { name: 'STUTTGART', accent: 'AIRSOFT', color: 'primary.main' },
    { name: 'CAMO', accent: 'SUPPLY', color: 'text.secondary' },
];

export default function Sponsoren() {
    return (
        <Box sx={{ py: 6 }}>
            <Container maxWidth="lg">
                <Box sx={{ textAlign: 'center', mb: 6 }}>
                    <HandshakeIcon sx={{ fontSize: 48, color: 'primary.main', mb: 2 }} />
                    <Typography variant="h3" color="primary" gutterBottom>
                        Unsere Partner &amp; Sponsoren
                    </Typography>
                    <Typography variant="subtitle1" color="text.secondary" sx={{ maxWidth: 700, mx: 'auto' }}>
                        Wir bedanken uns für den hervorragenden Support durch unsere Ausrüster und Partner in der Region Stuttgart.
                    </Typography>
                </Box>

                <Grid container spacing={4} justifyContent="center" sx={{ mb: 8 }}>
                    {SPONSORS.map((s, i) => (
                        <Grid item xs={12} sm={6} md={3} key={i}>
                            <Card
                                elevation={3}
                                sx={{
                                    height: '100%',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    p: 4,
                                    bgcolor: 'background.paper',
                                    border: 1,
                                    borderColor: 'divider',
                                    transition: 'transform 0.2s, box-shadow 0.2s',
                                    '&:hover': { transform: 'translateY(-4px)', boxShadow: 8 }
                                }}
                            >
                                <CardContent sx={{ textAlign: 'center' }}>
                                    <Typography variant="h5" fontWeight="bold">
                                        {s.name}
                                        <Box component="span" sx={{ color: s.color }}>{s.accent}</Box>
                                    </Typography>
                                </CardContent>
                            </Card>
                        </Grid>
                    ))}
                </Grid>

                <Divider sx={{ my: 6 }} />

                <Box sx={{ textAlign: 'center' }}>
                    <Typography variant="h5" color="primary" gutterBottom>
                        Sponsor werden?
                    </Typography>
                    <Typography variant="body1" color="text.secondary" sx={{ maxWidth: 600, mx: 'auto' }}>
                        Interessiert daran, das Team zu unterstützen? Kontaktiert uns über unsere sozialen Medien oder direkt per E-Mail.
                    </Typography>
                </Box>
            </Container>
        </Box>
    );
}
