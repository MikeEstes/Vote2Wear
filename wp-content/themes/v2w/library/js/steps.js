(function($) {

	var wrap, tabs, steps;

	// Are steps enabled?
	var steps_enabled = true;

	/**
	 *	Get total number of 
	 *	Steps
	 *
	 *	@return (int)
	 */
	var howMany = function() 
	{
		return steps.length;
	};

	/**
	 *	Get the current step DOM
	 *
	 *	@return (jQuery)
	 */
	var getCurrentStep = function() 
	{
		return steps.eq( currentStep() -1 );
	};

	/**
	 *	Get current step number
	 *
	 *	@return (int) current step
	 */
	var currentStep = function() 
	{
		var active = steps.filter('.active');
		return ( steps.index(active) ) + 1;
	};

	/**
	 *	Is last step?
	 *
	 *	@return (bool)
	 */
	var isLastStep = function() 
	{
		return ( currentStep() == steps.length ) ? true : false;
	};

	/**
	 *	Is step?
	 *	Checks to see if the current step
	 *	matches param
	 *
	 *	@pararm (int) step
	 */
	var isStep = function( step ) 
	{
		return (currentStep() == step) ? true :  false;
	};

	/**
	 *	Go to a Step
	 *
	 *	@param (int) step to visit
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
		 *	If there are more panels than
		 *	tabs, and the current panel is
		 *	beyond that of the steps, then
		 *	use the last tab as active
		 */
		if( (step+1) > tabs.length )
			tabs.filter(':last').addClass('active');
		else
			tabs.eq(step).addClass('active');

		//notify everybody!
		wrap.trigger('step', [step+1]);
	};

	/**
	 *	Previous Step
	 */
	var previousStep = function() 
	{
		goToStep( currentStep() - 1 );
	};

	/**
	 *	Next Step
	 */
	var nextStep = function() 
	{
		goToStep( currentStep() + 1 );
	};

	/**
	 *	Disable steps to prevent 
	 *	advancing.
	 */
	var disableSteps = function() 
	{
		steps_enabled = false;
	};

	/**
	 *	Enable steps
	 */
	var enableSteps = function() 
	{
		steps_enabled = true;
	};

	/**
	 *	Steps
	 */
	$.fn.steps = function() 
	{
		wrap = this,
		tabs = this.find('ul.tabs>li'),
		steps = this.find('ul.steps>li');

		//step backward
		this.find('.step-back').on('click', function(evt) {
			evt.preventDefault();
			previousStep();
		});

		//step forward
		this.find('.step-forward').on('click', function(evt) {
			evt.preventDefault();
			nextStep();
		});

		tabs.on('click', function(evt) {
			evt.preventDefault();
			var i = tabs.index($(this));
			goToStep(i+1);
		});

		return this;
	};

}(jQuery));