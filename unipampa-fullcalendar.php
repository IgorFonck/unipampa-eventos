<?php

/*
* Criar tipo Agenda.
*/
function criar_tipo_agenda() {

	// Labels
	$labels = array(
		'name'                => _x( 'Agendas', 'Post Type General Name', 'twentytwenty' ),
		'singular_name'       => _x( 'Agenda', 'Post Type Singular Name', 'twentytwenty' ),
		'menu_name'           => __( 'Agendas', 'twentytwenty' ),
		'parent_item_colon'   => __( 'Agenda ascendente', 'twentytwenty' ),
		'all_items'           => __( 'Todas as agendas', 'twentytwenty' ),
		'view_item'           => __( 'Ver agenda', 'twentytwenty' ),
		'add_new_item'        => __( 'Adicionar nova agenda', 'twentytwenty' ),
		'add_new'             => __( 'Adicionar nova', 'twentytwenty' ),
		'edit_item'           => __( 'Editar agenda', 'twentytwenty' ),
		'update_item'         => __( 'Atualizar agenda', 'twentytwenty' ),
		'search_items'        => __( 'Pesquisar agendas', 'twentytwenty' ),
		'not_found'           => __( 'Não encontrada', 'twentytwenty' ),
		'not_found_in_trash'  => __( 'Não encontrada na Lixeira', 'twentytwenty' ),
	);

	// Opções
	$args = array(
		'label'               => __( 'agenda', 'twentytwenty' ),
		'description'         => __( 'Agenda do Fullcalendar', 'twentytwenty' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'post-formats' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => false,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => false,
        'menu_position'       => 5,
        'menu_icon'			  => 'dashicons-calendar-alt',
        'can_export'          => false,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' 		  => false,

    );

    // Registra o tipo de dado personalizado e a taxonomia
	register_post_type( 'agenda', $args );

}
// Adiciona a ação na inicialização do tema
add_action( 'init', 'criar_tipo_agenda' );

/* 
* Criar página Agenda.
*/
function agenda_pagina() {
	if (!($page = get_page_by_title('Agenda','','agenda'))) {
		// Adiciona a página, caso não exista
		$pagina_agenda = array(
		'menu_order'    => 0,
		'comment_status' =>'closed',
		'post_content'  => '<div id="calendar"></div>',
		'post_name'     => 'agenda',
		'post_status'   => 'publish',
		'post_title'    => 'Agenda',
        'post_type'     => 'agenda'
		);
		wp_insert_post( $pagina_agenda );
	} else {
		// Não permite que a página seja alterada
		$pagina_atualizada = array(
			'ID'           => $page->ID,
			'post_content' => '<div id="calendar"></div>',
			'post_status'   => 'publish'
		);
		wp_update_post( $pagina_atualizada );
	}
}
add_action( 'init', 'agenda_pagina' );

/* 
* Adiciona o template single-agenda para exibir a agenda em full-width.
*/
function agenda_custom_template($single) {
    global $post;
    if ( $post->post_type == 'agenda' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . '/single-agenda.php' ) ) {
            return plugin_dir_path( __FILE__ ) . '/single-agenda.php';
        }
    }
    return $single;
}
add_filter('single_template', 'agenda_custom_template');

/* 
* Adiciona Fullcalendar apenas na página de agenda.
*/
function fullcalendar_scripts_queue() {
    if( is_single() && get_post_type() == 'agenda' ) {
        // Estilo 
		wp_enqueue_style( 'fullcalendar-style', plugin_dir_url( __FILE__ ) . 'fullcalendar/main.css' );
		wp_enqueue_style( 'fullcalendar-unipampa-style', plugin_dir_url( __FILE__ ) . 'fullcalendar/unipampa-fullcalendar-style.css' );
        
        // Scripts
        wp_register_script( 'fullcalendar-script', plugin_dir_url( __FILE__ ) . 'fullcalendar/main.js', null, null, true );
        wp_enqueue_script('fullcalendar-script');
        
        wp_register_script( 'fullcalendar-init', plugin_dir_url( __FILE__ ) . 'fullcalendar/unipampa-fullcalendar-init.js', null, null, true );
        $eventos_init = array( 'eventosCriados' => get_eventos_json() );
        wp_localize_script( 'fullcalendar-init', 'eventosInit', $eventos_init ); // Passa o JSON de eventos para o script através do parâmetro eventosInit.eventosCriados
        wp_enqueue_script('fullcalendar-init');
        
        wp_register_script( 'fullcalendar-locale', plugin_dir_url( __FILE__ ) . 'fullcalendar/locales/pt-br.js', null, null, true );
        wp_enqueue_script('fullcalendar-locale');
    }
}
add_action( 'wp_enqueue_scripts', 'fullcalendar_scripts_queue' );

