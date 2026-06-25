var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });
    $("#btnGuardarAbono").click(function (e) {
        guardaryeditarAbono(e);
    });

    $.post("../ajax/nomina_empleado.php?op=selectEmpleado", function (r) {
        // Agregamos opción por defecto antes de las demás
        $("#idempleado").html('<option value="">Seleccione un empleado</option>' + r);
        $('#idempleado').selectpicker('refresh');
    });   
    $.post("../ajax/cta_bancaria.php?op=selectBancoCuentaNomina", function (r) {
        // Agregamos opción por defecto antes de las demás
        $("#idcuenta").html('<option value="">Seleccione un banco</option>' + r);
        $('#idcuenta').selectpicker('refresh');
    });       
}

//Función limpiar
function limpiar() {
    $("#idempleado").val("").selectpicker('refresh'); 
    $("#monto_prestamo").val("");
    $("#no_cuotas").val("");
    $("#fecha_prestamo").val("");
    $("#fecha_ultimo_abono").val("");
    $("#concepto_prestamo").val("");
    $("#tipo_operacion").val("PRESTAMO");
    $("#tipo_operacion").selectpicker('refresh');
    $("#idotrosdecuentosempleado").val("");
    $("#detalles tbody").html("");
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
                url: '../ajax/nomina_otrosdecuentos_empleado.php?op=listar',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 20,//Paginación
            "order": [[1, "desc"]]//Ordenar (columna,orden)
        }).DataTable();
}


