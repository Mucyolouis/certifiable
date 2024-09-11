import './bootstrap';
// import { createRoot } from 'react-dom/client'
// import { createInertiaApp } from '@inertiajs/inertia-react'

// createInertiaApp({
//     resolve: name => {
//       const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
//       return pages[`./Pages/${name}.jsx`]
//     },
//     setup({ el, App, props }) {
//       createRoot(el).render(<App {...props} />)
//     },
//   })
import Echo from 'laravel-echo'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '8bb4951c1d33c4390e96',
  cluster: 'ap2',
  forceTLS: true
});

var channel = Echo.channel('my-channel');
channel.listen('.my-event', function(data) {
  alert(JSON.stringify(data));
});
