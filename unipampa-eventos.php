<?php
/*
Plugin Name: Unipampa Eventos
Plugin URL: http://unipampa.edu.br/
Description: Plugin para adicionar o tipo de dado Eventos e os shortcodes para mostá-los nas páginas.
Author: Equipe de Portais Institucionais (Unipampa)
Author URI: http://unipampa.edu.br/
*/

if (!defined('MYPLUGIN_PLUGIN_NAME'))
    define('MYPLUGIN_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('MYPLUGIN_PLUGIN_URL'))
    define('MYPLUGIN_PLUGIN_URL', WP_PLUGIN_URL . '/' . MYPLUGIN_PLUGIN_NAME);

/*
* Criar o tipo de dados Eventos.
*/
function criar_tipo_eventos() {

	// Labels
	$labels = array(
		'name'                => _x( 'Eventos', 'Post Type General Name', 'twentytwenty' ),
		'singular_name'       => _x( 'Evento', 'Post Type Singular Name', 'twentytwenty' ),
		'menu_name'           => __( 'Eventos', 'twentytwenty' ),
		'parent_item_colon'   => __( 'Evento ascendente', 'twentytwenty' ),
		'all_items'           => __( 'Todos os eventos', 'twentytwenty' ),
		'view_item'           => __( 'Ver evento', 'twentytwenty' ),
		'add_new_item'        => __( 'Adicionar novo evento', 'twentytwenty' ),
		'add_new'             => __( 'Adicionar novo', 'twentytwenty' ),
		'edit_item'           => __( 'Editar evento', 'twentytwenty' ),
		'update_item'         => __( 'Atualizar evento', 'twentytwenty' ),
		'search_items'        => __( 'Pesquisar eventos', 'twentytwenty' ),
		'not_found'           => __( 'Não encontrado', 'twentytwenty' ),
		'not_found_in_trash'  => __( 'Não encontrado na Lixeira', 'twentytwenty' ),
	);

	// Opções
	$args = array(
		'label'               => __( 'eventos', 'twentytwenty' ),
		'description'         => __( 'Eventos publicados no portal da Unipampa.', 'twentytwenty' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'			  => 'dashicons-calendar-alt',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'taxonomies'  		  => array( 'categoria-do-evento' ),
        'capability_type'     => 'post',
        'show_in_rest' 		  => true,

    );

    // Registra o tipo de dado personalizado e a taxonomia
	register_post_type( 'eventos', $args );
	register_taxonomy( 'categoria-do-evento', 'eventos', array('hierarchical'=>true) );

}
// Adiciona a ação na inicialização do tema
add_action( 'init', 'criar_tipo_eventos' );

/*
* Adicionar campos personalizados ao formulário do tipo Evento.
*/
function evento_create_meta_box(){
	// Cria a caixa "Informações do evento"
	add_meta_box("infoEvento-meta", "Informações do evento", "evento_meta_options", "eventos", "normal", "high");
}  
function evento_meta_options(){
	global $post;
	$custom = get_post_custom($post->ID);
	$evento_organizacao = isset($custom["evento_organizacao"][0])?$custom["evento_organizacao"][0]:'';
	$evento_data_inicio = isset($custom["evento_data_inicio"][0])?$custom["evento_data_inicio"][0]:'';
	$evento_data_fim = isset($custom["evento_data_fim"][0])?$custom["evento_data_fim"][0]:'';
	$evento_hora_inicio = isset($custom["evento_hora_inicio"][0])?$custom["evento_hora_inicio"][0]:'';
	$evento_hora_fim = isset($custom["evento_hora_fim"][0])?$custom["evento_hora_fim"][0]:'';
	$evento_dia_inteiro = isset($custom["evento_dia_inteiro"][0])?$custom["evento_dia_inteiro"][0]:'';
	$evento_local = isset($custom["evento_local"][0])?$custom["evento_local"][0]:'';
	$evento_endereco = isset($custom["evento_endereco"][0])?$custom["evento_endereco"][0]:'';
	$evento_nome_local = isset($custom["evento_nome_local"][0])?$custom["evento_nome_local"][0]:'';
	?>
	
	<!-- Campos HTML do formulário -->
	<label for="evento_organizacao" class="label-form">Organização: </label>
	<input id="evento_organizacao" class="input-large-form" type="text" required name="evento_organizacao" value="<?php echo $evento_organizacao; ?>" />

	<br>
	<label for="evento_data_inicio" class="label-form">Data: </label>
		<div class="d-inline-block mw-100-form">
		<input id="evento_data_inicio" class="input-date-form" type="date" required name="evento_data_inicio" value="<?php echo $evento_data_inicio; ?>" />
		<label for="evento_data_fim"> até </label>
		<input id="evento_data_fim" class="input-date-form" type="date" name="evento_data_fim" value="<?php echo $evento_data_fim; ?>" />
	</div>

	<br>
	<label for="evento_hora_inicio" class="label-form">Hora: </label>
	<div class="d-inline-block mw-100-form">
		<input id="evento_hora_inicio" class="input-time-form" type="time" required name="evento_hora_inicio" value="<?php echo $evento_hora_inicio; ?>" />
		<label for="evento_hora_fim"> até </label>
		<input id="evento_hora_fim" class="input-time-form" type="time" name="evento_hora_fim" value="<?php echo $evento_hora_fim; ?>" />
		<div class="d-inline-block">
			<input id="evento_dia_inteiro" class="input-checkbox" type="checkbox" name="evento_dia_inteiro" value="true" <?php echo $evento_dia_inteiro=="true" ? "checked":''; ?> onchange="diaInteiroCheck(this)" />
			<label for="evento_dia_inteiro"> dia inteiro? </label>
		</div>
	</div>

	<br>
	<label for="evento_local" class="label-form">Localização: </label>
	<input id="evento_local_fisico" type="radio" required name="evento_local" value="Físico" <?php echo $evento_local=="Físico"? "checked":''; ?> style="margin:10px 0 10px 10px;" checked onchange="tipoEventoFisicoChanged(this)" />
	<label for="evento_local_fisico">Física</label>
	<input id="evento_local_virtual" type="radio" required name="evento_local" value="Virtual" <?php echo $evento_local=="Virtual" ? "checked":''; ?> style="margin:10px 0 10px 10px;" onchange="tipoEventoVirtualChanged(this)" />
	<label for="evento_local_virtual">Virtual</label>

	<br>
	<label for="evento_nome_local" id="nomeLocalLabel" class="label-form">Nome do local: </label>
	<input id="evento_nome_local" class="input-large-form" type="text" required name="evento_nome_local" value="<?php echo $evento_nome_local; ?>" />
	
	<br>
	<label for="evento_endereco" id="enderecoLocalLabel" class="label-form">Endereço: </label>
	<input id="evento_endereco" class="input-large-form" type="text" required name="evento_endereco" value="<?php echo $evento_endereco; ?>" />

	<!-- Estilo do formulário -->
	<style type="text/css">
		.label-form {
		    width: 110px;
		    display: inline-block;
		    text-align: right;
		}
		.input-large-form {
			width:calc(100% - 134px);
			margin:10px;
		}
		.input-date-form, .input-time-form {
			width:160px;
			margin:10px;
		}
		.input-checkbox {
			margin:10px !important;
		}
		.d-inline-block {
			display: inline-block;
		}
		.mw-100-form {
			max-width:calc(100% - 134px);
		}
		@media (max-width: 1149px) {
			.input-time-form {
				width:95px;
			}
		}
		@media (max-width: 1049px) {
			.input-date-form {
				width:150px;
			}
		}
	</style>

	<!-- Script do formulário -->
	<script type="text/javascript">
		function diaInteiroCheck(checkboxElem) {
		  if (checkboxElem.checked) {
		    document.getElementById("evento_hora_inicio").required = false;
		  } else {
		    document.getElementById("evento_hora_inicio").required = true;
		  }
		}
		function tipoEventoFisicoChanged(radioButtonFisico) {
		  if (radioButtonFisico.checked) {
		  	// Evento físico
		    document.getElementById("nomeLocalLabel").innerHTML = "Nome do local:";
		    document.getElementById("enderecoLocalLabel").innerHTML = "Endereço:";
		  }
		}
		function tipoEventoVirtualChanged(radioButtonVirtual) {
		  if (radioButtonVirtual.checked) {
		  	// Evento virtual
		    document.getElementById("nomeLocalLabel").innerHTML = "Texto do link:";
		    document.getElementById("enderecoLocalLabel").innerHTML = "URL:";
		  }
		}
	</script>

	<?php
}  
function save_evento_info(){
	// Salva os campos personalizados no BD
	if(empty($_POST)) return;
	if(isset($_POST["evento_organizacao"])) {
		global $post;
		update_post_meta($post->ID, "evento_organizacao", $_POST["evento_organizacao"]);
		update_post_meta($post->ID, "evento_data_inicio", $_POST["evento_data_inicio"]);
		
		if($_POST["evento_data_fim"] != '') {
			update_post_meta($post->ID, "evento_data_fim", $_POST["evento_data_fim"]);
		}
		else {
			update_post_meta($post->ID, "evento_data_fim", $_POST["evento_data_inicio"]);
		}
		
		update_post_meta($post->ID, "evento_hora_inicio", $_POST["evento_hora_inicio"]);

		if($_POST["evento_hora_fim"] == '' && $_POST["evento_hora_inicio"] != '')
			update_post_meta($post->ID, "evento_hora_fim", $_POST["evento_hora_inicio"]);
		else
			update_post_meta($post->ID, "evento_hora_fim", $_POST["evento_hora_fim"]);
		
		$is_dia_inteiro = isset($_POST["evento_dia_inteiro"]) ? "true" : "false";
		update_post_meta($post->ID, "evento_dia_inteiro", $is_dia_inteiro);
		
		update_post_meta($post->ID, "evento_local", $_POST["evento_local"]);
		update_post_meta($post->ID, "evento_nome_local", $_POST["evento_nome_local"]);
		update_post_meta($post->ID, "evento_endereco", $_POST["evento_endereco"]);
	}
}
add_action("admin_init", "evento_create_meta_box");
add_action('save_post', 'save_evento_info');

/* 
* Altera a consulta principal para mostrar os eventos na página incial.
*/
function main_query_eventos( $query ) {
  if ( !is_admin() && $query->is_main_query() && $query->is_home() ) {
	
	// Mostra apenas posts do tipo evento
    $query->set( 'post_type', 'eventos' );
    
    // Ordena os eventos por data de início, a mais antiga primeiro
    $query->set('orderby', 'meta_value');	
	$query->set('meta_key', 'evento_data_inicio');
	$query->set('order', 'ASC');

	// Mostra apenas eventos cuja data final é maior que a data atual
	$query->set( 'meta_query', array(
      array(
          'key'     => 'evento_data_fim',
          'value'   => date("Y-m-d"),
          'compare' => '>=',
          'type'    => 'DATE'
      )
  	) );
  }
}
add_action( 'pre_get_posts', 'main_query_eventos' );

/* 
* Adiciona o template single-eventos para exibir um evento.
*/
function envento_custom_template($single) {
    global $post;
    if ( $post->post_type == 'eventos' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . '/single-eventos.php' ) ) {
            return plugin_dir_path( __FILE__ ) . '/single-eventos.php';
        }
    }
    return $single;
}
add_filter('single_template', 'envento_custom_template');