//Función Listar
function listarAbonosPrestamo() {
    var fecha_inicio_reporte = $("#fecha_inicio_reporte").val();
    var fecha_fin_reporte = $("#fecha_fin_reporte").val();
    tabla = $('#tbllistadoAbonos').dataTable(
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
                url: '../ajax/nomina_otrosdecuentos_empleado.php?op=listarAbonosPrestamo',
                type: "get",
                data: { fecha_inicio_reporte: fecha_inicio_reporte, fecha_fin_reporte: fecha_fin_reporte },
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
    e.preventDefault(); // Evitar acción predeterminada
    $("#btnGuardar").prop("disabled", true);

        // ================================
    // 🔍 VALIDACIONES ANTES DE GUARDAR
    // ================================

    let idempleado = $("#idempleado").val();
    let monto_prestamo = parseFloat($("#monto_prestamo").val()) || 0;
    let tipo_operacion = $("#tipo_operacion").val();
    let no_cuotas = $("#no_cuotas").val();
    let fecha_prestamo = $("#fecha_prestamo").val();
    let fecha_ultimo_abono = $("#fecha_ultimo_abono").val();
    let concepto_prestamo = $("#concepto_prestamo").val();
    let filasTabla = $("#detalles tbody tr").length;

    // 1️⃣ Validar empleado
    if (!idempleado || idempleado === "") {
        Swal.fire({
            icon: "warning",
            title: "Empleado requerido",
            text: "Debe seleccionar un empleado."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 2️⃣ Validar monto del préstamo
    if (monto_prestamo <= 0) {
        Swal.fire({
            icon: "warning",
            title: "Monto inválido",
            text: "El monto del préstamo debe ser mayor a 0."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    if (no_cuotas <= 0) {
      Swal.fire({
          icon: "warning",
          title: "No. de cuotas inválido",
          text: "El numero de cuotas debe ser mayor a 0."
      });
      $("#btnGuardar").prop("disabled", false);
      return;
    }   
    
    if (!fecha_prestamo || fecha_prestamo.trim() === "") {
      Swal.fire({
          icon: "warning",
          title: "Fecha requerida",
          text: "Debe seleccionar la fecha del préstamo."
      });
      $("#btnGuardar").prop("disabled", false);
      return;
    }   
    
    if (!fecha_ultimo_abono || fecha_ultimo_abono.trim() === "") {
      Swal.fire({
          icon: "warning",
          title: "Fecha requerida",
          text: "Debe seleccionar la fecha del préstamo ."
      });
      $("#btnGuardar").prop("disabled", false);
      return;
    }   
    
    if (!concepto_prestamo || concepto_prestamo.trim() === "") {
      Swal.fire({
          icon: "warning",
          title: "Concepto requerido",
          text: "Debe ingresar el concepto del préstamo."
      });
      $("#btnGuardar").prop("disabled", false);
      return;
    }    

    // 3️⃣ Validar tipo de operación
    if (!tipo_operacion || tipo_operacion === "") {
        Swal.fire({
            icon: "warning",
            title: "Tipo de operación requerido",
            text: "Debe seleccionar un tipo de operación."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 4️⃣ Validar que existan cuotas en la tabla
    if (filasTabla === 0) {
        Swal.fire({
            icon: "warning",
            title: "Sin cuotas",
            text: "Debe generar al menos una cuota antes de guardar."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // ================================
    // ✔️ SI TODO ESTÁ BIEN → PROCESAR
    // ================================

    const formData = new FormData($("#formulario")[0]);
    load(); // 🔹 Muestra el loader de carga (si ya lo tienes definido)

    $.ajax({
        url: "../ajax/nomina_otrosdecuentos_empleado.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            Swal.close(); // Cierra el loader si estaba activo
            console.log(datos);
            Swal.fire({
                title: "Mensaje!",
                text: datos,
                icon: "success",
                timer: 2500,
                timerProgressBar: true,
                willClose: () => {
                    mostrarform(false);

                    // ✅ Recargar DataTable si está inicializada
                    if (tabla && $.fn.DataTable.isDataTable('#tbllistado')) {
                        tabla.ajax.reload(null, false); // false = no reinicia la página actual
                    } else {
                        console.warn("⚠️ La variable 'tabla' no está definida o no es un DataTable activo.");
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al guardar: " + error,
            });
        }
    });

    limpiar(); // 🔹 Limpia el formulario
}


function guardaryeditarAbono(e) {
    e.preventDefault(); // Evitar acción predeterminada
    $("#btnGuardarAbono").prop("disabled", true);

    const formData = new FormData($("#formAbonoPrestamo")[0]);
    load(); // 🔹 Muestra el loader de carga (si ya lo tienes definido)

    $.ajax({
        url: "../ajax/nomina_otrosdecuentos_empleado.php?op=guardaryeditarAbono",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            Swal.close(); // Cierra el loader si estaba activo
            console.log(datos);
            Swal.fire({
                title: "Mensaje!",
                text: datos,
                icon: "success",
                timer: 2500,
                timerProgressBar: true,
                willClose: () => {
                     // ✅ Cerrar modal al finalizar mensaje
                     $("#modalAbonosPrestamo").modal("hide");

                    // ✅ Recargar DataTable si está inicializada
                    if (tabla && $.fn.DataTable.isDataTable('#tbllistado')) {
                        tabla.ajax.reload(null, false); // false = no reinicia la página actual
                    } else {
                        console.warn("⚠️ La variable 'tabla' no está definida o no es un DataTable activo.");
                    }
                }
            });
        },
        error: function (xhr, status, error) {
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al guardar: " + error,
            });
        }
    });

    limpiarAbono(); // 🔹 Limpia el formulario
}

function limpiarAbono() {
  $("#idotrosdecuentosempleado_abono").val("");
  $("#idempleadoAbono").val("");
  $("#monto_prestamoAbono").val("");
  $("#monto_abonoAbono").val("");
  $("#saldo_prestamoAbono").val("");
  
  $("#idcuenta").val("Seleccione un banco");
  $("#idcuenta").selectpicker('refresh');

  $("#descripcion_abono").val("");
  $("#fecha_abono").val("");

  $("#btnGuardarAbono").prop("disabled", false);
}


function mostrar(idotrosdecuentosempleado) {
    $.post(
      "../ajax/nomina_otrosdecuentos_empleado.php?op=mostrar",
      { idotrosdecuentosempleado: idotrosdecuentosempleado },
      function (response) {
        const data = JSON.parse(response);
        //console.log(data);
  
        // 🔹 Verificar si viene con error (prestamo con abonos)
        if (data.error && data.error === true) {
          Swal.fire({
            icon: "error",
            title: "Acción no permitida",
            text: data.mensaje || "No se puede editar este préstamo.",
          });
          return; // ❌ Detiene la ejecución — no abre el formulario
        }
  
        // 🔹 Si no hay error, mostrar formulario normalmente
        mostrarform(true);
  
        $("#idempleado").val(data.idempleado).selectpicker("refresh");
        $("#monto_prestamo").val(data.monto_prestamo);
        $("#no_cuotas").val(data.no_cuotas);
        $("#fecha_prestamo").val(data.fecha_prestamo);
        $("#fecha_ultimo_abono").val(data.fecha_ultimo_abono);
        $("#concepto_prestamo").val(data.concepto_prestamo);
        $("#idotrosdecuentosempleado").val(data.idotrosdecuentosempleado);
        $("#tipo_operacion").val(data.tipo_operacion);
        $("#tipo_operacion").selectpicker('refresh');
  
        // 🔹 Llamar función para mostrar las cuotas registradas
        detalleOtrosdecuentosempleado(idotrosdecuentosempleado);
      }
    );
  }
  

  function detalleOtrosdecuentosempleado(idotrosdecuentosempleado) {
    $("#detalles tbody").empty();
  
    $.post(
      "../ajax/nomina_otrosdecuentos_empleado.php?op=detallePrestamo",
      { idotrosdecuentosempleado: idotrosdecuentosempleado },
      function (data) {
        console.log("Respuesta:", data);
  
        let cuotas = [];
        try {
          cuotas = JSON.parse(data);
        } catch (e) {
          console.error("Error al parsear JSON:", e);
        }
  
        if (!cuotas || cuotas.length === 0) {
          $("#detalles tbody").append(`
            <tr><td colspan="4" class="text-center text-muted">No hay cuotas registradas.</td></tr>
          `);
          return;
        }
  
        // Recorremos las cuotas obtenidas
        $.each(cuotas, function (i, item) {
          const fila = `
            <tr>
              <td class="text-center">
                <button type="button" class="btn btn-danger btn-xs eliminarFila">
                  <i class="fa fa-times"></i>
                </button>
              </td>
              <td>
                ${item.no_cuota}
                <input type="hidden" name="no_cuota[]" value="${item.no_cuota}">
              </td>
              <td><input type="date" name="fecha_abono[]" value="${item.fecha_abono}" class="form-control input-sm" required></td>
              <td><input type="number" name="monto_abono[]" value="${item.monto_abono}" class="form-control input-sm" step="0.01" required></td>
            </tr>
          `;
          $("#detalles tbody").append(fila);
        });
      }
    );
  }
  
  
//Función para desactivar registros
function desactivar(idotrosdecuentosempleado) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "Se Eliminara este préstamo de empleado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, desactivar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loader
            load();

            $.post("../ajax/nomina_otrosdecuentos_empleado.php?op=desactivar", { idotrosdecuentosempleado: idotrosdecuentosempleado }, function (respuesta) {
                Swal.close(); // Cierra loader

                Swal.fire({
                    title: "Mensaje!",
                    text: respuesta,
                    icon: "success",
                    timer: 2500,
                    timerProgressBar: true,
                    willClose: () => {
                        // Recargar DataTable
                        if (tabla && $.fn.DataTable.isDataTable('#tbllistado')) {
                            tabla.ajax.reload(null, false); // false = conserva la página actual
                        } else {
                            console.warn("⚠️ La variable 'tabla' no está definida o no es un DataTable activo.");
                        }
                    }
                });
            }).fail(function (xhr, status, error) {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo desactivar el préstamo: " + error,
                });
            });
        }
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


// ======================================================
// 🔹 FUNCIÓN: Generar cuotas automáticamente
// ======================================================
function generarCuotas() {
    const monto = parseFloat($("#monto_prestamo").val()) || 0;
    const cuotas = parseInt($("#no_cuotas").val()) || 0;
    const fechaInicio = $("#fecha_prestamo").val();
  
    if (monto <= 0 || cuotas <= 0 || !fechaInicio) {
      Swal.fire({
        icon: "warning",
        title: "Campos incompletos",
        text: "Debes ingresar Monto del préstamo, No. de cuotas y Fecha del préstamo.",
      });
      return;
    }
  
    // Limpiar tabla antes de generar
    $("#detalles tbody").empty();
  
    // Calcular monto por cuota
    const montoCuota = (monto / cuotas).toFixed(2);
  
    // Convertir fecha inicial a objeto Date
    let fecha = new Date(fechaInicio);
    let ultimaFecha = null;
  
    for (let i = 1; i <= cuotas; i++) {
      // Crear fecha de abono sumando un mes a la fecha de préstamo
      const fechaAbono = new Date(fecha);
      fechaAbono.setMonth(fecha.getMonth() + (i - 1));
  
      // Guardar la última fecha generada
      ultimaFecha = fechaAbono;
  
      // Convertir a formato yyyy-mm-dd
      const fechaFormateada = fechaAbono.toISOString().split("T")[0];
  
      // Crear fila con el número de cuota
      const fila = `
        <tr>
          <td class="text-center">
            <button type="button" class="btn btn-danger btn-xs eliminarFila">
              <i class="fa fa-times"></i>
            </button>
          </td>
          <td>
            ${i}
            <input type="hidden" name="no_cuota[]" value="${i}">
          </td>
          <td><input type="date" name="fecha_abono[]" value="${fechaFormateada}" class="form-control input-sm" required></td>
          <td><input type="number" name="monto_abono[]" value="${montoCuota}" class="form-control input-sm" step="0.01" required></td>
        </tr>
      `;
  
      $("#detalles tbody").append(fila);
    }
  
    // ✅ Colocar automáticamente la última fecha generada en el campo "fecha_ultimo_abono"
    if (ultimaFecha) {
      const fechaFinal = ultimaFecha.toISOString().split("T")[0];
      $("#fecha_ultimo_abono").val(fechaFinal);
    }
  
    Swal.fire({
      icon: "success",
      title: "Cuotas generadas",
      text: `${cuotas} cuotas creadas correctamente.`,
      timer: 2000,
      showConfirmButton: false
    });
  }
  
  
  
  // ======================================================
  // 🔹 EVENTO: Clic en el botón de generar cuotas
  // ======================================================
  $(document).on("click", "#btnGenerarCuotas", function() {
    generarCuotas();
  });
  
  // ======================================================
  // 🔹 EVENTO: Eliminar fila individual
  // ======================================================
  $(document).on("click", ".eliminarFila", function() {
    $(this).closest("tr").remove();
  });
  
  function abonosPrestamo(idotrosdecuentosempleado) {
    // Mostrar el modal
    $("#modalAbonosPrestamo").modal("show");
  
    // Guardar el id del préstamo en el campo oculto
  
        $.post("../ajax/nomina_otrosdecuentos_empleado.php?op=mostrarAbonoPrestamo", { idotrosdecuentosempleado: idotrosdecuentosempleado }, function (data, status) {
            data = JSON.parse(data);
    
            $("#idotrosdecuentosempleado_abono").val(idotrosdecuentosempleado);
            $("#monto_prestamoAbono").val(data.saldo_prestamo);
            $("#idempleadoAbono").val(data.idempleado);
            $("#tipo_operacion_abono").val(data.tipo_operacion);
        });

  }

  $(document).on("change", "#monto_abonoAbono", function () {
    calcularSaldoAbono();
  });
  

// 🧮 Calcular saldo al escribir el abono
function calcularSaldoAbono() {
    const montoPrestamo = parseFloat($("#monto_prestamoAbono").val()) || 0;
    const montoAbono = parseFloat($("#monto_abonoAbono").val()) || 0;
  
    // Validar que no sea mayor al préstamo
    if (montoAbono > montoPrestamo) {
      Swal.fire({
        icon: "warning",
        title: "Monto inválido",
        text: "El monto del abono no puede ser mayor al monto del préstamo.",
      });
  
      // Restaurar el valor y limpiar saldo
      $("#monto_abonoAbono").val("");
      $("#saldo_prestamoAbono").val(montoPrestamo.toFixed(2));
      return;
    }
  
    // Calcular nuevo saldo
    const nuevoSaldo = montoPrestamo - montoAbono;
    $("#saldo_prestamoAbono").val(nuevoSaldo.toFixed(2));
  }
  

init();