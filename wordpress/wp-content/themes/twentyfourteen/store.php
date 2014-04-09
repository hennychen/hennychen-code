<?php
/*
Template Name: Store
*/
?>

<?php get_header(); ?>

<div id="container">
    <div id="content" role="main">
    <?php

    echo "string".get_query_var('storename');
    $stores = array(
        'milwaukee' => array(
            'name'    => "Milwaukee Store",
            'manager' => 'Richie Cunningam',
            'address' => '565 N Clinton Drive, Milwaukee',
            'phone'   => '555-31337-1337'
        ),
        'springfield' => array(
            'name'    => "Springfield Store",
            'manager' => 'Bart Simpsons',
            'address' => 'Evergreen Terrace, Springfield',
            'phone'   => '555-666-696969'
        ),
        'fairview' => array(
            'name'    => "Fairview Store",
            'manager' => 'Susan Mayer',
            'address' => '4353 Wisteria Lane, Fairview',
            'phone'   => '4-8-15-16-23-42'
        )
    );
    
    // Get store id
    $store = get_query_var( 'store_id' );
    
    // if store exists, display info
    if( array_key_exists( $store, $stores ) ) {
    
        extract( $stores[$store] );
        echo "<p>Store: $name</p>";
        echo "<p>Manager: $manager</p>";
        echo "<p>Location: $address</p>";
        echo "<p>Contact us: $phone</p>";
    
    // if store does not exist, list them all
    } else {
        
        // Get current page URL
        global $post;
        $page = untrailingslashit( get_permalink( $post->ID ) );
        
        echo '<p>Our stores:</p>';
        echo '<ul>';
        foreach( $stores as $store => $info ) {
            $name = $info['name'];
            echo "<li><a href='$page&storename=$store'>$name</a></li>\n";
        }
        echo '</ul>';
    }
    
    ?>
    </div><!-- #content -->
</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

