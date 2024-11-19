// script_enlaces.js
import SelectElementJS from './SelectElementJS.js';

// Ejecuta cuando el DOM esté completamente construido
document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencias a las etiquetas de las vistas

    // Referencia al contenedor de los mensajes
    const mensajeStatus = document.getElementById('mensajeStatus');

    // El botton FILTRAR
    const ejecutarEnlacesFilterLinks = document.getElementById('idEjecutarEnlacesFilter');

    // El botton RESET
    const resetEnlacesFilterLinks = document.getElementById('idResetEnlacesFilter');

    // Verificar si estamos en la página de ENLACES
    if (document.getElementById('idEnlacesPage')) {
        // Obtener referencia a la pagina
        const vistaEnlacesLinks = document.getElementById('idEnlacesPage');
        
        if (vistaEnlacesLinks.dataset.filterConsulta) {
            // Obtener la consulta de búsqueda seleccionada
            let sqlConsulta = vistaEnlacesLinks.dataset.filterConsulta;
           
            const mensajeConsulta = `
                <strong> "${sqlConsulta}" </strong>
            `;

            mensajeStatus.innerHTML = htmlMessageZone('success', mensajeConsulta);
        }

        // Obtener la opción de búsqueda seleccionada
        const searchOption = document.querySelector('input[name="searchOption"]:checked');
        generarSelectFiltrar(searchOption);
    }


    // Definimos metodo Click() de botton FILTRAR
    if (ejecutarEnlacesFilterLinks) {
        ejecutarEnlacesFilterLinks.addEventListener("click", function() {
            filtrarEnlaces(this);
        });
    }

    // Definimos metodo Click() de botton RESET
    if (resetEnlacesFilterLinks) {
        resetEnlacesFilterLinks.addEventListener("click", function() {
            resetEnlaces();
        });
    }

    // Escuchar cambios en los radio buttons
    document.querySelectorAll('input[name="searchOption"]').forEach(radio => {
        radio.addEventListener('change', function() {
            generarSelectFiltrar(this);
        });
    });
});

// Generar el select de filtro según la opción seleccionada
function generarSelectFiltrar(radio) {
    // Referencia al contenedor de los mensajes
    const mensajeStatus = document.getElementById('mensajeStatus');
    // Referencia de <div> del <select>
    const selectFiltrar = document.getElementById('idDynamicDivSelectFilter');

    // Limpiar el select
    selectFiltrar.innerHTML = '';

    // Obtener los valores de la opción seleccionada
    const sqlConsult = radio.dataset.sqlConsult;
    const sqlSelect = radio.dataset.sqlSelect;
    const field = radio.value;

    // Validar que se hyan selecionado Titulo
    if (field == 'titulo') {
        // Generamos el codigo HTML de la etiqueta <input> para el Titulo
        const inputHTML = `
            <input 
                type="text" 
                class="form-control" 
                id="idTitulo" 
                name="titulo" 
                placeholder="Título"
            >
        `;

        selectFiltrar.innerHTML = inputHTML;
    } else{
        // Enviar solicitud AJAX al servidor para ejecutar la consulta seleccionada
        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'views': 'enlaces',
                'crud': 'select-filter',
                'r': 'enlaces-filter-select',
                'field': field
            })
        })
        .then(response => response.json())  // Procesar la respuesta del servidor
        .then(data => {
            // alert("Parámetros: " + JSON.stringify(data.datos));
            if (data.success) {
                // Generar los elementos del select
                // Llamar a la función para generar el select usando los datos obtenidos
                const selectHTML = renderSelectFilter('idDynamicSelectFilter', 'dynamicSelectFilter', 'form-select', true, true, data.datos);
                selectFiltrar.innerHTML = selectHTML;
            } else {
                // Mostrar mensaje de error si no se pudo generar SELECT correctamente
                const mensajeError = `
                    <strong>Error: </strong> 
                    ${data.msg01} 
                    <strong> "${data.msg02}" </strong>
                    ${data.msg03}
                `;
                mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
            }
        })
        .catch(error => {
            // Mostrar mensaje de error si no se pudo completar la solicitud AJAX
            description = 'Error al intentar generar SELECT ';
            const mensajeError = `
                <strong>Error: </strong> 
                ${description}
                <strong> "${titleEnlaces}" </strong>
                intente nuevamente más tarde.
            `;
            mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
        });
    }
}

// Función para generar el <select> con los datos proporcionados usando SelectElementJS
function renderSelectFilter(id, name, className, required, multiple, options) {
    // Crear una nueva instancia de SelectElementJS
    const selectElement = new SelectElementJS(id, name, className, required);

    // Establecer la opción por defecto
    selectElement.setDefaultOption('Seleccionar...', 'default');


    // Añadir las opciones al <select> usando el método addOption
    options.forEach(option => {
        selectElement.addOption(option.f_field, option.f_field);
    });

    // Renderizar y devolver el HTML del <select>
    return selectElement.render();
}

function filtrarEnlaces (boton) {
    // Prevenir el envío normal del formulario
    //event.preventDefault();

    // Referencia al contenedor de los mensajes
    const mensajeStatus = document.getElementById('mensajeStatus');
    const tablaContainer = document.getElementById('tablaContainer'); // Contenedor donde se encuentra la tabla.

    // Obtener los datos consulta indicada y el valor introducido
    // Obtener la opción de búsqueda seleccionada
    const searchRadio = document.querySelector('input[name="searchOption"]:checked');

    // Obtener el valor del Radio seleccionado
    const searchField = searchRadio.value;
 
    // Obtener los datos del valor introducido
    const searchSelect = document.getElementById(
        (searchField === 'titulo') ? 'idTitulo' : 'idDynamicSelectFilter'
    );

    // Obtener la consulta seleccionada y el valor introducido para la búsqueda
    const searchConsulta = searchRadio.dataset.sqlConsult;
    const searchValue = searchSelect.value;

    // Enviar solicitud AJAX al servidor para ejecutar la consulta seleccionada
    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'views': 'enlaces',
            'crud': 'filter',
            'r': 'enlaces-filter',
            'consulta': searchConsulta,
            'field': searchField,
            'value': searchValue
        })
    })
    .then(response => response.json())  // Procesar la respuesta del servidor
    .then(data => {
        // alert("Parámetros: " + JSON.stringify(data.datos));
        if (data.success) {
            // Actualizar la tabla con el nuevo HTML recibido
            tablaContainer.innerHTML = data.tablaHTML;

            // Mostrar la consulta ejecutada y mensaje de éxito
            const mensajeSuccess = `
                <strong>Éxito: </strong> 
                ${data.msg01} 
                <strong> "${data.msg02}" </strong>
                ${data.msg03}
            `;
            mensajeStatus.innerHTML = htmlMessageZone('success', mensajeSuccess);
        } else {
            // Mostrar mensaje de error si no se pudo generar SELECT correctamente
            const mensajeError = `
                <strong>Error: </strong> 
                ${data.msg01} 
                <strong> "${data.msg02}" </strong>
                ${data.msg03}
            `;
            mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
        }
    })
    .catch(error => {
        // Mostrar mensaje de error si no se pudo completar la solicitud AJAX
        description = 'Error al intentar generar SELECT ';
        const mensajeError = `
            <strong>Error: </strong> 
            ${description}
            <strong> "${searchConsulta}" </strong>
            intente nuevamente más tarde.
        `;
        mensajeStatus.innerHTML = htmlMessageZone('danger', mensajeError);
    });
}

function resetEnlaces() {
    // Reiniciar la pagina
    window.location.href = 'index.php?r=home';
}