<?php
/*
Plugin Name: Author data
Author: Rodolfo Martínez
Author URI: http://www.escritoenelagua.com/
Version: 3.2
Description: Creates and manages a table with several data from the authors of a blog. It permits to list this data in various formats
Plugin URI: 

*/


//Parametrizar según idioma
$currentLocale = get_locale();
if(!empty($currentLocale)) {
$moFile = dirname(__FILE__) . "/languages/author-data-" . $currentLocale . ".mo";
if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('fichas', $moFile);
}

global $wpdb;
$tabla_ficha_autor = $wpdb->prefix . "ficha_autor";




//Insertar código en los posts para ver la lista de autores
add_shortcode('autores-ficha', 'autor_data_shortcode');
function autor_data_shortcode($atts) {
      return author_data();
}

//Insertar código en los posts para ver la lista de links
add_shortcode('autores-links', 'autor_links_shortcode');
function autor_links_shortcode($atts) {
      return author_links();
}

add_action('admin_menu', 'author_data_menu');

function author_data_menu() { 
    add_menu_page(__('Gestión', 'fichas'), __('Autores', 'citas'), 10 , 'author-data/author-data-manage.php', '', plugins_url('author-data/pluma.jpg'));
 		add_submenu_page('author-data/author-data-manage.php', __('Autores', 'fichas'), __('Gestión', 'fichas'), 10, 'author-data/author-data-manage.php');
		add_submenu_page('author-data/author-data-manage.php', __('Autores', 'fichas'), __('Configuración', 'fichas'), 10, 'author-data/author-data.php', 'author_data_settings');		

}




