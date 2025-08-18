<?php
/**
 * Sample Page Template for Water Meter Readings Plugin
 * 
 * This file demonstrates how to use the water meter readings plugin
 * in a WordPress page or post.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="page" class="site">
        <header id="masthead" class="site-header">
            <div class="container">
                <h1 class="site-title">Vesimittarin Lukemien Syöttö</h1>
                <p class="site-description">Syötä taloyhtiösi vesimittarin lukemat</p>
            </div>
        </header>

        <div id="content" class="site-content">
            <div class="container">
                <main id="main" class="site-main">
                    
                    <!-- Instructions Section -->
                    <section class="instructions">
                        <h2>Ohjeet</h2>
                        <div class="instruction-steps">
                            <div class="step">
                                <span class="step-number">1</span>
                                <p>Syötä taloyhtiösi numero (esim. A001, B002)</p>
                            </div>
                            <div class="step">
                                <span class="step-number">2</span>
                                <p>Syötä kuuman veden lukema</p>
                            </div>
                            <div class="step">
                                <span class="step-number">3</span>
                                <p>Syötä kylmän veden lukema</p>
                            </div>
                            <div class="step">
                                <span class="step-number">4</span>
                                <p>Lisää mahdolliset huomautukset</p>
                            </div>
                            <div class="step">
                                <span class="step-number">5</span>
                                <p>Lähetä lukemat</p>
                            </div>
                        </div>
                    </section>

                    <!-- Water Meter Form -->
                    <section class="water-meter-section">
                        <?php echo do_shortcode('[water_meter_form]'); ?>
                    </section>

                    <!-- Contact Information -->
                    <section class="contact-info">
                        <h3>Yhteystiedot</h3>
                        <p>Jos kohtaat ongelmia lukemien syötössä, ota yhteyttä taloyhtiön hallitukseen.</p>
                        <p><strong>Puhelin:</strong> +358 40 123 4567</p>
                        <p><strong>Sähköposti:</strong> hallitus@taloyhtio.fi</p>
                    </section>

                </main>
            </div>
        </div>

        <footer id="colophon" class="site-footer">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Taloyhtiön Vesimittarijärjestelmä</p>
            </div>
        </footer>
    </div>

    <?php wp_footer(); ?>

    <style>
        /* Additional styles for the sample page */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .site-header {
            background: #0073aa;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .site-title {
            margin: 0 0 10px 0;
            font-size: 2.5em;
        }

        .site-description {
            margin: 0;
            font-size: 1.2em;
            opacity: 0.9;
        }

        .site-content {
            padding: 40px 0;
        }

        .instructions {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .instruction-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .step-number {
            background: #0073aa;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .water-meter-section {
            margin: 40px 0;
        }

        .contact-info {
            background: #e7f3ff;
            padding: 30px;
            border-radius: 8px;
            border-left: 4px solid #0073aa;
        }

        .contact-info h3 {
            margin-top: 0;
            color: #0073aa;
        }

        .site-footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .instruction-steps {
                grid-template-columns: 1fr;
            }
            
            .site-title {
                font-size: 2em;
            }
        }
    </style>
</body>
</html>
