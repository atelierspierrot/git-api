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
@ini_set('display_errors', '1'); @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

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
    for ($i=0; $i<=$depth_from_root; $i++) {
        array_pop($parts);
    }
    return str_replace(join($ds, $parts), $ds.'[***]', $path);
}

/**
 * GET arguments settings
 */
$arg_ln = isset($_GET['ln']) ? $_GET['ln'] : 'en';

function getPhpClassManualLink($class_name, $ln='en')
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

// -----------------------------------
// Page Content
// -----------------------------------

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test & documentation of PHP "git-api" package</title>
    <meta name="description" content="A set of PHP classes to crypt and uncrypt" />
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Git API</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right" role="navigation">
                <li><a href="#bottom" title="Go to the bottom of the page">&darr;</a></li>
                <li><a href="#top" title="Back to the top of the page">&uarr;</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">

    <a id="top"></a>

    <header role="banner">
        <h1>The PHP "<em>git-api</em>" package <br><small>A PHP API to get infos and manage a <a href="http://git-scm.com/">GIT</a> distant or local repository</small></h1>
        <div class="hat">
            <p>These pages show and demonstrate the use and functionality of the <a href="http://github.com/atelierspierrot/git-api">atelierspierrot/git-api</a> PHP package you just downloaded.</p>
        </div>
    </header>

    <div id="content" role="main">

<form action="" method="get">
    <label>Repository to test:&nbsp;
    <input type="text" name="repo" value="<?php echo $repo; ?>" size="60" />
    </label>
    <input type="submit" />
</form>

<pre>
<?php
if (!empty($repo)) {
    if (\Library\Helper\Url::isUrl($repo)) {
        $tmp_dir = __DIR__.'/tmp';
        if (!file_exists($tmp_dir)) {
            if (!mkdir($tmp_dir)) {
                die(
                    sprintf('Can not create temporary directory "%s"!', $tmp_dir)
                );
            }
        }
        if (!is_writable($tmp_dir)) {
            die(
                sprintf('Temporary directory "%s" MUST be writable!', $tmp_dir)
            );
        }
        $a = \GitApi\GitApi::create(
            $tmp_dir.'/'.basename($repo), $repo
        );
    } else {
        $a = \GitApi\GitApi::open($repo);
    }

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
    foreach ($a->getTree() as $item) {
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

        </div>
    </div>

<footer id="footer">
    <div class="container">
        <div class="text-muted pull-left">
            This page is <a href="" title="Check now online" id="html_validation">HTML5</a> & <a href="" title="Check now online" id="css_validation">CSS3</a> valid.
        </div>
        <div class="text-muted pull-right">
            <a href="http://github.com/atelierspierrot/git-api">atelierspierrot/git-api</a> package by <a href="http://github.com/atelierspierrot">Les Ateliers Pierrot</a> under <a href="http://www.apache.org/licenses/LICENSE-2.0">Apache 2.0</a> license.
            <p class="text-muted small" id="user_agent"></p>
        </div>
    </div>
</footer>

<div id="message_box" class="msg_box"></div>
<a id="bottom"></a>

<!-- jQuery lib -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<!-- jQuery.tablesorter plugin
<script src="assets/js/jquery.tablesorter.min.js"></script>
-->

<!-- jQuery.highlight plugin -->
<script src="assets/js/highlight.js"></script>

<!-- scripts for demo -->
<script src="assets/scripts.js"></script>

<script>
    $(function() {
        getToHash();
        addCSSValidatorLink('assets/styles.css');
        addHTMLValidatorLink();
        $("#user_agent").html( navigator.userAgent );
        $('pre').each(function(i,o) {
            var dl = $(this).attr('data-language');
            if (dl) {
                $(this).addClass('code')
                    .highlight({indent:'tabs', code_lang: 'data-language'});
            }
        });
        initHandler('classinfo', true);
        initHandler('plaintext', true);
    });
</script>
</body>
</html>
