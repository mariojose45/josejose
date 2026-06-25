var tabla;

//Función que se ejecuta al inicio
function init() {
  mostrarform(false);
  listar();
  $('#MenuVentas').addClass("treeview active");
  $('#Clientes').addClass("active");

  $("#formulario").on("submit", function (e) {
    guardaryeditar(e);
  })

  $.post("../ajax/persona.php?op=selectCliente", function (r) {
    $("#idcliente").html(r);
    $('#idcliente').selectpicker('refresh');

  });

  $.post("../ajax/persona.php?op=selectSector", function (r) {
    $("#idsector").html(r);
    $('#idsector').selectpicker('refresh');
  });
}

//Función limpiar
function limpiar() {
  $("#nombre").val("");
  $("#num_documento").val("");
  $("#direccion").val("");
  $("#telefono").val("");
  $("#email").val("");
  $("#idpersona").val("");
  $("#codigo_cliente").val("");
  $("#trabajo").val("");
  $("#descuento_cliente").val("0");
}

//Función mostrar formulario
function mostrarform(flag) {
  limpiar();
  if (flag) {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnGuardar").prop("disabled", false);
    $("#btnagregar").hide();
  }
  else {
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
  }
}

//Función cancelarform
function cancelarform() {
  limpiar();
  mostrarform(false);
}

function listar_seguimiento() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  var idcliente = $("#idcliente").val();
  tabla = $('#tbllistado_seguimiento').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_seguimiento_reporte',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte,
          idcliente: idcliente
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}

function listar_seguimientoxrango() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  tabla = $('#tbllistado_seguimiento').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_seguimiento_reporte_rango',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}




function listar_tareas() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  var idcliente = $("#idcliente").val();
  tabla = $('#tbllistado_tarea').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_tareas_reporte',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte,
          idcliente: idcliente
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}


function listar_tareasxrango() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  tabla = $('#tbllistado_tarea').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_tareas_reporte_rango',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}




function listar_evento() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  var idcliente = $("#idcliente").val();
  tabla = $('#tbllistado_evento').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_eventos_reporte',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte,
          idcliente: idcliente
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}



function listar_eventoxrango() {
  var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
  var fecha_fin_reporte = $("#fecha_fin_reporte").val();
  tabla = $('#tbllistado_evento').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listar_eventos_reporte_rango',
        data: {
          fecha_inicio_reporte: fecha_inicio_reporte,
          fecha_fin_reporte: fecha_fin_reporte
        },
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}



//Función Listar
function listar() {
  tabla = $('#tbllistado').dataTable(
    {
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'
      ],
      "ajax":
      {
        url: '../ajax/persona.php?op=listarc',
        type: "get",
        dataType: "json",
        error: function (e) {
          console.log(e.responseText);
        }
      },
      "bDestroy": true,
      "iDisplayLength": 20,//Paginación
      "order": [[0, "desc"]]//Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled", true);
  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/persona.php?op=guardaryeditar",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,

    success: function (datos) {
      //console.log(datos)              
      Swal.fire({
        title: 'Mensaje!',
        text: datos,
        icon: 'success',
        timer: 2000, // 2 segundos
        timerProgressBar: true,
        willClose: () => {
          window.location.reload();
        }
      });
    }

  });
}

function mostrar(idpersona) {
  $.post("../ajax/persona.php?op=mostrar", { idpersona: idpersona }, function (data, status) {
    data = JSON.parse(data);
    mostrarform(true);


    //alert(data.tipo_cliente);

    $("#nombre").val(data.nombre);
    $("#tipo_documento").val(data.tipo_documento);
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val(data.num_documento);
    $("#tipo_cliente").val(data.tipo_cliente);
    $("#direccion").val(data.direccion);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#idpersona").val(data.idpersona);
    $("#codigo_cliente").val(data.codigo_cliente);
    $("#ubicacioncliente").val(data.ubicacion_maps);
    $("#trabajo").val(data.trabajo);
    $("#idsector").val(data.idsector);
    $('#idsector').selectpicker('refresh');
    $("#descuento_cliente").val(data.descuento_cliente);


  })
}

//Función para eliminar registros
function eliminar(idpersona) {
  bootbox.confirm("¿Está Seguro de eliminar el cliente?", function (result) {
    if (result) {
      $.post("../ajax/persona.php?op=eliminar", { idpersona: idpersona }, function (e) {
        bootbox.alert(e);
        tabla.ajax.reload();
      });
    }
  })
}


function capturarUbicacion() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const latitud = position.coords.latitude;
        const longitud = position.coords.longitude;
        const ubicacion = `${latitud},${longitud}`;
        document.getElementById('ubicacioncliente').value = ubicacion;

        // Actualiza el enlace de Google Maps
        const enlaceMapa = `https://www.google.com/maps?q=${latitud},${longitud}`;
        const botonMapa = document.getElementById('verEnMapa');
        botonMapa.href = enlaceMapa;
        botonMapa.style.display = 'inline'; // Mostrar el botón

        Swal.fire({
          icon: 'success',
          title: 'Ubicación capturada',
          text: `Latitud: ${latitud}, Longitud: ${longitud}`,
          timer: 3000,
        });
      },
      function (error) {
        let mensaje = '';
        switch (error.code) {
          case error.PERMISSION_DENIED:
            mensaje = 'Permiso denegado para obtener la ubicación.';
            break;
          case error.POSITION_UNAVAILABLE:
            mensaje = 'Información de ubicación no disponible.';
            break;
          case error.TIMEOUT:
            mensaje = 'La solicitud de ubicación expiró.';
            break;
          case error.UNKNOWN_ERROR:
            mensaje = 'Error desconocido al obtener la ubicación.';
            break;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error al capturar ubicación',
          text: mensaje,
        });
      }
    );
  } else {
    Swal.fire({
      icon: 'warning',
      title: 'Geolocalización no soportada',
      text: 'Tu navegador no soporta la API de geolocalización.',
    });
  }
}

function addFiador(idpersona) {
  $("#idpersona_fiador").val(idpersona);
  $.post("../ajax/persona.php?op=selectCliente", function (r) {
    $("#idfiador").html(r);
    $('#idfiador').selectpicker('refresh');
  });
  $('#modalFiador').modal('show');
}

function guardar_fiador() {
  load();
  let idpersona_fiador = $("#idpersona_fiador").val();
  let idfiador = $("#idfiador").val();
  $('#modalFiador').modal('hide');
  $.post("../ajax/persona.php?op=agregar_fiador", { idpersona_fiador: idpersona_fiador, idfiador: idfiador }, function (e) {
    //console.log("e ",e);
    Swal.fire({
      title: "Operación exitosa",
      text: "Fiador asociado correctamente",
      icon: "success"
    }).then(() => {
      window.location.reload();
    })
  });
}

function load() {
  Swal.fire({
    title: 'Espere un momento . . . ',
    allowOutsideClick: false,
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
}


init();