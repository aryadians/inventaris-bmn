import { registerSW } from 'virtual:pwa-register';

// Register the Service Worker
// immediate: true forces it to register immediately, good for simple apps
const updateSW = registerSW({
    immediate: true,
    onNeedRefresh() {
        console.log('New content available, click on reload button to update.');
        // Optional: show a toast to user to reload
    },
    onOfflineReady() {
        console.log('App is ready to work offline.');
    },
});

console.log('PWA Service Worker registered');
