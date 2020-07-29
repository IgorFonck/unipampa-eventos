<?php

/*
* Criar tipo Agenda.
*/

/* 
* Criar página Agenda.
*/

/* 
* Adiciona Fullcalendar.
*/
function fullcalendar_scripts_queue() {
	// Estilo 
	wp_enqueue_style( 'fullcalendar-style', plugin_dir_url( __FILE__ ) . 'fullcalendar/main.css' );
	
	// Scripts
	wp_register_script( 'fullcalendar-script', plugin_dir_url( __FILE__ ) . 'fullcalendar/main.js', null, null, true );
	wp_enqueue_script('fullcalendar-script');

	wp_register_script( 'fullcalendar-init', plugin_dir_url( __FILE__ ) . 'fullcalendar/unipampa-init.js', null, null, true );
	$eventos_init = array( 'eventosCriados' => get_eventos_json() );
	wp_localize_script( 'fullcalendar-init', 'eventosInit', $eventos_init ); // Passa o JSON de eventos para o script através do parâmetro eventosInit.eventosCriados
	wp_enqueue_script('fullcalendar-init');
	
	wp_register_script( 'fullcalendar-locale', plugin_dir_url( __FILE__ ) . 'fullcalendar/locales/pt-br.js', null, null, true );
	wp_enqueue_script('fullcalendar-locale');
}
add_action( 'wp_enqueue_scripts', 'fullcalendar_scripts_queue' );

/*
* Formata JSON de eventos criados.
*/
function get_eventos_json() {

	$eventos_array = get_posts( array('post_type'=>'eventos') );
	
	$count_obj = 0;
	$eventos_fullcalendar = "[";
	foreach($eventos_array as $objeto) {

		$data_inicio = get_post_meta($objeto->ID, 'evento_data_inicio', true);
		$data_fim = get_post_meta($objeto->ID, 'evento_data_fim', true);
		$hora_inicio = get_post_meta($objeto->ID, 'evento_hora_inicio', true);
		$hora_fim = get_post_meta($objeto->ID, 'evento_hora_fim', true);
		$dia_inteiro = get_post_meta($objeto->ID, 'evento_dia_inteiro', true);
		// $data_aux = str_replace('-', '/', $data_fim);
		// $fim_exc = date('Y-m-d',strtotime($data_aux . "+1 days"));

		$evento_url = get_post_permalink($objeto->ID);

		$combinedInicio = date('Y-m-d H:i:s', strtotime("$data_inicio $hora_inicio"));
		$combinedFim = date('Y-m-d H:i:s', strtotime("$data_fim $hora_fim"));
		
		if($count_obj != 0)
			$eventos_fullcalendar .= ',';

		$eventos_fullcalendar .= '{';
		$eventos_fullcalendar .= '"id": "'.$objeto->ID.'", ';
		$eventos_fullcalendar .= '"title": "'.$objeto->post_title.'", ';
		$eventos_fullcalendar .= '"start": "'.$combinedInicio.'", ';
		$eventos_fullcalendar .= '"end": "'.$combinedFim.'", ';
		// $eventos_fullcalendar .= '"startTime": "'.$combinedInicio.'", ';
		// $eventos_fullcalendar .= '"endTime": "'.$combinedFim.'", ';
		$eventos_fullcalendar .= '"url": "'.$evento_url.'", ';
		$eventos_fullcalendar .= '"allDay": '.$dia_inteiro.' ';
		$eventos_fullcalendar .= '}';
		$count_obj++;

	}
	$eventos_fullcalendar .= "]";

	//echo $eventos_fullcalendar;
	return $eventos_fullcalendar;

}