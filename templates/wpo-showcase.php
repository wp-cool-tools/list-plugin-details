<?php
$data     = $args[ 'data' ];
$settings = get_option( 'lpd_settings' );

// set block headline tag
$h_tag    = !empty( $settings[ 'lpd-block-h-tag'] ) ? $settings[ 'lpd-block-h-tag'] : 'h2';
// set headline
$headline = !empty( $settings[ 'lpd-block-headline' ] ) ? $settings[ 'lpd-block-headline' ] : __( 'Our free plugins', LPD_DOMAIN );
// set total download text
$total    = !empty( $settings[ 'lpd-block-total' ] ) ? $settings[ 'lpd-block-total' ] : __('Our plugins have been downloaded a total of %s times.', LPD_DOMAIN );
?>
<div class="<?php echo $settings[ 'lpd-block-class' ]; ?>">
    <<?php echo $h_tag; ?> class="<?php echo $settings[ 'lpd-block-h-class' ]; ?>"><?php echo $headline; ?></<?php echo $h_tag; ?>>
    <p class="<?php echo $settings[ 'lpd-block-p-class' ]; ?>"><?php echo sprintf( $total, '<strong>' . number_format_i18n( $data->downloads['total'], 0 ) . '</strong>' ); ?></p>

    <?php
    $items = count( $data->downloads );
    $item  = 0;
    ?>
    <?php foreach ( $data->downloads as $name => $meta ) : ?>

        <?php if( is_array( $meta ) ) : ?>

            <?php
            $item++;
            $next_block  = ( 1 == $item || 1 == $item % $settings[ 'lpd-rows' ] );
            $close_block = ( $item == $items || 0 == $item % $settings[ 'lpd-rows' ] );
            ?>

            <?php lpd_get_template_part( 'wpo-showcase', 'block', array( 'name'        => $name,
                                                                                    'meta'        => $meta,
                                                                                    'next_block'  => $next_block,
                                                                                    'close_block' => $close_block,
                                                                                    'settings'    => $settings
            ) ) ?>

        <?php endif; ?>

    <?php endforeach; ?>

</div>