import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// window.Echo.join(`chat`)
//     .here((users) => {
//         console.log(users);
//         // ...
//     })
//     .joining((user) => {
//         console.log(user.name+' just joined');
//     })
//     .leaving((user) => {
//         console.log(user.name+ ' left');
//     })
//     .error((error) => {
//         console.error(error);
//     });
