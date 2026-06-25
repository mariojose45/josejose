(function () {
    // Tiempo máximo de inactividad en milisegundos (40 minutos)
    const MAX_IDLE = 40 * 60 * 1000;

    // Temporizador que controla el cierre automático de sesión
    let timer = setTimeout(logout, MAX_IDLE);

    // Eventos que indican que el usuario está activo
    const eventosActividad = ['click', 'mousemove', 'keydown', 'scroll', 'touchstart'];

    eventosActividad.forEach(evt => {
        document.addEventListener(evt, resetTimer, true);
        window.addEventListener(evt, resetTimer, true); // 👈 se agrega también en window
    });

    // Si usas jQuery para AJAX, resetea el temporizador en cada llamada
    if (window.$) {
        $(document).ajaxComplete(function () {
            resetTimer();
        });
    }

    // Si usas Axios para llamadas HTTP, también resetea el temporizador
    if (window.axios) {
        axios.interceptors.response.use(function (response) {
            resetTimer();
            return response;
        }, function (error) {
            resetTimer();
            return Promise.reject(error);
        });
    }

    // Reinicia el temporizador por actividad del usuario
    function resetTimer() {
        clearTimeout(timer);
        timer = setTimeout(logout, MAX_IDLE);
        //console.log("⏳ Contador reiniciado a 40 min:", new Date().toLocaleTimeString());

        
    }

    // Función que se ejecuta al superar el tiempo de inactividad
    function logout() {
        localStorage.removeItem("jwt_token"); // (opcional) limpia token si estás usando JWT
        window.location = '../vistas/login.html'; // Redirige al login
    }

    // 🔄 Llamada periódica al backend para mantener viva la sesión PHP
    setInterval(function () {
        fetch('../ajax/timesession.php', {
            method: 'GET',
            credentials: 'same-origin' // importante si tu sesión depende de cookies
        }).then(() => {
            resetTimer(); // 👈 también reinicia el contador
        });
    }, 5 * 60 * 1000); // cada 5 minutos

})();
