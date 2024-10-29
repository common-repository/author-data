﻿<?php
/*


*/


//Parametrizar según idioma
$currentLocale = get_locale();
if(!empty($currentLocale)) {
$moFile = dirname(__FILE__) . "/languages/author-data-" . $currentLocale . ".mo";
if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('fichas', $moFile);
}


global $wpdb;
$tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
install_author_data();



/*********************************************************
**
**  Gestión de las fichas
**
***********************************************************/

               $n_mensaje = 0;
               $mensajes = array ('', __("No hay datos", 'fichas'), __("Has llegado al final", 'fichas'), __("Has llegado al inicio", 'fichas'), __("Formato de página web incorrecto", 'fichas'), __("Debe confirmar el borrado", 'fichas'), __("Borrado", 'fichas'));

               $grabar_tabla = __("Modificar", 'fichas');
               $ver_tabla = __("Ver datos", 'fichas');
               $borrar_tabla = __("Eliminar", 'fichas');
               $limpiar = __("Limpiar", 'fichas');
               $confirmar_borrado = __("Confirmar", 'fichas');
               $inicio = '<<';
               $anterior = '<';
               $siguiente = '>';
               $fin = '>>';
               $ed_html = 'html';
               $ed_visual = 'visual';

               if ($_POST) {
                   $errores = 0;
                   if ($_POST['autores'] == "")
                        $_POST['autores'] = '';
                   if($_POST["fecha_nac"] == "")
			$_POST["fecha_nac"] = "1965";
                   if($_POST["lugar_nac"] == "")
			$_POST["lugar_nac"] = "";
                   if($_POST["sitio_web"] == "")
			$_POST["sitio_web"] = "";
                  if($_POST['ficha'] == '')
                        $_POST['ficha'] = '';
                  if($_POST['tipo_ed'] == '')
                        $_POST['tipo_ed'] = $ed_html;
                  $_POST['ficha'] = author_data_formatear($_POST['ficha']);

/*****************************
*
* Visualiza datos existentes
*
*****************************/
                   if ($_POST['Gestionar'] == $ver_tabla) {
                       $nombre_autor = $_POST['autores'];
                       $consulta = "SELECT user_login, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " WHERE user_login = '$nombre_autor'";
                       $datos_previos = $wpdb->get_results($consulta);
                       if ($datos_previos) {
                           $_POST['autores'] = $datos_previos[0]->user_login;
                           $_POST['fecha_nac'] = $datos_previos[0]->fecha_nac;
                           $_POST['lugar_nac'] = $datos_previos[0]->lugar_nac;
                           $_POST['sitio_web'] = $datos_previos[0]->sitio_web;
                           $_POST['ficha'] = $datos_previos[0]->ficha;
                       }
                       else {
                           $_POST['fecha_nac'] = '1965';
                           $_POST['lugar_nac'] = '';
                           $_POST['sitio_web'] = '';
                           $_POST['ficha'] = '';
                       }
                   }

/*****************************
*
* Ir al inicio / final
*
*****************************/

                   if ($_POST['Gestionar'] == $inicio || $_POST['Gestionar'] == $fin) {
                       $nombre_autor = $_POST['autores'];
                       if ($_POST['Gestionar'] == $inicio) {
                           $query1_select = "SELECT usuarios.ID, usuarios.user_login as usuario_login, usuarios.display_name, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
                           $query1_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
                           $query1_where = " WHERE usuarios.ID IN (SELECT post_author FROM $wpdb->posts) AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
                           $query1_orderby = " ORDER BY apellido, nombre, usuarios.display_name ASC";
                           $query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
                       }
                       else {
                           $query1_select = "SELECT usuarios.ID, usuarios.user_login as usuario_login, usuarios.display_name, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
                           $query1_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
                           $query1_where = " WHERE usuarios.ID IN (SELECT post_author FROM $wpdb->posts) AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
                           $query1_orderby = " ORDER BY apellido DESC, nombre DESC, usuarios.display_name DESC";
                           $query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
                       };
                       $us_posts = $wpdb->get_results($query1);
                       if ($us_posts) {
                          $nombre_login = $us_posts[0]->usuario_login;
                          $consulta = "SELECT user_login, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " WHERE user_login = '$nombre_login'";
                          $datos_previos = $wpdb->get_results($consulta);
                          if ($datos_previos) {
                              $_POST['autores'] = $datos_previos[0]->user_login;
                              $_POST['fecha_nac'] = $datos_previos[0]->fecha_nac;
                              $_POST['lugar_nac'] = $datos_previos[0]->lugar_nac;
                              $_POST['sitio_web'] = $datos_previos[0]->sitio_web;
                              $_POST['ficha'] = $datos_previos[0]->ficha;
                          }
                          else {
                              $_POST['autores'] = $nombre_login;
                              $_POST['fecha_nac'] ='';
                              $_POST['lugar_nac'] = '';
                              $_POST['sitio_web'] = '';
                              $_POST['ficha'] = '';

                          }
                       }
                       else {
                           $n_mensaje = 1;
                     }
                   }

/*****************************
*
* Visualiza siguiente
*
*****************************/
                   if ($_POST['Gestionar'] == $siguiente) {
                       $nombre_autor = $_POST['autores'];
                       $nombre = $_POST['nombre'];
                       $apellido = $_POST['apellido'];
                       $query1_select = "SELECT usuarios.ID, usuarios.user_login as usuario_login, usuarios.display_name, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
                       $query1_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
                       $query1_where = " WHERE (usmetaa.meta_value > '$apellido' OR (usmetaa.meta_value = '$apellido' AND usmetan.meta_value > '$nombre')) AND usuarios.ID IN (SELECT post_author FROM $wpdb->posts) AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
                       $query1_orderby = " ORDER BY apellido, nombre, usuarios.display_name ASC";
                       $query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
                       $us_posts = $wpdb->get_results($query1);
                       if ($us_posts) {
                          $nombre_login = $us_posts[0]->usuario_login;
                          $consulta = "SELECT user_login, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " WHERE user_login = '$nombre_login'";
                          $datos_previos = $wpdb->get_results($consulta);
                          if ($datos_previos) {
                              $_POST['autores'] = $datos_previos[0]->user_login;
                              $_POST['fecha_nac'] = $datos_previos[0]->fecha_nac;
                              $_POST['lugar_nac'] = $datos_previos[0]->lugar_nac;
                              $_POST['sitio_web'] = $datos_previos[0]->sitio_web;
                              $_POST['ficha'] = $datos_previos[0]->ficha;
                              $_POST['nombre'] = $us_posts[0]->nombre;
                              $_POST['apellido'] = $us_posts[0]->apellido;
                          }
                          else {
                              $_POST['autores'] = $nombre_login;
                              $_POST['fecha_nac'] = '';
                              $_POST['lugar_nac'] = '';
                              $_POST['sitio_web'] = '';
                              $_POST['ficha'] = '';

                          }
                       }
                       else {
                           $n_mensaje = 2;
                     }
                   }

/*****************************
*
* Visualiza anterior
*
*****************************/
                   if ($_POST['Gestionar'] == $anterior) {
                       $nombre_autor = $_POST['autores'];
                       $nombre = $_POST['nombre'];
                       $apellido = $_POST['apellido'];
                       $query1_select = "SELECT usuarios.ID, usuarios.user_login as usuario_login, usuarios.display_name, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
                       $query1_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
                       $query1_where = " WHERE (usmetaa.meta_value < '$apellido' OR (usmetaa.meta_value = '$apellido' AND usmetan.meta_value < '$nombre')) AND usuarios.ID IN (SELECT post_author FROM $wpdb->posts) AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
                       $query1_orderby = " ORDER BY apellido DESC, nombre DESC, usuarios.display_name DESC";
                       $query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
                       $us_posts = $wpdb->get_results($query1);
                       if ($us_posts) {
                          $nombre_login = $us_posts[0]->usuario_login;
                          $consulta = "SELECT user_login, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " WHERE user_login = '$nombre_login'";
                          $datos_previos = $wpdb->get_results($consulta);
                          if ($datos_previos) {
                              $_POST['autores'] = $datos_previos[0]->user_login;
                              $_POST['fecha_nac'] = $datos_previos[0]->fecha_nac;
                              $_POST['lugar_nac'] = $datos_previos[0]->lugar_nac;
                              $_POST['sitio_web'] = $datos_previos[0]->sitio_web;
                              $_POST['ficha'] = $datos_previos[0]->ficha;
                              $_POST['nombre'] = $us_posts[0]->nombre;
                              $_POST['apellido'] = $us_posts[0]->apellido;
                          }
                          else {
                              $_POST['autores'] = $nombre_login;
                              $_POST['fecha_nac'] = '';
                              $_POST['lugar_nac'] = '';
                              $_POST['sitio_web'] = '';
                              $_POST['ficha'] = '';

                          }
                       }
                       else {
                           $n_mensaje = 3;
                     }
                   }


/**************************
*
* Limpia el formulario
*
***************************/

                   if ($_POST['Gestionar'] == $limpiar || $_POST['Gestionar'] == $confirmar_borrado) {
   		       //$_POST['autores'] = '';
                       $_POST['fecha_nac'] = '1965';
                       $_POST['lugar_nac'] = '';
                       $_POST['sitio_web'] = '';
                       $_POST['ficha'] = '';
                       $_POST['confirmar'] = 'n';
                                    }

                   update_option('autores', $_POST['autores']);
   		   update_option('fecha_nac', $_POST['fecha_nac']);
		   update_option('lugar_nac', $_POST['lugar_nac']);
		   update_option('sitio_web', $_POST['sitio_web']);
		   update_option('ficha', $_POST['ficha']);
                   update_option('tipo_ed', $_POST['tipo_ed']);
                   update_option('nombre', $_POST['nombre']);
                   update_option('apellido', $_POST['apellido']);
                  

             };

        $autores = get_option('autores');
        $fecha_nac = get_option('fecha_nac');
        $lugar_nac = get_option('lugar_nac');
        $sitio_web = get_option('sitio_web');
        $ficha = get_option('ficha');
        $tipo_ed = get_option('tipo_ed');
        $nombre = get_option('nombre');
        $apellido = get_option('apellido');


