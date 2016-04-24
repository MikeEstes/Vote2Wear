<?php /* Template Name: Contact */ ?>

<?php get_header(); ?>

<main class="content contact">

    <div class="title-hero-section">

        <h1 class="title green">Get in Touch <span class="line"></span></h1>
        <div class="line2">Here are a couple of simple options</div>
        <div class="links">
            <a href="mailto:support@vote2wear.com">support@vote2wear.com</a>
        </div>

    </div>
    <?php //end title-section ?>

    <div class="main-page">
        <div class="center">

            <div class="form-wrap">
                <div class="form-chooser">

                    <div class="form-choice active" id="form-choice-1">General Contact</div>

                    <div class="form-choice" id="form-choice-2">Legal Claims</div>

                </div>

                <div class="the-forms">

                    <div class="form form1">
                        <div class="text-part">Have a Question, Comment or Concern? Reach out, and we'll get right back to you!</div>
                        
                        <form action="process-general.php" method="POST">

                            <div class="form-row split">
                                <!-- NAME -->
                                <div id="name-group1" class="input-wrap">
                                    <input type="text" class="form-control" name="name1" placeholder="NAME">
                                    <!-- errors will go here -->
                                </div>

                                <!-- EMAIL -->
                                <div id="email-group1" class="input-wrap">
                                    <input type="text" class="form-control" name="email1" placeholder="EMAIL">
                                    <!-- errors will go here -->
                                </div>
                            </div>

                            <div class="form-row">
                                <!-- MESSAGE -->
                                <div id="message-group1" class="input-wrap">
                                    <textarea class="form-control" name="message1" placeholder="LEAVE US A MESSAGE!"></textarea>
                                    <!-- errors will go here -->
                                </div>
                            </div>
                            
                            <input type="submit" name="general" value="SUBMIT MESSAGE" />
                        </form>
                    </div>

                    <div class="form form2">
                        <div class="text-part">If you believe that your content has been used in a way that constitutes an infringement of your rights, please notify Vote2Wear's designated agent for complaints (below) by sending a Notice and Takedown Report, which must include the following important information:<br/><br/>&nbsp; &nbsp; &nbsp; &nbsp;An electronic or physical signature of the person authorised to act on behalf of the owner of the relevant matter; <br/>&nbsp; &nbsp; &nbsp; &nbsp;A description of the matter claimed to have been infringed;<br/>&nbsp; &nbsp; &nbsp; &nbsp;A description of where the claimed infringing content is located on the Vote2Wear site. <br/>&nbsp; &nbsp; &nbsp; &nbsp;Your address, telephone number, and email address;<br/>&nbsp; &nbsp; &nbsp; &nbsp;A statement by you that you have a good faith belief that the disputed use is not authorised by the owner, its agent, or the law;<br/>&nbsp; &nbsp; &nbsp; &nbsp;A statement by you, made under penalty of perjury, that:<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;a. the above information is accurate; and<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;b. you are authorised to act on behalf of the owner of the rights involved.<br/><br/>Vote2Wear's Designated Agent for complaints can be reached at: legal@vote2wear.com<br/><br/><a href="<?php echo get_permalink(2344); ?>">DCMA</a> information can be found here.</div>
                        
                        <form action="process-legal.php" method="POST">

                            <div class="form-row split">
                                <!-- NAME -->
                                <div id="name-group2" class="input-wrap">
                                    <input type="text" class="form-control" name="name2" placeholder="NAME">
                                    <!-- errors will go here -->
                                </div>

                                <!-- EMAIL -->
                                <div id="email-group2" class="input-wrap">
                                    <input type="text" class="form-control" name="email2" placeholder="EMAIL">
                                    <!-- errors will go here -->
                                </div>
                            </div>

                            <div class="form-row">
                                <!-- MESSAGE -->
                                <div id="message-group2" class="input-wrap">
                                    <textarea class="form-control" name="message2" placeholder="LEAVE US A MESSAGE!"></textarea>
                                    <!-- errors will go here -->
                                </div>
                            </div>
                            
                            <input type="submit" name="legal" value="SUBMIT CLAIM" />
                        </form>
                        
                    </div>
                </div>
            </div>
            <?php // end form-wrap ?>

            <div class="links-wrap">
                <div class="line1">Helpful Links</div>
                <div class="line2">We’d love to hear from you, however before contacting us please see if you can find the answer to your questions on one of the links below.
                    <div class="line"></div>
                </div>

                <ul class="links">
                    <li><a href="<?php echo get_permalink(30); ?>">faq</a>
                    </li>
                    <li><a href="<?php echo get_permalink(248); ?>">terms</a>
                    </li>
                    <li><a href="<?php echo get_permalink(248); ?>">privacy</a>
                    </li>
                    <li><a href="<?php echo get_permalink(30); ?>">returns</a>
                    </li>
                </ul>
            </div>
            <?php // end links-wrap ?>

            <div class="clear"></div>

        </div>
    </div>
    <?php // end main-page ?>

    <div class="contact-ctas">
        <div class="panel panel1">
            <div id="map"></div>
        </div>

        <div class="panel panel2">
            <div class="inner1">
                <div class="inner2">
                    <div class="line1">Vote2Wear</div>
                    <div class="line2">We love the creative commiunity - specifically the t-shirt community. So much so we created a platform for creatives to go head to head with worthy competitors.</div>
                    <div class="social">
                        <div class="text">Follow Us</div>
                        <a href="https://www.facebook.com/vote2wear/" class="facebook" target="_blank"></a>
                        <a href="https://twitter.com/Vote2Wear" class="twitter" target="_blank"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear"></div>
    </div>
    <?php // end contact-ctas ?>

</main>
<?php //end content ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK9TXnPJXNHbC2fJZCMVWXqZL07fCzaiE"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/contact.js"></script>

<?php get_footer(); ?>