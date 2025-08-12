document.addEventListener('DOMContentLoaded', () => {
    const openChatBtn = document.getElementById('openChatBtn');
    const closeChatBtn = document.getElementById('closeChatBtn');
    const chatBox = document.getElementById('chatBox');
    const messageInput = chatBox.querySelector('input[type="text"]');
    const sendMessageBtn = chatBox.querySelector('.btn-primary.btn-sm'); // El botón de enviar mensaje

    // NÚMERO DE TELÉFONO DE LA EMPRESA donde quieres recibir los mensajes
    // Formato: Código de país + número, sin "+", espacios ni guiones.
    const whatsappPhoneNumber = '50496938704'; // ¡Este es el número de la empresa!

    // MENSAJE DE BIENVENIDA/CONTEXTO que se enviará automáticamente con el mensaje del usuario
    const welcomeMessage = "¡Hola! Quisiera hacer una consulta sobre productos. Mi mensaje es el siguiente: ";

    // Función para abrir/cerrar el cuadro de chat
    if (openChatBtn && chatBox) {
        openChatBtn.addEventListener('click', () => {
            chatBox.classList.toggle('active'); // La clase 'active' controla la visibilidad con CSS
            openChatBtn.classList.toggle('hidden'); // Oculta el botón flotante cuando el chat está abierto
        });
    }

    if (closeChatBtn && chatBox) {
        closeChatBtn.addEventListener('click', () => {
            chatBox.classList.remove('active');
            openChatBtn.classList.remove('hidden'); // Muestra el botón flotante cuando el chat se cierra
        });
    }

    // Función para enviar el mensaje a WhatsApp real
    if (sendMessageBtn && messageInput) {
        const sendWhatsappMessage = () => {
            const userTypedMessage = messageInput.value.trim(); // Lo que el usuario escribió
            
            if (userTypedMessage) {
                // Combinar el mensaje de bienvenida con el mensaje del usuario
                const fullMessage = welcomeMessage + userTypedMessage;
                const encodedMessage = encodeURIComponent(fullMessage);
                
                // Construir la URL de WhatsApp Click-to-Chat con el número de la empresa y el mensaje completo
                const whatsappUrl = `https://wa.me/${whatsappPhoneNumber}?text=${encodedMessage}`;
                
                window.open(whatsappUrl, '_blank'); // Abre WhatsApp en una nueva pestaña
                
                // Opcional: Cierra el widget después de enviar y limpia el input
                chatBox.classList.remove('active');
                openChatBtn.classList.remove('hidden');
                messageInput.value = '';
            } else {
                alert('Por favor, escribe un mensaje antes de enviar.');
            }
        };

        sendMessageBtn.addEventListener('click', sendWhatsappMessage);

        // También permite enviar al presionar Enter en el campo de texto
        messageInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                sendWhatsappMessage();
            }
        });
    }
}); 