/****************************
*
* Elegir tipo de editor
*
*****************************/
                  

                   if ($_POST['tipo_ed']) {
                       $tipo_ed = $_POST['tipo_ed'];
                    }

                   if ($tipo_ed == $ed_visual) {
        
?>

<script type="text/javascript" src="../wp-includes/js/tinymce/tiny_mce.js"></script>

<script type="text/javascript">
<!--
tinyMCE.init({
theme : "advanced",
theme_advanced_toolbar_location : "top",
theme_advanced_layout_manager : "SimpleLayout",
theme_advanced_buttons1 : "undo,redo,separator,bold,italic,underline,strikethrough,separator,forecolor,fontsizeselect,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,sub,sup,separator,hr,charmap,",
theme_advanced_buttons2 : "cut,copy,paste,separator,bullist,numlist,separator,outdent,indent,separator,fontselect,separator,link,unlink,anchor,separator,image,cleanup,separator,code,removeformat,",
theme_advanced_buttons3 : "",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
verify_html : "false",
verify_css_classes : "false",
plugins : "zoom,advlink,emotions,iespell,style,advhr,contextmenu,advimage,",
mode : "exact",
elements : "editorContent",
valid_elements : "*[*]",
extended_valid_elements : "*[*]",
auto_reset_designmode : "true",
trim_span_elements : "false",
width : "447",
height : "300"
});
-->
</script>
<?php
}
?>


