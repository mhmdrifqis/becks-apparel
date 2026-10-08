import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const envHost = import.meta.env.VITE_REVERB_HOST;
const host = (envHost && !envHost.startsWith('$')) ? envHost : window.location.hostname;

const envPort = import.meta.env.VITE_REVERB_PORT;
const isHttps = (import.meta.env.VITE_REVERB_SCHEME === 'https') || (window.location.protocol === 'https:');
const port = (envPort && !String(envPort).startsWith('$')) ? parseInt(envPort) : (isHttps ? 443 : 80);

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: host,
    wsPort: port,
    wssPort: port,
    forceTLS: isHttps,
    enabledTransports: ['ws', 'wss'],
});
