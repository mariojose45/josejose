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
    $("#idnomina_pagos").val("");
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

function listaragrupados() {
    var fecha_inicio = $("#fecha_inicio_listar").val();
    var fecha_fin = $("#fecha_fin_listar").val();
    window.open("../reportes/exNominapagosPdfAgrupados.php?id=" + fecha_inicio + "&id2=" + fecha_fin);
}

function listaragrupados_excel() {
    var fecha_inicio = $("#fecha_inicio_listar").val();
    var fecha_fin = $("#fecha_fin_listar").val();
    window.open("../reportes/exNominapagosPdfAgrupados_excel.php?id=" + fecha_inicio + "&id2=" + fecha_fin);
}

//Función Listar
function listar() {
    var fecha_inicio = $("#fecha_inicio_listar").val();
    var fecha_fin = $("#fecha_fin_listar").val();
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
                url: '../ajax/nomina_pagos.php?op=listar',
                type: "get",
                data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
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
    if (!fecha_inicio || String(fecha_inicio).trim().length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe ingresar la fecha de inicio."
        });
        $("#btnGuardar").prop("disabled", false);
        return;
    }

    // 2️⃣ Validar fecha fin
    if (!fecha_fin || String(fecha_fin).trim().length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Fecha requerida",
            text: "Debe ingresar la fecha de inicio."
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
            dias_trabajados: fila.find("input[name='dias_trabajados[]']").val(),
            horas_trabajados: fila.find("input[name='horas_trabajados[]']").val(),
            diario: fila.find("input[name='diario[]']").val(),
            salario_base: fila.find("input[name='salario_base[]']").val(),
            total_salario: fila.find("input[name='total_salario[]']").val(),
            salario_extra: fila.find("input[name='salario_extra[]']").val(),
            total_devengado: fila.find("input[name='total_devengado[]']").val(),
            bonificacion_ley: fila.find("input[name='bonificacion_ley[]']").val(),
            bonificacion_productividad: fila.find("input[name='bonificacion_productividad[]']").val(),
            descuento_igss: fila.find("input[name='descuento_igss[]']").val(),
            descuento_isr: fila.find("input[name='descuento_isr[]']").val(),
            abono_prestamo: fila.find("input[name='abono_prestamo[]']").val(),
            abono_otrosDescuentos: fila.find("input[name='abono_otrosDescuentos[]']").val(),
            abono_adelantoQuincenal: fila.find("input[name='abono_adelantoQuincenal[]']").val(),
            abono_adelantoSalarial: fila.find("input[name='abono_adelantoSalarial[]']").val(),
            total_deducciones: fila.find("input[name='total_deducciones[]']").val(),
            liquido_recibir: fila.find("input[name='liquido_recibir[]']").val()
        });
    });

    var formData = new FormData(); //Crea un objeto FormData, que te permite enviar datos al servidor de forma segura y moderna (incluso archivos si quisieras).
    formData.append("idnomina_pagos", $("#idnomina_pagos").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("fecha_inicio", $("#fecha_inicio").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("fecha_fin", $("#fecha_fin").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("descripcion", $("#descripcion").val());//Agrega al FormData los campos individuales del formulario: la fecha y la descripción.
    formData.append("detalles_json", JSON.stringify(detalles));/// Convierte el arreglo detalles a texto en formato JSON y lo envía como una sola variable llamada "detalles_json".


    $.ajax({
        url: "../ajax/nomina_pagos.php?op=guardaryeditar",
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

function mostrar(idnomina_pagos) {
    load();
    $.post("../ajax/nomina_pagos.php?op=mostrar", { idnomina_pagos: idnomina_pagos }, function (data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        $("#idnomina_pagos").val(data.idnomina_pagos);
        $("#fecha_inicio").val(data.fechainicio);
        $("#fecha_fin").val(data.fechafin);
        $("#descripcion").val(data.descripcion);

        detallenominapagos(idnomina_pagos);

    })
}

function detallenominapagos(idnomina_pagos) {
    $.post("../ajax/nomina_pagos.php?op=detallenominapagos", { idnomina_pagos: idnomina_pagos }, function (data) {
        console.log(data);
        data = JSON.parse(data);
        Swal.close()

        $.each(data, function (i, item) {
            agregarDetalleEmpleados2(item.idempleado,
                item.nombre_empleado,
                item.cui_empleado,
                item.dias_trabajados,
                item.horas_trabajados,
                item.diario,
                item.salario_base,
                item.total_salario,
                item.salario_extra,
                item.total_devengado,
                item.bonificacion_ley,
                item.bonificacion_productividad,
                item.descuento_igss,
                item.descuento_isr,
                item.abono_prestamo,
                item.abono_otrosDescuentos,
                item.abono_adelantoQuincenal,
                item.abono_adelantoSalarial,
                item.total_deducciones,
                item.liquido_recibir);
        });
    })
}

function agregarDetalleEmpleados2(idempleado,
    nombres,
    cui,
    dias_trabajados,
    horas_trabajados,
    diario,
    salario_base,
    total_salario,
    salario_extra,
    total_devengado,
    bonificacion_ley,
    bonificacion_productividad,
    descuento_igss,
    descuento_isr,
    abono_prestamo,
    abono_otrosDescuentos,
    abono_adelantoQuincenal,
    abono_adelantoSalarial,
    total_deducciones,
    liquido_recibir) {
    //e.preventDefault();

    if (idempleado != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idempleado[]" value="' + idempleado + '">' + nombres + '</td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()"  style="width:50px" step="any" name="dias_trabajados[]" id="dias_trabajados' + cont + '" value="' + dias_trabajados + '"></td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()"  style="width:50px" step="any" name="horas_trabajados[]" id="horas_trabajados' + cont + '" value="' + horas_trabajados + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" style="width:50px" step="any" style="width:50px"  name="diario[]" id="diario' + cont + '" value="' + diario + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_base[]" id="salario_base' + cont + '"  value="' + salario_base + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_salario[]" id="total_salario' + cont + '"  value="' + total_salario + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_extra[]" id="salario_extra' + cont + '"  value="' + salario_extra + '"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_devengado[]" id="total_devengado' + cont + '"  value="' + total_devengado + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="bonificacion_ley[]" id="bonificacion_ley' + cont + '"  value="' + bonificacion_ley + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="bonificacion_productividad[]" id="bonificacion_productividad' + cont + '"  value="' + bonificacion_productividad + '" ></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="descuento_igss[]" id="descuento_igss' + cont + '"  value="' + descuento_igss + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="descuento_isr[]" id="descuento_isr' + cont + '"  value="' + descuento_isr + '"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_prestamo[]" id="abono_prestamo' + cont + '"  value="' + abono_prestamo + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_otrosDescuentos[]" id="abono_otrosDescuentos' + cont + '"  value="' + abono_otrosDescuentos + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_adelantoQuincenal[]" id="abono_adelantoQuincenal' + cont + '"  value="' + abono_adelantoQuincenal + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_adelantoSalarial[]" id="abono_adelantoSalarial' + cont + '" value="' + abono_adelantoSalarial + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_deducciones[]" id="total_deducciones' + cont + '"  value="' + total_deducciones + '" readonly></td>' +
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
function desactivar(idnomina_pagos) {
    bootbox.confirm("¿Está Seguro de desactivar la Nomina de Pagos?", function (result) {
        if (result) {
            $.post("../ajax/nomina_pagos.php?op=desactivar", { idnomina_pagos: idnomina_pagos }, function (e) {
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


    $.post("../ajax/nomina_pagos.php?op=listarEmpleados", { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin }, function (data) {

        data = JSON.parse(data);
        // console.log(data);
        //e.preventDefault();

        // 🧹 LIMPIEZA TOTAL ANTES DE CARGAR
        $('#detalles').find('tr.filas').remove();
        // Reiniciar contadores
        cont = 0;
        detalles = 0;

        // 🔥 Calculamos los días una sola vez
        let totalDias = calcularDias(fecha_inicio, fecha_fin);

        $.each(data, function (i, item) {
            //e.preventDefault();
            agregarDetalleEmpleados(item.idempleado, item.nombres, item.cui, item.salario_base,
                item.bonificacion, item.bono_productividad, item.salario_extra,
                item.descuento_igss, item.descuento_isr, item.abono_prestamo,
                item.abono_otrosDescuentos, item.abono_adelantoQuincenal, item.abono_adelantoSalarial, item.horas_trabajadas, totalDias
            );
        });
    })
}

function agregarDetalleEmpleados(idempleado, nombres, cui, salario_base,
    bonificacion, bono_productividad, salario_extra, descuento_igss, descuento_isr,
    abono_prestamo, abono_otrosDescuentos, abono_adelantoQuincenal, abono_adelantoSalarial, horas_trabajadas, totalDias) {
    //e.preventDefault();
    //console.log(horas_trabajadas);

    var bonificacion_ley = bonificacion / 2;
    var bonificacion_productividad = bono_productividad / 2;


    if (idempleado != "") {
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="idempleado[]" value="' + idempleado + '">' + nombres + '</td>' +
            '<td><input class="form-control" type="number" onchange="modificarSubototales()"  style="width:50px" step="any" name="dias_trabajados[]" id="dias_trabajados' + cont + '" value="' + totalDias + '"></td>' +
            '<td><input class="form-control" type="number" step="any" onchange="modificarSubototales()"  style="width:50px" step="any" name="horas_trabajados[]" id="horas_trabajados' + cont + '" value="' + horas_trabajadas + '"></td>' +
            '<td><input class="form-control"  onchange="modificarSubototales()" type="number" style="width:50px" step="any" style="width:50px"  name="diario[]" id="diario' + cont + '" value="0" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_base[]" id="salario_base' + cont + '"  value="' + salario_base + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_salario[]" id="total_salario' + cont + '"  value="0" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="salario_extra[]" id="salario_extra' + cont + '"  value="' + salario_extra + '"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_devengado[]" id="total_devengado' + cont + '"  value="0" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="bonificacion_ley[]" id="bonificacion_ley' + cont + '"  value="' + bonificacion_ley + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="bonificacion_productividad[]" id="bonificacion_productividad' + cont + '"  value="' + bonificacion_productividad + '" ></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="descuento_igss[]" id="descuento_igss' + cont + '"  value="' + descuento_igss + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="descuento_isr[]" id="descuento_isr' + cont + '"  value="' + descuento_isr + '"></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_prestamo[]" id="abono_prestamo' + cont + '"  value="' + abono_prestamo + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_otrosDescuentos[]" id="abono_otrosDescuentos' + cont + '"  value="' + abono_otrosDescuentos + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_adelantoQuincenal[]" id="abono_adelantoQuincenal' + cont + '"  value="' + abono_adelantoQuincenal + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="abono_adelantoSalarial[]" id="abono_adelantoSalarial' + cont + '" value="' + abono_adelantoSalarial + '" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="total_deducciones[]" id="total_deducciones' + cont + '"  value="0" readonly></td>' +
            '<td><input class="form-control"  type="number" onchange="modificarSubototales()" style="width:85px" step="any" name="liquido_recibir[]" id="liquido_recibir' + cont + '"  value="0" readonly></td>' +

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

function calcularDias(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);

    // Diferencia en milisegundos
    const diferencia = fin - inicio;

    // Convertir a días (incluyendo el último día)
    return Math.floor(diferencia / (1000 * 60 * 60 * 24)) + 1;
}

function modificarSubototales() {
    var diastrabajados = document.getElementsByName("dias_trabajados[]");
    var horas_trabajadas = document.getElementsByName("horas_trabajados[]");
    var diario = document.getElementsByName("diario[]");
    var salariobase = document.getElementsByName("salario_base[]");
    var totalsalario = document.getElementsByName("total_salario[]");
    var salarioextra = document.getElementsByName("salario_extra[]");
    var totaldevengado = document.getElementsByName("total_devengado[]");
    var bonificacionley = document.getElementsByName("bonificacion_ley[]");
    var bonificacionproductividad = document.getElementsByName("bonificacion_productividad[]");
    var descuentoigss = document.getElementsByName("descuento_igss[]");
    var descuentoisr = document.getElementsByName("descuento_isr[]");
    var abonoprestamo = document.getElementsByName("abono_prestamo[]");
    var abonootrosdescuentos = document.getElementsByName("abono_otrosDescuentos[]");
    var abonoadelantoquincenal = document.getElementsByName("abono_adelantoQuincenal[]");
    var abonoadelantosalarial = document.getElementsByName("abono_adelantoSalarial[]");
    var totaldeducciones = document.getElementsByName("total_deducciones[]");
    var liquido_recibir = document.getElementsByName("liquido_recibir[]");

    for (var i = 0; i < salariobase.length; i++) {
        var inp_diastrabajados = diastrabajados[i];
        var inp_horas_trabajadas = horas_trabajadas[i];
        var inp_diario = diario[i];
        var inp_salariobase = salariobase[i];
        var inp_totalsalario = totalsalario[i];
        var inp_salarioextra = salarioextra[i];
        var inp_totaldevengado = totaldevengado[i];
        var inp_bonificacionley = bonificacionley[i];
        var inp_bonificacionproductividad = bonificacionproductividad[i];
        var inp_descuentoigss = descuentoigss[i];
        var inp_descuentoisr = descuentoisr[i];
        var inp_abonoprestamo = abonoprestamo[i];
        var inp_abonootrosdescuentos = abonootrosdescuentos[i];
        var inp_abonoadelantoquincenal = abonoadelantoquincenal[i];
        var inp_abonoadelantosalarial = abonoadelantosalarial[i];
        var inp_totaldeducciones = totaldeducciones[i];
        var inp_liquido_recibir = liquido_recibir[i];

        // ✅ Calcular de salario x hora
        if (parseFloat(inp_diastrabajados.value) > 0) {
            inp_diario.value = (((parseFloat(inp_salariobase.value) / 2) / 13) / 12);
        } else {
            inp_diario.value = 0;
        }

        // ✅ Calcular total salario
        inp_totalsalario.value = (
            parseFloat(inp_horas_trabajadas.value) * parseFloat(inp_diario.value)
        ).toFixed(2);

        // ✅ Calcular total devengado
        inp_totaldevengado.value = (
            parseFloat(inp_totalsalario.value) +
            parseFloat(inp_salarioextra.value)
        ).toFixed(2);

        // ✅ Calcular igss
        /*inp_descuentoigss.value = (
            (parseFloat(inp_salariobase.value) * 0.0483) / 2
        ).toFixed(2);*/
        inp_descuentoigss.value = 0;
        //aka lo divido /2  aka da el valor de 30 dais pero como solo se paga 15 lo partis a la mitad

        // ✅ Calcular total deducciones
        inp_totaldeducciones.value = (
            parseFloat(inp_descuentoigss.value) +
            parseFloat(inp_descuentoisr.value) +
            parseFloat(inp_abonoprestamo.value) +   //500
            parseFloat(inp_abonootrosdescuentos.value) +  //500
            parseFloat(inp_abonoadelantoquincenal.value) +  //500
            parseFloat(inp_abonoadelantosalarial.value)  //500
        ).toFixed(2);

        // ✅ Calcular líquido a recibir
        inp_liquido_recibir.value = (
            (parseFloat(inp_totaldevengado.value) + parseFloat(inp_bonificacionley.value) + parseFloat(inp_bonificacionproductividad.value)) - parseFloat(inp_totaldeducciones.value)
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