<div class="wrap">
<h2><?php  _e('Datos del autor', 'fichas'); ?></h2>

<form target="_self" method="post" name="form_gestion">
<table width=70%>
<tr>
<td width=20% valign=middle><?php _e('Nombre', 'fichas'); ?>:</td>
<td valign=top colspan=2>
<?php
//Obtener usuarios con posts
global $wpdb;
$queautor1 = array ();
$queautor2 = array ();
$query1_select = "SELECT usuarios.ID, usuarios.user_login, usuarios.display_name, usmetan.meta_value as nombre, usmetaa.meta_value as apellido";
$query1_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmetan, $wpdb->usermeta as usmetaa";
$query1_where = " WHERE (usuarios.ID IN (SELECT post_author FROM $wpdb->posts) OR usuarios.user_login = 'redal') AND usuarios.ID = usmetan.user_id AND usuarios.ID = usmetaa.user_id AND usmetan.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
$query1_orderby = " ORDER BY apellido, nombre, usuarios.display_name ASC";
$query1 = $query1_select . $query1_from . $query1_where . $query1_orderby;
//echo $query1;

$us_posts = $wpdb->get_results($query1);
if ($us_posts) {
  $i = 0;
  foreach ($us_posts as $uspost) {
   $autores1[$i] = $uspost->user_login;
   $autores2[$i] = $uspost->display_name;
   $nombres[$i] = $uspost->nombre;
   $apellidos[$i] = $uspost->apellido;
   $i++;
  }
};
$j = $i;
?>
<input name="autores" type="hidden" style="width:100%;" value="<?php echo $autores; ?>"/>
<select name="autores">
<?php
if ($i == 0) {
    $autores1 = array ("all");
    $autores2 = array (__("Sin autores", 'fichas'));
    $j = $i;
};
$i = 0;
while ($i < $j)
{
?>
         
         <option <?php if ($autores==$autores1[$i]) {echo 'selected'; $nombre_vis = $autores2[$i]; $nombre = $nombres[$i]; $apellido = $apellidos[$i];} ?> value="<?php echo $autores1[$i] ?>"><?php echo $autores2[$i] ?></option>
<?php
$i++;
}
?>
</td>
<td>
<input name="nombre" type="hidden" style="width:100%;" value="<?php echo $nombre; ?>"/>
<input name="apellido" type="hidden" style="width:100%;" value="<?php echo $apellido; ?>"/>
</td>
<td></td>
</tr>


