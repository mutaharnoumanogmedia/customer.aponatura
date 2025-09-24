     <link rel="stylesheet" href="{{ asset('css/chat-widget.css' . '?' . time()) }}">



     <button id="cbLauncher" class="btn chatbot-launcher d-flex align-items-center justify-content-center" type="button"
         aria-label="Open chat" aria-haspopup="dialog" aria-controls="cbWindow">
         <i class="bi bi-whatsapp fs-5 me-2"></i>
         {{ __('Chat with us') }}
     </button>

     <!-- Chatbot Window -->
     <section id="cbWindow" class="chatbot-window" role="dialog" aria-modal="false" aria-labelledby="cbTitle">
         <!-- Header -->
         <header class="chatbot-header p-3 d-flex align-items-center justify-content-between">
             <div class="d-flex align-items-center gap-2">
                 <span class="bg-white bg-opacity-25 rounded-circle p-2 d-inline-flex">
                     @if (!empty($brand->favicon_path))
                         <img src="{{ env('STORAGE_URL') . '/' . $brand->favicon_path }}" width="32" height="32"
                             alt="">
                     @else
                         <i class="bi bi-robot fs-6 text-dark"></i>
                     @endif
                 </span>
                 <div>
                     <h6 id="cbTitle" class="mb-0">{{ $brand->name }} {{ __('Support') }}</h6>
                     <small class="opacity-75">
                         {{ __('How can I help you today') }}?
                     </small>
                 </div>
             </div>
             <div class="d-flex align-items-center gap-1">
                 {{-- <button id="cbMinimize" class="btn btn-sm btn-light bg-white bg-opacity-25 text-dark border-0"
                     aria-label="Minimize">
                     <i class="bi bi-dash-lg"></i>
                 </button> --}}
                 <button id="cbClose" class="btn btn-sm btn-light bg-white bg-opacity-25 text-dark border-0"
                     aria-label="Close chat">
                     <i class="bi bi-x-lg"></i>
                 </button>
             </div>
         </header>

         <!-- Messages -->
         <main id="cbMessages" class="chatbot-messages" tabindex="0" aria-live="polite" aria-atomic="false">
             {{-- <div class="msg msg-bot">
                 <strong>Hi!</strong> I’m your assistant. How can I help today?
             </div> --}}

         </main>

         <!-- Input -->
         <footer class="chatbot-input">
             <form id="cbForm" class="d-flex gap-2">
                 <label for="cbInput" class="visually-hidden">{{ __('Message') }}</label>
                 <input id="cbInput" class="form-control" type="text" placeholder="{{ __('Write a message') }}..."
                     autocomplete="off" />
                 <button class="btn btn-primary" id="btnSend" type="submit" aria-label="{{ __('Send') }}">
                     <i class="bi bi-send-fill"></i>
                 </button>
             </form>
         </footer>
     </section>



     <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>

     <script>
         const launcher = document.getElementById('cbLauncher');
         const win = document.getElementById('cbWindow');
         const closeBtn = document.getElementById('cbClose');
         const form = document.getElementById('cbForm');
         const input = document.getElementById('cbInput');
         const messages = document.getElementById('cbMessages');

         let askOrderAction = false;
         let hasPhone = false;


         if (!hasPhone) {
             input.removeAttribute("readonly");
         } else {
             input.setAttribute("readonly", true);
         }

         const openChat = () => {
             win.classList.add('show');
             launcher.setAttribute('aria-expanded', 'true');
             win.setAttribute('aria-modal', 'true');
             input.focus();
             initialMessage();

             if (!localStorage.getItem('chatOpened')) {
                 logTheChat("Chat Option Clicked", 'bot');
                 addClickLog('chat_whatsapp');
                 localStorage.setItem('chatOpened', 'true');
             }
         };

         const closeChat = () => {
             win.classList.remove('show');
             launcher.setAttribute('aria-expanded', 'false');
             win.setAttribute('aria-modal', 'false');
         };

         const minimizeChat = () => {
             // simple toggle: minimize just closes but keeps state; customize if needed
             closeChat();
         };

         launcher.addEventListener('click', () => {
             if (win.classList.contains('show')) {
                 closeChat();
             } else {
                 openChat();
             }
         });

         closeBtn.addEventListener('click', closeChat);

         // Util: scroll messages to bottom
         function scrollToBottom() {
             messages.scrollTop = messages.scrollHeight;
         }

         // Render a bubble
         function bubble(text, who = 'bot', additionalActionButtons = []) {
             const div = `<div class="msg msg-${who}">
                                 ${text}
                                 
                                 ${additionalActionButtons.length > 0 ? `<div class="action-buttons mt-2">${additionalActionButtons.join('')}</div>` : ''}
                                  
                             </div>`;
             messages.insertAdjacentHTML('beforeend', div);
             scrollToBottom();
         }

         // Typing indicator
         function showTyping() {
             const wrap = document.createElement('div');
             wrap.className = 'msg msg-bot';
             wrap.setAttribute('data-typing', 'true');
             wrap.innerHTML = `<span class="typing" aria-label="Assistant is typing">
                                        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                                    </span>`;
             messages.appendChild(wrap);
             scrollToBottom();
             return wrap;
         }

         function removeTyping(node) {
             node?.remove();
         }

         function initialMessage() {
             const typingIndicator = showTyping();
             //  bubble("Hi! I’m your assistant. How can I help today?", 'bot');
             console.log("Initial message sent");
             //ajax call to check user whatsapp number

             fetch("{{ route('customer.get-phone-number-verified') }}", {
                     headers: {
                         "Accept": "application/json"
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     console.log(data);

                     removeTyping(typingIndicator);
                     if (data.found) {
                         logTheChat("Hi! I’m your assistant. How can I help today?", 'bot');
                         bubble("{{ __('support.hello_assistant') }}", 'bot', [
                             `<a class="btn btn-chat-message-action" 
                             onclick="logTheChat('Redirected to WhatsApp', 'user');"
                             href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text=Hello" target="_blank">{{ __('chat_on_whatsapp') }}</a>`,
                             `<a class="btn btn-chat-message-action" href="{{ $brand->website_url }}" target="_blank">{{ __('Visit') }} {{ $brand->name }}</a>`,
                             `<button class="btn btn-chat-message-action" onclick="bubble('{{ __('Order Tracking') }}', 'user');  logTheChat('Order Tracking' , 'user'); appendAskOrderAction(); this.classList.add('active'); ">
                                {{ __('Order Tracking') }}
                                </button>`,
                         ]);
                         hasPhone = true;
                         input.setAttribute("readonly", true);
                     } else {
                         logTheChat("Ask for WhatsApp number", 'bot');
                         bubble("{{ __('support.hello') }}", 'bot');
                         bubble("{{ __('support.provide_whatsapp') }}", 'bot');
                     }
                 })
                 .catch(error => {
                     console.error("Error:", error);
                     bubble("{{ __('support.error') }}.", 'bot');
                 });

         }

         function appendAskOrderAction() {
             const typing = showTyping();
             //fetch customers orders and append as list
             fetch("{{ route('customer.get-orders') }}?skip=0&take=10", {
                     method: "GET",
                     headers: {
                         "Content-Type": "application/json",
                         "Accept": "application/json"
                     }
                 }).then(res => res.json())
                 .then(data => {
                     removeTyping(typing);
                     if (data.orders && data.orders.length > 0) {
                         bubble("{{ __('support.review_order') }}:", 'bot');
                         console.log(data.orders);

                         const orderList = data.orders.map((order, index) =>

                             `<div>  <button class='msg msg-bot' style='text-align:left !important;' onclick='askOrderAction=true; logTheChat("Asked for Order : ${order.bestellNr}", "user"); input.value = "${order.bestellNr}"; document.getElementById("btnSend").click()'> ${index+1}. <b>${order.bestellNr}</b> <br/><span style='font-size:11px; '>{{ __('Ordered Product') }}: ${order.products}</span></button></div>`

                         ).join('');
                         bubble(`<div>${orderList}</div>`, 'bot');
                     } else {
                         logTheChat("No orders found.", 'bot');
                         bubble("{{ __('no_orders_found') }}.", 'bot');
                         bubble("{{ __('support.contact') }}.", 'bot', [
                             `<a class="btn btn-chat-message-action" 
                             onclick="logTheChat('Redirected to WhatsApp', 'user');"
                             href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text=Hello" target="_blank">{{ __('chat_on_whatsapp') }}</a>`
                         ]);
                         appendQRasMessage(`https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text=Hello`);
                         bubble("{{ __('support.scan_whatsapp') }}", 'bot');
                     }
                 })
                 .catch(error => {
                     console.error("Error:", error);
                     bubble("{{ __('support.error') }}.", 'bot');
                 });
             askOrderAction = true;
         }


         function getAndSaveWhatsappNumber(number) {
             if (!hasPhone) {
                 const phoneNumber = number.trim();
                 if (phoneNumber) {
                     // Save the phone number
                     fetch("{{ env('APP_LOGIN_URL') }}/api/profile/update-phone", {
                             method: "POST",
                             headers: {
                                 "Content-Type": "application/json",
                                 "Accept": "application/json",
                                 'Token': '{{ env('APP_LOGIN_TOKEN') }}'
                             },
                             body: JSON.stringify({
                                 phone: phoneNumber,
                                 user_email: '{{ auth()->guard('customer')->user()->email }}'
                             })

                         }).then(res => res.json())
                         .then(data => {
                             if (data.success) {
                                 logTheChat(`WhatsApp number ${phoneNumber} saved successfully!`, 'bot');
                                 hasPhone = true;
                                 // Optionally, you can re-fetch the phone number to update the state
                                 initialMessage();
                             } else {
                                 bubble("{{ __('support.save_whatsapp_error') }}", 'bot');
                             }
                         })
                         .catch(error => {
                             console.error("Error:", error);
                             bubble("{{ __('support.save_whatsapp_error') }}", 'bot');
                         });
                 }


             }
         }


         function appendQRasMessage(text) {
             const qrCodeCanvas = document.createElement('canvas');
             //set id of this div
             const orderNumber = Math.floor(Math.random() * 1000000);
             qrCodeCanvas.id = `qr-code-${orderNumber}`;
             var qr = new QRious({
                 element: qrCodeCanvas,
                 value: text,
                 size: 200
             });
             qrCodeCanvas.innerHTML = `
                    <h5>{{ __('support.scan_tracking') }} ${orderNumber}</h5>
                    <canvas id="qr-code-${orderNumber}"></canvas>
                 `;
             messages.appendChild(qrCodeCanvas);
         }

         function logTheChat(textMessage, senderType) {
             fetch("{{ route('customer.store-support-chat-log') }}", {
                     method: "POST",
                     headers: {
                         "Content-Type": "application/json",
                         "Accept": "application/json"
                     },
                     body: JSON.stringify({
                         message: textMessage,
                         sender: senderType
                     })
                 }).then(res => res.json())
                 .then(data => {
                     if (data.success) {
                         console.log("Chat logged successfully!");
                     } else {
                         console.log("Failed to log chat. Please try again.");
                     }
                 })
                 .catch(error => {
                     console.error("Error:", error);
                 });
         }

         // Handle send
         form.addEventListener('submit', async (e) => {
             e.preventDefault();
             const text = input.value.trim();
             if (!text) return;

             bubble(text, 'user');
             input.value = '';
             const typing = showTyping();

             // MOCK REPLY: replace with your API call
             // Example for a backend endpoint:
             // const res = await fetch('/api/chat', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ message: text }) });
             // const data = await res.json();
             // const reply = data.reply;

             removeTyping(typing);
             //  bubble(reply, 'bot');

             if (!hasPhone) {
                 getAndSaveWhatsappNumber(text);
             }

             if (askOrderAction) {
                 // Handle order tracking
                 const orderNumber = text; // Assume user enters order number directly
                 //generate whatsapp link
                 const whatsappLink =
                     `https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text=Hello%2C%20I%20need%20help%20with%20my%20order%20${orderNumber}`;
                 //delay of 0.5s , show typing
                 const type = showTyping();
                 setTimeout(() => {
                     removeTyping(type);
                 }, 500);

                 bubble(`{{ __('support.click_tracking') }}: ${orderNumber}`,
                     'bot', [
                         `<a class="btn btn-chat-message-action"
                         onclick="logTheChat('Redirected to WhatsApp', 'user');"
                         href="${whatsappLink}" target="_blank"> <i class='bi bi-whatsapp'></i> {{ __('Track Order') }}</a>`
                     ]);

                 //create a dynamic div
                 appendQRasMessage(whatsappLink);

                 bubble("{{ __('support.scan_whatsapp') }}", 'bot');
                 askOrderAction = false;


             }
         });



         // Open on first click or programmatically:
         //  openChat();
         //  initialMessage();
     </script>
