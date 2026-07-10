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

  $.post("../ajax/persona.php?op=selectRuta", function (r) {
    $("#idruta").html(r);
    $('#idruta').selectpicker('refresh');
  });
}

//Función limpiar
function limpiar() {
  $("#nombre").val("");
  $("#num_documento").val("CF");
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
load();
  $.ajax({
    url: "../ajax/persona.php?op=guardaryeditarCliente",
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
    $("#idpersona").val(data.idpersona);
    $("#nombre").val(data.nombre);
    $("#nombre_comercial").val(data.nombre_comercial);
    $("#tipo_documento").val(data.tipo_documento);
    $("#tipo_documento").selectpicker('refresh');
    $("#num_documento").val(data.num_documento);

    $("#direccion").val(data.direccion);
    $("#direccion_comercial").val(data.direccion_comercial);
    $("#telefono").val(data.telefono);
    $("#email").val(data.email);
    $("#trabajo").val(data.trabajo);

    $("#idsector").val(data.idsector);
    $('#idsector').selectpicker('refresh');

    $("#idruta").val(data.idruta);
    $('#idruta').selectpicker('refresh');

    $("#tipo_cliente").val(data.tipo_cliente);
    $('#tipo_cliente').selectpicker('refresh');

    $("#codigo_cliente").val(data.codigo_cliente);
    $("#ubicacioncliente").val(data.ubicacion_maps);


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

function mostrarModalSector() {
  $("#modalSector").modal("show");
  $("#nombre_sector").val("");
  $("#descripcion_sector").val("");
}

function guardarSector() {
  var nombre = $("#nombre_sector").val().trim();
  var descripcion = $("#descripcion_sector").val().trim();

  if (nombre == "") {
    Swal.fire({
      title: 'Atención',
      text: "El nombre del sector no puede estar vacío",
      icon: 'warning'
    });
    return;
  }

  $.post("../ajax/sector.php?op=guardaryeditar", { idsector: "", nombre: nombre, descripcion: descripcion }, function (e) {
    Swal.fire({
      title: 'Mensaje',
      text: e,
      icon: 'success',
      timer: 2000,
      timerProgressBar: true
    });

    $("#modalSector").modal("hide");

    // Refrescar el select de sectores
    $.post("../ajax/persona.php?op=selectSector", function (r) {
      $("#idsector").html(r);
      $('#idsector').selectpicker('refresh');
    });
  });
}

function mostrarModalRuta() {
  $("#modalRuta").modal("show");
  $("#nombre_ruta").val("");
  $("#descripcion_ruta").val("");
}

function guardarRuta() {
  var nombre = $("#nombre_ruta").val().trim();
  var descripcion = $("#descripcion_ruta").val().trim();

  if (nombre == "") {
    Swal.fire({
      title: 'Atención',
      text: "El nombre de la ruta no puede estar vacío",
      icon: 'warning'
    });
    return;
  }

  $.post("../ajax/ruta_visita.php?op=guardaryeditar", { idruta: "", nombre: nombre, descripcion: descripcion }, function (e) {
    Swal.fire({
      title: 'Mensaje',
      text: e,
      icon: 'success',
      timer: 2000,
      timerProgressBar: true
    });

    $("#modalRuta").modal("hide");

    // Refrescar el select de rutas
    $.post("../ajax/persona.php?op=selectRuta", function (r) {
      $("#idruta").html(r);
      $('#idruta').selectpicker('refresh');
    });
  });
}

function validarnit() {
  var nit = $("#num_documento").val().trim();
  var tipo = $("#tipo_documento").val();

  if (tipo == "NIT") {
    // Eliminar espacios y guiones dentro del NIT
    nit = $("#num_documento").val().replace(/[\s-]+/g, "");
  } else if (tipo == "DPI") {
    // Prefijo fijo
    let cui = "CUI";
    // Quitar espacios y guiones del DPI
    let dpiNumero = $("#num_documento").val().replace(/[\s-]+/g, "");
    // Concatenar CUI + numero DPI
    nit = cui + dpiNumero;
  }

  if (nit == "") {
    Swal.fire({
      title: 'Atención',
      text: "Debe colocar un nit mayor a 6 caracteres",
      icon: 'warning'
    });
    return;
  }

  $.post("../ajax/persona.php?op=validarnit", { nit: nit }, function (data) {
    try {
      data = JSON.parse(data);

      // Validar si nombre no es nulo, indefinido o vacío
      if (data["receptor"] && data["receptor"]["nombre"] != null && data["receptor"]["nombre"].trim() !== "") {
        let nombre = data["receptor"]["nombre"];
        let direccion = data["receptor"]["direccion"] != null ? data["receptor"]["direccion"] : "CIUDAD";

        $("#nombre").val(nombre);
        $("#direccion").val(direccion);

        Swal.fire({
          title: '¡Datos Encontrados!',
          text: "Se encontraron los datos del cliente.",
          icon: 'success',
          timer: 1500,
          timerProgressBar: true
        });
      } else {
        $("#nombre").val("");
        $("#direccion").val("CIUDAD");

        Swal.fire({
          title: 'Mensaje!',
          text: "Nit No Existe, por favor llenar los datos del nuevo cliente",
          icon: 'info',
          timer: 2000,
          timerProgressBar: true
        });

        $("#nombre").focus();
      }
    } catch (error) {
      Swal.fire({
        title: 'Mensaje!',
        text: "Error al procesar la respuesta del servidor. Intente nuevamente.",
        icon: 'error',
        timer: 2000,
        timerProgressBar: true
      });
    }
  });
}

init();