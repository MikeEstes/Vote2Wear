(function($) {


    var form = null;


    var tabs = $('#dprogress>li'),
        steps = $('#dpanels>li');


    var steps_enabled = true;


    //Maximum # of Colors
    var _maxColors = 3;


    //Manage selected colors
    var colors = [];


    //Shirt template prefix
    var shirt_prefix = '/mens/3600-';

    //Shirt template color
    var shirt_color = '';


    //File upload in progress?
    var is_uploading = false;


    //Artwork information
    var artwork = false;


    //Is the form currently submitting?
    var is_submitting = false;


    /**
     *        Get the current step DOM
     *
     *        @return (jQuery)
     */
    var getCurrentStep = function()
    {
        return steps.eq( currentStep() -1 );
    };


    /**
     *        Get current step number
     *
     *        @return (int) current step
     */
    var currentStep = function()
    {
        var active = steps.filter('.active');
        return ( steps.index(active) ) + 1;
    };


    /**
     *        Is last step?
     *
     *        @return (bool)
     */
    var isLastStep = function()
    {
        return ( currentStep() == steps.length );
    };


    /**
     *        Is step?
     *        Checks to see if the current step
     *        matches param
     *
     *        @pararm (int) step
     */
    var isStep = function( step )
    {
        return (currentStep() == step);
    };


    /**
     *        Go to a Step
     *
     *        integer
     *        @param (step)  step to visit
     */
    var goToStep = function( step )
    {
        //ensure feature is enabled
        if( ! steps_enabled )
            return;


        //ensure valid step was supplied
        if( step < 1 || step > steps.length )
            return;


        //fix step # for 0 based index
        step--;


        //if this step is already active, do nothing
        if( steps.eq(step).hasClass('active') )
            return;


        steps.filter('.active').removeClass('active');
        tabs.filter('.active').removeClass('active');


        steps.eq(step).addClass('active');


        /**
         *        If there are more panels than
         *        tabs, and the current panel is
         *        beyond that of the steps, then
         *        use the last tab as active
         */
        if( (step+1) > tabs.length )
            tabs.filter(':last').addClass('active');
        else
            tabs.eq(step).addClass('active');


        //notify everybody!
        form.trigger('step', [step+1]);
    };


    /**
     *        Previous Step
     */
    var previousStep = function()
    {
        goToStep( currentStep() - 1 );
    };


    /**
     *        Next Step
     */
    var nextStep = function()
    {
        goToStep( currentStep() + 1 );
    };


    /**
     *        Disable steps to prevent
     *        advancing.
     */
    var disableSteps = function()
    {
        steps_enabled = false;
    };


    /**
     *        Enable steps
     */
    var enableSteps = function()
    {
        steps_enabled = true;
    };


    /**
     *        File Uploader
     *        Allow user to upload design
     */
    var initFileUploader = function()
    {
        //init
        $('#dupload').fileupload({
            //options
            url: V2W.ajaxurl,
            type: 'POST',
            formData: {
                action: 'upload_design'
            },
            autoUpload: true,
            maxNumberOfFiles: 1,
            acceptFileTypes: '/(\.|\/)(png)$/i',


            // on upload start, disable steps
            start: function(e)
            {
                steps_enabled = false;
                is_uploading = true;
            },


            // on upload finish, enable steps
            done: function(e, data)
            {
                steps_enabled = true;
                is_uploading = false;


                if( data.result.code != 200 ) {
                    getCurrentStep().find('.msg').html( data.result.message );
                    return;
                }


                //save in artwork var
                artwork = {
                    'original_filename': data.result.original_name,
                    'filename': data.result.filename,
                    'url': data.result.url,
                    'width': data.result.width,
                    'height': data.result.height
                };
            },


            // add file to queue
            add: function(e, data)
            {
                var filename = data.files[0].name;
                setFileUploadLabel(filename);


                //clear artwork
                artwork = false;


                data.submit();
            },


            // upload progress
            progress: function(e, data)
            {
                var progress = parseInt(data.loaded / data.total * 100, 10),
                    filename = data.files[0].name;


                setFileUploadLabel( filename + ' (' + progress + '%)' );
            },


            // on fail
            fail: function(e, data)
            {
            }
        });


        //trigger open
        $('#file-upload-btn').on('click', function(evt) {
            $('#dupload').trigger('click');
        });
    };


    /**
     *        Update File Upload Label
     *
     *        @param (string) label
     */
    var setFileUploadLabel = function(label)
    {
        if( label == 'undefined' )
            label = '';


        $('#artwork-filename').val( label );
    };


    /**
     *        Color Switcher
     *        Switch between primary color views
     */
    var colorSwitcher = function()
    {
        var primary = $('#primary-color-choices>li'),
            secondary = $('#secondary-color-choices>li');


        primary.on('click', function(evt) {


            //if already active, ignore
            if( $(this).hasClass('active') )
                return;


            var i = primary.index( $(this) );


            primary.filter('.active').removeClass('active');
            $(this).addClass('active');


            secondary.filter('.active').removeClass('active');
            secondary.eq(i).addClass('active');


        });
    };


    /**
     *        Colors full?
     *        Check if colors have reached their
     *        maximum limit
     *
     *        @return (bool)
     */
    var isColorsFull = function()
    {
        return ( colors.length >= _maxColors );
    };


    /**
     *        Colors empty?
     *        Check to see if the colors array
     *        is empty
     *
     *        @return (bool)
     */
    var isColorsEmpty = function()
    {
        return ( colors.length <= 0 );
    };


    /**
     *        Add Color
     *
     *        @param (colorObj) color object containing color code and label
     */
    var addColor = function( colorObj )
    {
        //if the color is already present, ignore

        var colorFound = false;

        colors.forEach(function(clrObj){
            if (clrObj.color === colorObj.color){
                colorFound = true;
            }
        });

        if( colorFound )
            return;


        if( isColorsFull() )
            return false;


        colors.push(colorObj);
        return true;
    };


    /**
     *        Remove Color
     *
     *        @param (string) color code
     */
    var removeColor = function( colorObj )
    {
        for (var i = 0, l = colors.length; i < l; i++) {
            if (colors[i].color === colorObj.color) {
                colors.splice(i, 1);
                break;
            }
        }
    };


    /**
     *        Color Picker
     */
    var colorPicker = function()
    {
        var selected = $('#selected-colors');


        form.find('.color-manager .color-box').on('click', function(evt) {


            var colorObj =  {
                color: $(this).data('color'),
                label: $(this).find("span.label").text()
            };

            //if has selected class, user is removing it
            if( $(this).hasClass('selected') ) {
                //REMOVE


                //remove color from stack
                removeColor(colorObj);


                //remove from DOM
                $(this).remove();


            } else {
                //ADD
                //attempt to add the color to the stack
                if( addColor(colorObj) ) {
                    //clone the element and move to selected stack
                    var copy = $(this).clone( true );
                    copy.addClass('selected');
                    selected.append( copy );
                }
            }


        });
    };


    /**
     *        Clear Errors
     */
    var clearErrors = function()
    {
        form.find('.error').removeClass('error');
        form.find('.msg').html('');
    };


    /**
     *        Init Draggable & Resizable
     *        jQuery UI components
     *
     *        @param (int) width of scaled image,
     *        @param (int) height of scaled image
     */
    var initPlacement = function( w, h )
    {
        var d = $('#placement-inner'),
            r = $('#placement-artwork');


        //destroy if needed
        if( d.hasClass('ui-draggable') )
            d.draggable('destroy');


        //init
        //draggable
        d.draggable({
            containment: 'parent',
            cursor: 'move'
        });

        $('#horizontal-center-btn').on('click', centerImageHorizontally);
        $('#vertical-center-btn').on('click', centerImageVertically);
        $('#womens-template-btn').on('click', function(){
            setShirtStyle('/womens/3900-');
        });
        $('#mens-template-btn').on('click', function() {
            setShirtStyle('/mens/3600-');
        });
        $('#available-colors > div').remove();
        colors.forEach(function(clr){
            $('#available-colors').append('<div class="color-box selected" data-color="'+clr.color+'"> <div class="inner"> <div class="color-circ" style="background:'+clr.color+'"></div> <span class="label">'+clr.label+'</span></div> </div>');
        });
        $('#available-colors>div').on('click', function(evt) {
            setShirtColor($(this).data('color'));
        });

        $("#flat-slider-vertical-1")
            .slider({
                max: 100,
                min: 10,
                range: "min",
                value: 100,
                slide: function(event, ui) {
                    var rMaxHeight = $(r[0]).css('max-height');
                    rMaxHeight = Number(rMaxHeight.substring(0, rMaxHeight.length - 2));
                    var rMaxWidth = $(r[0]).css('max-width');
                    rMaxWidth = Number(rMaxWidth.substring(0, rMaxWidth.length - 2));
                    var newHeight = rMaxHeight * (ui.value/100);
                    var newWidth = rMaxWidth * (ui.value/100);
                    var rHeight = $(r[0]).css('height');
                    rHeight = Number(rHeight.substring(0, rHeight.length - 2));
                    var rWidth = $(r[0]).css('width');
                    rWidth = Number(rWidth.substring(0, rWidth.length - 2));
                    r[0].style.height = newHeight + 'px';
                    r[0].style.width = newWidth + 'px';
                    $('.ui-slider-tip').text(ui.value + '%');

                    var dLeftPos = d.css("left");
                    dLeftPos = Number(dLeftPos.substring(0, dLeftPos.length - 2));

                    var dTopPos = d.css("top");
                    dTopPos = Number(dTopPos.substring(0, dTopPos.length - 2));


                    if (newWidth > rWidth && (Math.ceil(dLeftPos) + Math.ceil(newWidth)) >= 200 || (Math.ceil(dTopPos) + Math.ceil(newHeight)) >= 200) {

                        dLeftPos = dLeftPos - (newWidth - rWidth);
                        if (dLeftPos <= 0) {
                            dLeftPos = 0;
                        }

                        dTopPos -= (newHeight - rHeight);
                        if (dTopPos <= 0) {
                            dTopPos = 0;
                        }

                        d.css("left", dLeftPos + 'px');
                        d.css("top", dTopPos + 'px');
                    }
                },
                change: function(event, ui) {
                    $('.ui-slider-tip').text(ui.value + '%');
                },
                orientation: "horizontal"
            })
            .slider("pips", {
                first: "pip",
                last: "pip",
                step: 2
            })
            .slider("float");

        $('.ui-slider-tip').text($('.ui-slider-tip').text() + '%');


        centerImageHorizontally();
        centerImageVertically();
    };

    /**
     * Center Image Horizontally
     */
    var centerImageHorizontally = function()
    {
        var d = $('#placement-inner'),
            r = $('#placement-artwork');

        var dHorizPos = (200 - r[0].width)/2;

        d.css("right", dHorizPos);
        d.css("left", dHorizPos);
    };

    /**
     * Center Image Vertically
     */
    var centerImageVertically = function()
    {
        var d = $('#placement-inner'),
            r = $('#placement-artwork');

        var dTop = (200 - r[0].height)/2;

        d.css("top", dTop);
    };


    /**
     *        Placement ui loader
     */
    var showPlacementLoader = function( show )
    {
        if( show ) {
            $('#placement-loader').show();
        }else {
            $('#placement-loader').hide();
        }
    };


    /**
     *        Set shirt template
     *        Based on the colors selected,
     *        fill the designplacement area with
     *        the correct shirt template
     */
    var setShirtTemplate = function()
    {
        disableSteps();        //prevent moving on at this point

        shirt_color = colors[0].color;

        var color = colors[0].color.replace('#', ''),
            template = V2W.shirts + shirt_prefix +  color + '.png';


        //remove old img element
        $('#placement-artwork').remove();




        //figure image scale
        var ratio = 200 / 3600,        //template is 3600, but scaling down to 200
            w = artwork.width * ratio,
            h = artwork.width * ratio;


        //create new img element
        var img = $('<img id="placement-artwork" />')
            .attr('src', artwork.url)
            .attr('width', w)
            .attr('height', h)
            .css({
                maxWidth: (w < 200) ? w : 200,        //in case too large
                maxHeight: (h < 200) ? h : 200        //in case too large
            });


        showPlacementLoader( true );


        //preload the image
        $(img).one('load', function(evt) {


            $('#placement-inner').html( img );
            $('#placement-inner').css({
                maxWidth: (w < 200) ? w + 4 : 204,        //in case too large
                maxHeight: (h < 200) ? h + 4 : 204        //in case too large
            });


            //set template
            $('#placement-outer').css({
                'background-image': 'url('+ template +')',
                'background-repeat': 'no-repeat',
                'background-position': 'top left'
            });


            initPlacement( w, h );
            showPlacementLoader( false );


            //resume
            enableSteps();
        });
    };

    /**
     * Set the style for the shirt template
     */
    var setShirtStyle = function(shirtStyle)
    {
        shirt_prefix = shirtStyle;
        replaceShirtTemplate();
    };

    /**
     * Set the style for the shirt template
     */
    var setShirtColor = function(shirtColor)
    {
        shirt_color = shirtColor;
        replaceShirtTemplate();
    };

    /**
     *        Set shirt template
     *        Based on the colors and style selected,
     *        fill the designplacement area with
     *        the correct shirt template
     */
    var replaceShirtTemplate = function()
    {
        disableSteps();        //prevent moving on at this point

        var color = shirt_color.replace('#', ''),
            template = V2W.shirts + shirt_prefix +  color + '.png';

        //set template
        $('#placement-outer').css({
            'background-image': 'url('+ template +')',
            'background-repeat': 'no-repeat',
            'background-position': 'top left'
        });

        //resume
        enableSteps();
    };


    /**
     *        Submit Design
     */
    $.fn.submitDesign = function()
    {
        form = $(this);


        /**
         *        Init File Uploader
         *        Allows user to upload their
         *        design.
         */
        initFileUploader();


        /**
         *        Advance Buttons
         *        When a user clicks continue on
         *        any step, this event handler will
         *        take over.
         */
        $(this).find('a.advance').on('click', function(evt) {
            evt.preventDefault();


            //clear existing errors
            clearErrors();


            //handle steps
            switch( currentStep() )
            {
                case 1:
                    validateStep1();
                    break;
                case 2:
                    validateStep2();
                    break;
                case 3:
                    validateStep3();
                    break;
            }


            return false;
        });


        /**
         *        Back Buttons
         *        When a user clicks back on any
         *        step, this event handler will take
         *        over.
         */
        $(this).find('a.reverse').on('click', function(evt) {
            evt.preventDefault();


            previousStep();


            return false;
        });


        /**
         *        Primary Color Tabs
         *        Allows users to switch between primary
         *        color views
         */
        colorSwitcher();


        /**
         *        Color Selectors
         *        When a user adds/removes a color,
         *        this event handler will take over.
         */
        colorPicker();


        /**
         *        Placement
         *        Allows user to set the placement
         *        of their design within the shirt
         *        template. Have to re-init each time
         *        the last step is loaded
         */
        $(this).on('step', function(evt, step) {
            if( step == 3 ) {
                setShirtTemplate();
                //initPlacement();        //moved to a callback on previous called function
            }
        });


        /**
         *        Form Submit
         *        If the user submits the form via
         *        submit button or keyboard event, this
         *        event handler will take over.
         */
        $(this).on('submit', function(evt) {
            //handling this with other methods
            return false;
        });

        return this;
    };


    /**
     *        Validate Step 1
     */
    var validateStep1 = function()
    {
        var n = form.find('input[name=name]'),
            d = form.find('textarea'),
            t = form.find('input[name=tags]'),
            f = form.find('input[type=file]').parent();


        var legal = form.find('#dlegal');


        var errors = false;


        //validate legal agreement
        if( ! legal.is(':checked') ) {
            getCurrentStep().find('.msg').html('You must agree to the terms and conditions.');
            return;
        }


        //if uploading a file, display message and quit
        if( is_uploading ) {
            getCurrentStep().find('.msg').html('Uploading your design. Please wait...');
            return;
        }


        if( ! artwork ) {
            f.addClass('error');
            errors = true;
        }


        if( n.val() == '' ) {
            n.addClass('error');
            errors = true;
        }


        if( d.val() == '' ) {
            d.addClass('error');
            errors = true;
        }


        //@todo are tags required?


        if( ! errors ) {
            nextStep();
        }
    };


    /**
     *        Validate Step 2
     */
    var validateStep2 = function()
    {
        //ensure at least one color is selected
        if( isColorsEmpty() ) {
            getCurrentStep().find('.msg').html('Select at least one color for your shirt design.');
            return;
        }


        nextStep();


    };


    /**
     *        Validate Step 3
     */
    var validateStep3 = function()
    {
        //if the form is processing, do nothing
        if( is_submitting || ! steps_enabled )
            return false;


        var btn = getCurrentStep().find('.advance');


        //disable form
        is_submitting = true;


        //user feedback
        btn.text('Processing...');


        //get placed design details
        var w = $('#placement-artwork').width(),
            h = $('#placement-artwork').height(),
            o = $('#placement-inner').position();


        //fix position
        o.left -= 141;
        o.top -= 65;

        var colorsOnly = [];

        colors.forEach(function(clrObj){
            colorsOnly.push(clrObj.color);
        });

        var defaultTemplateObj = {shirt_color: shirt_color};

        if (shirt_prefix === "/womens/3900-") {
            defaultTemplateObj.shirt_prefix = "womens";
        } else {
            defaultTemplateObj.shirt_prefix = "mens";
        }

        //build data
        var data = {
            action: 'submit_design',
            colors: colorsOnly,
            default_prefix: defaultTemplateObj.shirt_prefix,
            default_color: defaultTemplateObj.shirt_color,
            artwork: artwork,
            width: w,
            height: h,
            offset: o,
            name: form.find('input[name=name]:first').val(),
            description: form.find('textarea[name=description]:first').val(),
            tags: form.find('input[name=tags]:first').val(),
        };


        //submit the design
        $.ajax({
            url: V2W.ajaxurl,
            type: 'POST',
            data: data,
            success: function(result) {

                //if( result.code != 200 ) {
                    //is_submitting = false;
                    //btn.text('Submit Design');
                //}else {
                    //success!!!
                    nextStep();
                    resetForm();
                //}

            },
            error: function(){
    		is_submitting = false;
                btn.text('Submit Design');
                getCurrentStep().find('.final-step-error').html('Error sumbitting design, please try again.');
  	    }
        });


    };


    /**
     *        Reset Form
     *        Clears various form f
     
     lds
     *        for security purposes
     */
    var resetForm = function()
    {
        form.find('input[type=text]').val('');
        artwork = false;
        colors = [];
        steps_enabled = false;
    };




}(jQuery));


//DOM Ready
jQuery(document).ready(function($) {
    $('#dform').submitDesign();


    //legal checkbox toggle on span click
    $('#dstep1 .legal').each(function() {
        var cb = $(this).find('input[type=checkbox]');
        $(this).find('span').on('click', function(evt) {
            cb.trigger('click');
        });
    });
});