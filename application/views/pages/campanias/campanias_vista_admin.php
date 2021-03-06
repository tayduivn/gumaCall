<header class="demo-header mdl-layout__header ">
    <div class="row center ColorHeader">        
       <div class="container_reloj">
            <div class="clock">
                <ul class="ul_r">
                    <li id="hours"></li>
                    <li id="point">:</li>
                    <li id="min"></li>
                    <li id="type"></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<!--  CONTENIDO PRINCIPAL -->
<main class="mdl-layout__content mdl-color--grey-100">
    <div class="contenedor">
        <div class="noMargen row TextColor center"><div class="col s12 m8 l12 offset-m1">LISTADO DE CAMPAÑAS</div></div>
        <div class="noMargen Buscar row column">
            <div id="agregarUsuario" class="col offset-s10 s10 right">
                <a href="crearCampania" class="BtnBlue waves-effect btn modal-trigger"><i class="material-icons left">add</i>CREAR CAMPAÑA</a>
            </div>
        </div><br><br>
        <div class="row">
            <div id="tableCampaniasVA">
            <table id="tblcampaniasVA" class="TblData">
                <thead>
                <tr>
                    <th>Nº CAMPAÑA</th>                    
                    <th>NOMBRE</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA CIERRE</th>                    
                    <th>META</th>
                    <th>REAL</th>
                    <th>OBSERVACIONES</th>
                    <th>ACTIVA</th>
                    <th style="display:none">ESTADO1</th>
                    <th>Opciones</th>                  
                </tr>
                </thead>
                <tfoot style="display:none">
                    <tr>
                        <th>Nº CAMPAÑA</th>
                        <th>NOMBRE</th>
                    </tr>
                </tfoot>
                <tbody class="center">
                <?php 
                    if ($listaCampanias) {
                        foreach ($listaCampanias as $key) {
                            if ($key['Estado']==2) {
                                $class='tachado';
                                $chk="<input type='checkbox' class='filled-in' id='chkActivo".$key['ID_Campannas']."'>";
                            }elseif ($key['Estado']!=2) {
                                $class='noTachado';
                                $chk="<input type='checkbox' checked='checked' class='filled-in' id='chkActivo".$key['ID_Campannas']."'>";                                
                            }

                            if ($key['Estado']==1) {
                                $estado='Activa';
                                $status="<li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 2)'>Inactivar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 3)'>Aprobar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 4)'>Procesar</a></li>
                                         <li class='divider'></li>
                                         <li><div id='numcamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='editarCampania(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Editar</a></div></li>
                                         <li><div id='NumCamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='addClientCamp(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Nuevo</a></div></li>";
                            }elseif ($key['Estado']==2) {
                                $estado='Inactiva';
                                $status="<li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 1)'>Activar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 3)'>Aprobar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 4)'>Procesar</a></li>
                                         <li class='divider'></li>
                                         <li><div id='numcamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='editarCampania(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Editar</a></div></li>
                                         <li><div id='NumCamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='addClientCamp(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Nuevo</a></div></li>";
                            }elseif ($key['Estado']==3) {
                                $estado='Aprobada';
                                $status="<li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 1)'>Activar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 2)'>Inactivar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 4)'>Procesar</a></li>
                                         <li class='divider'></li>
                                         <li><div id='numcamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='editarCampania(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Editar</a></div></li>
                                         <li><div id='NumCamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='addClientCamp(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Nuevo</a></div></li>";
                            }elseif ($key['Estado']==4) {
                                $estado='Procesando';
                                $status="<li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 1)'>Activar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 2)'>Inactivar</a></li>
                                         <li><a href='#!' onclick='cambiaEstadoCamp(".'"'.$key['ID_Campannas'].'"'.", 3)'>Aprobar</a></li>
                                         <li class='divider'></li>
                                         <li><div id='numcamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='editarCampania(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Editar</a></div></li>
                                         <li><div id='NumCamp".$key['ID_Campannas']."' style='padding: 7px 7px'><a onclick='addClientCamp(".'"'.$key['ID_Campannas'].'","'.$key['Nombre'].'"'.")' href='#' class='lista-edicion'>Nuevo</a></div></li>";
                            }
                            echo"
                            <tr>
                                <td class='".$class."'><a href='detallesVA/".$key['ID_Campannas']."'>".$key['ID_Campannas']."</a> </td>
                                <td class='".$class."'>".$key['Nombre']."</td>
                                <td class='".$class."'>
                                    <span>".date('d-m-Y', strtotime($key['Fecha_Inicio']))."</span>
                                </td>
                                <td class='".$class."'>
                                    <span>".date('d-m-Y', strtotime($key['Fecha_Cierre']))."</span>
                                </td>
                                <td class='".$class."'>
                                    <span>C$ ".number_format($key['Meta'],2)."</span>
                                </td>
                                <td class='".$class."'>
                                    <span>C$ ".number_format($key['monto'],2)."</span>
                                </td>
                                <td class='".$class."'>
                                    <span>".$key['Observaciones']."</span>
                                </td>
                                <td>".$chk."
                                    <label for='chkActivo".$key['ID_Campannas']."'></label>
                                </td>
                                <td style='display:none'>".$estado."</td>
                                <td> 
                                    <div>
                                        <a class='dropdown-button btn-floating  blue' href='#' data-activates='dropdown1".$key['ID_Campannas']."'><i class='small material-icons'>list</i></a>
                                        <ul id='dropdown1".$key['ID_Campannas']."' class='dropdown-content ul-dr'>
                                        ".$status."
                                        </ul>
                                    </div>                              
                                </td>
                            </tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</main>
<!--MODAL: EDITANDO CAMPAÑAS-->
<div id="modalEditarCamp" class="modal">
    <div class="modal-content"><br>
        <div class="row center">            
            <span id="nombreCampania" class="titulosModales"></span><br><br>
            <span style="font-family: robotomedium">SELECCIONAR AGENTES ACTIVOS</span>
            <input type='hidden' id="idCampania2">                   
        </div>
        <div class="row center">
            <div id="agente" class="col s12">
                <div class="row">  
                    <div class="col s12 m12 l12">                
                        <div class="row center">
                            <table id="tblAdmAgentes" class="TblData">
                                <thead>
                                    <tr>                                
                                        <th class="th-campania">SELECCIONAR</th>
                                        <th>ID_USUARIO</th>
                                        <th>NOMBRE AGENTE</th>
                                    </tr>
                                </thead>
                                <tbody class="center">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cliente" class="col s12"></div>
        </div>
        <div class="row center">
            <a id="guardarEdicion" class="BtnBlue waves-effect btn modal-trigger">GUARDAR</a>&nbsp;&nbsp;
            <a id="cancelarProceso" class="modal-action modal-close BtnCancelar waves-effect btn modal-trigger">CANCELAR</a>
        </div>
    </div>
</div>

<!--MODAL: AGRREGANDO CLIENTE A CAMPAÑA-->
<div id="modalAddClienteCamp" class="modal" style="width:1000px;">
    <div class="modal-content"><br>
        <div class="row center">            
            <span id="nombreCampania1" class="titulosModales"></span><br><br>
            <span style="font-family: robotomedium">SELECCIONAR CLIENTE</span>
            <input type='hidden' id="idCampaniaclient" name="idCampaniaclient">                   
        </div>
        <div class="row center">
            <div id="agente" class="col s12">
                <div class="row">  
                    <div class="col s12 m12 l12">          
                        <div class="row center">
                                <div class="col s4 m4 l4 input-field right" id="divsearchClients">
                                    <i class="material-icons prefix">search</i>
                                    <input type="text" id="searchClients" placeholder="BUSCAR CLIENTE">   
                                </div>
                            <table id="tbladdclientcamp" class="TblData">
                                <thead>
                                    <tr>                                
                                        <th>ID_CLIENTE</th>
                                        <th>NOMBRE CLIENTE</th>
                                        <th class="th-campania">AGREGAR</th>
                                    </tr>
                                </thead>
                                <tbody class="center">
                                    <?php
                                        if(!$Clients)
                                        {}else{
                                            foreach ($Clients as $key) {
                                                echo"
                                                    <tr>
                                                        <td>".$key["ID_Cliente"]."</td>
                                                        <td>".$key["Nombre"]. "</td>
                                                        <td><a href='#' onclick='saveClient(".'"'.$key["ID_Cliente"].'"'.")' class='btn BtnBlue waves-effect waves-light'><i class='material-icons'>add</i></a></td>
                                                    </tr>
                                                ";
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cliente" class="col s12"></div>
        </div>
        <div class="row center">
            <a id="saveClientCamp" class="BtnBlue waves-effect btn modal-trigger">GUARDAR</a>&nbsp;&nbsp;
            <a id="" class="modal-action modal-close BtnCancelar waves-effect btn modal-trigger">CANCELAR</a>
        </div>
    </div>
</div>