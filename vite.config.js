import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import picocolors from 'picocolors';
import tailwindcss from '@tailwindcss/vite';

/**
 * When VITE_DEV_SERVER_URL is an https tunnel, Vite would normally print http://127.0.0.1:5173/
 * as "Local" — IDEs linkify that and open the wrong URL. Print the public URL instead.
 */
function tunnelDevUrlPrintPlugin(publicUrl) {
    const displayUrl = publicUrl.replace(/\/?$/, '/');

    return {
        name: 'quantyra-tunnel-dev-url-print',
        apply: 'serve',
        configureServer(server) {
            server.printUrls = () => {
                const colorUrl = displayUrl.replace(/:(\d+)\//, (_, port) => `:${picocolors.bold(port)}/`);
                server.config.logger.info(
                    `  ${picocolors.green('➜')}  ${picocolors.bold('Local')}:   ${picocolors.cyan(colorUrl)}`,
                );
            };
        },
    };
}

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    // Laravel plugin writes public/hot from server.origin when set; otherwise it uses 127.0.0.1:5173
    // (broken behind https://dev… in the browser). Prefer VITE_DEV_SERVER_URL, else APP_URL.
    const devServerUrl = (env.VITE_DEV_SERVER_URL || env.APP_URL || '').trim();

    const server = {
        cors: true,
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    };

    const plugins = [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ];

    if (devServerUrl.startsWith('https://')) {
        try {
            const url = new URL(devServerUrl);
            server.hmr = {
                host: url.hostname,
                protocol: 'wss',
                clientPort: 443,
            };
            server.origin = url.origin;
        } catch {
            // Invalid URL; keep default HMR.
        }
        plugins.push(tunnelDevUrlPrintPlugin(devServerUrl));
    }

    return {
        plugins,
        server,
    };
});