function author_data_settings() {
   if ($_POST) {
                if($_POST["vnombre"] == "")
			$_POST["vnombre"] = "si";
                if($_POST["vfecha"] == "")
			$_POST["vfecha"] = "si";
                if($_POST["vlugar"] == "")
			$_POST["vlugar"] = "si";
                if($_POST["vweb"] == "")
			$_POST["vweb"] = "si";
                if($_POST["vficha"] == "")
			$_POST["vficha"] = "si";
                if($_POST["nomtam"] == "")
			$_POST["nomtam"] = "+1";
                if($_POST["nomcol"] == "")
                        $_POST["nomcol"] = "";
                if($_POST["nomestilo"] == "")
                        $_POST["nomestilo"] = "";
                if($_POST["parrafo"] == "")
                        $_POST["parrafo"] = "justify";
                if($_POST["lista"] == "")
                        $_POST["lista"] = "ul";
		update_option('vnombre', $_POST['vnombre']);
		update_option('vfecha', $_POST['vfecha']);
		update_option('vlugar', $_POST['vlugar']);
		update_option('vweb', $_POST['vweb']);
		update_option('vficha', $_POST['vficha']);
		update_option('nomtam', $_POST['nomtam']);
		update_option('nomestilo', $_POST['nomestilo']);
		update_option('nomcol', $_POST['nomcol']);
		update_option('parrafo', $_POST['parrafo']);
		update_option('lista', $_POST['lista']);
	}
	// Get options
	$vnombre = get_option('vnombre');
	$vfecha = get_option('vfecha');
	$vlugar = get_option('vlugar');
	$vweb = get_option('vweb');
	$vficha = get_option('vficha');
	$nomtam = get_option('nomtam');
	$nomestilo = get_option('nomestilo');
	$nomcol = get_option('nomcol');
	$parrafo = get_option('parrafo');
	$lista = get_option('lista');

?>
<div class="wrap">
<h2><?php  _e('Datos de los autores', 'fichas'); ?></h2>

<?php
//Mensaje de opciones actualizadas
	if ($_POST) {
echo '<div id="message" class="updated fade"><p>';
_e("Opciones actualizadas", 'fichas');
echo '.</p></div>';
};

?>

<form target="_self" method="post">
<table width=70%>
<tr>
<td valign=top></td>
<td valign=top><h3><?php _e('Datos a visualizar', 'fichas'); ?></h3></td>
<td width=3% valign=top></td>
<td valign=top><h3><?php _e("Valores actuales", 'fichas'); ?></h3></td>
</tr>
<tr>
<td width=20% valign=top><strong><?php _e("Nombre", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="vnombre" type="hidden" style="width:100%;" value="<?php echo $vnombre; ?>" />
<input type="radio" <?php if ($vnombre == 'si') echo ' checked'; ?> name="vnombre" value="si"><?php _e('Sí', 'fichas'); ?><br/>
<input type="radio" <?php if ($vnombre == 'no') echo ' checked'; ?> name="vnombre" value="no"><?php _e('No', 'fichas'); ?>
</td>
<td width=3%></td> 
<td width=20%><?php
 if ($vnombre == "si") _e('Sí', 'fichas');
 if ($vnombre == "no") _e('No', 'fichas');
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
<td width=20% valign=top><strong><?php _e("Fecha de nacimiento", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="vfecha" type="hidden" style="width:100%;" value="<?php echo $vfecha; ?>" />
<input type="radio" <?php if ($vfecha == 'si') echo ' checked'; ?> name="vfecha" value="si"><?php _e('Sí', 'fichas'); ?><br/>
<input type="radio" <?php if ($vfecha == 'no') echo ' checked'; ?> name="vfecha" value="no"><?php _e('No', 'fichas'); ?>
</td>
<td width=3%></td> 
<td width=20%><?php
 if ($vfecha == "si") _e('Sí', 'fichas');
 if ($vfecha == "no") _e('No', 'fichas');
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
<td width=20% valign=top><strong><?php _e("Lugar de nacimiento", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="vlugar" type="hidden" style="width:100%;" value="<?php echo $vvlugar; ?>" />
<input type="radio" <?php if ($vlugar == 'si') echo ' checked'; ?> name="vlugar" value="si"><?php _e('Sí', 'fichas'); ?><br/>
<input type="radio" <?php if ($vlugar == 'no') echo ' checked'; ?> name="vlugar" value="no"><?php _e('No', 'fichas'); ?>
</td>
<td width=3%></td> 
<td width=20%><?php
 if ($vlugar == "si") _e('Sí', 'fichas');
 if ($vlugar == "no") _e('No', 'fichas');
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
<td width=20% valign=top><strong><?php _e("Sitio web", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="vweb" type="hidden" style="width:100%;" value="<?php echo $vweb; ?>" />
<input type="radio" <?php if ($vweb == 'si') echo ' checked'; ?> name="vweb" value="si"><?php _e('Sí', 'fichas'); ?><br/>
<input type="radio" <?php if ($vweb == 'no') echo ' checked'; ?> name="vweb" value="no"><?php _e('No', 'fichas'); ?>
</td>
<td width=3%></td> 
<td width=20%><?php
 if ($vweb == "si") _e('Sí', 'fichas');
 if ($vweb == "no") _e('No', 'fichas');
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td width=20% valign=top><strong><?php _e("Datos biográficos", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="vficha" type="hidden" style="width:100%;" value="<?php echo $vficha; ?>" />
<input type="radio" <?php if ($vficha == 'si') echo ' checked'; ?> name="vficha" value="si"><?php _e('Sí', 'fichas'); ?><br/>
<input type="radio" <?php if ($vficha == 'no') echo ' checked'; ?> name="vficha" value="no"><?php _e('No', 'fichas'); ?>
</td>
<td width=3%></td> 
<td width=20%><?php
 if ($vficha == "si") _e('Sí', 'fichas');
 if ($vficha == "no") _e('No', 'fichas');
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td valign=top></td>
<td valign=top><h3><?php _e('Formato', 'fichas'); ?></h3></td>
<td width=3% valign=top></td>
<td valign=top></td>
</tr>

<tr>
<td width=20% valign=top><strong><?php _e("Tamaño nombre autor", 'fichas'); ?>: </strong></td>
<td valign=top>
<input name="nomtam" type="hidden" style="width:100%;" value="<?php echo $nomtam; ?>" />
<select name="nomtam">
<?php
$i=8;
while ($i<=30)
{ 
?>
    <option <?php if ($nomtam==$i) echo 'selected'; ?> value="<?php echo $i; ?>"><?php echo $i; ?> </option>
<?php 
$i++;
}
?>
                    </select>
</td>
<td width=3%></td> 
<td width=20%><?php echo $nomtam; ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td width=20$><strong><?php _e("Color nombre autor", 'fichas'); ?>: </strong></td>
<td><input name=" nomcol" type="hidden" style="width:100%;" value="<?php echo $nomcol; ?>" />
<select name="nomcol">
<?php
$color1 = array ("", "#000000", "#696969", "#8B0000", "#FF4500", "#006400", "#FFFF00", "#FFFFFF");
$color2 = array (__("Neutro", 'fichas'), __("Negro", 'fichas'), __("Gris", 'fichas'), __("Rojo oscuro", 'fichas'), __("Naranja", 'fichas'), __("Verde", 'fichas'), __("Amarillo", 'fichas'), __("Blanco", 'fichas'));
$i = 0;
while ($i < 8)
{
?>
                    	<option <?php if ($nomcol==$color1[$i]) echo 'selected'; ?> value="<?php echo $color1[$i]; ?>"><?php echo $color2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select>
</td>
<td width=3%></td>
<td width=20%>
<span style="background-color: <?php echo $nomcol; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td width=20%><strong><?php _e("Estilo nombre autor", 'fichas'); ?>: </strong></td>
<td><input name="nomestilo" type="hidden" style="width:100%;" value="<?php echo $nomestilo; ?>" />
<select name="nomestilo">
<?php
$tipo1 = array ("", "em", "strong");
$tipo2 = array (__("Normal", 'fichas'), __("Cursiva", 'fichas'), __("Negrita", 'fichas'));
$i = 0;
while ($i < 3)
{
?>
                    	<option <?php if ($nomestilo==$tipo1[$i]) echo 'selected'; ?> value="<?php echo $tipo1[$i]; ?>"><?php echo $tipo2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select> 
</td>
<td width=3%></td>
<td width=20%><?php
$i = 0;
while ($i < 3)
{
 if ($nomestilo==$tipo1[$i]) echo $tipo2[$i];
 $i++;
}
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td width=20%><strong><?php _e("Alineación", 'fichas'); ?>: </strong></td>
<td><input name="parrafo" type="hidden" style="width:100%;" value="<?php echo $parrafo; ?>" />
<select name="parrafo">
<?php
$parr1 = array ("left", "right", "center", "justify");
$parr2 = array (__("Izquierda", 'fichas'), __("Derecha", 'fichas'), __("Centro", 'fichas'), __("Justificada", 'fichas'));
$i = 0;
while ($i < 4)
{
?>
                    	<option <?php if ($parrafo==$parr1[$i]) echo 'selected'; ?> value="<?php echo $parr1[$i]; ?>"><?php echo $parr2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select> 
</td>
<td width=3%></td>
<td width=20%><?php
$i = 0;
while ($i < 4)
{
 if ($parrafo==$parr1[$i]) echo $parr2[$i];
 $i++;
}
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tr>
<td width=20%><strong><?php _e("Visualizar como<br/>(sólo para links)", 'fichas'); ?>: </strong></td>
<td><input name="lista" type="hidden" style="width:100%;" value="<?php echo $lista; ?>" />
<select name="lista">
<?php
$lista1 = array ("ul", "ol", "table");
$lista2 = array (__("Lista", 'fichas'), __("Lista numerada", 'fichas'), __("Tabla", 'fichas'));
$i = 0;
while ($i < 3)
{
?>
                    	<option <?php if ($lista==$lista1[$i]) echo 'selected'; ?> value="<?php echo $lista1[$i]; ?>"><?php echo $lista2[$i]; ?></option>
<?php
$i++;
}
?>
                    </select> 
</td>
<td width=3%></td>
<td width=20%><?php
$i = 0;
while ($i < 3)
{
 if ($lista==$lista1[$i]) echo $lista2[$i];
 $i++;
}
 ?></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>



</table>

<p class="submit">
		<input name="submitted" type="hidden" value="yes" />
		<input type="submit" name="Submit" value="<?php _e("Actualizar opciones", 'fichas'); ?>" />
</p>

</form>
<?php 


}


function install_author_data(){
   global $wpdb;
   $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
   if($wpdb->get_var("show tables like '$tabla_ficha_autor'") != $tabla_ficha_autor) {

   $sql = "CREATE TABLE " . $tabla_ficha_autor . " (
	      ID int(5) unsigned NOT NULL auto_increment,
              user_login varchar(60) NOT NULL default '',
	      nombre varchar(30) NOT NULL default '',
	      apellidos varchar(40) NOT NULL default '',
              nombre_vis varchar (70) NOT NULL default '',
              fecha_nac varchar(4) NOT NULL default '',
              lugar_nac varchar(40) NOT NULL default '',
              sitio_web varchar(100) NOT NULL default '',
              ficha longtext NOT NULL default '',
	      PRIMARY KEY  (ID, user_login) 		); 		";
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
   }
}



$vnombre = get_option('vnombre');
$vfecha = get_option('vfecha');
$vlugar = get_option('vlugar');
$vweb = get_option('vweb');
$vficha = get_option('vficha');
$nomtam = get_option('nomtam');
$nomestilo = get_option('nomestilo');
$nomcol = get_option('nomcol');
$parrafo = get_option('parrafo');
$lista = get_option('lista');

function author_data() {
    global $vnombre;
    global $vfecha;
    global $vlugar;
    global $vweb;
    global $vficha;
    global $nomtam;
    global $nomestilo;
    global $nomcol;
    global $parrafo;
    global $lista;
    global $wpdb;
    $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";
    $consulta = "SELECT ID, user_login, nombre, apellidos, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " ORDER BY apellidos ASC, nombre ASC";
    $listado = $wpdb->get_results($consulta);
    if ($listado) {

       if ($nomestilo == 'strong') {
           $peso = 'bold';
           $tipo = 'normal';
       }
       else {
            $peso = 'normal';
            $tipo = $nomestilo;
       }
       echo '<STYLE type="text/css">';
       echo '#nombre{font-style:' . $tipo .';font-style:' . $tipo .';font-weight:'. $peso .';';
       if ($nomcolor != "") echo 'font-color:'. $nomcolor .';';
       echo '}';
       echo '#datos{font-size: 12px;text-align:' . $parrafo . ';font-style:normal;font-weight:normal;}';
       echo '</STYLE>';

       foreach($listado as $fila) {
            $ID = $fila->ID;
            $usuario = $fila->user_login;
            $nombre = $fila->nombre;
            $apellidos = $fila->apellidos;
            $fecha_nac = $fila->fecha_nac;
            $lugar_nac = $fila->lugar_nac;
            $sitio_web = $fila->sitio_web;
            $ficha = $fila->ficha;
            //Comprobar si tiene posts publicados
            if ($usuario != 'redal') {
                $consulta = "SELECT 1 FROM $wpdb->users WHERE user_login = '$usuario' AND ID IN (SELECT post_author FROM $wpdb->posts WHERE post_status = 'publish')";
                $publicado = $wpdb->get_results($consulta); 
            }
            else $publicado[0] = '1';
            if ($publicado && $ficha != '') {
               //Formatear nombre
               if ($vnombre == "si") {
                  $linea_nombre = '<div id=nombre>'. $nombre . ' ' . $apellidos . '</div>'; 
               }
               else {
                  $linea_nombre = '';
               }
               //Formatear fecha y lugar de nacimiento
               $linea_fecha = '<div id=datos>';
               if ($vlugar == "si") {
                     $linea_fecha = $linea_fecha . $lugar_nac;
               }
               if ($vfecha == "si") {
                  if ($lugar_nac != '') {
                     $linea_fecha = $linea_fecha . ', ' . $fecha_nac;
                  }
                  else {
                     $linea_fecha = $linea_fecha . $fecha_nac;
                  }
               }
               $linea_fecha =  $linea_fecha . '</div>';
               //Formatear página web
               if ($vweb == "si" && $sitio_web != "") {
                   $linea_web = '<div id=datos><a href="' . $sitio_web . '" target=_blank>' . __("Página personal", "fichas") .'</a></div>';
               }
               else {
                   $linea_web = '';
               }
               //Formatear ficha biográfica
               if ($vficha == "si") {
                   $linea_ficha = '<div id=datos>' . $ficha . '</div>';
               }
               else {
                   $linea_ficha = '';
               }
               //Visualizar
               echo $linea_nombre;
               echo $linea_fecha;
               echo $linea_web;
               echo $linea_ficha;
               echo '<p> </p>';
            }
       }
    };
}

function author_links() {
    global $lista;
    global $nomtam;
    global $nomestilo;
    global $nomcol;
    $alfabeto = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z');
   global $wpdb;
   $tabla_ficha_autor = $wpdb->prefix . "ficha_autor";

   echo '<h2>' . __('Los autores', 'fichas')  . '</h2>';

   echo '<' . $lista . '>';
   
   foreach ($alfabeto as $alfa) {
		$letra = $alfa;
		$consulta = "SELECT ID, user_login, nombre, apellidos, fecha_nac, lugar_nac, sitio_web, ficha FROM " . $tabla_ficha_autor . " WHERE substr(apellidos, 1, 1) = '" . $letra . "' ORDER BY apellidos ASC, nombre ASC";
		$listado = $wpdb->get_results($consulta);
		$primero = 's';
                $ficha = $listado[0]->ficha;
		$cabeceras = 'n';
		if ($listado) {
				foreach($listado as $fila) {
						$ID = $fila->ID;
						$usuario = $fila->user_login;
						$nombre = $fila->nombre;
						$apellidos = $fila->apellidos;
						$sitio_web = $fila->sitio_web;
						//Comprobar si tiene posts publicados
                                                if ($usuario != 'redal') {
						   $consulta = "SELECT 1 FROM $wpdb->users WHERE user_login = '$usuario' AND ID IN (SELECT post_author FROM $wpdb->posts WHERE post_status = 'publish')";
						   $publicado = $wpdb->get_results($consulta); 
                                                }
                                                else $publicado[0] = '1';
						if (($publicado) && (substr($sitio_web, 8, 1) != ' ')) {
							if ($primero == 's') {
                                                           if ($lista != 'table') {
								echo '<li><h2>' . $letra . '</h2><'. $lista . '>'; 
                                                            }
                                                            else {
                                                                echo '<tr><td colspan=2><h2>' . $letra . '</h2></td></tr>';
                                                            }
							    $primero = 'n';
							    $cabeceras = 's';

							}
                                                        if ($lista != 'table') {
							   echo '<li><a href="'. $sitio_web . '" target=_blank>'. $nombre . ' ' . $apellidos . '</a></li>';
                                                        }
                                                        else {
							   echo '<tr><td width=10%></td><td><a href="'. $sitio_web . '" target=_blank>'. $nombre . ' ' . $apellidos . '</a></td></tr>';
                                                        }
						}
				}
		}
		if ($cabeceras == 's' && $lista != 'table') {
						echo '</'. $lista . '></li>';
		}
   }
   echo '</' . $lista . '>';
}


//add_action('template_redirect', 'author_data_actualizar');
register_activation_hook( __FILE__, 'install_author_data' );

?>