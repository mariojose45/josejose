var app = new Vue({
    el: '#app',
    data: {
      title: 'Modulo Ordenes!',
      subtitle:'Gestion Ordenes',
      ordenes:[],
        timeout: null, // Variable para el temporizador
        idleTime: 30000 // Tiempo de inactividad en milisegundos (30 segundos)      
    },
    mounted () {
        this.loading();
        this.startIdleTimer(); // Iniciar el temporizador de inactividad
      axios
        .get('../ajax/orden_cocina.php?op=get_ordenes_bar')
        .then(response => {
            Swal.close();
            console.log(response);
            this.ordenes=response.data; 
        }); 

        // Eventos para reiniciar el temporizador al detectar actividad
        window.addEventListener('mousemove', this.resetIdleTimer);
        window.addEventListener('keydown', this.resetIdleTimer);
    },  
    methods: {
        compleaddetalle: function (iddetalle) {
            this.loading() 
            axios
            .get('../ajax/orden_cocina.php?op=complete_detalle&iddetalle='+iddetalle)
            .then(response => {
                this.loaddata();
            })
        },
        completedOrden: function (idorden) {
            this.loading()
            axios
            .get('../ajax/orden_cocina.php?op=complete_orden&idorden='+idorden)
            .then(response => {
                this.loaddata();
            })
        },
        loaddata: function() {
            axios
            .get('../ajax/orden_cocina.php?op=get_ordenes_bar')
            .then(response => {
                Swal.close();
                console.log(response);
                this.ordenes=response.data;
            })
        },
        loading:function(){
            Swal.fire({
                title: 'Espere un momento . . . ',
                allowOutsideClick:false,
                timerProgressBar: true,
                didOpen: () => {
                  Swal.showLoading()
                },
                willClose: () => {
                 Swal.close()
                }
              }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                  console.log('I was closed by the timer')
                }
              })
        },
        startIdleTimer: function() {
            this.timeout = setTimeout(() => {
                location.reload(); // Recargar la página
            }, this.idleTime);
        },
        resetIdleTimer: function() {
            clearTimeout(this.timeout); // Limpiar el temporizador anterior
            this.startIdleTimer(); // Iniciar un nuevo temporizador
        }
    }
})