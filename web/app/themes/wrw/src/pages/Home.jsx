import React from 'react';
import { Box, Container, Typography, Button, Grid, Card, CardContent, CardMedia, CardActions } from '@mui/material';
import OpenInNewIcon from '@mui/icons-material/OpenInNew';
import ArrowDownwardIcon from '@mui/icons-material/ArrowDownward';
import AddIcon from '@mui/icons-material/Add';
function TeamCard({ primaryText, secondaryText, image, alt }) {
    return (
        <Card sx={{
            width: '100%',
            display: 'flex',
            alignItems: 'center',
            p: 2,
            background: 'linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%)',
            border: '1px solid rgba(255, 255, 255, 0.08)',
            borderRadius: 3,
            transition: 'all 0.3s ease',
            '&:hover': {
                transform: 'translateY(-2px)',
                boxShadow: '0 8px 24px rgba(0, 0, 0, 0.4)',
                borderColor: 'primary.main',
            }
        }}>
            {image && (
                <Box sx={{ width: 80, height: 80, display: 'flex', alignItems: 'center', justifyContent: 'center', mr: 3, flexShrink: 0, overflow: 'hidden' }}>
                    <Box component="img" src={image} alt={alt || primaryText} sx={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' }} />
                </Box>
            )}
            <Box>
                <Typography variant="subtitle1" sx={{ fontWeight: 700, color: 'text.primary' }}>{primaryText}</Typography>
                <Typography variant="body2" color="text.secondary">{secondaryText}</Typography>
            </Box>
        </Card>
    );
}

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
                    <Box sx={{ display: 'flex', gap: 2, justifyContent: 'center', mt: 4, flexWrap: 'wrap' }}>
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
                <Container maxWidth="lg">
                    <Grid container spacing={6} alignItems="flex-start">
                        <Grid size={{ xs: 12, md: 7 }}>
                            {wpData.historyHtml ? (
                                <Box
                                    sx={{
                                        textAlign: 'left',
                                        '& h3': { color: 'primary.main', fontWeight: 700, textTransform: 'uppercase', letterSpacing: 1, mt: 4, mb: 2, borderBottom: '1px solid', borderColor: 'divider', pb: 1 },
                                        '& h3:first-of-type': { mt: 0 },
                                        '& p': { color: 'text.secondary', lineHeight: 1.8, mb: 2 },
                                    }}
                                    dangerouslySetInnerHTML={{ __html: wpData.historyHtml }}
                                />
                            ) : (
                                <Box sx={{ textAlign: 'left' }}>
                                    <Typography variant="h4" color="primary" gutterBottom sx={{ borderBottom: '1px solid', borderColor: 'divider', pb: 1, textTransform: 'uppercase', fontWeight: 700 }}>Die Geschichte</Typography>
                                    <Typography variant="body1" color="text.secondary" sx={{ lineHeight: 1.8, mb: 2 }}>Das Team Wild Rovers Württemberg gibt es jetzt schon seit Mitte 2006...</Typography>
                                </Box>
                            )}
                        </Grid>

                        <Grid size={{ xs: 12, md: 5 }}>
                            {wpData.historyImages && (
                                <Box sx={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 2, mt: { xs: 4, md: 0 } }}>
                                    <Typography variant="h5" color="primary.main" sx={{ fontWeight: 700, mb: 2, textTransform: 'uppercase', letterSpacing: '1px', alignSelf: 'flex-start' }}>
                                        Die Fusion der Teams
                                    </Typography>

                                    {/* Card 1: TSAT-BW */}
                                    <TeamCard
                                        primaryText="TSAT – BW"
                                        secondaryText="Gegründet Mitte 2006, legte den Grundstein unseres Teams."
                                        image={wpData.historyImages.tsat}
                                        alt="TSAT Logo"
                                    />

                                    {/* Connection 1 */}
                                    <Box sx={{ display: 'flex', alignItems: 'center', gap: 1, my: 0.5, color: 'primary.main' }}>
                                        <AddIcon fontSize="small" />
                                        <Typography variant="caption" sx={{ textTransform: 'uppercase', letterSpacing: 1.5, fontWeight: 700, color: 'text.secondary' }}></Typography>
                                    </Box>

                                    {/* Card 2: Legion Esslingen 1 */}
                                    <TeamCard
                                        primaryText="Legion Esslingen 1"
                                        secondaryText="Langjähriger Partner und treuer Freund auf dem Spielfeld."
                                        image={wpData.historyImages.legion}
                                        alt="Legion Esslingen 1 Logo"
                                    />

                                    {/* Connection 2 */}
                                    <Box sx={{ display: 'flex', alignItems: 'center', gap: 1, my: 0.5, color: 'primary.main' }}>
                                        <ArrowDownwardIcon fontSize="small" />
                                        <Typography variant="caption" sx={{ textTransform: 'uppercase', letterSpacing: 1.5, fontWeight: 700, color: 'text.secondary' }}></Typography>
                                    </Box>

                                    {/* Card 3: Wild Rovers Württemberg */}
                                    <TeamCard
                                        primaryText="Wild Rovers Württemberg"
                                        secondaryText="2016 vereint unter einem neuen Namen und Logo."
                                        image={wpData.historyImages.rovers}
                                        alt="Wild Rovers Württemberg Logo"
                                    />
                                </Box>
                            )}
                        </Grid>
                    </Grid>
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
