import React, { useState } from 'react';
import { AppBar, Toolbar, IconButton, Typography, Drawer, List, ListItem, ListItemButton, ListItemText, ListItemIcon, Box, useTheme, useMediaQuery, CssBaseline, Button, Avatar, Divider } from '@mui/material';
import MenuIcon from '@mui/icons-material/Menu';
import HomeIcon from '@mui/icons-material/Home';
import GroupIcon from '@mui/icons-material/Group';
import EventIcon from '@mui/icons-material/Event';
import GavelIcon from '@mui/icons-material/Gavel';
import HandshakeIcon from '@mui/icons-material/Handshake';
import CollectionsIcon from '@mui/icons-material/Collections';
import ForumIcon from '@mui/icons-material/Forum';
import CampaignIcon from '@mui/icons-material/Campaign';
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings';
import LoginIcon from '@mui/icons-material/Login';
import LogoutIcon from '@mui/icons-material/Logout';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';

const drawerWidth = 250;

export default function Layout({ children, wpData }) {
    const theme = useTheme();
    const isMobile = useMediaQuery(theme.breakpoints.down('md'));
    const [mobileOpen, setMobileOpen] = useState(false);

    const handleDrawerToggle = () => {
        setMobileOpen(!mobileOpen);
    };

    const urls = wpData.urls || {};
    const user = wpData.user;
    const isLoggedIn = wpData.isLoggedIn;
    const isAdmin = wpData.isAdmin;

    const publicNavLinks = [
        { text: 'Home', href: urls.home, icon: <HomeIcon /> },
        { text: 'Team', href: urls.team, icon: <GroupIcon /> },
        { text: 'Events', href: urls.events, icon: <EventIcon /> },
        { text: 'Regeln', href: urls.regeln, icon: <GavelIcon /> },
        { text: 'Sponsoren', href: urls.sponsoren, icon: <HandshakeIcon /> },
        { text: 'Galerie', href: urls.galerie, icon: <CollectionsIcon /> },
    ];

    const memberNavLinks = [
        { text: 'Forum', href: urls.forum, icon: <ForumIcon /> },
        { text: 'Ankündigungen', href: urls.announcements, icon: <CampaignIcon /> },
    ];

    const adminNavLinks = [
        { text: 'Admin Overview', href: urls.admin, icon: <AdminPanelSettingsIcon /> },
    ];

    const drawer = (
        <Box sx={{ display: 'flex', flexDirection: 'column', height: '100%', bgcolor: 'background.paper' }}>
            <Box sx={{ p: 2, textAlign: 'center' }}>
                <a href={urls.home} style={{ textDecoration: 'none', color: 'inherit' }}>
                    <Typography variant="h6" color="primary" fontWeight="bold">
                        Wild Rovers
                    </Typography>
                    <Typography variant="caption" color="text.secondary">Württemberg</Typography>
                </a>
            </Box>
            <Divider />

            <List>
                {publicNavLinks.map((item) => (
                    <ListItem key={item.text} disablePadding>
                        <ListItemButton component="a" href={item.href}>
                            <ListItemIcon>{item.icon}</ListItemIcon>
                            <ListItemText primary={item.text} />
                        </ListItemButton>
                    </ListItem>
                ))}
            </List>

            {isLoggedIn && (
                <>
                    <Divider />
                    <List>
                        <Typography variant="caption" sx={{ px: 2, pb: 1, color: 'text.secondary', display: 'block', textTransform: 'uppercase', fontWeight: 'bold' }}>
                            Mitglieder
                        </Typography>
                        {memberNavLinks.map((item) => (
                            <ListItem key={item.text} disablePadding>
                                <ListItemButton component="a" href={item.href}>
                                    <ListItemIcon>{item.icon}</ListItemIcon>
                                    <ListItemText primary={item.text} />
                                </ListItemButton>
                            </ListItem>
                        ))}
                    </List>
                </>
            )}

            {isAdmin && (
                <>
                    <Divider />
                    <List>
                        <ListItem disablePadding>
                            <ListItemButton component="a" href={urls.admin}>
                                <ListItemIcon><AdminPanelSettingsIcon color="error" /></ListItemIcon>
                                <ListItemText primary="Admin Overview" primaryTypographyProps={{ color: 'error.main' }} />
                            </ListItemButton>
                        </ListItem>
                    </List>
                </>
            )}

            <Box sx={{ flexGrow: 1 }} />
            <Divider />

            <Box sx={{ p: 2 }}>
                {isLoggedIn ? (
                    <Box sx={{ display: 'flex', alignItems: 'center', gap: 2 }}>
                        <Avatar src={user?.avatar} alt={user?.name} />
                        <Box sx={{ flexGrow: 1, overflow: 'hidden' }}>
                            <Typography variant="body2" noWrap>{user?.name}</Typography>
                        </Box>
                        <IconButton color="error" component="a" href={urls.logout} title="Logout" size="small">
                            <LogoutIcon />
                        </IconButton>
                    </Box>
                ) : (
                    <Button fullWidth variant="contained" color="secondary" startIcon={<LoginIcon />} href={urls.login}>
                        Login
                    </Button>
                )}
            </Box>
        </Box>
    );

    return (
        <Box sx={{ display: 'flex' }}>
            <CssBaseline />

            {/* Top AppBar — always visible */}
            <AppBar
                position="fixed"
                sx={{
                    zIndex: theme.zIndex.drawer + 1,
                    bgcolor: 'background.paper',
                    borderBottom: 1,
                    borderColor: 'divider',
                    boxShadow: 'none',
                }}
            >
                <Toolbar>
                    {/* Hamburger for mobile */}
                    {isMobile && (
                        <IconButton
                            color="inherit"
                            aria-label="open drawer"
                            edge="start"
                            onClick={handleDrawerToggle}
                            sx={{ mr: 2 }}
                        >
                            <MenuIcon />
                        </IconButton>
                    )}

                    <Typography variant="h6" noWrap component="div" sx={{ flexGrow: 1, color: 'primary.main', fontWeight: 'bold' }}>
                        Wild Rovers
                    </Typography>

                    {/* Right-side profile / login */}
                    {isLoggedIn ? (
                        <Box sx={{ display: 'flex', alignItems: 'center', gap: 1 }}>
                            <Avatar
                                src={user?.avatar}
                                alt={user?.name}
                                sx={{ width: 36, height: 36, cursor: 'pointer' }}
                                component="a"
                                href={urls.profil}
                                title="Mein Profil"
                            />
                            <Typography variant="body2" sx={{ display: { xs: 'none', sm: 'block' } }}>
                                {user?.name}
                            </Typography>
                            <IconButton color="error" component="a" href={urls.logout} title="Logout" size="small">
                                <LogoutIcon />
                            </IconButton>
                        </Box>
                    ) : (
                        <Button variant="contained" color="secondary" startIcon={<LoginIcon />} href={urls.login} size="small">
                            Login
                        </Button>
                    )}
                </Toolbar>
            </AppBar>

            {/* Responsive Drawer */}
            <Box component="nav" sx={{ width: { md: drawerWidth }, flexShrink: { md: 0 } }}>
                <Drawer
                    variant="temporary"
                    open={mobileOpen}
                    onClose={handleDrawerToggle}
                    ModalProps={{ keepMounted: true }}
                    sx={{
                        display: { xs: 'block', md: 'none' },
                        '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth, mt: '64px' },
                    }}
                >
                    {drawer}
                </Drawer>
                <Drawer
                    variant="permanent"
                    sx={{
                        display: { xs: 'none', md: 'block' },
                        '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth, mt: '64px', height: 'calc(100% - 64px)' },
                    }}
                    open
                >
                    {drawer}
                </Drawer>
            </Box>

            {/* Main Content */}
            <Box
                component="main"
                sx={{ flexGrow: 1, p: 0, width: { md: `calc(100% - ${drawerWidth}px)` }, minHeight: '100vh', display: 'flex', flexDirection: 'column', mt: '64px' }}
            >
                <Box sx={{ flexGrow: 1 }}>
                    {children}
                </Box>

                <Box component="footer" sx={{ p: 4, textAlign: 'center', borderTop: 1, borderColor: 'divider', bgcolor: 'background.default', mt: 'auto' }}>
                    <Typography variant="body2" color="text.secondary">
                        &copy; {new Date().getFullYear()} Wild Rovers Württemberg | Airsoft Stuttgart
                    </Typography>
                </Box>
            </Box>
        </Box>
    );
}