/*
* Formata JSON de eventos criados.
*/
function get_eventos_json() {

	$eventos_array = get_posts( array(
		'post_type' => 'eventos', 
		'numberposts' => -1
		) 
	);

	// Categoria e cor
	$categorias_array = get_terms( array('taxonomy'=>'categoria-do-evento') );
	$colors = array(
		'#9C27B0', //roxo 
		'#EEB500', //amarelo
		'#03A9F4', //azul
		'#FF5722', //laranja
		'#B71C1C', //vermelho
		'#078358', //verde
		'#F20C7B', //rosa
		'#8b5205', //marrom
		'#51555a', //cinza
		'#06198d' //azul escuro
	); 
	$categ_color_match = array();
	$cont_colors = 0;
	foreach ($categorias_array as $categ) :
		$categ_color_match = array_merge($categ_color_match, array("term-".($categ->term_id) => $colors[$cont_colors]));
		$cont_colors++;
	endforeach;

	$count_obj = 0;
	$eventos_fullcalendar = "[";
	foreach($eventos_array as $objeto) {

		$data_inicio = get_post_meta($objeto->ID, 'evento_data_inicio', true);
		$data_fim = get_post_meta($objeto->ID, 'evento_data_fim', true);
		$hora_inicio = get_post_meta($objeto->ID, 'evento_hora_inicio', true);
		$hora_fim = get_post_meta($objeto->ID, 'evento_hora_fim', true);
		$dia_inteiro = get_post_meta($objeto->ID, 'evento_dia_inteiro', true);
		
		if($dia_inteiro == 'true') {
			// Soma um dia
			$data_aux = str_replace('-', '/', $data_fim);
			$data_fim = date('Y-m-d',strtotime($data_aux . "+1 days"));
			
			// Não adiciona horários
			$resultInicio = $data_inicio;
			$resultFim = $data_fim;
		}
		else {
			// Adiciona horários
			$resultInicio = date('Y-m-d H:i:s', strtotime("$data_inicio $hora_inicio"));
			$resultFim = date('Y-m-d H:i:s', strtotime("$data_fim $hora_fim"));
		}

		$evento_url = get_post_permalink($objeto->ID);

		$evento_cat = get_the_terms( $objeto->ID, 'categoria-do-evento' );
		$term_id = $evento_cat[0]->term_id;

		if($count_obj != 0)
			$eventos_fullcalendar .= ',';

		$eventos_fullcalendar .= '{';
		$eventos_fullcalendar .= '"id": "'.$objeto->ID.'", ';
		$eventos_fullcalendar .= '"title": "'.addslashes($objeto->post_title).'", ';
		$eventos_fullcalendar .= '"start": "'.$resultInicio.'", ';
		$eventos_fullcalendar .= '"end": "'.$resultFim.'", ';
		$eventos_fullcalendar .= '"url": "'.$evento_url.'", ';
		$eventos_fullcalendar .= '"color": "'.$categ_color_match['term-'.$term_id].'", ';
		$eventos_fullcalendar .= '"allDay": '.$dia_inteiro.' ';
		$eventos_fullcalendar .= '}';
		$count_obj++;

	}
	$eventos_fullcalendar .= "]";

	echo $eventos_fullcalendar;
	return $eventos_fullcalendar;

}

function get_legenda_cores() {

	// Categoria e cor
	$categorias_array = get_terms( array('taxonomy'=>'categoria-do-evento') );
	$colors = array(
		'#9C27B0', //roxo 
		'#EEB500', //amarelo
		'#03A9F4', //azul
		'#FF5722', //laranja
		'#B71C1C', //vermelho
		'#078358', //verde
		'#F20C7B', //rosa
		'#8b5205', //marrom
		'#51555a', //cinza
		'#06198d' //azul escuro
	); 
	$categ_color_match = array();
	$cont_colors = 0;

	$legenda = "<div class='m-3'><h5>Categorias:</h5>";

	foreach ($categorias_array as $categ) :
		$categ_color_match = array_merge($categ_color_match, array("term-".($categ->term_id) => $colors[$cont_colors]));
		//print_r($categ);
		
		// Imprime
		$legenda .= "<a class='d-block text-decoration-none p-1' href='".get_term_link($categ)."'><span style='color:".$colors[$cont_colors]."'>⬤</span> ".$categ->name."</a>";
		
		$cont_colors++;
	endforeach;

	$legenda .= "</div>";

	return $legenda;

}