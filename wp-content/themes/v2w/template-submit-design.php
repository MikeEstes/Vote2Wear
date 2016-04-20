<?php /* Template Name: Submit Design */ ?>
<?php get_header(); ?>


<main class="content submit-design">
        <div class="inner">


                <div class="title-hero-section">
                
                        <h1 class="title green">Submit A Design <span class="line"></span></h1>
                        <div class="line2">show us what you've got</div>
                        <div class="links">
                                <a href="<?php bloginfo('template_directory'); ?>/library/files/Art-Submission-Walkthrough-Document.pdf" target="_blank">Learn How to Submit Your Design</a>
                        </div>
                        
                </div>
                <?php // end title-section ?>
        
        <div id="mobile-submit-notice">Please use your desktop to submit a design.</div>


                <section id="dsubmit">
                        <form action="<?php echo get_option('siteurl'); ?>" method="POST" id="dform">


                                <?php //Progress Bar / Tabs ?>
                                <ul class="tabs" id="dprogress">
                                        <li class="active">Upload Shirt</li>
                                        <li>Choose Shirt Color</li>
                                        <li>Finalize Design</li>
                                </ul>


                                <?php //Steps / Panels ?>
                                <ul class="panels" id="dpanels">
                                        <li id="dstep1" class="panel step active">
                                                
                                                <div class="a-row file">
                                                        <input type="text" name="filename" placeholder="SELECT A FILE" class="file file-trigger" id="artwork-filename" readonly />
                                                        <input type="button" name="file_upload" value="BROWSE" id="file-upload-btn" class="file-trigger" />
                                                        <input type="file" name="artwork" id="dupload" class="file-uploader" accept=".png" />
                                                </div>


                                                <div class="a-row field">
                                                        <input type="text" name="name" value="" placeholder="WHAT IS THE NAME OF YOUR DESIGN?" />
                                                        <span class="tooltip">EXAMPLE: <?php echo EXAMPLE_DESIGN_NAME; ?></span>
                                                </div>


                                                <div class="a-row field">
                                                        <textarea id="ddescription" name="description" placeholder="ADD A DESCRIPTION OF YOUR DESIGN"></textarea>
                                                </div>


                                                <div class="a-row field tags">
                                                        <input type="text" id="dtags" value="" name="tags" placeholder="ADD TAGS" />
                                                        <span class="tooltip">EXAMPLE: <?php echo EXAMPLE_DESIGN_TAGS; ?></span>
                                                </div>




                        <div class="a-row field legal">
                            <input type="checkbox" value="agree" id="dlegal" />
                            <span class="label">I agree to the legal <a href="<?php echo get_option('siteurl'); ?>/terms-privacy" target="_blank">terms &amp; conditions</a></span>
                        </div>


                                                <div class="submit-row field continue">
                                                        <a href="#" class="btn2 advance">Save &amp; Continue</a>
                                                </div>
                                                
                                                <div class="msg"></div>


                                        </li>
                                        <li id="dstep2" class="panel step">


                                                <?php // container to hold the selected colors ?>
                                                <div class="selected-container">
                        
                                <div class="text">CHOOSE UP TO THREE COLORS</div>
                            
                            <?php // @todo move inline css to less file. Chris had to add a wrap for JS needs ?>
                            <div id="selected-colors" class="" style="display: inline;">
                                <?php /* EXAMPLE
                                    <div class="color-box selected">
                                            <div class="inner">
                                                <div class="color-circ"></div>
                                            <span class="label">Saphire</span>
                                            <div class="icon"></div>
                                        </div>
                                    </div>
                                */ ?>
                            </div>
                            
                        </div>
                        
                        <?php // main container to hold the color choices ?>
                        <div class="color-manager">
                            <?php $colors = get_available_shirt_colors(); ?>
                        
                                <div class="bg-piece"></div>
                                
                            <div class="primary-colors">
                                    <ul id="primary-color-choices">
                                    <?php foreach( $colors as $i => $color ) : ?>
                                           <li class="a-choice <?php echo ($i==0) ? 'active' : ''; ?>"><?php echo $color['primary']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <div class="secondary-color">
                                    <ul id="secondary-color-choices">


                                    <?php foreach( $colors as $i => $color ) : ?>
                                            <li class="a-panel <?php echo ($i==0) ? 'active' : ''; ?>">
                                    
                                                <div class="panel-title">
                                                <?php echo $color['primary']; ?>
                                            </div>
                                            
                                            <div class="swatch-wrap">
                                                <?php foreach( $color['secondary'] as $label => $option ) : ?>
                                                    <div class="color-box" data-color="<?php echo $option; ?>">
                                                        <div class="inner">
                                                            <div class="color-circ" style="background: <?php echo $option; ?>"></div>
                                                            <span class="label"><?php echo $label; ?></span>
                                                            <div class="icon"></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>


                                        </li>
                                    <?php endforeach; ?>
                                    
                                </ul>
                            </div>
                            
                            <div class="clear"></div>
                            
                        </div>


                                                <div class="field continue submit-row">
                                                        <a href="#" class="btn2 advance">Save &amp; Continue</a>
                                                        <a href="#" class="btn-prev reverse">Previous Step</a>
                                                </div>
                        
                        <div class="msg"></div>
                                                
                                        </li>
                                        <li id="dstep3" class="panel step">


                                                <div class="final-panel-wrap">
                            <div id="placement-loader">Loading design. Please wait...</div>


                                <div class="text">
                                    <div class="line1">Scale and position your design.</div>
                                <div id="design-instruction-widgets">
                                    <div class="submit-row">
                                        <span class="section-header">Default Shirt</span>
                                        <div id="template-btn-row">
                                            <a id="mens-template-btn" class="btn2 ">Men's</a>
                                            <a id="womens-template-btn" class="btn2 ">Women's</a>
                                        </div>
                                    </div>
                                    <div id="available-colors" class="submit-row"></div>
                                    <div class="submit-row">
                                        <span class="section-header">Scale</span>
                                        <div id="slider-container" class="vertical">
                                            <div id="flat-slider-vertical-1"></div>                   
                                        </div>
                                    </div>
                                    <div class="submit-row">
                                        <span class="section-header">Center Design</span>
                                        <div id="center-btn-row">
                                            <a id="horizontal-center-btn" class="btn2 ">Horizontally</a>
                                            <a id="vertical-center-btn" class="btn2 ">Vertically</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="design-wrap" id="placement-outer">
                                <div id="placement-printable">
                                    <div id="placement-inner" class="placement-inner">
                                        <?php //<img id="placement-artwork" /> ?>
                                    </div>
                                </div>
                            </div>


                        </div>


                                                <div class="field continue submit-row">
                                                        <a href="#" class="btn2 advance">Submit Design</a>
                                                        <a href="#" class="btn-prev reverse">Previous Step</a>
                                                        <div id="final-step-error" class="msg"></div>
                                                </div>
                                                
                                        </li>
                    <li id="dstep4" class="panel step">


                        <span>Success! Your shirt design has been submitted.</span><br /><br />
                        <a href="<?php echo get_option('siteurl'); ?>" class="btn2">Vote Daily Battles</a>


                    </li>
                                </ul>


                        </form>
                </section>
        
        <div class="how-it-all-works">
                <div class="center">
            
                    <div class="section-title">How It All Works</div>
                
                <div class="section-icons">
                        <div class="a-icon a-icon-1">
                            <div class="icon"></div>
                        <div class="icon-text">The Idea</div>
                    </div>
                    
                    <div class="a-icon a-icon-2">
                            <div class="icon"></div>
                        <div class="icon-text">The Creation</div>
                    </div>
                    
                    <div class="a-icon a-icon-3">
                            <div class="icon"></div>
                        <div class="icon-text">Upload to V2W</div>
                    </div>
                    
                    <div class="a-icon a-icon-4">
                            <div class="icon"></div>
                        <div class="icon-text">Share It</div>
                    </div>
                </div>
                
                <div class="content-cols">
                    <div class="col col1">
                        <h2 class="headline">General Rules</h2>
                        <div class="">
                            <p>At Vote2Wear, we are all about creativity and unique custom shirts that no other online entity offers. We welcome artists of all experience levels and from all walks of life. We encourage our community to give feedback, write comments and reviews, and give critiques on designs. We ask our users to be mature, respectful and considerate their interactions with other users.</p>
                            <p>At Vote2Wear we don't tolerate any unlawful, threatening, harassing, abusive, defamatory, invasive of privacy or publicity rights, vulgar, obscene, sexually explicit, hateful, profane, indecent, racially or ethnically derogatory, or otherwise objectionable conduct. Such conduct is considered a breach of our Terms of Use on Prohibited Use and will result in account closure.</p>
                            <p>It is necessary that all Vote2Wear users respect Intellectual property rights of others, including copyright and trademarks. Users who design for Vote2Wear must only upload content they have created themselves and have permission to use and authorize others to use. Always respect the copyright and trademarks of all the work seen or bought on Vote2Wear.</p>
                            <p>Any work displayed on Vote2Wear gets put on the internet for the world to see. Users need to be aware that publishing work in this way attracts legal responsibilities. It is up to the user to ensure no laws are being broken by publishing your work on Vote2Wear.<p>
                            <p>Above all, we ask our users to have fun using our platform, contribute positively to the community, make connections, collaborate, share and explore their creativity.</p>
                        </div>
                    </div>
                        
                    <div class="col col2">
                        <h2 class="headline">Artwork Guidelines</h2>
                        <div class="">
                            <p>Here you can find all the information on how to upload work on Vote2Wear!</p>
                            <p>We are always looking for new creative art and innovative artist to share their work on our platform. If you have a design you want us to consider, feel free to submit it using the form below.</p>
                            <p>When you submit a design to Vote2Wear you can earn commissions on your work. Every shirt sold with your design will earn you a $2 dollar commission. You can also build a fan base within our community; we have thousands of unique visitors every day. When you participate in a daily battle, you are on the stage and all eyes are on you.</p>
                            <p>When you submit work to Vote2Wear, you keep your copyright because we license the rights to display your work on our site.</p>
                        </div>
                        <h2 class="headline">Submission Guidelines</h2>
                        <div class="">
                            <p>&bull; Our print canvas is 3,600 x 3,600 at 300 dpi. (12 inches by 12 inches)</p>
                            <p>&bull; All files submitted should be no larger than 3,600 x 3,600 and no smaller than 1,000 x 1,000.</p>
                            <p>&bull; File resolution should not be less than 150 dpi. (300 dpi is recommended.)</p>
                            <p>&bull; All files submitted should be in .png format.</p>
                        </div>
                    </div>
                    
                    <div class="clear"></div>
                </div>
            
            </div>
        </div>


        </div>
</main>


<?php // @todo optimize location/request ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.structure.min.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/library/css/vertical-slider.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css">
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/fileuploader/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/fileuploader/jquery.fileupload.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/design.js"></script>
<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/jquery-ui-pips.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/jquery-rain.js"></script>


<?php get_footer(); ?>