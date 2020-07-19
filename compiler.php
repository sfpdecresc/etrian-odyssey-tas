<?
class P{
	// when in doubt, return FALSE ; only return TRUE when absolutely certain nothing funny will happen
	public static function is_okay_path($m){
		if (!is_string($m)){return FALSE;}
		if ($m === ""  ){return FALSE;}
		if ($m === "." ){return FALSE;}
		if ($m === ".."){return FALSE;}
		// starts with a usual character
		// can also contain slashes and dots as long as there are never two dots side by side
		if (preg_match('/^[a-zA-Z0-9_][a-zA-Z0-9_\.\/]*$/',$m) !== 1){return FALSE;}
		if (preg_match('/\.\./',$m) === 1){return FALSE;}
		return TRUE;}
	public static function repl($regexS,$replaceFxn){global $inS;
		$inS = preg_replace_callback($regexS,$replaceFxn,$inS);}
	public static function error($s){global $writeBinA;
		echo "ERROR : ".$s.PHP_EOL.PHP_EOL;
		die;}
	public static function buildLine($o=[]){
		//|c|RLDUTSBAYXWEGxxx yyy s|
		if (!isset($o["buttonSA"])){$o["buttonSA"] = [];}
		if (!isset($o["xN"      ])){$o["xN"      ] = NULL;}
		if (!isset($o["yN"      ])){$o["yN"      ] = NULL;}
		if (!isset($o["stylusF" ])){$o["stylusF" ] = FALSE;}
		if (!isset($o["micF"    ])){$o["micF"    ] = FALSE;}
		if (!isset($o["resetF"  ])){$o["resetF"  ] = FALSE;}
		if (!isset($o["lidF"    ])){$o["lidF"    ] = FALSE;}
		//....
		//       |c|RLDUTSBAYXWEGxxx yyy s|
		$resS = "|0|.............000 000 0|";
		$resS[1] = strval((($o["micF"]?1:0)+($o["resetF"]?2:0)+($o["lidF"]?4:0)))[0];
		if (in_array("R",$o["buttonSA"],TRUE)){$resS[ 3] = "R";}
		if (in_array("L",$o["buttonSA"],TRUE)){$resS[ 4] = "L";}
		if (in_array("D",$o["buttonSA"],TRUE)){$resS[ 5] = "D";}
		if (in_array("U",$o["buttonSA"],TRUE)){$resS[ 6] = "U";}
		if (in_array("T",$o["buttonSA"],TRUE)){$resS[ 7] = "T";} // sTart
		if (in_array("S",$o["buttonSA"],TRUE)){$resS[ 8] = "S";} // Select
		if (in_array("B",$o["buttonSA"],TRUE)){$resS[ 9] = "B";}
		if (in_array("A",$o["buttonSA"],TRUE)){$resS[10] = "A";}
		if (in_array("Y",$o["buttonSA"],TRUE)){$resS[11] = "Y";}
		if (in_array("X",$o["buttonSA"],TRUE)){$resS[12] = "X";}
		if (in_array("W",$o["buttonSA"],TRUE)){$resS[13] = "W";} // West trigger
		if (in_array("E",$o["buttonSA"],TRUE)){$resS[14] = "E";} // East trigger
		if (in_array("G",$o["buttonSA"],TRUE)){$resS[15] = "G";} // debuG
		// 0-255 0-191
		if ($o["xN"] !== NULL){
			$xS = strval($o["xN"]);
			while (mb_strlen($xS) < 3){$xS = "0".$xS;}
			$resS[16] = $xS[mb_strlen($xS)-3];
			$resS[17] = $xS[mb_strlen($xS)-2];
			$resS[18] = $xS[mb_strlen($xS)-1];}
		if ($o["yN"] !== NULL){
			$yS = strval($o["yN"]);
			while (mb_strlen($yS) < 3){$yS = "0".$yS;}
			$resS[20] = $yS[mb_strlen($yS)-3];
			$resS[21] = $yS[mb_strlen($yS)-2];
			$resS[22] = $yS[mb_strlen($yS)-1];}
		if ($o["stylusF"] === TRUE){
			$resS[24] = "1";}
		return $resS;}
}




