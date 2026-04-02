import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

import { createRequire } from 'module';
const require = createRequire(import.meta.url);
const os = require('os');

function getLocalIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        // Ignora adaptadores virtuais (VirtualBox, VMware, WSL, etc)
        if (name.toLowerCase().includes('vethernet') ||
            name.toLowerCase().includes('virtualbox') ||
            name.toLowerCase().includes('vmware') ||
            name.toLowerCase().includes('wsl') ||
            name.toLowerCase().includes('loopback')) {
            continue;
        }
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal) {
                // Ignora IPs do range 192.168.56.x (VirtualBox host-only)
                if (iface.address.startsWith('192.168.56.')) continue;
                return iface.address;
            }
        }
    }
    return '0.0.0.0';
}

const localIP = getLocalIP();

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/editor.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: localIP,
        port: 5173,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
