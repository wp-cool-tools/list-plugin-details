<?php
$data     = $args[ 'data' ];
$settings = get_option( 'lpi_settings' );

// set block headline tag
$h_tag    = !empty( $settings['lpi-block-h-tag'] ) ? $settings['lpi-block-h-tag'] : 'h2';
// set headline
$headline = !empty( $settings[ 'lpi-block-headline' ] ) ? $settings[ 'lpi-block-headline' ] : __( 'Our free plugins', LPI_DOMAIN );
// set total download text
$total    = !empty( $settings[ 'lpi-block-total' ] ) ? $settings[ 'lpi-block-total' ] : __('Our plugins have been downloaded a total of %s times.', LPI_DOMAIN );
?>
<div class="<?php echo $settings[ 'lpi-block-class' ]; ?>">
    <<?php echo $h_tag; ?> class="<?php echo $settings[ 'lpi-block-h-class' ]; ?>"><?php echo $headline; ?></<?php echo $h_tag; ?>>
    <p class="<?php echo $settings[ 'lpi-block-p-class' ]; ?>"><?php echo sprintf( $total, '<strong>' . number_format_i18n( $data->downloads['total'], 0 ) . '</strong>' ); ?></p>

    <?php
    $items = count( $data->downloads );
    $item  = 0;
    ?>
    <?php foreach ( $data->downloads as $name => $meta ) : ?>

        <?php if( is_array( $meta ) ) : ?>

            <?php
            $item++;
            $next_block  = ( 1 == $item || 1 == $item % $settings[ 'lpi-rows' ] );
            $close_block = ( $item == $items || 0 == $item % $settings[ 'lpi-rows' ] );
            ?>

            <?php lpi_get_template_part( 'wpo-showcase', 'block', array( 'name'        => $name,
                                                                                    'meta'        => $meta,
                                                                                    'next_block'  => $next_block,
                                                                                    'close_block' => $close_block,
                                                                                    'settings'    => $settings
            ) ) ?>

        <?php endif; ?>

    <?php endforeach; ?>

</div>