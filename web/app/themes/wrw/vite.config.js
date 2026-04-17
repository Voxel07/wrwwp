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
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('react') || id.includes('react-dom') || id.includes('@mui/material') || id.includes('@emotion/react') || id.includes('@emotion/styled')) {
                            return 'vendor';
                        }
                    }
                }
            }
        }
    }
});
