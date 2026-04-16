import React from 'react';
import {
    Box, Container, Typography, Table, TableBody, TableCell,
    TableContainer, TableHead, TableRow, Paper, Select, MenuItem,
    FormControl, Button
} from '@mui/material';
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings';

export default function AdminOverview({ wpData }) {
    const { isAdmin, adminPostUrl, rosterUsers = [], mitglieder = [] } = wpData;

    if (!isAdmin) {
        return (
            <Box sx={{ py: 12, textAlign: 'center' }}>
                <Typography variant="h5" color="error">Zugriff verweigert.</Typography>
                <Typography color="text.secondary" sx={{ mt: 1 }}>Nur Admins können diese Seite sehen.</Typography>
            </Box>
        );
    }

    const ROLE_LABELS = { vorstand: 'Vorstand', mitglied: 'Mitglied', frischling: 'Frischling' };

    return (
        <Box sx={{ py: 6 }}>
            <Container maxWidth="lg">
                <Box sx={{ textAlign: 'center', mb: 6 }}>
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

                <TableContainer component={Paper} sx={{ bgcolor: 'background.paper', border: 1, borderColor: 'divider' }}>
                    <Table>
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
                                            {/* form= links this select to the hidden <form> above */}
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
            </Container>
        </Box>
    );
}
