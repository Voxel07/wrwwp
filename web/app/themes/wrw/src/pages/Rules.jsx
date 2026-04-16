import React from 'react';
import { Box, Container, Typography, Accordion, AccordionSummary, AccordionDetails } from '@mui/material';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import { styled } from '@mui/material/styles';

const StyledMarkdown = styled('div')(({ theme }) => ({
    color: theme.palette.text.primary,
    lineHeight: 1.6,
    '& h1, & h2, & h3': {
        color: theme.palette.primary.main,
        marginTop: theme.spacing(4),
        marginBottom: theme.spacing(2),
        borderBottom: `1px solid ${theme.palette.divider}`,
        paddingBottom: theme.spacing(1),
    },
    '& p': {
        marginBottom: theme.spacing(2),
    },
    '& ul, & ol': {
        marginBottom: theme.spacing(3),
        listStylePosition: 'inside',
    },
    '& li': {
        marginBottom: theme.spacing(1),
    }
}));

export default function Rules({ wpData }) {
    const rules = wpData.rules || { short: '', full: '' };

    return (
        <Box sx={{ py: 6 }}>
            <Container maxWidth="md">
                <Box sx={{ bgcolor: 'background.paper', p: { xs: 3, md: 6 }, borderRadius: 3, boxShadow: 4 }}>
                    <Typography variant="h3" color="primary" align="center" sx={{ mb: 5, textTransform: 'uppercase' }}>
                        Infos & Regeln
                    </Typography>

                    <Box>
                        {rules.short && (
                            <Accordion sx={{ bgcolor: 'background.default', border: 1, borderColor: 'divider', mb: 2, '&:before': { display: 'none' } }}>
                                <AccordionSummary expandIcon={<ExpandMoreIcon />}>
                                    <Typography variant="h6" fontWeight="bold">Die Kurzfassung</Typography>
                                </AccordionSummary>
                                <AccordionDetails sx={{ bgcolor: '#0c0f0f', p: 3 }}>
                                    <StyledMarkdown dangerouslySetInnerHTML={{ __html: rules.short }} />
                                </AccordionDetails>
                            </Accordion>
                        )}

                        {rules.full && (
                            <Accordion sx={{ bgcolor: 'background.default', border: 1, borderColor: 'divider', '&:before': { display: 'none' } }}>
                                <AccordionSummary expandIcon={<ExpandMoreIcon />}>
                                    <Typography variant="h6" fontWeight="bold">Satzung</Typography>
                                </AccordionSummary>
                                <AccordionDetails sx={{ bgcolor: '#0c0f0f', p: 3 }}>
                                    <StyledMarkdown dangerouslySetInnerHTML={{ __html: rules.full }} />
                                </AccordionDetails>
                            </Accordion>
                        )}

                        {!rules.short && !rules.full && (
                            <Typography align="center" color="error">
                                System Error: Parsedown fehlt oder Markdown Dateien nicht gefunden.
                            </Typography>
                        )}
                    </Box>
                </Box>
            </Container>
        </Box>
    );
}
