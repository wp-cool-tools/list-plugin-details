<?php
$meta        = $args[ 'meta' ];
$name        = $args[ 'name' ];
$next_block  = $args[ 'next_block' ];
$close_block = $args[ 'close_block' ];
$settings    = $args[ 'settings' ];

$rating      = number_format_i18n( $meta[ 'rating' ] / 20, 1 ) ;
$rating      = ( 0 == $rating ? __('none', LPD_DOMAIN ) : $rating .= '/5' );
// set default card headline tag
$h_tag       =  empty( $settings['lpd-h-tag'] ) ? $settings['lpd-h-tag'] : 'h3';
?>
<?php if ( $next_block ) :?>
<div class="<?php echo $settings[ 'lpd-row-class' ] ?>">
    <?php endif; ?>
    <div class="<?php echo $settings[ 'lpd-card-class' ] ?>">
        <figure class="<?php echo $settings[ 'lpd-figure-class' ] ?>"><?php echo get_the_post_thumbnail( $meta[ 'post_id' ], 'medium' ); ?></figure>
        <div class="<?php echo $settings[ 'lpd-button-cover' ] ?>">
            <div class="<?php echo $settings[ 'lpd-button' ] ?>"><a href="<?php echo $meta[ 'download_link' ]; ?>" class="<?php echo $settings[ 'lpd-button-a' ] ?>" target="_blank"><?php _e('Download', LPD_DOMAIN ); ?></a></div>
        </div>
        <<?php echo $h_tag ?> class="<?php echo $settings[ 'lpd-h-class' ] ?>"><a href="<?php echo $meta[ 'homepage' ]; ?>" target="_blank"><?php echo $name ; ?></a></<?php echo $h_tag ?>>
        <p>
            <?php if( $settings[ 'lpd-retrieve-version' ] ) : ?>
            <img src="https://img.shields.io/wordpress/plugin/v/<?php echo $meta[ 'slug' ];?>.svg?style=<?php echo !empty( $settings[ 'lpd-badge-style' ]) ? $settings[ 'lpd-badge-style' ] : 'plastic'; ?><?php echo !empty( $settings[ 'lpd-retrieve-version-label' ]) ? '&label=' . $settings[ 'lpd-retrieve-version-label' ] : ''; ?>"<?php echo( $settings[ 'lpd-retrieve-tested' ] ? ' style="float: left; margin-right: 10px;"' : '' ); ?>>
            <?php endif; ?>
            <?php if( $settings[ 'lpd-retrieve-tested' ] ) : ?>
            <img src="https://img.shields.io/wordpress/v/<?php echo $meta[ 'slug' ];?>.svg?style=<?php echo !empty( $settings[ 'lpd-badge-style' ]) ? $settings[ 'lpd-badge-style' ] : 'plastic'; ?><?php echo !empty( $settings[ 'lpd-retrieve-tested-label' ]) ? '&label=' . $settings[ 'lpd-retrieve-tested-label' ] : ''; ?>">
            <?php endif; ?>
        </p>
        <p><i class="fas fa-download" style="color: #2e2e2e">&nbsp;</i><?php _e('Downloads:', LPD_DOMAIN ); ?> <?php echo number_format_i18n( $meta[ 'downloads' ] ); ?>
            <br><i class="fas fa-star" style="color: #2e2e2e"></i>&nbsp;<?php _e('Rating:', LPD_DOMAIN ); ?> <?php echo $rating ; ?>
            <?php if ( 0 <> $meta[ 'num_ratings' ] ) : ?>
            <br><i class="fas fa-user-edit" style="color: #2e2e2e"></i>&nbsp;<?php _e('Reviews:', LPD_DOMAIN ); ?> <?php echo $meta[ 'num_ratings' ] ; ?>
            <?php endif; ?>
        </p>
    </div>
    <?php if ( $close_block ) : ?>
</div>
<?php endif; ?>