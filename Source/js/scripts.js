// JavaScript Document
function alertnateRows()
{
		var i=0;
	$('.formTable tr').each(function(index) {

			i++;
			var cl = "r1";
			if(i%2 == 0)
				cl = "r2";
			
			$(this).addClass(cl);
	});
}