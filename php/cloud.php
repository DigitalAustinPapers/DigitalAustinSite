<?php
	include('php/wordcloud.class.php');
	// Condense cloud
	// also, people/places
	// move to top
	// clickable tags
	// Initite the basic object
	function cloud($text, $numWords){
		$cloud = new wordCloud();
		// Basic Add Option
		$cloud->addString($text);
		$cloud->removeWord('and');
		$cloud->removeWord('a');
		$cloud->removeWord('the');
		$cloud->removeWord('I');
		$cloud->removeWord('it');
		$cloud->removeWord('have');
		$cloud->removeWord('to');
		$cloud->removeWord('of');
		$cloud->removeWord('that');
		$cloud->removeWord('is');
		$cloud->removeWord('be');
		$cloud->removeWord('in');
		$cloud->removeWord('as');
		$cloud->removeWord('you');
		$cloud->removeWord('for');
		$cloud->removeWord('will');
		$cloud->removeWord('all');
		$cloud->removeWord('not');
		$cloud->removeWord('from');
		$cloud->removeWord('me');
		$cloud->removeWord('or');
		$cloud->removeWord('would');
		$cloud->removeWord('has');
		$cloud->removeWord('now');
		$cloud->removeWord('had');
		$cloud->removeWord('since');
		$cloud->removeWord('about');
		$cloud->removeWord('by');
		$cloud->removeWord('on');
		$cloud->removeWord('so');
		$cloud->removeWord('there');
		$cloud->removeWord('was');
		$cloud->removeWord('with');
		$cloud->removeWord('if');
		$cloud->removeWord('they');
		$cloud->removeWord('were');
		$cloud->removeWord('which');
		$cloud->removeWord('your');
		$cloud->removeWord('but');
		$cloud->removeWord('at');
		$cloud->removeWord('this');
		$cloud->removeWord('he');
		$cloud->removeWord('am');
		$cloud->removeWord('are');
		$cloud->removeWord('here');
		$cloud->removeWord('my');
		$cloud->removeWord('no');
		$cloud->removeWord('them');
		$cloud->removeWord('there');
		$cloud->removeWord('can');
		$cloud->removeWord('been');
		$cloud->removeWord('an');
		$cloud->removeWord('their');
		$cloud->removeWord('him');
		$cloud->removeWord('we');
		$cloud->removeWord('very');
		$cloud->removeWord('up');
		$cloud->removeWord('mr');
		$cloud->removeWord('mr.');
		$cloud->removeWord('being');
		$cloud->removeWord('do');
		$cloud->removeWord('much');
		$cloud->removeWord('what');
		$cloud->removeWord('her');
		$cloud->removeWord('one');
		$cloud->removeWord('f');
		$cloud->removeWord('shall');
		$cloud->removeWord('his');
		$cloud->removeWord('than');
		$cloud->removeWord('n');
		$cloud->removeWord('get');
		$cloud->removeWord('may');
		$cloud->removeWord('our');
		$cloud->removeWord('some');
		$cloud->removeWord('when');
		$cloud->removeWord('any');
		$cloud->removeWord('who');
		$cloud->removeWord('out');
		$cloud->removeWord('should');
		$cloud->removeWord('told');
		$cloud->removeWord('made');
		$cloud->removeWord('also');
		$cloud->removeWord('us');
		$cloud->removeWord('said');
		$cloud->removeWord('could');
		$cloud->removeWord('before');
		$cloud->removeWord('such');
		$cloud->removeWord('other');
		$cloud->removeWord('its');
		$cloud->orderBy('size', 'desc');
		$cloud->setLimit($numWords);
		echo $cloud->showCloud();
	}
?>