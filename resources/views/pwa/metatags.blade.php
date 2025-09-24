  <meta name="apple-mobile-web-app-status-bar" content="#ff5f00">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#ff5f00" />
  <link rel="apple-touch-icon" href="{{ url('logo.PNG') }}">
  <link rel="manifest" href="{{ url('/manifest.json') }}">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <link rel="apple-touch-icon" sizes="16x16" href="/images/icons/ios/16.png">
  <link rel="apple-touch-icon" sizes="20x20" href="/images/icons/ios/20.png">
  <link rel="apple-touch-icon" sizes="29x29" href="/images/icons/ios/29.png">
  <link rel="apple-touch-icon" sizes="32x32" href="/images/icons/ios/32.png">
  <link rel="apple-touch-icon" sizes="40x40" href="/images/icons/ios/40.png">
  <link rel="apple-touch-icon" sizes="50x50" href="/images/icons/ios/50.png">
  <link rel="apple-touch-icon" sizes="57x57" href="/images/icons/ios/57.png">
  <link rel="apple-touch-icon" sizes="58x58" href="/images/icons/ios/58.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/icons/ios/60.png">
  <link rel="apple-touch-icon" sizes="64x64" href="/images/icons/ios/64.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/icons/ios/72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/icons/ios/76.png">
  <link rel="apple-touch-icon" sizes="80x80" href="/images/icons/ios/80.png">
  <link rel="apple-touch-icon" sizes="87x87" href="/images/icons/ios/87.png">
  <link rel="apple-touch-icon" sizes="100x100" href="/images/icons/ios/100.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/icons/ios/114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/icons/ios/120.png">
  <link rel="apple-touch-icon" sizes="128x128" href="/images/icons/ios/128.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/icons/ios/144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/ios/152.png">
  <link rel="apple-touch-icon" sizes="167x167" href="/images/icons/ios/167.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/ios/180.png">
  <link rel="apple-touch-icon" sizes="192x192" href="/images/icons/ios/192.png">
  <link rel="apple-touch-icon" sizes="256x256" href="/images/icons/ios/256.png">
  <link rel="apple-touch-icon" sizes="512x512" href="/images/icons/ios/512.png">
  <link rel="apple-touch-icon" sizes="1024x1024" href="/images/icons/ios/1024.png">

  <link href="/images/icons/ios/1024.png" sizes="1024x1024" rel="apple-touch-startup-image">
  <link href="/images/icons/ios/512.png" sizes="512x512" rel="apple-touch-startup-image">
  <link href="/images/icons/ios/256.png" sizes="256x256" rel="apple-touch-startup-image">
  <link href="/images/icons/ios/192.png" sizes="192x192" rel="apple-touch-startup-image">
  <script type="text/javascript">
      // Initialize the service worker
      if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('/sw.js', {
              scope: '/'
          }).then(function(registration) {
              // Registration was successful
              console.log('Laravel PWA: ServiceWorker registration successful with scope: ', registration.scope);
          }, function(err) {
              // registration failed :(
              console.log('Laravel PWA: ServiceWorker registration failed: ', err);
          });
      }
  </script>
