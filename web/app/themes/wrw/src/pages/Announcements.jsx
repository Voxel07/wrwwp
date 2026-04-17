import React, { useState } from 'react';
import { Box, Container, Typography, Card, CardContent, CardActions, Grid, Button, TextField, IconButton, Tooltip, Divider } from '@mui/material';
import CampaignIcon from '@mui/icons-material/Campaign';
import ScheduleIcon from '@mui/icons-material/Schedule';
import EditIcon from '@mui/icons-material/Edit';
import CloseIcon from '@mui/icons-material/Close';
import { marked } from 'marked';

// Configure marked for safe output
marked.setOptions({ breaks: true, gfm: true });

function renderMarkdown(md) {
    return { __html: marked.parse(md || '') };
}

function AnnouncementCard({ item, canEdit, adminPostUrl }) {
    const [editing, setEditing] = useState(false);
    const [title, setTitle] = useState(item.title);
    const [content, setContent] = useState(item.rawContent || '');
    const [preview, setPreview] = useState(false);
    const formId = `edit-form-${item.id}`;

    return (
        <Card sx={{ mb: 3, bgcolor: 'background.paper', border: 1, borderColor: editing ? 'secondary.main' : 'divider' }} elevation={2}>
            {/* Hidden edit form — submitted natively */}
            {canEdit && (
                <form id={formId} action={adminPostUrl} method="POST" style={{ display: 'none' }}>
                    <input type="hidden" name="action" value="wrw_edit_announcement" />
                    <input type="hidden" name="post_id" value={item.id} />
                    <input type="hidden" name="wrw_announcement_nonce" value={item.nonceEdit} />
                    <input type="hidden" name="announcement_title" value={title} />
                    <input type="hidden" name="announcement_content" value={content} />
                </form>
            )}

            <CardContent>
                {editing ? (
                    <Box sx={{ display: 'flex', flexDirection: 'column', gap: 2 }}>
                        <TextField
                            label="Titel"
                            value={title}
                            onChange={(e) => setTitle(e.target.value)}
                            fullWidth size="small"
                            autoFocus
                        />

                        <Box sx={{ display: 'flex', gap: 1, mb: 0.5 }}>
                            <Button size="small" variant={preview ? 'outlined' : 'contained'} color="secondary" onClick={() => setPreview(false)}>Bearbeiten</Button>
                            <Button size="small" variant={preview ? 'contained' : 'outlined'} color="secondary" onClick={() => setPreview(true)}>Vorschau</Button>
                            <Typography variant="caption" color="text.secondary" sx={{ ml: 'auto', alignSelf: 'center' }}>
                                Markdown wird unterstützt: **fett**, *kursiv*, `code`, ## Überschrift
                            </Typography>
                        </Box>

                        {preview ? (
                            <Box
                                sx={{
                                    p: 2, border: 1, borderColor: 'divider', borderRadius: 1, minHeight: 120,
                                    '& h2,& h3': { color: 'primary.main', mt: 1 },
                                    '& code': { bgcolor: 'rgba(255,255,255,0.1)', px: 0.5, borderRadius: 0.5 },
                                    '& ul,& ol': { pl: 3 },
                                    '& p': { mb: 1 },
                                }}
                                dangerouslySetInnerHTML={renderMarkdown(content)}
                            />
                        ) : (
                            <TextField
                                multiline
                                rows={8}
                                value={content}
                                onChange={(e) => setContent(e.target.value)}
                                fullWidth
                                placeholder="Nachricht in Markdown... **fett**, *kursiv*, ## Überschrift"
                                inputProps={{ style: { resize: 'vertical', minHeight: 160, fontFamily: 'monospace', fontSize: '0.875rem' } }}
                            />
                        )}
                    </Box>
                ) : (
                    <>
                        <Box sx={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between' }}>
                            <Typography variant="h5" color="text.primary" gutterBottom sx={{ flex: 1 }}>
                                {item.title}
                            </Typography>
                            {canEdit && (
                                <Tooltip title="Bearbeiten">
                                    <IconButton size="small" onClick={() => setEditing(true)} sx={{ ml: 1, color: 'text.secondary' }}>
                                        <EditIcon fontSize="small" />
                                    </IconButton>
                                </Tooltip>
                            )}
                        </Box>
                        <Typography variant="body2" color="secondary" fontWeight="bold" sx={{ display: 'flex', alignItems: 'center', mb: 2 }}>
                            <ScheduleIcon fontSize="small" sx={{ mr: 0.5 }} /> Veröffentlicht am {item.date}
                        </Typography>
                        <Box
                            sx={{
                                '& h2,& h3': { color: 'primary.main', mt: 1, mb: 0.5 },
                                '& code': { bgcolor: 'rgba(255,255,255,0.1)', px: 0.5, borderRadius: 0.5, fontFamily: 'monospace' },
                                '& ul,& ol': { pl: 3 },
                                '& p': { mb: 1, color: 'text.primary' },
                                '& strong': { color: 'primary.light' },
                            }}
                            dangerouslySetInnerHTML={{ __html: item.content }}
                        />
                    </>
                )}
            </CardContent>

            {editing && (
                <CardActions sx={{ borderTop: 1, borderColor: 'divider', px: 2, pb: 2, gap: 1 }}>
                    <Button
                        type="submit"
                        form={formId}
                        variant="contained"
                        color="secondary"
                        size="small"
                    >
                        Speichern
                    </Button>
                    <Button size="small" variant="outlined" color="inherit" startIcon={<CloseIcon />} onClick={() => { setEditing(false); setTitle(item.title); setContent(item.rawContent || ''); }}>
                        Abbrechen
                    </Button>
                </CardActions>
            )}
        </Card>
    );
}

export default function Announcements({ wpData }) {
    const { isLoggedIn, canEditAnnouncements, adminPostUrl, nonceCreate, announcements = [] } = wpData;

    if (!isLoggedIn) {
        return (
            <Box sx={{ py: 10, textAlign: 'center' }}>
                <Typography variant="h6" color="text.secondary">
                    🔒 Bitte logge dich ein, um Ankündigungen zu sehen.
                </Typography>
            </Box>
        );
    }

    return (
        <Box sx={{ py: 6 }}>
            <Container maxWidth="md">
                <Typography variant="h3" color="primary" align="center" gutterBottom>
                    Verbands-Ankündigungen
                </Typography>
                <Typography variant="subtitle1" color="text.secondary" align="center" sx={{ mb: 6 }}>
                    Interne Updates, Operationsbefehle und Statusberichte exklusiv für registrierte Mitglieder.
                </Typography>

                {canEditAnnouncements && (
                    <Box sx={{ mb: 6, p: 3, border: 1, borderColor: 'secondary.main', borderRadius: 2, bgcolor: 'background.paper', borderStyle: 'dashed' }}>
                        <Typography variant="h6" color="secondary" gutterBottom sx={{ display: 'flex', alignItems: 'center' }}>
                            <CampaignIcon sx={{ mr: 1 }} /> Neue Ankündigung erstellen
                        </Typography>
                        <form action={adminPostUrl} method="POST">
                            <input type="hidden" name="action" value="wrw_create_announcement" />
                            <input type="hidden" name="wrw_announcement_nonce" value={nonceCreate} />
                            <Grid container spacing={2}>
                                <Grid size={{ xs: 12 }}>
                                    <TextField name="announcement_title" label="Titel" required fullWidth size="small" />
                                </Grid>
                                <Grid size={{ xs: 12 }}>
                                    <TextField
                                        name="announcement_content"
                                        label="Nachricht (Markdown unterstützt)"
                                        multiline rows={4}
                                        required fullWidth
                                        placeholder="**fett**, *kursiv*, ## Überschrift, `code`..."
                                        sx={{ fontFamily: 'monospace' }}
                                    />
                                </Grid>
                                <Grid size={{ xs: 12 }}>
                                    <Button type="submit" variant="contained" color="secondary" fullWidth>
                                        Veröffentlichen &amp; Broadcast
                                    </Button>
                                </Grid>
                            </Grid>
                        </form>
                        <Typography variant="caption" display="block" align="center" color="text.secondary" sx={{ mt: 2 }}>
                            Setze <code>WP_OPTION_WEBHOOK_URL</code> in <code>.env</code> für Webhook-Broadcasts.
                        </Typography>
                    </Box>
                )}

                <Box>
                    {announcements.length > 0 ? announcements.map((item) => (
                        <AnnouncementCard
                            key={item.id}
                            item={item}
                            canEdit={canEditAnnouncements}
                            adminPostUrl={adminPostUrl}
                        />
                    )) : (
                        <Box sx={{ p: 4, textAlign: 'center', bgcolor: 'background.paper', borderRadius: 2, border: 1, borderColor: 'divider' }}>
                            <Typography color="text.secondary">Bisher keine Ankündigungen vorhanden.</Typography>
                        </Box>
                    )}
                </Box>
            </Container>
        </Box>
    );
}