//========================================================================================================================
// get command-line arguments
//........................................................................................................................
if (!isset($argv[1])){echo "please specify output TAS .dsm file as first argument".PHP_EOL;die;}
// mainly to prevent accidental argument reversal
if (substr($argv[1],mb_strlen($argv[1])-4) !== ".dsm"){echo "please specify output TAS .dsm file as first argument".PHP_EOL;die;}
if (count(getopt("h",["help"])) >= 1){
	echo "Usage: php -f compiler.php <output_file> <input_file>...".PHP_EOL;
	echo "       php -f compiler.php -- --help".PHP_EOL;
	echo "       php -f compiler.php -- -h".PHP_EOL;
	echo PHP_EOL;
	echo "Ex:    php -f compiler.php exampleTAS.dsm library1.txt library2.txt exampleTAS.txt".PHP_EOL;
	echo PHP_EOL;
	echo "This program compiles one or more EtrianOdysseyTAS code files into a .dsm DeSmuME TAS file. The input files are combined in left-to-right order.".PHP_EOL;
	echo PHP_EOL;
	die;}
if (!isset($argv[2])){echo "please specify at least one input TAS .txt file starting with the second argument".PHP_EOL;die;}
for ($argvI = 0; $argvI < $argc; $argvI++){
	if (!P::is_okay_path($argv[$argvI])){echo "please don't use a path-breaker path, just the filename in the current directory - sorry".PHP_EOL;die;}}




//========================================================================================================================
// read all code files, combining them
//........................................................................................................................
$inS = "";
for ($argvI = 2; $argvI < $argc; $argvI++){
	$inS .= file_get_contents($argv[$argvI])." ";}
$outSA = [];




//========================================================================================================================
// .dsm header
//........................................................................................................................
// this line must exist at the front of the file, all others are optional
$outSA[] = "version 1";
$outSA[] = "--------------------------";
// some info on what this .dsm is
$outSA[] = "Etrian Odyssey (North America)";
$outSA[] = "Programmable Tool-Assisted Speedrun";
$outSA[] = "DeSmuME v0.9.11";
$outSA[] = "--------------------------";
// recommended[by desmume] flags
$outSA[] = "emuVersion 91100";
$outSA[] = "romChecksum 7F723E35";
$outSA[] = "romSerial NTR-AKYE-USA";
$outSA[] = "guid B3E28C55-1747-9811-2F54-112D0558F56B";
$outSA[] = "useExtBios 0";
$outSA[] = "advancedTiming 1";
$outSA[] = "useExtFirmware 0";
$outSA[] = "firmNickname EOTAS";
$outSA[] = "firmMessage EOI is best";
$outSA[] = "firmFavColour 8";
$outSA[] = "firmBirthMonth 1";
$outSA[] = "firmBirthDay 1";
$outSA[] = "firmLanguage 1";
$outSA[] = "rtcStartNew 2020-JAN-01 00:00:00:000";
$outSA[] = "--------------------------";




//========================================================================================================================
// remove comments
//........................................................................................................................
// comments
$inS = preg_replace_callback(
	'/\/\/.*$/m',
	function($matchSA){
		return "";},
	$inS);




//========================================================================================================================
// convert loops
//........................................................................................................................
// single-layer loops
$inS = preg_replace_callback(
	'/(\d+)\s*\{(.*?)\}/m',
	function($matchSA){
		return str_repeat($matchSA[2]." ",intval($matchSA[1]));},
	$inS);