<tr><td colspan=5>&nbsp;</td></tr>

<tr>
<td width=20% valign=middle><?php _e('Lugar de nacimiento', 'fichas'); ?>:</td>
<td valign=top width=35%>
<input name="lugar_nac" type="hidden" style="width:100%;" value="<?php echo $lugar_nac; ?>" />
<input name="lugar_nac" type="text" size=30 value="<?php echo $lugar_nac; ?>" />
</td>
<td width=5% valign=middle><?php _e('Año', 'fichas'); ?>:</td>
<td valign=top>
<input name="fecha_nac" type="hidden" style="width:100%;" value="<?php echo $fecha_nac; ?>" />
<select name="fecha_nac">
<?php 
$i=1930;
while ($i<=2009)
{ ?>
    <option <?php if ($fecha_nac==$i) echo 'selected'; ?> value="<?php echo $i; ?>"><?php echo $i; ?> </option>
<?php 
$i++;
}
?>
                    </select>
</td>
<td></td>
</tr>

<tr><td colspan=5>&nbsp;</td></tr>

<tr>
<td width=20% valign=middle><?php 
if ( ($sitio_web != '') && ((substr($sitio_web, 0, 7) != 'http://') && (substr($sitio_web, 0, 8) != 'https://')) ) {
  echo '<font color=#8B0000><strong>* </strong></font>';
  $errores = 1;};
_e('Sitio web', 'fichas'); 
?>:</td>
<td valign=top colspan=4>
<input name="sitio_web" type="hidden" style="width:100%;" value="<?php echo $sitio_web; 
?>" />
<input name="sitio_web" type="text" size=60 value="<?php if ($sitio_web == '') echo 'http://'; else echo $sitio_web; ?>" />
</td>
</tr>

<tr><td colspan=5>&nbsp;</td></tr>

