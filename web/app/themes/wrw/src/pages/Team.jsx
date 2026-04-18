import React, { useRef, useEffect } from 'react';
import { Box, Container, Typography, Grid, Card, CardContent, Avatar, Divider, Chip } from '@mui/material';
import VanillaTilt from 'vanilla-tilt';

function TiltCard({ children, sx, ...rest }) {
    const tiltRef = useRef(null);

    useEffect(() => {
        if (tiltRef.current) {
            VanillaTilt.init(tiltRef.current, {
                max: 15,
                speed: 400,
                scale: 1.03,
                glare: true,
                "max-glare": 0.2,
            });
        }
        return () => tiltRef.current?.vanillaTilt?.destroy();
    }, []);

    return (
        <Card ref={tiltRef} sx={sx} {...rest}>
            {children}
        </Card>
    );
}

export default function Team({ wpData }) {
    const teamConfig = wpData.teamConfig || { roles: {}, members: {} };
    const { roles, members } = teamConfig;

    return (
        <Box sx={{ py: { xs: 3, md: 6 } }}>
            <Container maxWidth="lg">
                <Typography variant="h3" color="primary" align="center" gutterBottom>
                    Das Team
                </Typography>
                <Typography variant="subtitle1" color="text.secondary" align="center" sx={{ mb: 6, maxWidth: 800, mx: 'auto' }}>
                    Die Wild Rovers Württemberg sind ein engagiertes Airsoft-Team aus dem Großraum Stuttgart.
                </Typography>

                {Object.entries(roles).map(([roleKey, roleTitle]) => {
                    const roleMembers = members[roleKey] || [];
                    if (roleMembers.length === 0) return null;

                    return (
                        <Box key={roleKey} sx={{ mb: 8 }}>
                            <Typography variant="h4" sx={{ borderBottom: 2, borderColor: 'primary.main', pb: 1, mb: 4 }}>
                                {roleTitle}
                            </Typography>

                            {/* Use CSS Grid for uniform card widths */}
                            <Grid container spacing={4} alignItems="stretch">
                                {roleMembers.map((member) => (
                                    <Grid
                                        size={{ xs: 12, sm: 6, md: 4 }}
                                        key={member.id}
                                        sx={{ display: 'flex' }}
                                    >
                                        <TiltCard
                                            sx={{
                                                width: '100%',
                                                position: 'relative',
                                                overflow: 'visible',
                                                bgcolor: 'background.paper',
                                                display: 'flex',
                                                flexDirection: 'column',
                                                transformStyle: 'preserve-3d',
                                            }}
                                            elevation={3}
                                        >

                                            {member.ribbon && (
                                                <Box sx={{
                                                    position: 'absolute',
                                                    top: 15,
                                                    right: -8,
                                                    backgroundColor: 'secondary.main',
                                                    color: 'white',
                                                    fontWeight: 'bold',
                                                    fontSize: '0.8rem',
                                                    py: 0.5,
                                                    px: 2,
                                                    boxShadow: 2,
                                                    zIndex: 10,
                                                    borderTopLeftRadius: 4,
                                                    borderBottomLeftRadius: 4,
                                                    '&::before': {
                                                        content: '""',
                                                        position: 'absolute',
                                                        top: '100%',
                                                        right: 0,
                                                        width: 0,
                                                        height: 0,
                                                        borderTop: '8px solid',
                                                        borderTopColor: 'secondary.dark',
                                                        borderRight: '8px solid transparent',
                                                        filter: 'brightness(0.7)'
                                                    }
                                                }}>
                                                    {member.ribbon}
                                                </Box>
                                            )}

                                            {member.bdayIsToday && (
                                                <Box sx={{ position: 'absolute', top: 10, left: 10, fontSize: '1.2rem', zIndex: 10 }} title="Hat heute Geburtstag!">
                                                    🎂
                                                </Box>
                                            )}

                                            <CardContent sx={{ textAlign: 'center', flexGrow: 1, display: 'flex', flexDirection: 'column' }}>
                                                <Avatar
                                                    src={member.avatar}
                                                    alt={member.name}
                                                    sx={{ width: 120, height: 120, mx: 'auto', mb: 2, border: 2, borderColor: 'divider' }}
                                                />
                                                <Typography variant="h6" component="h3" gutterBottom>
                                                    {member.name}
                                                </Typography>

                                                {roleKey === 'frischling' && member.mentorName && (
                                                    <Typography variant="caption" color="secondary.main" fontWeight="bold" display="block" mb={1}>
                                                        👉 Mentor: {member.mentorName}
                                                    </Typography>
                                                )}

                                                {member.birthday && (
                                                    <Typography variant="body2" color="primary.main" fontWeight="bold">
                                                        {member.age}
                                                    </Typography>
                                                )}

                                                {member.phrase && (
                                                    <Typography variant="body2" color="text.secondary" fontStyle="italic" mt={1}>
                                                        "{member.phrase}"
                                                    </Typography>
                                                )}

                                                <Box sx={{ mt: 'auto', pt: 2, borderTop: 1, borderColor: 'divider', display: 'flex', flexDirection: 'column', gap: 1 }}>
                                                    <Typography variant="caption" color="text.secondary">
                                                        {member.duration}
                                                    </Typography>
                                                </Box>

                                                {roleKey === 'mitglied' && member.mentorOf && member.mentorOf.length > 0 && (
                                                    <Box sx={{ mt: 2, p: 1.5, bgcolor: 'rgba(74, 86, 91, 0.1)', borderLeft: 3, borderColor: 'secondary.main', textAlign: 'left', borderRadius: 1 }}>
                                                        <Typography variant="caption" fontWeight="bold" display="block" mb={0.5}>👨‍🏫 Mentoriert:</Typography>
                                                        <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 0.5 }}>
                                                            {member.mentorOf.map((fName, i) => <Chip key={i} label={fName} size="small" variant="filled" sx={{ fontSize: '0.7rem', height: 20 }} />)}
                                                        </Box>
                                                    </Box>
                                                )}

                                                {member.visitedOps > 0 && (
                                                    <Box sx={{ mt: 2, p: 1, bgcolor: 'rgba(0,0,0,0.3)', borderRadius: 1, border: 1, borderColor: 'divider', borderStyle: 'dashed' }}>
                                                        <Typography variant="caption">
                                                            🎖️ Ops besucht: <Typography component="span" variant="caption" color="secondary.main" fontWeight="bold">{member.visitedOps}</Typography>
                                                        </Typography>
                                                    </Box>
                                                )}
                                            </CardContent>
                                        </TiltCard>
                                    </Grid>
                                ))}
                            </Grid>
                        </Box>
                    );
                })}
            </Container>
        </Box>
    );
}