/* 
* Adiciona campos personalizados ao Feed RSS.
*/
function evento_custom_fields_rss() {
	// Organização:
    if(get_post_type() == 'eventos' && $evento_organizacao = get_post_meta(get_the_ID(), 'evento_organizacao', true)) {
        ?> <organizacao><?php echo $evento_organizacao ?></organizacao> <?php
	}
	// Data de início:
    if(get_post_type() == 'eventos' && $evento_data_inicio = get_post_meta(get_the_ID(), 'evento_data_inicio', true)) {
        ?> <data_inicio><?php echo $evento_data_inicio ?></data_inicio> <?php
	}
	// Data final:
    if(get_post_type() == 'eventos' && $evento_data_fim = get_post_meta(get_the_ID(), 'evento_data_fim', true)) {
        ?> <data_fim><?php echo $evento_data_fim ?></data_fim> <?php
	}
	// Hora de início:
    if(get_post_type() == 'eventos' && $evento_hora_inicio = get_post_meta(get_the_ID(), 'evento_hora_inicio', true)) {
        ?> <hora_inicio><?php echo $evento_hora_inicio ?></hora_inicio> <?php
	}
	// Hora final:
    if(get_post_type() == 'eventos' && $evento_hora_fim = get_post_meta(get_the_ID(), 'evento_hora_fim', true)) {
        ?> <hora_fim><?php echo $evento_hora_fim ?></hora_fim> <?php
	}
	// Dia inteiro:
    if(get_post_type() == 'eventos' && $evento_dia_inteiro = get_post_meta(get_the_ID(), 'evento_dia_inteiro', true)) {
        ?> <dia_inteiro><?php echo $evento_dia_inteiro ?></dia_inteiro> <?php
	}
	// Tipo de local:
    if(get_post_type() == 'eventos' && $evento_local = get_post_meta(get_the_ID(), 'evento_local', true)) {
        ?> <tipo_local><?php echo $evento_local ?></tipo_local> <?php
	}
	// Endereço ou URL:
    if(get_post_type() == 'eventos' && $evento_endereco = get_post_meta(get_the_ID(), 'evento_endereco', true)) {
        ?> <endereco_ou_url><?php echo $evento_endereco ?></endereco_ou_url> <?php
	}
	// Nome do local ou texto do link:
    if(get_post_type() == 'eventos' && $evento_nome_local = get_post_meta(get_the_ID(), 'evento_nome_local', true)) {
        ?> <nome_local_ou_texto_link><?php echo $evento_nome_local ?></nome_local_ou_texto_link> <?php
	}

}
add_action('rss2_item', 'evento_custom_fields_rss');

/*
* Posiciona a caixa "Imagem destacada" em posição mais destacada no formulário.
*/
function mover_imagem_meta_box(){
	remove_meta_box( 'postimagediv', 'eventos', 'side' );
	add_meta_box('postimagediv', __('Featured image'), 'post_thumbnail_meta_box', 'eventos', 'normal', 'high');
}
add_action('do_meta_boxes', 'mover_imagem_meta_box');

/*
* Adiciona agenda com o Fullcalendar.
*/
require plugin_dir_path( __FILE__ ) . '/unipampa-fullcalendar.php';