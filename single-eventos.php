<?php
/**
 * O template para mostrar um evento criado através do plugin unipampa-eventos.
 *
 * @package Unipampa 2020
 */

get_header(); ?>

	<section id="primary" class="content-area col-sm-12 col-lg-9">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-thumbnail post-full-thumbnail">
					<?php the_post_thumbnail(); ?>
				</div>
				<header class="entry-header">
					<?php
					if ( is_single() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif;

					if ( 'eventos' === get_post_type() ) : ?>
					<div class="entry-meta small posted-on">
						<?php 
						$terms = get_the_terms( get_the_ID(), 'categoria-do-evento' );
						$terms_cont = 0;
						if(!empty($terms)) {
							foreach ( $terms as $term ) {
							    $term_link = get_term_link( $term );
							    if ( is_wp_error( $term_link ) ) {
							        continue;
							    }
							    echo $terms_cont == 0 ? "Publicado na categoria: " : ", ";
							    echo '<a href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
							    $terms_cont++;
							} 
						}
						?>
					</div><!-- .entry-meta -->
					<?php
					endif; ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<span class="font-weight-bold d-block">Descrição: </span>
					<?php the_content(); ?>
					
					<p><span class="font-weight-bold">Organização: </span>
					<?php echo get_post_meta(get_the_ID(),'evento_organizacao',true); ?></p>

					<p><span class="font-weight-bold">Data: </span>
					<?php 
						$data_inicio = strtotime(get_post_meta(get_the_ID(),'evento_data_inicio',true));
						$data_fim = strtotime(get_post_meta(get_the_ID(),'evento_data_fim',true));
						if($data_inicio >= $data_fim) {
							echo date("d/m/Y",$data_inicio);
						}
						else {
							echo date("d/m/Y",$data_inicio)." até ".date("d/m/Y",$data_fim);
						}
					?>
					<br>
					<span class="font-weight-bold">Horário: </span>
					<?php 
						$hora_inicio = get_post_meta(get_the_ID(),'evento_hora_inicio',true);
						$hora_fim = get_post_meta(get_the_ID(),'evento_hora_fim',true);
						$dia_inteiro = get_post_meta(get_the_ID(),'evento_dia_inteiro',true);
						if($dia_inteiro == "true") {
							echo "O dia inteiro.";
						}
						else if($hora_inicio >= $hora_fim) {
							echo $hora_inicio;
						}
						else {
							echo "das ".$hora_inicio." às ".$hora_fim;
						}
					?></p>

					<p><span class="font-weight-bold">Local: </span>
					<?php 
						$local_tipo = get_post_meta(get_the_ID(),'evento_local',true);
						$endereco_ou_url = get_post_meta(get_the_ID(),'evento_endereco',true);
						$nome_local = get_post_meta(get_the_ID(),'evento_nome_local',true);
						if($local_tipo == "Virtual") {
							echo "evento virtual";
							?>
								<br><span class="font-weight-bold">URL para acesso: <a href="<?php echo $endereco_ou_url; ?>" target="_blank"><?php echo $nome_local; ?></a></span>
							<?php
						}
						else if($local_tipo == "Físico") {
							echo $nome_local;
							?>
								<br><span class="font-weight-bold">Endereço: </span>
							<?php
							echo $endereco_ou_url;
						}
					?></p>


					<?php
						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wp-bootstrap-starter' ),
							'after'  => '</div>',
						) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php wp_bootstrap_starter_entry_footer(); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->

			<?php

			    the_post_navigation( array('prev_text'=>'Anterior', 'next_text'=>'Próximo') );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();
get_footer();
