<?php
const GOAL = 25000;

$csv = fopen('sessions.csv', 'r');
$headers = fgetcsv($csv);
$sessions = [];
while ($line = fgetcsv($csv)) {
	$session = [];
	foreach ($headers as $hI => $h) {
		$session[$h] = $line[$hI];
	}
	$sessions []= $session;
}

$minutesTotal = 0;
$wordsTotal = 0;
$minutesPerDay = [];
$wordsPerDay = [];

foreach ($sessions as $session) {
	$minutesTotal += $session['MINUTES'];
	$wordsTotal += $session['WORDS'];

	if (!isset($wordsPerDay[$session['DAY']])) {
		$wordsPerDay[$session['DAY']] = $session['WORDS'];
	} else {
		$wordsPerDay[$session['DAY']] += $session['WORDS'];
	}

	if (!isset($minutesPerDay[$session['DAY']])) {
		$minutesPerDay[$session['DAY']] = $session['MINUTES'];
	} else {
		$minutesPerDay[$session['DAY']] += $session['MINUTES'];
	}
}

foreach ($minutesPerDay as $day => $minutes) {
	echo 'day ' . $day . ' > ' . $minutes . ' minutes, ' . $wordsPerDay[$day] . ' words, ' . (int) ($wordsPerDay[$day] / $minutes) . ' wpm' . PHP_EOL;
}

$hoursDisp = (int) ($minutesTotal / 60);
$minutesDisp = $minutesTotal % 60;
$wpm = $wordsTotal / $minutesTotal;
echo '--> ' . $hoursDisp . 'h' . $minutesDisp . 'm, ' . $wordsTotal . ' words, ' . (int) $wpm . ' wpm' . PHP_EOL;

$minutesLeft = (GOAL - $wordsTotal) / $wpm;
$hoursLeft = (int) ($minutesLeft / 60);
$minutesLeft = $minutesLeft % 60;
echo '--> ' . $hoursLeft . 'h' . $minutesLeft . 'm of work left before reaching ' . GOAL . ' words' . PHP_EOL;
