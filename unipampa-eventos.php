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


/* Criar o tipo de dados Eventos */
function criar_tipo_eventos() {

// Set UI labels for Custom Post Type
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

// Set other options for Custom Post Type

	$args = array(
		'label'               => __( 'eventos', 'twentytwenty' ),
		'description'         => __( 'Eventos para divulgação no portal da Unipampa', 'twentytwenty' ),
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

    // Registra o tipo de dado personalizado
	register_post_type( 'eventos', $args );
	register_taxonomy( 'categoria-do-evento', 'eventos', array('hierarchical'=>true) );

}
// Adiciona a ação na inicialização do tema
add_action( 'init', 'criar_tipo_eventos' );


function evento_create_meta_box(){
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
	
	<label for="evento_organizacao">Organização: </label>
	<input id="evento_organizacao" type="text" required name="evento_organizacao" value="<?php echo $evento_organizacao; ?>" style="width:80%;margin:10px;" />

	<br>
	<label for="evento_data_inicio">Data: </label>
	<input id="evento_data_inicio" type="date" required name="evento_data_inicio" value="<?php echo $evento_data_inicio; ?>" style="width:200px;margin:10px;" />
	<label for="evento_data_fim"> até </label>
	<input id="evento_data_fim" type="date" required name="evento_data_fim" value="<?php echo $evento_data_fim; ?>" style="width:200px;margin:10px;" />

	<br>
	<label for="evento_hora_inicio">Hora: </label>
	<input id="evento_hora_inicio" type="time" required name="evento_hora_inicio" value="<?php echo $evento_hora_inicio; ?>" style="width:200px;margin:10px;" />
	<label for="evento_hora_fim"> até </label>
	<input id="evento_hora_fim" type="time" required name="evento_hora_fim" value="<?php echo $evento_hora_fim; ?>" style="width:200px;margin:10px;" />
	<input id="evento_dia_inteiro" type="checkbox" name="evento_dia_inteiro" value="true" <?php echo $evento_dia_inteiro=="true" ? "checked":''; ?> />
	<label for="evento_dia_inteiro"> dia inteiro? </label>

	<br>
	<label for="evento_local">Localização: </label>
	<input id="evento_local_virtual" type="radio" required name="evento_local" value="Virtual" <?php echo $evento_local=="Virtual" ? "checked":''; ?> style="margin:10px 0 10px 10px;" />
	<label for="evento_local_virtual">Virtual</label>
	<input id="evento_local_fisico" type="radio" required name="evento_local" value="Físico" <?php echo $evento_local=="Físico"? "checked":''; ?> style="margin:10px 0 10px 10px;" />
	<label for="evento_local_fisico">Física</label>

	<br>
	<label for="evento_endereco">Endereço/URL: </label>
	<input id="evento_endereco" type="text" required name="evento_endereco" value="<?php echo $evento_endereco; ?>" style="width:60%;margin:10px;" />

	<br>
	<label for="evento_nome_local">Nome do local/texto do link: </label>
	<input id="evento_nome_local" type="text" required name="evento_nome_local" value="<?php echo $evento_nome_local; ?>" style="width:60%;margin:10px;" />

	<?php
}  
function save_evento_info(){
	if(empty($_POST)) return;
	if(isset($_POST["evento_organizacao"])) {
		global $post;
		update_post_meta($post->ID, "evento_organizacao", $_POST["evento_organizacao"]);
		update_post_meta($post->ID, "evento_data_inicio", $_POST["evento_data_inicio"]);
		update_post_meta($post->ID, "evento_data_fim", $_POST["evento_data_fim"]);
		update_post_meta($post->ID, "evento_hora_inicio", $_POST["evento_hora_inicio"]);
		update_post_meta($post->ID, "evento_hora_fim", $_POST["evento_hora_fim"]);
		$is_dia_inteiro = isset($_POST["evento_dia_inteiro"])?"true":"false";
		update_post_meta($post->ID, "evento_dia_inteiro", $is_dia_inteiro);
		update_post_meta($post->ID, "evento_local", $_POST["evento_local"]);
		update_post_meta($post->ID, "evento_endereco", $_POST["evento_endereco"]);
		update_post_meta($post->ID, "evento_nome_local", $_POST["evento_nome_local"]);
	}
}
add_action("admin_init", "evento_create_meta_box");
add_action('save_post', 'save_evento_info');

/* Mostra os eventos na página incial */
function main_query_eventos( $query ) {
  if ( !is_admin() && $query->is_main_query() && $query->is_home() ) {
    // Mostra apenas posts do tipo evento
    $query->set( 'post_type', 'eventos' );
    
    // Ordena os eventos por data de início, a mais próxima primeiro
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

/* Adiciona o template single-eventos para exibir um evento */
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