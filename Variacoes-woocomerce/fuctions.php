//Pagina de produtos campos adicionais - Variacao


add_action( 'woocommerce_product_after_variable_attributes', 'rudr_fields', 10, 3 );

function rudr_fields( $loop, $variation_data, $variation ) {

	woocommerce_wp_text_input(
		array(
			'id'            => 'text_embalagem[' . $loop . ']',
			'label'         => 'Embalagem',
			'wrapper_class' => 'form-row',
			'placeholder'   => '',
			'desc_tip'      => 'true',
			'description'   => '',
			'value'         => get_post_meta( $variation->ID, 'rudr_embalagem', true )
		)
	);

	woocommerce_wp_text_input(
		array(
			'id'            => 'text_unidade[' . $loop . ']',
			'label'         => 'Unidade',
			'wrapper_class' => 'form-row',
			'placeholder'   => '',
			'desc_tip'      => 'true',
			'description'   => '',
			'value'         => get_post_meta( $variation->ID, 'rudr_unidade', true )
		)
	);


	woocommerce_wp_text_input(
		array(
			'id'            => 'text_dimensional[' . $loop . ']',
			'label'         => 'Dimensional',
			'wrapper_class' => 'form-row',
			'placeholder'   => '',
			'desc_tip'      => 'true',
			'description'   => '',
			'value'         => get_post_meta( $variation->ID, 'rudr_dimensional', true )
		)
	);

	


	woocommerce_wp_text_input(
		array(
			'id'            => 'text_codigobarras[' . $loop . ']',
			'label'         => 'Código de barras',
			'wrapper_class' => 'form-row',
			'placeholder'   => '',
			'desc_tip'      => 'true',
			'description'   => '',
			'value'         => get_post_meta( $variation->ID, 'rudr_codigobarras', true )
		)
	);



}


add_action( 'woocommerce_save_product_variation', 'rudr_save_fields', 10, 2 );

function rudr_save_fields( $variation_id, $loop ) {


	$text_embalagem = ! empty( $_POST[ 'text_embalagem' ][ $loop ] ) ? $_POST[ 'text_embalagem' ][ $loop ] : '';
	update_post_meta( $variation_id, 'rudr_embalagem', sanitize_text_field( $text_embalagem ) );


	$text_unidade = ! empty( $_POST[ 'text_unidade' ][ $loop ] ) ? $_POST[ 'text_unidade' ][ $loop ] : '';
	update_post_meta( $variation_id, 'rudr_unidade', sanitize_text_field( $text_unidade ) );


	$text_dimensional = ! empty( $_POST[ 'text_dimensional' ][ $loop ] ) ? $_POST[ 'text_dimensional' ][ $loop ] : '';
	update_post_meta( $variation_id, 'rudr_dimensional', sanitize_text_field( $text_dimensional ) );

	
	$text_codigobarras = ! empty( $_POST[ 'text_codigobarras' ][ $loop ] ) ? $_POST[ 'text_codigobarras' ][ $loop ] : '';
	update_post_meta( $variation_id, 'rudr_codigobarras', sanitize_text_field( $text_codigobarras ) );

}




function exibir_variacoes_do_produto( $atts ) {
    $atts = shortcode_atts( array(
        'id' =>  get_the_ID(),
    ), $atts );

    // Obter todas as variações do produto
    $variacoes = get_children( array(
        'post_parent' => $atts['id'],
        'post_type' => 'product_variation',
        'numberposts' => -1,
        'post_status' => 'publish',
    ) );

    // Inicializar uma variável para armazenar o HTML da lista de variações
    $lista_variacoes = '';

    // Verificar se há variações disponíveis
    if ( count( $variacoes ) > 0 ) {
		$html = '<table class="table-variacao">';
		$html .= '<tr>';
		$html .= '<td class="header">Código</td>';
		$html .= '<td class="header">Dimensional</td>';
		$html .= '<td class="header">Unidade</td>';
		$html .= '<td class="header">Embalagem</td>';
		$html .= '<td class="header">Código de Baras</td>';
		$html .= '</tr>';
		
		// Loop através de todas as variações e adicione as informações na tabela HTML
		foreach ( $variacoes as $variacao ) {
			$variacao_id = $variacao->ID;
			$variacao_obj = new WC_Product_Variation( $variacao_id );
			$nome_variacao = $variacao_obj->get_formatted_name();
			$codigo_variacao = $variacao_obj->get_sku();
			$dimensional_variacao = get_post_meta( $variacao_id, 'rudr_dimensional', true );
			$unidade_variacao = get_post_meta( $variacao_id, 'rudr_unidade', true );
			$embalagem_variacao = get_post_meta( $variacao_id, 'rudr_embalagem', true );
			$codigobarras_variacao = get_post_meta( $variacao_id, 'rudr_codigobarras', true );
		
			$html .= '<tr>';
			$html .= '<td>' . $codigo_variacao . '</td>';
			$html .= '<td>' . $dimensional_variacao . '</td>';
			$html .= '<td>' . $unidade_variacao . '</td>';
			$html .= '<td>' . $embalagem_variacao . '</td>';
			$html .= '<td>' . $codigobarras_variacao . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</table>';
    } else {
        $lista_variacoes = 'Não há informações comerciais disponiveis';
    }

    // Retorne o HTML da lista de variações
    return $html;

}
add_shortcode( 'exibir_variacoes', 'exibir_variacoes_do_produto' );