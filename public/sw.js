const CACHE_NAME = 'zpos-cache-v3';
const urlsToCache = [
  '/',
  '/manifest.json'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return Promise.all(
          urlsToCache.map(url => {
            return cache.add(url).catch(error => {
              console.error('Failed to cache:', url, error);
            });
          })
        );
      })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request);
    })
  );
});

self.addEventListener('push', function (e) {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    return;
  }

  if (e.data) {
    var msg = e.data.json();
    e.waitUntil(self.registration.showNotification(msg.title, {
      body: msg.body,
      icon: msg.icon || '/images/icon-192.png',
      actions: msg.actions || [],
      data: msg.data || {}
    }));
  }
});

self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  var action = event.action;
  var promise = Promise.resolve();

  if (action) {
    promise = clients.openWindow(action);
  } else {
    promise = clients.openWindow('/');
  }

  event.waitUntil(promise);
});
