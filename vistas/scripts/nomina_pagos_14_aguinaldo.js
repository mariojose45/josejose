var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    /*$("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })*/

    $("#btnGuardar").click(function (e) {
        guardaryeditar(e);
    });
}

//Función limpiar
function limpiar() {
    $("#idnomina_pagos_14_aguinaldo").val("");
    $("#fecha_inicio").val("");
    $("#fecha_fin").val("");
    $("#descripcion").val("");
    $(".filas").remove();
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
                url: '../ajax/nomina_pagos_14_aguinaldo.php?op=listar',
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


    // ======================================
    // 🔍 VALIDAR CAMPOS ANTES DE GUARDAR
    // ======================================
    let fecha_inicio = $("#fecha_inicio").val();
    let fecha_fin = $("#fecha_fin").val();
    let descripcion = $("#descripcion").val();
    let filas = $("tr.filas").length;

    // 1️⃣ Validar fecha inicio
    if (!fecha_inicio || fecha_inicio.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe ingresar la fecha de inicio."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 2️⃣ Validar fecha fin
    if (!fecha_fin || fecha_fin.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe ingresar la fecha de fin."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 3️⃣ Validar descripción
    if (!descripcion || descripcion.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Descripción requerida",
            text: "Debe ingresar una descripción."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 4️⃣ Validar que existan empleados cargados
    if (filas === 0) {
        Swal.fire({
            icon: "warning",
            title: "Sin empleados",
            text: "Debe cargar al menos un empleado para continuar."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // ======================================
    // ✔️ SI TODO OK → ARMAR DETALLES Y GUARDAR
    // ======================================    

    var detalles = []; // creo un array para guardar los detalles vacio
    $("tr.filas").each(function () {
        var fila = $(this);
        detalles.push({
            idempleado: fila.find("input[name='idempleado[]']").val(),
            salario_base: fila.find("input[name='salario_base[]']").val(),
            fecha_del: fila.find("input[name='fecha_del[]']").val(),
            fecha_al: fila.find("input[name='fecha_al[]']").val(),
            dias_trabajados: fila.find("input[name='dias_trabajados[]']").val(),
            total_devengado: fila.find("input[name='total_devengado[]']").val(),
            anticipos_bono_14: fila.find("input[name='anticipos_bono_14[]']").val(),
            liquido_recibir: fila.find("input[name='liquido_recibir[]']").val(),
        });
    });

    var formData = new FormData(); //Crea un objeto FormData, que te permite enviar datos al servidor de forma segura y moderna (incluso archivos si quisieras).
    formData.append("idnomina_pagos_14_aguinaldo", $("#idnomina_pagos_14_aguinaldo").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("fecha_inicio", $("#fecha_inicio").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("fecha_fin", $("#fecha_fin").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("descripcion", $("#descripcion").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("tipo_operacion", $("#tipo_operacion").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("detalles_json", JSON.stringify(detalles));/// Convierte el arreglo detalles a texto en formato JSON y lo envía como una sola variable llamada "detalles_json".


    $.ajax({
        url: "../ajax/nomina_pagos_14_aguinaldo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function (datos) {
            console.log(datos);
            Swal.fire({
                title: "Mensaje",
                text: datos,
                icon: "success",
                timer: 2500,
                showConfirmButton: false
            });
            mostrarform(false);
            window.location.reload();
        }

    });
    limpiar();
}

function mostrar(idnomina_pagos_14_aguinaldo) {
    load();
    $.post("../ajax/nomina_pagos_14_aguinaldo.php?op=mostrar", { idnomina_pagos_14_aguinaldo: idnomina_pagos_14_aguinaldo }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        $("#idnomina_pagos_14_aguinaldo").val(data.idnomina_pagos_14_aguinaldo);
        $("#fecha_inicio").val(data.fechainicio);
        $("#fecha_fin").val(data.fechafin);
        $("#descripcion").val(data.descripcion);
        $("#tipo_operacion").val(data.tipo_operacion);
        $("#tipo_operacion").selectpicker('refresh');

        detallenominapagos(idnomina_pagos_14_aguinaldo);

    })
}

function detallenominapagos(idnomina_pagos_14_aguinaldo) {
    $.post("../ajax/nomina_pagos_14_aguinaldo.php?op=detallenominapagos", { idnomina_pagos_14_aguinaldo: idnomina_pagos_14_aguinaldo }, function (data) {
        console.log(data);
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalleEmpleados2(item.idempleado,
                item.nombre_empleado,
                item.cui_empleado,
                item.dias_trabajados,
                item.salario_base,
                item.total_devengado,
                item.liquido_recibir,
                item.fecha_del,
                item.fecha_al,
                item.anticipos_bono_14,
                item.puesto_empleado);
        });
    })
}

function agregarDetalleEmpleados2(idempleado,
    nombre_empleado,
    cui_empleado,
    dias_trabajados,
    salario_base,
    total_devengado,
    liquido_recibir,
    fecha_del,
    fecha_al,
    anticipos_bono_14,
    puesto_empleado) {
    //e.preventDefault();

    if (idempleado != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idempleado[]" value="' + idempleado + '">' + nombre_empleado + '</td>' +
            '<td>' + puesto_empleado + '</td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_base[]" id="salario_base' + cont + '"  value="' + salario_base + '" ></td>' +
            '<td><input class="form-control"  type="date" onchange="modificarSubototales()" style="width:85px"  name="fecha_del[]" id="fecha_del' + cont + '" value="' + fecha_del + '" ></td>' +
            '<td><input class="form-control"  type="date" onchange="modificarSubototales()" style="width:85px"  name="fecha_al[]" id="fecha_al' + cont + '" value="' + fecha_al + '" ></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px"  name="dias_trabajados[]" id="dias_trabajados' + cont + '" value="' + dias_trabajados + '" ></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_devengado[]" id="total_devengado' + cont + '" value="' + total_devengado + '" readonly ></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="anticipos_bono_14[]" id="anticipos_bono_14' + cont + '"  value="' + anticipos_bono_14 + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="liquido_recibir[]" id="liquido_recibir' + cont + '"  value="' + liquido_recibir + '" readonly></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
    }
    else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
    modificarSubototales();

}

//Función para desactivar registros
function desactivar(idnomina_pagos_14_aguinaldo) {
    bootbox.confirm("¿Está Seguro de desactivar la Nomina 14-Aguinaldo?", function (result) {
        if (result) {
            $.post("../ajax/nomina_pagos_14_aguinaldo.php?op=desactivar", { idnomina_pagos_14_aguinaldo: idnomina_pagos_14_aguinaldo }, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

var cont = 0;
var detalles = 0;

function listarEmpleados() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    $(".filas").remove();

    // ================================
    // 🔍 VALIDAR FECHAS OBLIGATORIAS
    // ================================
    if (!fecha_inicio || fecha_inicio.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe seleccionar la fecha de inicio."
        });
        return; // 🚫 Detener ejecución
    }

    if (!fecha_fin || fecha_fin.trim() === "") {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe seleccionar la fecha de fin."
        });
        return; // 🚫 Detener ejecución
    }

    // ================================
    // ✔️ SI TODO ESTÁ BIEN → CONTINUAR
    // ================================    
    $.post("../ajax/nomina_pagos_14_aguinaldo.php?op=listarEmpleados2", { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function (data) {

        data = JSON.parse(data);
        console.log(data);
        //e.preventDefault();

        $.each(data, function (i, item) {
            //e.preventDefault();
            agregarDetalleEmpleados(item.idempleado, item.nombres, item.cui, item.salario_base,
                item.bonificacion, item.bono_productividad, item.salario_extra,
                item.descuento_igss, item.descuento_isr, item.abono_prestamo,
                item.abono_otrosDescuentos, item.abono_adelantoQuincenal, item.abono_adelantoSalarial
                , item.abono_14, item.abono_aguinaldo, item.puesto, item.dias_trabajados_en_anio, item.fecha_inicio_laboral
            );
        });
    })
}

function agregarDetalleEmpleados(idempleado, nombres, cui, salario_base,
    bonificacion, bono_productividad, salario_extra, descuento_igss, descuento_isr,
    abono_prestamo, abono_otrosDescuentos, abono_adelantoQuincenal,
    abono_adelantoSalarial, abono_14, abono_aguinaldo, puesto, dias_trabajados_en_anio, fecha_inicio_laboral) {

    // 🔵 Obtener el tipo de operación seleccionado
    const tipoOperacion = $("#tipo_operacion").val();

    // 🔵 Definir qué anticipo usar según el tipo de operación
    let valorAnticipo = 0;
    if (tipoOperacion === "Bono 14") {
        valorAnticipo = abono_14;
    } else if (tipoOperacion === "Aguinaldo") {
        valorAnticipo = abono_aguinaldo;
    }


    // Año actual
    const yearActual = new Date().getFullYear();

    // Fecha inicial: siempre 1 de enero del año actual
    const fechaDelDefaultAnual = `${yearActual}-01-01`;

    // Fecha final: fecha de hoy
    const hoy = new Date();
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const dia = String(hoy.getDate()).padStart(2, '0');
    const fechaAlDefault = `${yearActual}-${mes}-${dia}`;

    // ⬇️ NUEVO: determinar fecha_del según antigüedad
    let fechaDelFinal = "";

    if (dias_trabajados_en_anio >= 365) {
        // ✔ Tiene un año o más → aplicar bono completo
        fechaDelFinal = fechaDelDefaultAnual;
    } else {
        // ✔ Aún no tiene un año → usar fecha real de ingreso
        fechaDelFinal = fecha_inicio_laboral;
    }

    if (idempleado != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idempleado[]" value="' + idempleado + '">' + nombres + '</td>' +
            '<td>' + puesto + '</td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_base[]" id="salario_base' + cont + '" value="' + salario_base + '"></td>' +

            // 👇 Aquí usamos la fecha calculada
            '<td><input class="form-control" type="date" onchange="modificarSubototales()" style="width:85px" name="fecha_del[]" id="fecha_del' + cont + '" value="' + fechaDelFinal + '"></td>' +

            '<td><input class="form-control" type="date" onchange="modificarSubototales()" style="width:85px" name="fecha_al[]" id="fecha_al' + cont + '" value="' + fechaAlDefault + '"></td>' +

            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:85px" name="dias_trabajados[]" id="dias_trabajados' + cont + '" value="' + dias_trabajados_en_anio + '"></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_devengado[]" id="total_devengado' + cont + '" readonly></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="anticipos_bono_14[]" id="anticipos_bono_14' + cont + '" value="' + valorAnticipo + '" readonly></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="liquido_recibir[]" id="liquido_recibir' + cont + '" value="0" readonly></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
    } else {
        alert("Error al ingresar el detalle, revisar los datos del empleado");
    }

    modificarSubototales();
}


function modificarSubototales() {
    var diastrabajados = document.getElementsByName("dias_trabajados[]");
    var salariobase = document.getElementsByName("salario_base[]");
    var t_bono_14 = document.getElementsByName("total_devengado[]");
    var anticipos_bono_14 = document.getElementsByName("anticipos_bono_14[]");
    var liquido_recibir = document.getElementsByName("liquido_recibir[]");

    for (var i = 0; i < salariobase.length; i++) {
        var inp_diastrabajados = diastrabajados[i];
        var inp_salariobase = salariobase[i];
        var inp_t_bono_14 = t_bono_14[i];
        var inp_anticipos_bono_14 = anticipos_bono_14[i];
        var inp_liquido_recibir = liquido_recibir[i];



        // ✅ Total liquido a recibir
        inp_t_bono_14.value = (
            (parseFloat(inp_salariobase.value) / 365) * parseFloat(inp_diastrabajados.value)
        ).toFixed(2);

        // ✅ Calcular líquido a recibir
        inp_liquido_recibir.value = (
            (parseFloat(inp_t_bono_14.value) - parseFloat(inp_anticipos_bono_14.value))
        ).toFixed(2);
    }
}


function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    }
    else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    //calcularTotales();
    detalles = detalles - 1;
    evaluar();
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