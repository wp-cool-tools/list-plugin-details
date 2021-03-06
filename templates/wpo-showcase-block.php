<?php
$meta        = $args[ 'meta' ];
$name        = $args[ 'name' ];
$next_block  = $args[ 'next_block' ];
$close_block = $args[ 'close_block' ];
$settings    = $args[ 'settings' ];

$rating      = number_format_i18n( $meta[ 'rating' ] / 20, 1 ) ;
$rating      = ( 0 == $rating ? __('none', LPI_DOMAIN ) : $rating .= '/5' );
// set default card headline tag
$h_tag       =  empty( $settings['lpi-h-tag'] ) ? $settings['lpi-h-tag'] : 'h3';
?>
<?php if ( $next_block ) :?>
<div class="<?php echo $settings[ 'lpi-row-class' ] ?>">
    <?php endif; ?>
    <div class="<?php echo $settings[ 'lpi-card-class' ] ?>">
        <figure class="<?php echo $settings[ 'lpi-figure-class' ] ?>wp-block-image"><?php echo get_the_post_thumbnail( $meta[ 'post_id' ] ); ?></figure>
        <div class="<?php echo $settings[ 'lpi-button-cover' ] ?>">
            <div class="<?php echo $settings[ 'lpi-button' ] ?>"><a href="<?php echo $meta[ 'download_link' ]; ?>" class="<?php echo $settings[ 'lpi-button-a' ] ?>" target="_blank"><?php _e('Download', LPI_DOMAIN ); ?></a></div>
        </div>
        <<?php echo $h_tag ?> class="<?php echo $settings[ 'lpi-h-class' ] ?>"><a href="<?php echo $meta[ 'homepage' ]; ?>" target="_blank"><?php echo $name ; ?></a></<?php echo $h_tag ?>>
        <p>
            <?php if( $settings[ 'lpi-retrieve-version' ] ) : ?>
            <img src="https://img.shields.io/wordpress/plugin/v/<?php echo $meta[ 'slug' ];?>.svg?style=<?php echo !empty( $settings[ 'lpi-badge-style' ]) ? $settings[ 'lpi-badge-style' ] : 'plastic'; ?><?php echo !empty( $settings[ 'lpi-retrieve-version-label' ]) ? '&label=' . $settings[ 'lpi-retrieve-version-label' ] : ''; ?>"<?php echo( $settings[ 'lpi-retrieve-tested' ] ? ' style="float: left; margin-right: 10px;"' : '' ); ?>>
            <?php endif; ?>
            <?php if( $settings[ 'lpi-retrieve-tested' ] ) : ?>
            <img src="https://img.shields.io/wordpress/v/<?php echo $meta[ 'slug' ];?>.svg?style=<?php echo !empty( $settings[ 'lpi-badge-style' ]) ? $settings[ 'lpi-badge-style' ] : 'plastic'; ?><?php echo !empty( $settings[ 'lpi-retrieve-tested-label' ]) ? '&label=' . $settings[ 'lpi-retrieve-tested-label' ] : ''; ?>">
            <?php endif; ?>
        </p>
        <p><i class="fas fa-download" style="color: #2e2e2e">&nbsp;</i><?php _e('Downloads:', LPI_DOMAIN ); ?> <?php echo number_format_i18n( $meta[ 'downloads' ] ); ?>
            <br><i class="fas fa-star" style="color: #2e2e2e"></i>&nbsp;<?php _e('Rating:', LPI_DOMAIN ); ?> <?php echo $rating ; ?>
            <?php if ( 0 <> $meta[ 'num_ratings' ] ) : ?>
            <br><i class="fas fa-user-edit" style="color: #2e2e2e"></i>&nbsp;<?php _e('Reviews:', LPI_DOMAIN ); ?> <?php echo $meta[ 'num_ratings' ] ; ?>
            <?php endif; ?>
        </p>
    </div>
    <?php if ( $close_block ) : ?>
</div>
<?php endif; ?>