//========================================================================================================================
// split code into individual commands, process each into $writeBinA
//........................................................................................................................
$writePointerS = "";
$writeBinA     = [""=>[]];
$comSA = preg_split('/\s+/',trim($inS));
foreach ($comSA as $comS){
	$appS = "";
	// long instruction
	if ($comS[0] === "@"){
		switch (mb_substr($comS,0,4)){default:P::error("invalid syntax, see following line:".PHP_EOL.$comS);
			// reset
			break;case "@rst":
				$appS = P::buildLine(["resetF" => TRUE]);
			// stylus up/down
			break;case "@sty":
				$regexMatchF = FALSE;
				$appS = preg_replace_callback(
					'/@sty\[(\d{1,3}),(\d{1,3}),(\d)\]/',
					function($matchSA)use(&$regexMatchF){$regexMatchF = TRUE;return P::buildLine([
						"xN"      => intval(ltrim($matchSA[1],"0")),
						"yN"      => intval(ltrim($matchSA[2],"0")),
						"stylusF" => ($matchSA[3]==="1"),]);},
					$comS);
				if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}
			// set all signals manually
			break;case "@all":
				$regexMatchF = FALSE;
				$appS = preg_replace_callback(
					'/@all\[(\d)\]\[([RLDUTSBAYXWEG]*)\]\[(\d{1,3}),(\d{1,3}),(\d)\]/',
					function($matchSA)use(&$regexMatchF){$regexMatchF = TRUE;return P::buildLine([
						"micF"     => (intval($matchSA[1])&1)?TRUE:FALSE,
						"resetF"   => (intval($matchSA[1])&2)?TRUE:FALSE,
						"lidF"     => (intval($matchSA[1])&4)?TRUE:FALSE,
						"buttonSA" => str_split(mb_strtoupper($matchSA[2])),
						"xN"       => intval(ltrim($matchSA[3],"0")),
						"yN"       => intval(ltrim($matchSA[4],"0")),
						"stylusF"  => intval($matchSA[5])?TRUE:FALSE,]);},
					$comS);
				if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}}}
	// compilation instructions
	else if ($comS[0] === "#"){
		switch (mb_substr($comS,0,4)){default:P::error("invalid syntax, see following line:".PHP_EOL.$comS);
			// set write pointer (making a function)
			break;case "#fxn":
				$regexMatchF = FALSE;
				$appS = preg_replace_callback(
					'/#fxn\[(\w+)\]/',
					function($matchSA)use(&$regexMatchF,&$writePointerS,&$writeBinA){$regexMatchF = TRUE;
						$writePointerS = $matchSA[1];
						$writeBinA[$writePointerS] = [];
						return "";},
					$comS);
				if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}
			// call function
			break;case "#cal":
				$regexMatchF = FALSE;
				$appS = preg_replace_callback(
					'/#cal\[(\w+)\]/',
					function($matchSA)use(&$regexMatchF,&$writeBinA){$regexMatchF = TRUE;
						if (!isset($writeBinA[$matchSA[1]])){P::error("function [".$matchSA[1]."] not defined");return "";}
						return implode(PHP_EOL,$writeBinA[$matchSA[1]]);},
					$comS);
				if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}
			// label (leave them alone, handle them in the next compilation section)
			break;case "#lbl":
				$regexMatchF = FALSE;
				$appS = preg_replace_callback(
					'/#lbl\[(\w+)\]/',
					function($matchSA)use(&$regexMatchF){$regexMatchF = TRUE;
						return $matchSA[0];},
					$comS);
				if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}
			// optimization, command is ignored, but should have a comment explaining what should have gone in its place pre-optimization
			break;case "#opt":;
			// die, stop parsing, used to make programming/debugging more convenient
			break;case "#die":
				break 2;}}
	// wait instruction
	else if ($comS === "_"){
		$appS = P::buildLine();}
	// short instruction
	else{
		$regexMatchF = FALSE;
		$appS = preg_replace_callback(
			'/([RLDUWEBAYXTSG]+)/',
			function($matchSA)use(&$regexMatchF){$regexMatchF = TRUE;return P::buildLine([
				"buttonSA"=>str_split(mb_strtoupper($matchSA[1]))]);},
			$comS);
		if (!$regexMatchF){P::error("invalid syntax, see following line:".PHP_EOL.$comS);}}
	
	if ($appS !== ""){
		$writeBinA[$writePointerS][] = $appS;}}




//========================================================================================================================
// handle labels
//........................................................................................................................
// add START and END breakpoint label, re-inventory to make sure each element is exactly one command
$comSA = explode(PHP_EOL,"#lbl[TAS_START]".PHP_EOL.implode(PHP_EOL,$writeBinA["main"]).PHP_EOL."#lbl[TAS_END]");
$lineN = 0;
$breakpointNA = [];
foreach ($comSA as $comS){
	$holdLineF = FALSE;
	$comS = preg_replace_callback(
		'/#lbl\[(\w+)\]/',
		function($matchSA)use(&$holdLineF,&$breakpointNA,$lineN){
			$holdLineF = TRUE;
			$breakpointNA[$matchSA[1]] = $lineN;
			return "";},
		$comS);
	if (!$holdLineF){$lineN++;}
	if ($comS !== ""){
		$outSA[] = $comS;}}




//========================================================================================================================
// write out compiled code and display meta information
//........................................................................................................................
file_put_contents($argv[1],implode(PHP_EOL,$outSA));
if (count($breakpointNA) > 0){
	echo "---- labels ----".PHP_EOL;}
foreach ($breakpointNA as $breakpointS=>$breakpointN){
	echo $breakpointS." : ".$breakpointN.PHP_EOL;}
?>