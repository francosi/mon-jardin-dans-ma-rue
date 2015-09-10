/*
	Parallax plugin v1.1
	December, 2013
	
	Author : BeliG
	Doc : http://www.design-fluide.com/?p=987
 */
(function(e){e.fn.parallax=function(t){var n=e(this);var r=n.offset();var i=n.position();var s={coeff:0,type:"background",visibility:n.css("visibility"),position:n.css("position")};var o=e.extend(s,t);var u=function(e){if(e.css("backgroundPosition")==undefined){return e.css("backgroundPositionX")}else{return e.css("backgroundPosition").split(" ")[0]}};var a=function(e){newPos=e*o.coeff;if(o.type=="sprite"){newPos+=o.position=="fixed"?i.top:r.top}n.css("background-position",u(n)+" "+newPos+"px")};return this.each(function(){var t=Math.round(r.left);if(o.type=="sprite"){t+=parseInt(u(n));n.css({backgroundPosition:t+"px "+r.top+"px",top:0,bottom:0,height:"auto"})}else if(t!=0){n.css("background-position",t+"px 0px")}n.css("background-attachment","fixed");var i=e(window).scrollTop();if(i>0){a(i)}if(o.visibility=="hidden"){n.css("visibility","visible")}e(window).bind("scroll",function(){i=e(window).scrollTop();if(i<parseInt(r.top+n.height())&&parseInt(e(window).height()+i)>r.top){a(i)}})})}})(jQuery)