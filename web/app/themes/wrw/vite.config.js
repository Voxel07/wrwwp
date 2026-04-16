import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    plugins: [react()],
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: path.resolve(__dirname, 'src/main.jsx'),
            output: {
                entryFileNames: 'assets/main.js',
                assetFileNames: 'assets/[name].[ext]',
                chunkFileNames: 'assets/[name].js',
                manualChunks: {
                    vendor: ['react', 'react-dom', '@mui/material', '@emotion/react', '@emotion/styled']
                }
            }
        }
    }
});
