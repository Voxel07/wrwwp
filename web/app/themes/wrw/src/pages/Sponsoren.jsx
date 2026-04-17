import React from 'react';
import { Box, Container, Typography, Grid, Card, CardContent, Divider, Button } from '@mui/material';
import HandshakeIcon from '@mui/icons-material/Handshake';
import OpenInNewIcon from '@mui/icons-material/OpenInNew';

const SPONSORS = [
    {
        name: 'Airsoft Helden',
        description: 'Dein Ausrüstungspartner für Airsoft-Equipment, Taktisches Gear und alles rund um das Hobby.',
        url: 'https://airsofthelden.com',
        color: 'primary.main',
    },
    {
        name: 'Softair Store',
        description: 'Großes Sortiment an Softair-Waffen, Zubehör und Schutzausrüstung für jeden Spielstil.',
        url: 'https://www.softairstore.de',
        color: 'secondary.main',
    },
    {
        name: 'Airsoft 2go',
        description: 'Professioneller Online-Shop für Airsoft-Ausrüstung, Repliken und taktisches Zubehör.',
        url: 'https://airsoft2go.de',
        color: 'primary.main',
    },
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

                <Grid container spacing={4} justifyContent="center" sx={{ mb: 8 }} alignItems="stretch">
                    {SPONSORS.map((s, i) => (
                        <Grid size={{ xs: 12, sm: 6, md: 4 }} key={i} sx={{ display: 'flex' }}>
                            <Card
                                elevation={3}
                                sx={{
                                    width: '100%',
                                    display: 'flex',
                                    flexDirection: 'column',
                                    bgcolor: 'background.paper',
                                    border: 1,
                                    borderColor: 'divider',
                                    transition: 'transform 0.2s, box-shadow 0.2s',
                                    '&:hover': { transform: 'translateY(-4px)', boxShadow: 8 }
                                }}
                            >
                                <CardContent sx={{ flexGrow: 1, textAlign: 'center', p: 4 }}>
                                    <Typography variant="h5" fontWeight="bold" color={s.color} gutterBottom>
                                        {s.name}
                                    </Typography>
                                    <Typography variant="body2" color="text.secondary">
                                        {s.description}
                                    </Typography>
                                </CardContent>
                                <Box sx={{ p: 2, pt: 0, textAlign: 'center' }}>
                                    <Button
                                        variant="outlined"
                                        size="small"
                                        href={s.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        endIcon={<OpenInNewIcon fontSize="small" />}
                                        sx={{ borderColor: s.color, color: s.color }}
                                    >
                                        Website besuchen
                                    </Button>
                                </Box>
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
