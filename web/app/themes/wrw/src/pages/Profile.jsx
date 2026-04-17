import React, { useState } from 'react';
import {
    Box, Container, Typography, TextField, Button, Grid, Paper,
    Avatar, Divider, Alert, Select, MenuItem, FormControl, InputLabel, Chip
} from '@mui/material';
import SaveIcon from '@mui/icons-material/Save';
import PersonIcon from '@mui/icons-material/Person';

export default function Profile({ wpData }) {
    const { profileData = {}, adminPostUrl, profileNonce, attendedOps = {} } = wpData;

    if (!wpData.isLoggedIn) {
        return (
            <Box sx={{ py: 12, textAlign: 'center' }}>
                <Typography variant="h5" color="text.secondary">Du musst eingeloggt sein um dein Profil zu sehen.</Typography>
            </Box>
        );
    }

    return (
        <Box sx={{ py: 6 }}>
            <Container maxWidth="md">
                <Box sx={{ textAlign: 'center', mb: 6 }}>
                    <PersonIcon sx={{ fontSize: 48, color: 'primary.main', mb: 2 }} />
                    <Typography variant="h3" color="primary" gutterBottom>
                        Mein Profil
                    </Typography>
                    <Typography variant="subtitle1" color="text.secondary">
                        Hier kannst du deine Team-Präferenzen verwalten.
                    </Typography>
                </Box>

                {profileData.updatedParam && (
                    <Alert severity="success" sx={{ mb: 4 }}>Profil erfolgreich aktualisiert.</Alert>
                )}

                <Paper sx={{ p: 4, bgcolor: 'background.paper', border: 1, borderColor: 'divider' }} elevation={3}>
                    <Box
                        component="form"
                        action={adminPostUrl}
                        method="POST"
                        encType="multipart/form-data"
                        sx={{ display: 'flex', flexDirection: 'column', gap: 3 }}
                    >
                        <input type="hidden" name="action" value="wrw_update_profile" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                        <input type="hidden" name="wrw_profile_nonce" value={profileNonce} />

                        {/* Read-only system info */}
                        <Grid container spacing={2}>
                            <Grid size={{ xs: 12, sm: 6 }}>
                                <TextField
                                    label="System Benutzername"
                                    value={profileData.login || ''}
                                    disabled fullWidth size="small"
                                    sx={{ input: { color: 'text.disabled' } }}
                                />
                            </Grid>
                            <Grid size={{ xs: 12, sm: 6 }}>
                                <TextField
                                    label="Team Rang (Rolle)"
                                    value={profileData.roleLabel || 'Unbekannt'}
                                    disabled fullWidth size="small"
                                    sx={{ input: { color: 'secondary.main', fontWeight: 'bold' } }}
                                />
                            </Grid>
                        </Grid>

                        <Divider />

                        {/* Editable fields */}
                        <Grid container spacing={2}>
                            <Grid size={{ xs: 12, sm: 6 }}>
                                <TextField label="Vorname / Callsign" name="first_name" defaultValue={profileData.firstName || ''} required fullWidth size="small" />
                            </Grid>
                            <Grid size={{ xs: 12, sm: 6 }}>
                                <TextField label="Nachname" name="last_name" defaultValue={profileData.lastName || ''} required fullWidth size="small" />
                            </Grid>
                        </Grid>

                        <TextField label="E-Mail" name="user_email" type="email" defaultValue={profileData.email || ''} required fullWidth size="small" />

                        <Divider>
                            <Typography variant="caption" color="text.secondary">Team-Card Felder</Typography>
                        </Divider>
                        <Typography variant="caption" color="text.secondary">
                            Diese Daten werden in deinem öffentlichen <a href={profileData.teamUrl} style={{ color: 'inherit' }}>Team-Dossier</a> angezeigt.
                        </Typography>

                        <Box>
                            <Typography variant="body2" color="text.secondary" mb={1}>
                                📷 Profilbild (Avatar) – lädt über Gravatar, falls leer
                            </Typography>
                            <input
                                type="file"
                                name="wrw_profile_picture"
                                accept="image/*"
                                style={{ color: 'inherit', width: '100%' }}
                            />
                            {profileData.hasAvatar && (
                                <Typography variant="caption" color="secondary.main" sx={{ mt: 0.5, display: 'block' }}>
                                    ✓ Bild bereits hinterlegt.
                                </Typography>
                            )}
                        </Box>

                        <TextField
                            label="🎂 Geburtstag"
                            name="wrw_birthday"
                            type="date"
                            defaultValue={profileData.birthday || ''}
                            InputLabelProps={{ shrink: true }}
                            fullWidth size="small"
                        />

                        <TextField
                            label="Short Phrase (Persönliches Motto)"
                            name="wrw_phrase"
                            defaultValue={profileData.phrase || ''}
                            placeholder="z.B. Hit it till it's dead..."
                            fullWidth size="small"
                        />

                        <FormControl fullWidth size="small">
                            <InputLabel>Benachrichtigungspräferenz</InputLabel>
                            <Select name="wrw_notification_pref" defaultValue={profileData.notifPref || 'webhook'} label="Benachrichtigungspräferenz">
                                <MenuItem value="webhook">Instant Messenger (Webhook)</MenuItem>
                                <MenuItem value="mail">E-Mail via WP-Mail</MenuItem>
                            </Select>
                        </FormControl>

                        <Divider>
                            <Typography variant="caption" color="text.secondary">📍 Meine Operationen</Typography>
                        </Divider>

                        {Object.keys(attendedOps).length === 0 ? (
                            <Typography color="text.secondary" fontStyle="italic">
                                Bisher an keinen aufgezeichneten Operationen teilgenommen.
                            </Typography>
                        ) : (
                            Object.entries(attendedOps).map(([year, titles]) => (
                                <Box key={year} sx={{ p: 2, bgcolor: 'rgba(0,0,0,0.2)', borderRadius: 1 }}>
                                    <Typography variant="subtitle2" fontWeight="bold" sx={{ borderBottom: 1, borderColor: 'divider', pb: 0.5, mb: 1 }}>
                                        {year}
                                    </Typography>
                                    <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 0.5 }}>
                                        {titles.map((t, i) => <Chip key={i} label={t} size="small" variant="outlined" />)}
                                    </Box>
                                </Box>
                            ))
                        )}

                        <Button type="submit" variant="contained" color="primary" fullWidth size="large" startIcon={<SaveIcon />} sx={{ mt: 2 }}>
                            Präferenzen Aktualisieren
                        </Button>
                    </Box>
                </Paper>
            </Container>
        </Box>
    );
}
