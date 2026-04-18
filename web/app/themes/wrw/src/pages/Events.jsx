import React from 'react';
import { Box, Container, Typography, Card, CardContent, CardActions, Button, Grid, Chip } from '@mui/material';
import EventAvailableIcon from '@mui/icons-material/EventAvailable';
import GroupIcon from '@mui/icons-material/Group';

export default function Events({ wpData }) {
    const { eventList = [], canEditEvents, adminPostUrl, nonceCreateEvent, isLoggedIn } = wpData;

    return (
        <Box sx={{ py: { xs: 4, md: 6 } }}>
            <Container maxWidth="lg">
                <Typography variant="h3" color="primary" align="center" gutterBottom>
                    Einsatzplan & Events
                </Typography>
                <Typography variant="subtitle1" color="text.secondary" align="center" sx={{ mb: 6 }}>
                    Hier findet ihr alle anstehenden und vergangenen Operationen. Klickt auf "Teilnehmen", um euch einzutragen.
                </Typography>

                {/* Admin Creation Shell */}
                {canEditEvents && (
                    <Box sx={{ mb: 6, p: 3, border: 1, borderColor: 'secondary.main', borderRadius: 2, bgcolor: 'background.paper', borderStyle: 'dashed' }}>
                        <Typography variant="h6" color="secondary" gutterBottom>
                            📅 Neues Event erstellen (Admin)
                        </Typography>
                        <form action={adminPostUrl} method="POST">
                            <input type="hidden" name="action" value="wrw_create_event" />
                            <input type="hidden" name="wrw_event_nonce" value={nonceCreateEvent} />

                            <Grid container spacing={2}>
                                <Grid size={{ xs: 12 }}>
                                    <input type="text" name="event_name" placeholder="Event Name" required style={{ width: '100%', padding: '10px', background: '#0c0f0f', color: '#eef2f1', border: '1px solid #74808f', borderRadius: '4px', boxSizing: 'border-box' }} />
                                </Grid>
                                <Grid size={{ xs: 12, sm: 6 }}>
                                    <input type="date" name="event_date" required style={{ width: '100%', padding: '10px', background: '#0c0f0f', color: '#eef2f1', border: '1px solid #74808f', borderRadius: '4px', colorScheme: 'dark', boxSizing: 'border-box' }} />
                                </Grid>
                                <Grid size={{ xs: 12, sm: 6 }}>
                                    <input type="text" name="event_location" placeholder="Ort / Gelände" required style={{ width: '100%', padding: '10px', background: '#0c0f0f', color: '#eef2f1', border: '1px solid #74808f', borderRadius: '4px', boxSizing: 'border-box' }} />
                                </Grid>
                                <Grid size={{ xs: 12 }}>
                                    <textarea name="event_description" rows={4} placeholder="Event Beschreibung..." required style={{ width: '100%', padding: '10px', background: '#0c0f0f', color: '#eef2f1', border: '1px solid #74808f', borderRadius: '4px', boxSizing: 'border-box', resize: 'vertical' }}></textarea>
                                </Grid>
                                <Grid size={{ xs: 12 }}>
                                    <Button type="submit" variant="contained" color="secondary" fullWidth>Event Veröffentlichen</Button>
                                </Grid>
                            </Grid>
                        </form>
                    </Box>
                )}

                <Grid container spacing={4} justifyContent="center">
                    {eventList.length > 0 ? eventList.map((evt) => (
                        <Grid size={{ xs: 12, md: 8 }} key={evt.id}>
                            <Card sx={{ bgcolor: 'background.paper', border: 1, borderColor: 'divider', height: '100%', display: 'flex', flexDirection: 'column' }} elevation={2}>
                                <CardContent sx={{ flexGrow: 1 }}>
                                    <Typography variant="h5" color="primary" gutterBottom>
                                        {evt.title}
                                    </Typography>

                                    {(evt.date || evt.location) && (
                                        <Typography variant="subtitle1" color="secondary" fontWeight="bold" sx={{ mb: 2 }}>
                                            {evt.date} {evt.location ? ` | ${evt.location}` : ''}
                                        </Typography>
                                    )}

                                    <Typography variant="body1" color="text.secondary" dangerouslySetInnerHTML={{ __html: evt.excerpt }} sx={{ mb: 3 }} />

                                    <Box sx={{ borderTop: 1, borderColor: 'divider', pt: 2, mb: 2 }}>
                                        <Typography variant="subtitle2" sx={{ display: 'flex', alignItems: 'center', mb: 1 }}>
                                            <GroupIcon fontSize="small" sx={{ mr: 1 }} /> {evt.participantCount} Teilnehmer:
                                        </Typography>
                                        {evt.participantCount > 0 ? (
                                            <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 1 }}>
                                                {evt.participants.map((pName, i) => (
                                                    <Chip key={i} label={pName} size="small" variant="outlined" />
                                                ))}
                                            </Box>
                                        ) : (
                                            <Typography variant="caption" color="text.secondary">Noch keine Anmeldungen.</Typography>
                                        )}
                                    </Box>
                                </CardContent>

                                <CardActions sx={{ borderTop: 1, borderColor: 'divider', p: 2 }}>
                                    {isLoggedIn ? (
                                        <form action={adminPostUrl} method="POST" style={{ display: 'flex', alignItems: 'center', width: '100%' }}>
                                            <input type="hidden" name="action" value="wrw_event_register" />
                                            <input type="hidden" name="event_id" value={evt.id} />
                                            <input type="hidden" name="wrw_event_nonce" value={evt.nonceRegister} />

                                            {evt.isRegistered ? (
                                                <>
                                                    <input type="hidden" name="registration_action" value="unregister" />
                                                    <Button type="submit" variant="outlined" color="inherit">Absagen</Button>
                                                    <Box sx={{ flexGrow: 1 }} />
                                                    <Typography color="success.main" variant="body2" fontWeight="bold" sx={{ display: 'flex', alignItems: 'center' }}>
                                                        <EventAvailableIcon fontSize="small" sx={{ mr: 0.5 }} /> Dabei
                                                    </Typography>
                                                </>
                                            ) : (
                                                <>
                                                    <input type="hidden" name="registration_action" value="register" />
                                                    <Button type="submit" variant="contained" color="secondary">Teilnehmen</Button>
                                                </>
                                            )}
                                        </form>
                                    ) : (
                                        <Typography variant="caption" color="text.secondary" sx={{ fontStyle: 'italic' }}>
                                            Logge dich ein um teilzunehmen.
                                        </Typography>
                                    )}
                                </CardActions>

                            </Card>
                        </Grid>
                    )) : (
                        <Typography align="center" color="text.secondary" sx={{ mt: 4 }}>Aktuell keine Events geplant.</Typography>
                    )}
                </Grid>
            </Container>
        </Box>
    );
}
