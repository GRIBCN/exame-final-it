// Function para generfar el mensaje para la zona de los mensajes
function htmlMessageZone(tipo, mensaje) {
    const msgHTML = `
        <div 
            class="alert alert-${tipo} alert-dismissible" 
            role="alert"
            style="width: 100%;"
        >
            <span style="flex-grow: 1;">
                ${mensaje}
            </span>
            <button 
                type="button" 
                class="btn-close button-mensaje-status-cerrar ms-2" 
                data-bs-dismiss="alert" 
                aria-label="Close"
                style="margin-left: 10px;"
            >
            </button>
        </div>
    `;
    return msgHTML;
}