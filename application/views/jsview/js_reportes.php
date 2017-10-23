<script>
$(document).ready(function() {
    $('.datepicker').pickadate({
        labelMonthNext: 'Mes siguiente',
        labelMonthPrev: 'Mes anterior',
        labelMonthSelect: 'Selecciona un mes',
        labelYearSelect: 'Selecciona un año',
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        weekdaysLetter: ['D', 'L', 'M', 'X', 'J', 'V', 'S'],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar',
        format: 'yyyy-mm-dd',
        closeOnSelect: false
    });

    $('#tblTiempos').DataTable({
        "ordering": true,
        "info": false,
        "bPaginate": false,
        "bfilter": false,
        "pagingType": "full_numbers",
        "aaSorting": [
            [0, "asc"]
        ],
        "lengthMenu": [
            [20, 10, -1],
            [20, 30, "Todo"]
        ],
        "language": {
            "zeroRecords": "NO HAY RESULTADOS",
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
        }
    });
    $(function() {
        $("ul li").each(function(){
            if($(this).attr("id") == 'reportes')
            $(this).addClass("urlActual");
         })
    });
    var pathname = window.location.pathname;

    if (pathname.match(/tipoRpt.*/)) {
        $("#menu ul li").each(function(){
            var pos = $(this).attr("id");
            if ($(this).attr("id")==pos) {
                $("#"+pos+" a").attr("href", "../"+pos+"");
            }
        })
    }
    if (pathname.match(/rptclientes*/)) {
        cargaCampania();
    }

    if (pathname.match(/rptagentes*/)) {
        $('#filtroPorFechas').show();
    }else if (pathname.match(/rptclientes*/)) {
        $('#filtroPorCamp').show();
    }

    $('#tblReportes').DataTable({
        "bFilter": true,
        "scrollCollapse": true,
        "info":    false,            
        "lengthMenu": [[20,30,50,100,-1], [20,30,50,100,"Todo"]],
        "language": {
            "zeroRecords": "NO HAY RESULTADOS",
            "paginate": {
                "first":      "Primera",
                "last":       "Última ",
                "next":       "Siguiente",
                "previous":   "Anterior"                    
            },
            "lengthMenu": "MOSTRAR _MENU_",
            "emptyTable": "NO HAY DATOS DISPONIBLES",
            "search":     "BUSCAR"
        }
    });      
});

$("#selectRpt").on('change', function(event) {
    var tipoReporte = $('#selectRpt').val();
    if (tipoReporte==1) {
        $('#reportesAgentes').fadeIn('slow');
    }else {
        $('#reportesAgentes').fadeOut('slow');
    }
});

function cargaCampania() {
    $.ajax({
        url: "../filtrarPorCamp",
        type: 'post',
        async: true,
        success: function(data) {
            $('#campania').empty();
            $.each(JSON.parse(data), function(i, item) {
                $("#campania").append('<option value="' + item['value'] + '">' + item['desc'] + '</option>');
            });
            $('#campania').trigger("chosen:updated");
        }
    }); 
}

$('#generarRpt').click(function() {
    var pathname = window.location.pathname;
    var valor = $('#selectRpt').val();

    if ($('#selectRpt').val()==null) {
        mensaje("Seleccione un valor para generar el reporte", "error");
    }else {
        if (pathname.match(/rptcampania/)) {
            generarReporte(valor, 1);
            $('#val').val(1);
            $("#rptCampaniaModal").openModal();
        }else if (pathname.match(/rptagentes/)) {
            generarReporte(valor, 2);     
            $('#val').val(2);
            $("#rptAgenteModal").openModal();
        }else if (pathname.match(/rptclientes/)) {
            limpiarControles();
            generarReporte(valor, 3);
            $('#val').val(3);
            $("#rptClienteModal").openModal();
        }
    }
})

