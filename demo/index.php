<?php

/**
 * Show errors at least initially
 *
 * `E_ALL` => for hard dev
 * `E_ALL & ~E_STRICT` => for hard dev in PHP5.4 avoiding strict warnings
 * `E_ALL & ~E_NOTICE & ~E_STRICT` => classic setting
 */
//@ini_set('display_errors','1'); @error_reporting(E_ALL);
//@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_STRICT);
@ini_set('display_errors','1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

/**
 * Set a default timezone to avoid PHP5 warnings
 */
$dtmz = @date_default_timezone_get();
date_default_timezone_set($dtmz?:'Europe/Paris');

/**
 * For security, transform a realpath as '/[***]/package_root/...'
 *
 * @param string $path
 * @param int $depth_from_root
 *
 * @return string
 */
function _getSecuredRealPath($path, $depth_from_root = 1)
{
    $ds = DIRECTORY_SEPARATOR;
    $parts = explode($ds, realpath('.'));
    for ($i=0; $i<=$depth_from_root; $i++) array_pop($parts);
    return str_replace(join($ds, $parts), $ds.'[***]', $path);
}

/**
 * GET arguments settings
 */
$arg_ln = isset($_GET['ln']) ? $_GET['ln'] : 'en';

function getPhpClassManualLink( $class_name, $ln='en' )
{
    return sprintf('http://php.net/manual/%s/class.%s.php', $ln, strtolower($class_name));
}

if (file_exists($a = __DIR__.'/../vendor/autoload.php')) {
    require_once $a;
} else {
    die('You need to run Composer on your project to use this interface!');
}

$repo = !empty($_GET) && isset($_GET['repo']) ? $_GET['repo'] : 
    \Library\Helper\Url::resolvePath(__DIR__.'/..', true);

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <!--[if IE]> <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> <![endif]-->
    <title>GIT API package demo</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="assets/html5boilerplate/css/normalize.css" />
    <link rel="stylesheet" href="assets/html5boilerplate/css/main.css" />
    <script src="assets/html5boilerplate/js/vendor/modernizr-2.6.2.min.js"></script>
	<link rel="stylesheet" href="assets/styles.css" />
    <!--[if lt IE 5.5]> <link rel="stylesheet" href="assets/styles_ie_lt5.5.css" /> <![endif]-->
    <!--[if gte IE 5.5]> <link rel="stylesheet" href="assets/styles_ie_gte5.5.css" /> <![endif]-->
</head>
<body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <header id="top" role="banner">
        <hgroup>
            <h1>PHP GIT API package demo</h1>
            <h2 class="slogan">A PHP API to get infos and manage a GIT distant or local repository.</h2>
        </hgroup>
        <div class="hat">
            <p>These pages show and demonstrate the use and functionality of the <a href="https://github.com/atelierspierrot/git-api">atelierspierrot/git-api</a> PHP package you just downloaded.</p>
        </div>
    </header>

	<nav>
        <div class="info">
            <p><a href="http://github.com/atelierspierrot/git-api">See online on GitHub</a></p>
            <p class="comment">The sources of this package are hosted on <a href="http://github.com">GitHub</a>. To follow sources updates, report a bug or read opened bug tickets and any other information, please see the GitHub website above.</p>
        </div>

    	<p class="credits" id="user_agent"></p>
	</nav>

    <div id="content" role="main">

        <article>

<form action="" method="get">
    <label>Repository to test:&nbsp;
    <input type="text" name="repo" value="<?php echo $repo; ?>" size="60" />
    </label>
    <input type="submit" />
</form>

<pre>
<?php
if (!empty($repo)) {
    $a = \GitApi\GitApi::open($repo);
    echo '<br />new GitApi object : '.$a->getRepositoryPath();
    echo '<br />';
    var_export($a);

    echo '<br />';
    echo '<br />getCommitersList : ';
    var_export($a->getCommitersList());

    echo '<br />';
    echo '<br />getBranchesList : ';
    $branches = $a->getBranchesList();
    var_export($branches);

    echo '<br />';
    echo '<br />getCommitsList : ';
    $history = $a->getCommitsList();
    var_export($history);

    echo '<br />';
    echo '<br />getCommitInfos for commit : dfdc1f17336deb5aae8d0438098479b328d773fb<br />';
//    var_export($a->getCommitInfos('dfdc1f17336deb5aae8d0438098479b328d773fb'));

    echo '<br />';
    echo '<br />getCurrentBranch : ';
    $br = $a->getCurrentBranch();
    var_export($br);

    echo '<br />';
    echo '<br />getDescription : ';
    var_export($a->getDescription());

    echo '<br />';
    echo '<br />getTagsList : ';
    var_export($a->getTagsList());

    echo '<br />';
    echo '<br />getLastCommitInfos : ';
    var_export($a->getLastCommitInfos());

    echo '<br />';
    echo '<br />getTree : ';
    var_export($a->getTree());

    echo '<br />';
    $first_dir = null;
    foreach($a->getTree() as $item) {
        if ($item['type']==='tree') {
            $first_dir = $item['path'];
            break;
        }
    }
    echo '<br />getTree for first dir: "'.$first_dir.'" : ';
    var_export($a->getTree('HEAD', $first_dir));

    echo '<br />';
    echo '<br />getFilesInfo : ';
    var_export($a->getFilesInfo());

    echo '<br />';
    echo '<br />getRecursiveTree : ';
    var_export($a->getRecursiveTree());

    echo '<br />';
    echo '<br />getCommitsHistory : ';
    var_export($a->getCommitsHistory());

    echo '<br />';
    echo '<hr />';
    echo '<br />commands cache : ';
    var_export($a->getGitConsole()->getCache());
}
?>
</pre>

        </article>
    </div>

    <footer id="footer">
		<div class="credits float-left">
		    This page is <a href="" title="Check now online" id="html_validation">HTML5</a> & <a href="" title="Check now online" id="css_validation">CSS3</a> valid.
		</div>
		<div class="credits float-right">
            <a href="http://github.com/atelierspierrot/git-api">atelierspierrot/git-api</a> package by <a href="http://github.com/atelierspierrot">Les Ateliers Pierrot</a> under <a href="http://opensource.org/licenses/GPL-3.0">GNU GPL v.3</a> license.
		</div>
    </footer>

    <div class="back_menu" id="short_navigation">
        <a href="#footer" title="Go to the bottom of the page"><span class="text">Go to bottom&nbsp;</span>&darr;</a>
        &nbsp;|&nbsp;
        <a href="#top" title="Back to the top of the page"><span class="text">Back to top&nbsp;</span>&uarr;</a>
        <ul id="short_menu" class="menu" role="navigation"></ul>
    </div>

    <div id="message_box" class="msg_box"></div>

<!-- jQuery lib -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/jquery/jquery-last.min.js"><\/script>')</script>
<script>$.uiBackCompat = false;</script>

<!-- jQuery.highlight plugin -->
<script src="assets/jquery/jquery.highlight.js"></script>

<!-- scripts for demo -->
<script src="assets/scripts.js"></script>

<script>
$(function() {
    initBacklinks();
    activateMenuItem();
    getToHash();
    buildFootNotes();
    addCSSValidatorLink('assets/styles.css');
    addHTMLValidatorLink();
    $("#user_agent").html( navigator.userAgent );
    $('pre.code').highlight({source:0, indent:'tabs', code_lang: 'data-language'});
    initHandler('source');
    $('#source_block_content').text( $('#js_code').html() );
});
</script>
<script id="js_code">
</script>
</body>
</html>
