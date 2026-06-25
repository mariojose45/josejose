// ======================================================
// 📦 LECTOR DE CÓDIGOS DE BARRAS (QuaggaJS para iMin POS)
// ======================================================

let scannerActivo = false;

// 🚀 Iniciar cámara y escaneo
function iniciarCamara() {
  if (scannerActivo) return;

  const target = document.querySelector("#video");
  if (!target) {
    Swal.fire({
      icon: "error",
      title: "No se encontró el contenedor del video (#video)",
      text: "Verifica que exista el div con id='video' en tu HTML.",
    });
    return;
  }

  scannerActivo = true;

  Quagga.init(
    {
      inputStream: {
        name: "Live",
        type: "LiveStream",
        target: target,
        constraints: {
          facingMode: "environment", // cámara trasera
          width: { min: 640 },
          height: { min: 480 },
        },
      },
      decoder: {
        readers: [
          "ean_reader", // Códigos EAN-13 (supermercado)
          "ean_8_reader",
          "code_128_reader", // Códigos de barras largos
          "upc_reader",
          "code_39_reader",
        ],
      },
      locate: true,
    },
    function (err) {
      if (err) {
        console.error("❌ Error Quagga.init:", err);
        Swal.fire({
          icon: "error",
          title: "Error al iniciar cámara",
          text: err.message,
        });
        scannerActivo = false;
        return;
      }

      Quagga.start();
      Swal.fire({
        icon: "success",
        title: "Cámara lista",
        text: "Apunte el código de barras al lector",
        timer: 1500,
        showConfirmButton: false,
      });
    }
  );

  // 🎯 Evento cuando detecta un código
  Quagga.onDetected((result) => {
    const codigo = result.codeResult.code;
    console.log("✅ Código detectado:", codigo);

    detenerCamara();

    // 🔊 Sonido de confirmación
    const beep = new Audio("https://actions.google.com/sounds/v1/cartoon/wood_plank_flicks.ogg");
    beep.play();

    $("#buscar_codigo").val(codigo);
    buscarProductoPorCodigo(codigo);

    Swal.fire({
      icon: "success",
      title: "Código leído",
      text: codigo,
      timer: 1000,
      showConfirmButton: false,
    });
  });
}

// 🛑 Detener cámara y escaneo
function detenerCamara() {
  if (scannerActivo) {
    Quagga.stop();
    scannerActivo = false;
    console.log("⏹️ Cámara detenida");
  }
}

// 🧩 Buscar producto (usa tu backend existente)
function buscarProductoPorCodigo(codigo) {
  const idsucursal = $("#idsucursal").val() || 1;

  $.ajax({
    url: "ajax/articulo.php?op=buscarArticuloPorCodigo",
    type: "POST",
    data: { codigo: codigo, idsucursal: idsucursal },
    dataType: "json",
    success: function (data) {
      console.log("🔍 Respuesta del servidor:", data);

      if (data && data.success && data.data.length > 0) {
        const p = data.data[0];
        agregarDetalle(
          p.idarticulo,
          p.nombre,
          p.precio_venta,
          p.precio_compra,
          p.stock,
          p.precio_ventaNocturno,
          p.precio_rango1_Mecanico,
          p.precio_rango1_Distribuidor,
          p.precio_rango1_Mayorista,
          p.precio_rango2_MecanicoDos,
          p.precio_rango2_DistribuidorDos,
          p.precio_rango2_MayoristaDos,
          p.precio_rango3_MecanicoTres,
          p.precio_rango3_DistribuidorTres,
          p.precio_rango3_MayoristaTres,
          p.nombre_01, p.stock_unidad, p.precio_unidad,
          p.nombre_02, p.stock_blister, p.precio_blister,
          p.nombre_03, p.stock_caja, p.precio_caja,
          p.nombre_04, p.stock_fardo, p.precio_fardo,
          p.nombre_05, p.stock_sacos, p.precio_sacos,
          p.nombre_06, p.stock_paquete, p.precio_paquete,
          p.nombre_07, p.stock_07, p.precio_07,
          p.nombre_08, p.stock_08, p.precio_08,
          p.nombre_09, p.stock_09, p.precio_09,
          p.nombre_10, p.stock_10, p.precio_10,
          p.nombre_11, p.stock_11, p.precio_11,
          p.nombre_12, p.stock_12, p.precio_12,
          p.nombre_13, p.stock_13, p.precio_13,
          p.nombre_14, p.stock_14, p.precio_14,
          p.nombre_15, p.stock_15, p.precio_15,
          p.nombre_16, p.stock_16, p.precio_16,
          p.nombre_17, p.stock_17, p.precio_17,
          p.nombre_18, p.stock_18, p.precio_18,
          p.nombre_19, p.stock_19, p.precio_19,
          p.nombre_20, p.stock_20, p.precio_20
        );
        Swal.fire({
          icon: "success",
          title: "Producto agregado",
          text: p.nombre,
          timer: 1200,
          showConfirmButton: false,
        });
      } else {
        Swal.fire({
          icon: "warning",
          title: "Código no encontrado",
          timer: 1500,
          showConfirmButton: false,
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("❌ Error AJAX:", error);
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar al servidor",
      });
    },
  });
}

// 🎛️ Eventos de los botones
$(document).ready(() => {
  $("#btnIniciarCamara").on("click", (e) => {
    e.preventDefault();
    iniciarCamara();
  });

  $("#btnDetenerCamara").on("click", (e) => {
    e.preventDefault();
    detenerCamara();
  });
});
