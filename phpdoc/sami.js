(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '    <ul>                <li data-name="namespace:GitApi" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="GitApi.html">GitApi</a>                    </div>                    <div class="bd">                            <ul>                <li data-name="class:GitApi_GitApi" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="GitApi/GitApi.html">GitApi</a>                    </div>                </li>                            <li data-name="class:GitApi_GitCommand" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="GitApi/GitCommand.html">GitCommand</a>                    </div>                </li>                            <li data-name="class:GitApi_GitRuntimeException" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="GitApi/GitRuntimeException.html">GitRuntimeException</a>                    </div>                </li>                            <li data-name="class:GitApi_Repository" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="GitApi/Repository.html">Repository</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    {"type": "Namespace", "link": "GitApi.html", "name": "GitApi", "doc": "Namespace GitApi"},
            
            {"type": "Class", "fromName": "GitApi", "fromLink": "GitApi.html", "link": "GitApi/GitApi.html", "name": "GitApi\\GitApi", "doc": "&quot;Largely inspired from &lt;a href=\&quot;http:\/\/github.com\/kbjr\/Git.php\&quot;&gt;http:\/\/github.com\/kbjr\/Git.php&lt;\/a&gt;&quot;"},
                                                        {"type": "Method", "fromName": "GitApi\\GitApi", "fromLink": "GitApi/GitApi.html", "link": "GitApi/GitApi.html#method_setBin", "name": "GitApi\\GitApi::setBin", "doc": "&quot;Sets git executable path&quot;"},
                    {"type": "Method", "fromName": "GitApi\\GitApi", "fromLink": "GitApi/GitApi.html", "link": "GitApi/GitApi.html#method_getBin", "name": "GitApi\\GitApi::getBin", "doc": "&quot;Gets git executable path&quot;"},
                    {"type": "Method", "fromName": "GitApi\\GitApi", "fromLink": "GitApi/GitApi.html", "link": "GitApi/GitApi.html#method_create", "name": "GitApi\\GitApi::create", "doc": "&quot;Create a new git repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\GitApi", "fromLink": "GitApi/GitApi.html", "link": "GitApi/GitApi.html#method_open", "name": "GitApi\\GitApi::open", "doc": "&quot;Open an existing git repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\GitApi", "fromLink": "GitApi/GitApi.html", "link": "GitApi/GitApi.html#method_isRepository", "name": "GitApi\\GitApi::isRepository", "doc": "&quot;Checks if a variable is an instance of GitRepo&quot;"},
            
            {"type": "Class", "fromName": "GitApi", "fromLink": "GitApi.html", "link": "GitApi/GitCommand.html", "name": "GitApi\\GitCommand", "doc": "&quot;Largely inspired from &lt;a href=\&quot;http:\/\/github.com\/kbjr\/Git.php\&quot;&gt;http:\/\/github.com\/kbjr\/Git.php&lt;\/a&gt;&quot;"},
                                                        {"type": "Method", "fromName": "GitApi\\GitCommand", "fromLink": "GitApi/GitCommand.html", "link": "GitApi/GitCommand.html#method_run", "name": "GitApi\\GitCommand::run", "doc": "&quot;Run a command in the git repository&quot;"},
            
            {"type": "Class", "fromName": "GitApi", "fromLink": "GitApi.html", "link": "GitApi/GitRuntimeException.html", "name": "GitApi\\GitRuntimeException", "doc": "&quot;\n&quot;"},
                    
            {"type": "Class", "fromName": "GitApi", "fromLink": "GitApi.html", "link": "GitApi/Repository.html", "name": "GitApi\\Repository", "doc": "&quot;Largely inspired from &lt;a href=\&quot;http:\/\/github.com\/kbjr\/Git.php\&quot;&gt;http:\/\/github.com\/kbjr\/Git.php&lt;\/a&gt;&quot;"},
                                                        {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method___construct", "name": "GitApi\\Repository::__construct", "doc": "&quot;Constructor&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_setGitConsole", "name": "GitApi\\Repository::setGitConsole", "doc": "&quot;Define the console object&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getGitConsole", "name": "GitApi\\Repository::getGitConsole", "doc": "&quot;Get the console object&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getRepositoryPath", "name": "GitApi\\Repository::getRepositoryPath", "doc": "&quot;Get the repository local path&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_createNew", "name": "GitApi\\Repository::createNew", "doc": "&quot;Create a new git repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_setRepositoryPath", "name": "GitApi\\Repository::setRepositoryPath", "doc": "&quot;Set the repository&#039;s path&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_testGit", "name": "GitApi\\Repository::testGit", "doc": "&quot;Tests if git is installed&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_run", "name": "GitApi\\Repository::run", "doc": "&quot;Run a git command in the git repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getRemoteOriginUrl", "name": "GitApi\\Repository::getRemoteOriginUrl", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCommitsList", "name": "GitApi\\Repository::getCommitsList", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCommitsHistory", "name": "GitApi\\Repository::getCommitsHistory", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getLastCommitInfos", "name": "GitApi\\Repository::getLastCommitInfos", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCommitInfos", "name": "GitApi\\Repository::getCommitInfos", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCommitChanges", "name": "GitApi\\Repository::getCommitChanges", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCommitersList", "name": "GitApi\\Repository::getCommitersList", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getRecursiveTree", "name": "GitApi\\Repository::getRecursiveTree", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getTree", "name": "GitApi\\Repository::getTree", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getFilesInfo", "name": "GitApi\\Repository::getFilesInfo", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getRaw", "name": "GitApi\\Repository::getRaw", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_isImageContent", "name": "GitApi\\Repository::isImageContent", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getRawImageData", "name": "GitApi\\Repository::getRawImageData", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_add", "name": "GitApi\\Repository::add", "doc": "&quot;Runs a &lt;code&gt;git add&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_commit", "name": "GitApi\\Repository::commit", "doc": "&quot;Runs a &lt;code&gt;git commit&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_cloneTo", "name": "GitApi\\Repository::cloneTo", "doc": "&quot;Runs a &lt;code&gt;git clone&lt;\/code&gt; call to clone the current repository\ninto a different directory&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_cloneFrom", "name": "GitApi\\Repository::cloneFrom", "doc": "&quot;Runs a &lt;code&gt;git clone&lt;\/code&gt; call to clone a different repository\ninto the current repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_cloneRemote", "name": "GitApi\\Repository::cloneRemote", "doc": "&quot;Runs a &lt;code&gt;git clone&lt;\/code&gt; call to clone a remote repository\ninto the current repository&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_clean", "name": "GitApi\\Repository::clean", "doc": "&quot;Runs a &lt;code&gt;git clean&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_createBranch", "name": "GitApi\\Repository::createBranch", "doc": "&quot;Runs a &lt;code&gt;git branch&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_deleteBranch", "name": "GitApi\\Repository::deleteBranch", "doc": "&quot;Runs a &lt;code&gt;git branch -[d|D]&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getBranchesList", "name": "GitApi\\Repository::getBranchesList", "doc": "&quot;Runs a &lt;code&gt;git branch&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getCurrentBranch", "name": "GitApi\\Repository::getCurrentBranch", "doc": "&quot;Returns name of active branch&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_checkout", "name": "GitApi\\Repository::checkout", "doc": "&quot;Runs a &lt;code&gt;git checkout&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_merge", "name": "GitApi\\Repository::merge", "doc": "&quot;Runs a &lt;code&gt;git merge&lt;\/code&gt; call&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_fetch", "name": "GitApi\\Repository::fetch", "doc": "&quot;Runs a git fetch on the current branch&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getTagsList", "name": "GitApi\\Repository::getTagsList", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_buildTagTarball", "name": "GitApi\\Repository::buildTagTarball", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_buildTarball", "name": "GitApi\\Repository::buildTarball", "doc": "&quot;\n&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_addTag", "name": "GitApi\\Repository::addTag", "doc": "&quot;Add a new tag on the current position&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_push", "name": "GitApi\\Repository::push", "doc": "&quot;Push specific branch to a remote&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_pull", "name": "GitApi\\Repository::pull", "doc": "&quot;Pull specific branch from remote&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_setDescription", "name": "GitApi\\Repository::setDescription", "doc": "&quot;Sets the project description.&quot;"},
                    {"type": "Method", "fromName": "GitApi\\Repository", "fromLink": "GitApi/Repository.html", "link": "GitApi/Repository.html#method_getDescription", "name": "GitApi\\Repository::getDescription", "doc": "&quot;Gets the project description.&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


