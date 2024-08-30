import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '1b532c8a94e78a812ec3',
    cluster: 'ap2',
    forceTLS: true
});

