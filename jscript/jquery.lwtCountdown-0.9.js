/*!
 * jQuery Countdown plugin v0.9
 * http://projects.littlewebthings.com/lwtCountdown/
 *
 * Copyright 2010, Vassilis Dourdounis
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
(function($){

	$.fn.countDown = function (options) {

		config = {};

		$.extend(config, options);

		var targetTime = new Date();
		targetTime.setDate(config.targetDate.day);
		targetTime.setMonth(config.targetDate.month-1);
		targetTime.setFullYear(config.targetDate.year);
		targetTime.setHours(config.targetDate.hour);
		targetTime.setMinutes(config.targetDate.min);
		targetTime.setSeconds(config.targetDate.sec);

		var nowTime = new Date();

		diffSecs = Math.floor((targetTime.valueOf()-nowTime.valueOf())/1000);

		$('#' + this.attr('id') + ' .digit').html('<div class="top"></div><div class="bottom"></div>');
		this.doCountDown(diffSecs, 500);

	}

	$.fn.doCountDown = function (diffSecs, duration) {
		if (diffSecs <= 0)
		{
			diffSecs = 0;
		}

		secs = diffSecs % 60;
		mins = Math.floor(diffSecs/60)%60;
		hours = Math.floor(diffSecs/60/60)%24;
		days = Math.floor(diffSecs/60/60/24)%7;
		weeks = Math.floor(diffSecs/60/60/24/7);

		this.dashChangeTo('seconds_dash', secs, duration ? duration : 800);
		this.dashChangeTo('minutes_dash', mins, duration ? duration : 1200);
		this.dashChangeTo('hours_dash', hours, duration ? duration : 1200);
		this.dashChangeTo('days_dash', days, duration ? duration : 1200);
		this.dashChangeTo('weeks_dash', weeks, duration ? duration : 1200);

		if (diffSecs > 0)
		{
			e = this;
			setTimeout(function() { e.doCountDown(diffSecs-1) } , 1000);
		}
	}

	$.fn.dashChangeTo = function(dash, n, duration) {
		d2 = n%10;
		d1 = (n - n%10) / 10

		if ($('#' + dash))
		{
			this.digitChangeTo('#' + this.attr('id') + ' .' + dash + ' .digit:first', d1, duration);
			this.digitChangeTo('#' + this.attr('id') + ' .' + dash + ' .digit:last', d2, duration);
		}
	}

	$.fn.digitChangeTo = function (digit, n, duration) {
		if (!duration)
		{
			duration = 800;
		}
		if ($(digit + ' div.top').html() != n + '')
		{

			$(digit + ' div.top').css({'display': 'none'});
			$(digit + ' div.top').html((n ? n : '0')).slideDown(duration);

			$(digit + ' div.bottom').animate({'height': ''}, duration, function() {
				$(digit + ' div.bottom').html($(digit + ' div.top').html());
				$(digit + ' div.bottom').css({'display': 'block', 'height': ''});
				$(digit + ' div.top').hide().slideUp(10);

			
			});
		}
	}

})(jQuery);