function generarReporte(identificador, tipoReporte) {
    var dataReporte  = new Array(); var d1=''; var d2='';
    if (tipoReporte==1) {    
        dataReporte[0] = identificador +","+ tipoReporte +","+ d1 +","+ d2;
    }else if (tipoReporte==2) {
        if ($('#desde').val()=="" && $('#hasta').val()=="") {
            d1= moment(new Date()).format('YYYY-MM-DD 00:00:00');
            d2= moment(new Date()).format('YYYY-MM-DD 23:59:59');   
        }else {
            d1 = moment($('#desde').val()).format('YYYY-MM-DD 00:00:00');
            d2 = moment($('#hasta').val()).format('YYYY-MM-DD 23:59:59');
        }
        dataReporte[0] = identificador +","+ tipoReporte +","+ d1 +","+ d2;
    }else if (tipoReporte==3) {
        dataReporte[0] = identificador +","+ tipoReporte +","+ d1 +","+ d2;
    }
        
    var form_data = {
        data: dataReporte
    };

    $.ajax({
        url: "../generarRep",
        type: 'post',
        async: true,
        data: form_data,
        success: function(data) {
            if (data.length!=0) {
                $.each(JSON.parse(data), function(i, item) {
                    if (tipoReporte==1) {
                        $.each(item['array_1'], function(i, item) {
                            $('#idTemporal').val(item['ID_Campannas']);
                            $('#tituloCampania').text(item['ID_Campannas'] +' - '+item['nombre']);
                            $('#fechasCampania').text('Del '+moment(item['fechaInicio']).format('DD/MM/YYYY')+' al '+moment(item['fechaCierre']).format('DD/MM/YYYY'));
                            $('#monto').text('META: C$ '+ parseFloat(item['meta']).toLocaleString("en-US"));
                            $('#real').text('REAL: C$ '+ parseFloat(item['montoReal']).toLocaleString("en-US"));
                            $('#observacion').val(item['observacion']);
                            $('#mensaje').val(item['mensaje']);
                            $('#totalLlamada').text(parseInt(item['totalLlamadas']));
                            $('#tiempoTotal').text(item['tiempoTotal']);
                            $('#tiempoPromedio').text(moment(item['tiempoPromedio'], ["h:mm:ss"]).format("HH:mm:ss"));
                            $('#unidades').text(parseInt(item['unidad']));

                        });
                        $('#tblClienteRpt').DataTable({                       
                            "destroy": true,
                            "data": item['array_2'],
                            "info":    false,
                            "bPaginate": true,
                            "paging": true,
                            "ordering": false,
                            "pagingType": "full_numbers",
                            "emptyTable": "No hay datos disponibles en la tabla",
                            "language": {
                                "zeroRecords": "No hay datos disponibles"
                            },
                            columns: [
                                { "data": "ID_Cliente" },
                                { "data": "Nombre" },
                                { "data": "Meta", render: $.fn.dataTable.render.number( ',', '.', 2 )},
                                { "data": "montoReal", render: $.fn.dataTable.render.number( ',', '.', 2 ) }
                            ]
                        });
                    }else if (tipoReporte==2) {
                        $.each(item['array_1'], function(i, item) {
                            $('#idTemporal').val(item['IdUser']);
                            $('#nombreUsuario').text(item['nombre']);
                            $('#usuario').text(item['usuario']);
                            $('#HC').text(moment(item['tiempoON'], ["h:mm:ss"]).format("HH:mm:ss"));
                            $('#TP').text(moment(item['tiempoPAUSA'], ["h:mm:ss"]).format("HH:mm:ss"));
                            $('#TT').text(moment(item['tiempoTotal'], ["h:mm:ss"]).format("HH:mm:ss"));
                        });
                        $('#tblDetalleConexion').DataTable({                     
                            "destroy": true,
                            "data": item['array_2'],
                            "info":    false,
                            "bPaginate": false,
                            "paging": false,
                            "ordering": false,
                            "pagingType": "full_numbers",
                            "emptyTable": "No hay datos disponibles en la tabla",
                            "language": {
                                "zeroRecords": "No hay datos disponibles"
                            },
                            columns: [
                                { "data": "FechaInicio", render: function (data, type, row) {
                                    data = moment(data).format('DD/MM/YYYY h:mm:ss');
                                    return data;
                                } },
                                { "data": "FechaFinal", render: function (data, type, row) {
                                    data = moment(data).format('DD/MM/YYYY h:mm:ss');
                                    return data;
                                } },
                                { "data": "Tiempo_Total" },
                                { "data": "Tipo" }
                            ]
                        });

                    }else if (tipoReporte==3) {
                        $('#nombreCliente').text(item['nombre']);
                        $('#direccion').text(item['direccion']);
                        $('#idTemporal').val(item['idCliente']);
                        $('#tblClientes').DataTable({                       
                            "destroy": true,
                            "data": JSON.parse(data),
                            "info":    false,
                            "bPaginate": false,
                            "paging": false,
                            "ordering": false,
                            "pagingType": "full_numbers",
                            "emptyTable": "No hay datos disponibles en la tabla",
                            "language": {
                                "zeroRecords": "No hay datos disponibles"
                            },
                            "columnDefs": [
                                {
                                    "targets": [ 4, 5, 6, 7, 8, 9 ],
                                    "visible": false
                                }
                            ],
                            columns: [
                                { "data": "campania" },
                                { "data": "monto", render: $.fn.dataTable.render.number( ',', '.', 2 )},
                                { "data": "meta", render: $.fn.dataTable.render.number( ',', '.', 2 )},
                                { "data": "unidad" },
                                { "data": "nombre" },
                                { "data": "direccion" },
                                { "data": "telefono1" },
                                { "data": "telefono2" },
                                { "data": "Telefono3" },
                                { "data": "idCliente" },
                                { "data": "agente" }
                            ]
                        });
                    }
                });
            }else if (data.length===0) {
                limpiarControles(tipoReporte);
            }
        }
    });
}

function imprimirRpt() { 
    var tipoRpt = $('#val').val();
    var val = $('#idTemporal').val();

    if (tipoRpt==1) {        
        window.open("../generarPDF?tipo=rptcampania&id="+val+" ", '_blank');
    }else if (tipoRpt==2) {
        var d1 = moment($('#desde').val()).format('YYYY-MM-DD');
        var d2 = moment($('#hasta').val()).format('YYYY-MM-DD');
        var url = 'reporte-agente='+d1+"="+d2;
        window.open("../generarPDF?tipo=rptagentes&id="+val+"&f1="+d1+"&f2="+d2+" ", '_blank');
    }else if (tipoRpt==3) {
        window.open("../generarPDF?tipo=rptclientes&id="+val+" ", '_blank');
    }
}

function limpiarControles(identificador) {
    if (identificador==1) {
        $('#idTemporal').val('');
        $('#tituloCampania').text('');
        $('#fechasCampania').text('');
        $('#monto').text('');
        $('#real').text('');
        $('#observacion').val('');
        $('#mensaje').val('');
        $('#totalLlamada').text('');
        $('#tiempoTotal').text('');
        $('#tiempoPromedio').text('');
        $('#unidades').text('');
         
        $('#tblClienteRpt').DataTable()
            .clear()
            .draw();
    }else if (identificador==2) {
        $('#idTemporal').val('');
        $('#nombreUsuario').text('');
        $('#usuario').text('');
        $('#HC').text('');
        $('#TP').text('');
        $('#TT').text('');
    }else if (identificador==3) {
        $('#nombreCliente').text('');
        $('#direccion').text('');
        $('#idTemporal').val('');

        $('#tblClientes').DataTable()
            .clear()
            .draw();
    }
}
</script>