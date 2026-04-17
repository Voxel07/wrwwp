import React from 'react';
import { Box, Container, Typography, Button, Grid, Card, CardContent, CardMedia, CardActions } from '@mui/material';
import OpenInNewIcon from '@mui/icons-material/OpenInNew';

export default function Home({ wpData }) {
    const { events = [], fields = [], kioskUrl, urls } = wpData;

    return (
        <Box>
            {/* Hero Section */}
            <Box sx={{ textAlign: 'center', py: { xs: 8, md: 12 }, borderBottom: 1, borderColor: 'divider' }}>
                <Container maxWidth="md">
                    <Typography variant="h2" component="h1" gutterBottom sx={{ textShadow: '2px 2px 4px rgba(0,0,0,0.8)' }}>
                        Willkommen bei den<br />Wild Rovers Württemberg
                    </Typography>
                    <Typography variant="h6" color="text.secondary" paragraph>
                        Airsoft Team aus Stuttgart und Umgebung. Disziplin, Taktik, und Kameradschaft.
                    </Typography>
                    <Box sx={{ display: 'flex', gap: 2, justifyContent: 'center', mt: 4 }}>
                        <Button variant="contained" color="secondary" href={urls?.regeln}>
                            Regeln
                        </Button>
                        <Button variant="contained" color="primary">
                            Join the Team
                        </Button>
                    </Box>
                </Container>
            </Box>

            {/* History Section */}
            <Box sx={{ py: 8 }}>
                <Container maxWidth="md" sx={{ textAlign: 'center' }}>
                    {wpData.historyHtml ? (
                        <Box
                            sx={{
                                textAlign: 'left',
                                '& h3': { color: 'primary.main', fontWeight: 700, textTransform: 'uppercase', letterSpacing: 1, mt: 4, mb: 2, borderBottom: '1px solid', borderColor: 'divider', pb: 1 },
                                '& p': { color: 'text.secondary', lineHeight: 1.8, mb: 2 },
                            }}
                            dangerouslySetInnerHTML={{ __html: wpData.historyHtml }}
                        />
                    ) : (
                        <>
                            <Typography variant="h4" color="primary" gutterBottom>Die Geschichte</Typography>
                            <Typography variant="body1" paragraph>Das Team Wild Rovers Württemberg gibt es jetzt schon seit Mitte 2006...</Typography>
                        </>
                    )}
                </Container>
            </Box>

            {/* Kiosk / Random Impressions */}
            {kioskUrl && (
                <Box sx={{ py: 8, bgcolor: 'background.paper' }}>
                    <Container maxWidth="xl">
                        <Typography variant="h4" color="primary" align="center" gutterBottom mb={6}>
                            Zufällige Impressionen
                        </Typography>
                        <Box sx={{ height: '60vh', borderRadius: 2, overflow: 'hidden', border: 1, borderColor: 'divider', boxShadow: 3 }}>
                            <iframe
                                src={kioskUrl}
                                style={{ width: '100%', height: '100%', border: 'none' }}
                                title="Immich Kiosk Random Viewer"
                            />
                        </Box>
                    </Container>
                </Box>
            )}

            {/* Fields Section */}
            <Box sx={{ py: 8 }}>
                <Container>
                    <Typography variant="h4" color="primary" align="center" gutterBottom mb={6}>
                        Unsere Spielfelder &amp; Hauptevents
                    </Typography>
                    <Grid container spacing={4}>
                        {fields.map((field, idx) => (
                            <Grid size={{ xs: 12, sm: 6, md: 4 }} key={idx}>
                                <Card
                                    sx={{
                                        height: '100%',
                                        display: 'flex',
                                        flexDirection: 'column',
                                        transition: 'transform 0.2s, box-shadow 0.2s',
                                        '&:hover': { transform: 'translateY(-4px)', boxShadow: 6 },
                                    }}
                                    elevation={1}
                                >
                                    {field.image && (
                                        <CardMedia
                                            component="img"
                                            height="200"
                                            image={field.image}
                                            alt={field.title}
                                            sx={{ objectFit: 'cover' }}
                                            onError={(e) => { e.target.style.display = 'none'; }}
                                        />
                                    )}
                                    <CardContent sx={{ flexGrow: 1 }}>
                                        <Typography variant="h6" gutterBottom>{field.title}</Typography>
                                        <Typography variant="body2" color="text.secondary">{field.description}</Typography>
                                    </CardContent>
                                    {field.url && (
                                        <CardActions>
                                            <Button
                                                size="small"
                                                color="primary"
                                                href={field.url}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                endIcon={<OpenInNewIcon fontSize="small" />}
                                            >
                                                Website besuchen
                                            </Button>
                                        </CardActions>
                                    )}
                                </Card>
                            </Grid>
                        ))}
                    </Grid>
                </Container>
            </Box>

            {/* Events Section */}
            <Box sx={{ py: 8, bgcolor: 'background.paper' }}>
                <Container>
                    <Typography variant="h4" color="primary" align="center" gutterBottom mb={6}>
                        Anstehende Events
                    </Typography>
                    <Grid container spacing={4} justifyContent="center" mb={4}>
                        {events.length > 0 ? events.map((evt, idx) => (
                            <Grid size={{ xs: 12, sm: 6, md: 4 }} key={idx}>
                                <Card sx={{ height: '100%', display: 'flex', flexDirection: 'column' }} elevation={2}>
                                    <CardContent sx={{ flexGrow: 1 }}>
                                        <Typography variant="h6" component="h3" gutterBottom>
                                            <a href={evt.permalink} style={{ color: 'inherit', textDecoration: 'none' }}>
                                                {evt.title}
                                            </a>
                                        </Typography>
                                        {(evt.date || evt.location) && (
                                            <Typography variant="subtitle2" color="secondary" fontWeight="bold" mb={2}>
                                                {evt.date} {evt.location ? ` | ${evt.location}` : ''}
                                            </Typography>
                                        )}
                                        <Typography variant="body2" color="text.secondary" dangerouslySetInnerHTML={{ __html: evt.excerpt }} />
                                    </CardContent>
                                    <CardActions>
                                        <Button size="small" color="primary" href={evt.permalink}>Learn More</Button>
                                    </CardActions>
                                </Card>
                            </Grid>
                        )) : (
                            <Typography color="text.secondary" align="center">Aktuell keine Events geplant.</Typography>
                        )}
                    </Grid>
                    <Box textAlign="center">
                        <Button variant="contained" href={urls?.events}>Alle Events anzeigen</Button>
                    </Box>
                </Container>
            </Box>

        </Box>
    );
}