<tr>
<td width=20% valign=top><?php _e('Biografía', 'fichas'); ?>:</td>
<td valign=top colspan=4>
<div align=left>
<STYLE type="text/css">
   input.inactivo {border: none; background: #fff; color: #505050}
   input.activo {border: none; background: #505050; color: #FFF;}
</STYLE>
<input name="tipo_ed" type="hidden" style="width:100%;" value="<?php echo $tipo_ed; ?>" />
<input type="submit" class="<?php if ($tipo_ed == $ed_visual) echo 'inactivo'; else echo 'activo'; ?>" name="tipo_ed" value="<?php echo $ed_html; ?>" />
<input type="submit" class="<?php if ($tipo_ed == $ed_html) echo 'inactivo'; else echo 'activo'; ?>"name="tipo_ed" value="<?php echo $ed_visual; ?>" />
</div>
<input name="ficha" type="hidden" style="width:100%;" value="<?php echo $ficha; ?>" />
<textarea id ="editorContent" name="ficha" cols="58" rows="10"><?php echo $ficha; ?></textarea>

<tr>
<td></td>
<td valign=top colspan=3><strong><p class="submit">
		<input name="submitted" type="hidden" value="yes" />
                <input type="submit" name="Gestionar" value="<?php echo $inicio; ?>" />
                <input type="submit" name="Gestionar" value="<?php echo $anterior; ?>" />
                <input type="submit" name="Gestionar" value="<?php echo $siguiente; ?>" />
                <input type="submit" name="Gestionar" value="<?php echo $fin; ?>" />
&nbsp;|&nbsp;
		<input type="submit" name="Gestionar" value="<?php echo $grabar_tabla; ?>" />
<?php
             //Borrado / Confirmación
             if ($_POST['Gestionar'] != $borrar_tabla || $errores > 0) {
                echo '<input type="submit" name="Gestionar" value="' . $borrar_tabla . '" />';
             }
             else {
                echo '<input type="submit" name="Gestionar" value="' . $confirmar_borrado . '" />';
             }; 
?>
&nbsp;|&nbsp;
                <input type="submit" name="Gestionar" value="<?php echo $ver_tabla; ?>" />
                <input type="submit" name="Gestionar" value="<?php echo $limpiar; ?>" />


</p></td>
<td></td>
</tr>

</table>
<!-- Fin de tabla de opciones -->

</form>
<?php
             
             
/************************************
*
* Mensaje de error
*
************************************/

             if ($_POST['Gestionar'] == $grabar_tabla && $errores > 0) {
                     $n_mensaje = 4;
             };

/************************************
*
* Mensaje de confirmación de borrado
*
************************************/

             if ($_POST['Gestionar'] == $borrar_tabla && $errores == 0) {
                     $n_mensaje = 5;
             };


/*************************************
*
*  Borrar una fila
*
**************************************/
             if ($_POST['Gestionar'] == $confirmar_borrado && $errores == 0) {
                   author_data_borrar($autores);
                   $n_mensaje = 6;
                   $_POST['fecha_nac'] = '1965';
                   $_POST['lugar_nac'] = '';
                   $_POST['sitio_web'] = '';
                   $_POST['ficha'] = '';
                   $_POST['confirmar'] = 'n';
             };


             
/************************************
*
* Actualiza la tabla
*
*************************************/

             if ($_POST['Gestionar'] == $grabar_tabla && $errores == 0) {
               //Obtener nombre y apellidos
               $consulta_select = "SELECT usmeta.user_id, usmeta.meta_key, usmeta.meta_value as nombre, usmetaa.meta_value as apellido";
     $consulta_from = " FROM $wpdb->users as usuarios, $wpdb->usermeta as usmeta,  $wpdb->usermeta as usmetaa";
     $consulta_where = " WHERE usuarios.user_login = '$autores' AND usuarios.ID = usmeta.user_id AND usuarios.ID = usmetaa.user_id  AND usmeta.meta_key = 'first_name' AND usmetaa.meta_key ='last_name'";
      $consulta = $consulta_select . $consulta_from . $consulta_where;
      $nombre_ap = $wpdb->get_results($consulta);
      $nombre = $nombre_ap[0]->nombre;
      $apellidos = $nombre_ap[0]->apellido;
      //echo '<br />Nombre: ' . $nombre . ' ' . $apellidos;
      author_data_actualizar(0, $autores, $nombre, $apellidos, $nombre_vis, $fecha_nac, $lugar_nac, $sitio_web, $ficha);
      
             }

/*************************************
*
* Visualiza mensaje si lo hay
*
**************************************/
      if ($n_mensaje != 0) {
	
            echo '<div id="message" class="updated fade"><p>';
            echo $mensajes[$n_mensaje];
            echo '.</p></div>';}



/*********************************
*
* Compatabilidad html
*
**********************************/
function author_data_formatear($ficha) {
       $longitud = strlen($ficha);
       $ficha_aux = array ();
       $j=0;
       for ($i=0;$i<=$longitud;$i++) {
          if (substr($ficha,$i,1) == '"') {
              $ficha_aux[$j] = '&';
              $j++;
              $ficha_aux[$j] = 'q';
              $j++;
              $ficha_aux[$j] = 'u';
              $j++;
              $ficha_aux[$j] = 'o';
              $j++;
              $ficha_aux[$j] = 't';
              $j++;
              $ficha_aux[$j] = ';';
              $j++;
          }
          else {
              $ficha_aux[$j] = substr($ficha,$i,1);
               $j++;
          }
       }
       $ficha = implode('', $ficha_aux);
       return $ficha;

}


function author_data_borrar($user_login) {
    global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $borrar = $wpdb->query("DELETE FROM " . $tabla_ficha_autor . "  WHERE user_login = '$user_login'");
}

function obtener_ultimo_ID() {
    global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $consulta = "SELECT ID FROM " . $tabla_ficha_autor . " ORDER BY ID DESC";
    $leer = $wpdb->get_results($consulta);
    if ($leer) { $ultimo_ID = $leer[0]->ID;}
    else {$ultimo_ID = 0; };
    return $ultimo_ID;
}

function ver_si_existe($user_login) {
    global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $consulta = "SELECT user_login FROM " . $tabla_ficha_autor . " WHERE user_login ='$user_login'";
    //echo '<br /> Ver si existe en tabla: ' . $consulta;
    return $wpdb->get_var($consulta);
}

function leer_para_visualizar($user_login) {
     global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $consulta = "SELECT * FROM " . $tabla_ficha_autor . " WHERE user_login ='$user_login'";
    $devuelve = $wpdb->get_results($consulta);
    //echo '<br /> Fila actualizada: ' . $devuelve[0]->ID . ' ' . $devuelve[0]->user_login . ' ' . $devuelve[0]->fecha_nac . ' ' . $devuelve[0]->lugar_nac . ' ' . $devuelve[0]->sitio_web . ' ' . $devuelve[0]->ficha;
}



function author_data_actualizar($ID_actualizar, $user_login_actualizar, $nombre_actualizar, $apellidos_actualizar, $nombre_vis_actualizar, $fecha_nac_actualizar, $lugar_nac_actualizar, $sitio_web_actualizar, $ficha_actualizar) {
    global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $fila = ver_si_existe($user_login_actualizar);
    		if (!$fila){
                       //Obtener siguiente id
                       $ultimo_ID = obtener_ultimo_ID();
                       //Echo '<br />Ultimo ID en la tabla ' . $ultimo_ID;
                       $ID_actualizar = $ultimo_ID + 1;

			$insertar = $wpdb->query("INSERT INTO " . $tabla_ficha_autor . "  VALUES ($ID_actualizar, '$user_login_actualizar', '$nombre_actualizar', '$apellidos_actualizar', '$nombre_vis_actualizar', '$fecha_nac_actualizar', '$lugar_nac_actualizar', '$sitio_web_actualizar', '$ficha_actualizar')") or die("Failed Query of " . $insertar);
//echo '<br /> Insertar: ' . $insertar;
		}	
                else {

                        $actualizar = $wpdb->query("UPDATE " . $tabla_ficha_autor . " SET fecha_nac = '$fecha_nac_actualizar', lugar_nac = '$lugar_nac_actualizar', sitio_web = '$sitio_web_actualizar', ficha = '$ficha_actualizar'  WHERE user_login = '$user_login_actualizar'");
/*
$actualizar = "UPDATE " . $tabla_ficha_autor . " SET fecha_nac = '$fecha_nac_actualizar', lugar_nac = '$lugar_nac_actualizar', sitio_web = '$sitio_web_actualizar', ficha = '$ficha_actualizar'  WHERE user_login = '$user_login_actualizar'";
echo '<br/>' . $actualizar;
mysql_db_query(drimar_com, $actualizar) or die("Failed Query of " . $actualizar);
*/
		}
                leer_para_visualizar($user_login_actualizar);
                
}



?>