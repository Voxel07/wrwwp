import React from 'react';
import {
    Box, Container, Typography, Table, TableBody, TableCell,
    TableContainer, TableHead, TableRow, Paper, Select, MenuItem,
    FormControl, Button, Card, CardContent, CardActions, Divider
} from '@mui/material';
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings';

export default function AdminOverview({ wpData }) {
    const { isAdmin, adminPostUrl, rosterUsers = [], mitglieder = [] } = wpData;

    if (!isAdmin) {
        return (
            <Box sx={{ py: 12, textAlign: 'center', px: 2 }}>
                <Typography variant="h5" color="error">Zugriff verweigert.</Typography>
                <Typography color="text.secondary" sx={{ mt: 1 }}>Nur Admins können diese Seite sehen.</Typography>
            </Box>
        );
    }

    const ROLE_LABELS = { vorstand: 'Vorstand', mitglied: 'Mitglied', frischling: 'Frischling' };

    return (
        <Box sx={{ py: { xs: 4, md: 6 } }}>
            <Container maxWidth="lg">
                <Box sx={{ textAlign: 'center', mb: { xs: 4, md: 6 } }}>
                    <AdminPanelSettingsIcon sx={{ fontSize: 48, color: 'error.main', mb: 2 }} />
                    <Typography variant="h3" color="error" gutterBottom>
                        Admin Roster Matrix
                    </Typography>
                    <Typography variant="subtitle1" color="text.secondary">
                        Schnelle Zuweisung von Rängen und Mentoren.
                    </Typography>
                </Box>

                {/* Hidden forms — one per user, referenced via the HTML5 form= attribute */}
                {rosterUsers.map((u) => (
                    <form
                        key={`form-${u.id}`}
                        id={`roster-form-${u.id}`}
                        action={adminPostUrl}
                        method="POST"
                        style={{ display: 'none' }}
                    >
                        <input type="hidden" name="action" value="wrw_update_user_admin" />
                        <input type="hidden" name="target_user_id" value={u.id} />
                        <input type="hidden" name="wrw_admin_nonce" value={u.nonce} />
                    </form>
                ))}

                {/* ── Desktop: scrollable table ── */}
                <Box sx={{ display: { xs: 'none', md: 'block' } }}>
                    <TableContainer
                        component={Paper}
                        sx={{ bgcolor: 'background.paper', border: 1, borderColor: 'divider', overflowX: 'auto' }}
                    >
                        <Table sx={{ minWidth: 600 }}>
                            <TableHead>
                                <TableRow sx={{ '& th': { fontWeight: 'bold', color: 'primary.main', borderBottom: 2, borderColor: 'divider' } }}>
                                    <TableCell>Name (Callsign)</TableCell>
                                    <TableCell>Rang (Rolle)</TableCell>
                                    <TableCell>Mentor (bei Frischlingen)</TableCell>
                                    <TableCell>Aktion</TableCell>
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {rosterUsers.map((u) => (
                                    <TableRow key={u.id} sx={{ '&:hover': { bgcolor: 'rgba(255,255,255,0.03)' } }}>
                                        <TableCell>
                                            <Typography>{u.displayName}</Typography>
                                        </TableCell>
                                        <TableCell>
                                            <FormControl size="small">
                                                <Select
                                                    name="wrw_role"
                                                    defaultValue={u.role}
                                                    inputProps={{ form: `roster-form-${u.id}` }}
                                                    sx={{ minWidth: 130 }}
                                                >
                                                    {Object.entries(ROLE_LABELS).map(([val, label]) => (
                                                        <MenuItem key={val} value={val}>{label}</MenuItem>
                                                    ))}
                                                </Select>
                                            </FormControl>
                                        </TableCell>
                                        <TableCell>
                                            <FormControl size="small">
                                                <Select
                                                    name="wrw_mentor_id"
                                                    defaultValue={u.mentorId || ''}
                                                    inputProps={{ form: `roster-form-${u.id}` }}
                                                    sx={{ minWidth: 160 }}
                                                >
                                                    <MenuItem value="">-- Kein Mentor --</MenuItem>
                                                    {mitglieder.map((m) => (
                                                        <MenuItem key={m.id} value={m.id}>{m.displayName}</MenuItem>
                                                    ))}
                                                </Select>
                                            </FormControl>
                                        </TableCell>
                                        <TableCell>
                                            <Button
                                                type="submit"
                                                form={`roster-form-${u.id}`}
                                                variant="contained"
                                                color="error"
                                                size="small"
                                            >
                                                Speichern
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                                {rosterUsers.length === 0 && (
                                    <TableRow>
                                        <TableCell colSpan={4} align="center">
                                            <Typography color="text.secondary" sx={{ py: 4 }}>Keine Benutzer gefunden.</Typography>
                                        </TableCell>
                                    </TableRow>
                                )}
                            </TableBody>
                        </Table>
                    </TableContainer>
                </Box>

                {/* ── Mobile: stacked cards ── */}
                <Box sx={{ display: { xs: 'block', md: 'none' } }}>
                    {rosterUsers.length === 0 && (
                        <Typography color="text.secondary" align="center" sx={{ py: 4 }}>Keine Benutzer gefunden.</Typography>
                    )}
                    {rosterUsers.map((u) => (
                        <Card
                            key={u.id}
                            sx={{ mb: 2, bgcolor: 'background.paper', border: 1, borderColor: 'divider' }}
                            elevation={2}
                        >
                            <CardContent>
                                <Typography variant="h6" gutterBottom>{u.displayName}</Typography>
                                <Divider sx={{ mb: 2 }} />

                                <Typography variant="caption" color="text.secondary" display="block" mb={0.5}>
                                    Rang (Rolle)
                                </Typography>
                                <FormControl size="small" fullWidth sx={{ mb: 2 }}>
                                    <Select
                                        name="wrw_role"
                                        defaultValue={u.role}
                                        inputProps={{ form: `roster-form-${u.id}` }}
                                    >
                                        {Object.entries(ROLE_LABELS).map(([val, label]) => (
                                            <MenuItem key={val} value={val}>{label}</MenuItem>
                                        ))}
                                    </Select>
                                </FormControl>

                                <Typography variant="caption" color="text.secondary" display="block" mb={0.5}>
                                    Mentor (bei Frischlingen)
                                </Typography>
                                <FormControl size="small" fullWidth>
                                    <Select
                                        name="wrw_mentor_id"
                                        defaultValue={u.mentorId || ''}
                                        inputProps={{ form: `roster-form-${u.id}` }}
                                    >
                                        <MenuItem value="">-- Kein Mentor --</MenuItem>
                                        {mitglieder.map((m) => (
                                            <MenuItem key={m.id} value={m.id}>{m.displayName}</MenuItem>
                                        ))}
                                    </Select>
                                </FormControl>
                            </CardContent>
                            <CardActions sx={{ px: 2, pb: 2 }}>
                                <Button
                                    type="submit"
                                    form={`roster-form-${u.id}`}
                                    variant="contained"
                                    color="error"
                                    fullWidth
                                >
                                    Speichern
                                </Button>
                            </CardActions>
                        </Card>
                    ))}
                </Box>
            </Container>
        </Box>
    );
}
