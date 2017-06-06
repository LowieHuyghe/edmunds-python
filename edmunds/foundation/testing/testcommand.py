
from edmunds.console.command import Command, Option
import nose
import sys


class TestCommand(Command):
    """
    Run the application's unittests
    """

    option_list = [
        Option('-V', '--version', action='store_true', help='Output nose version and exit'),
        Option('-p', '--plugins', action='store_true', help='Output list of available plugins and exit. Combine with higher verbosity for greater detail'),
        Option('-v', '--verbose', action='store_true', help='Be more verbose. [NOSE_VERBOSE]'),
        Option('--verbosity', help='Set verbosity; --verbosity=2 is the same as -v'),
        Option('-q', '--quiet', action='store_true', help='Be less verbose'),
        Option('-c', '--config', help='Load configuration from config file(s). May be specified multiple times; in that case, all config files will be loaded and combined'),
        Option('-w', '--where', help='Look for tests in this directory. May be specified multiple times. The first directory passed will be used as the working directory, in place of the current working directory, which is the default. Others will be added to the list of tests to execute. [NOSE_WHERE]'),
        Option('--py3where3WHERE', help='Look for tests in this directory under Python 3.x. Functions the same as \'where\', but only applies if running under Python 3.x or above.  Note that, if present under 3.x, this option completely replaces any directories specified with \'where\', so the \'where\' option becomes ineffective. [NOSE_PY3WHERE]'),
        Option('-m', '--match', '--testmatch', help='Files, directories, function names, and class names that match this regular expression are considered tests.  Default: (?:^|[\b_\./-])[Tt]est [NOSE_TESTMATCH]'),
        Option('--tests', help='Run these tests (comma-separated list). This argument is useful mainly from configuration files; on the command line, just pass the tests to run as additional arguments with no switch.'),
        Option('-l', '--debug', help='Activate debug logging for one or more systems. Available debug loggers: nose, nose.importer, nose.inspector, nose.plugins, nose.result and nose.selector. Separate multiple names with a comma.'),
        Option('--debug-log', help='Log debug messages to this file (default: sys.stderr)'),
        Option('--logging-config', '--log-config', help='Load logging config from this file -- bypasses all other logging config settings.'),
        Option('-I', '--ignore-files', help='Completely ignore any file that matches this regular expression. Takes precedence over any other settings or plugins. Specifying this option will replace the default setting. Specify this option multiple times to add more regular expressions [NOSE_IGNORE_FILES]'),
        Option('-e', '--exclude', help='Don\'t run tests that match regular expression [NOSE_EXCLUDE]'),
        Option('-i', '--include', help='This regular expression will be applied to files, directories, function names, and class names for a chance to include additional tests that do not match TESTMATCH.  Specify this option multiple times to add more regular expressions [NOSE_INCLUDE]'),
        Option('-x', '--stop', action='store_true', help='Stop running tests after the first error or failure'),
        Option('-P', '--no-path-adjustment', action='store_true', help='Don\'t make any changes to sys.path when loading tests [NOSE_NOPATH]'),
        Option('--exe', action='store_true', help='Look for tests in python modules that are executable. Normal behavior is to exclude executable modules, since they may not be import-safe [NOSE_INCLUDE_EXE]'),
        Option('--noexe', action='store_true', help='DO NOT look for tests in python modules that are executable. (The default on the windows platform is to do so.)'),
        Option('--traverse-namespace', action='store_true', help='Traverse through all path entries of a namespace package'),
        Option('--first-package-wins', '--first-pkg-wins', '--1st-pkg-wins', action='store_true', help='nose\'s importer will normally evict a package from sys.modules if it sees a package with the same name in a different location. Set this option to disable that behavior.'),
        Option('--no-byte-compile', action='store_true', help='Prevent nose from byte-compiling the source into .pyc files while nose is scanning for and running tests.'),
        Option('-a', '--attr', action='store_true', help='Run only tests that have attributes specified by ATTR [NOSE_ATTR]'),
        Option('-A', '--eval-attr', action='store_true', help='Run only tests for whose attributes the Python expression EXPR evaluates to True [NOSE_EVAL_ATTR]'),
        Option('-s', '--nocapture', action='store_true', help='Don\'t capture stdout (any stdout output will be printed immediately) [NOSE_NOCAPTURE]'),
        Option('--nologcapture', action='store_true', help='Disable logging capture plugin. Logging configuration will be left intact. [NOSE_NOLOGCAPTURE]'),
        Option('--logging-format', help='Specify custom format to print statements. Uses the same format as used by standard logging handlers. [NOSE_LOGFORMAT]'),
        Option('--logging-datefmt', help='Specify custom date/time format to print statements. Uses the same format as used by standard logging handlers. [NOSE_LOGDATEFMT]'),
        Option('--logging-filter', help='Specify which statements to filter in/out. By default, everything is captured. If the output is too verbose, use this option to filter out needless output. Example: filter=foo will capture statements issued ONLY to  foo or foo.what.ever.sub but not foobar or other logger. Specify multiple loggers with comma: filter=foo,bar,baz. If any logger name is prefixed with a minus, eg filter=-foo, it will be excluded rather than included. Default: exclude logging messages from nose itself (-nose). [NOSE_LOGFILTER]'),
        Option('--logging-clear-handlers', action='store_true', help='Clear all other logging handlers'),
        Option('--logging-level', help='Set the log level to capture'),
        Option('--with-coverage', action='store_true', help='Enable plugin Coverage:  Activate a coverage report using Ned Batchelder\'s coverage module. [NOSE_WITH_COVERAGE]'),
        Option('--cover-package', help='Restrict coverage output to selected packages [NOSE_COVER_PACKAGE]'),
        Option('--cover-erase', action='store_true', help='Erase previously collected coverage statistics before run'),
        Option('--cover-tests', action='store_true', help='Include test modules in coverage report [NOSE_COVER_TESTS]'),
        Option('--cover-min-percentage', help='Minimum percentage of coverage for tests to pass [NOSE_COVER_MIN_PERCENTAGE]'),
        Option('--cover-inclusive', action='store_true', help='Include all python files under working directory in coverage report.  Useful for discovering holes in test coverage if not all files are imported by the test suite. [NOSE_COVER_INCLUSIVE]'),
        Option('--cover-html', action='store_true', help='Produce HTML coverage information'),
        Option('--cover-html-dir', help='Produce HTML coverage information in dir'),
        Option('--cover-branches', action='store_true', help='Include branch coverage in coverage report [NOSE_COVER_BRANCHES]'),
        Option('--cover-xml', action='store_true', help='Produce XML coverage information'),
        Option('--cover-xml-file', help='Produce XML coverage information in file'),
        Option('--pdb', action='store_true', help='Drop into debugger on failures or errors'),
        Option('--pdb-failures', action='store_true', help='Drop into debugger on failures'),
        Option('--pdb-errors', action='store_true', help='Drop into debugger on errors'),
        Option('--no-deprecated', action='store_true', help='Disable special handling of DeprecatedTest exceptions.'),
        Option('--with-doctest', action='store_true', help='Enable plugin Doctest:  Activate doctest plugin to find and run doctests in non-test modules. [NOSE_WITH_DOCTEST]'),
        Option('--doctest-tests', action='store_true', help='Also look for doctests in test modules. Note that classes, methods and functions should have either doctests or non-doctest tests, not both. [NOSE_DOCTEST_TESTS]'),
        Option('--doctest-extension', help='Also look for doctests in files with this extension [NOSE_DOCTEST_EXTENSION]'),
        Option('--doctest-result-variable', help='Change the variable name set to the result of the last interpreter command from the default \'_\'. Can be used to avoid conflicts with the _() function used for text translation. [NOSE_DOCTEST_RESULT_VAR]'),
        Option('--doctest-fixtures', help='Find fixtures for a doctest file in module with this name appended to the base name of the doctest file'),
        Option('--doctest-options', help='Specify options to pass to doctest. Eg. \'+ELLIPSIS,+NORMALIZE_WHITESPACE\''),
        Option('--with-isolation', action='store_true', help='Enable plugin IsolationPlugin:  Activate the isolation plugin to isolate changes to external modules to a single test module or package. The isolation plugin resets the contents of sys.modules after each test module or package runs to its state before the test. PLEASE NOTE that this plugin should not be used with the coverage plugin, or in any other case where module reloading may produce undesirable side-effects. [NOSE_WITH_ISOLATION]'),
        Option('-d', '--detailed-errors', '--failure-detail', action='store_true', help='Add detail to error output by attempting to evaluate failed asserts [NOSE_DETAILED_ERRORS]'),
        Option('--with-profile', action='store_true', help='Enable plugin Profile:  Use this plugin to run tests using the hotshot profiler.   [NOSE_WITH_PROFILE]'),
        Option('--profile-sort', help='Set sort order for profiler output'),
        Option('--profile-stats-file', help='Profiler stats file; default is a new temp file on each run'),
        Option('--profile-restrict', help='Restrict profiler output. See help for pstats.Stats for details'),
        Option('--no-skip', action='store_true', help='Disable special handling of SkipTest exceptions.'),
        Option('--with-id', action='store_true', help='Enable plugin TestId:  Activate to add a test id (like #1) to each test name output. Activate with --failed to rerun failing tests only.  [NOSE_WITH_ID]'),
        Option('--id-file', help='Store test ids found in test runs in this file. Default is the file .noseids in the working directory.'),
        Option('--failed', action='store_true', help='Run the tests that failed in the last test run.'),
        Option('--processes', help='Spread test run among this many processes. Set a number equal to the number of processors or cores in your machine for best results. Pass a negative number to have the number of processes automatically set to the number of cores. Passing 0 means to disable parallel testing. Default is 0 unless NOSE_PROCESSES is set. [NOSE_PROCESSES]'),
        Option('--process-timeout', help='Set timeout for return of results from each test runner process. Default is 10. [NOSE_PROCESS_TIMEOUT]'),
        Option('--process-restartworker', action='store_true', help='If set, will restart each worker process once their tests are done, this helps control memory leaks from killing the system. [NOSE_PROCESS_RESTARTWORKER]'),
        Option('--with-xunit', action='store_true', help='Enable plugin Xunit: This plugin provides test results in the standard XUnit XML format. [NOSE_WITH_XUNIT]'),
        Option('--xunit-file', help='Path to xml file to store the xunit report in. Default is nosetests.xml in the working directory [NOSE_XUNIT_FILE]'),
        Option('--xunit-testsuite-name', help='Name of the testsuite in the xunit xml, generated by plugin. Default test suite name is nosetests.'),
        Option('--all-modules', action='store_true', help='Enable plugin AllModules: Collect tests from all python modules.  [NOSE_ALL_MODULES]'),
        Option('--collect-only', action='store_true', help='Enable collect-only:  Collect and output test names only, don\'t run any tests.  [COLLECT_ONLY]'),
    ]

    def run(self, **kwargs):
        """
        Run the command
        :param kwargs:  kwargs
        :return:        void
        """

        argv = sys.argv[:]
        while len(argv) and argv[0] != 'test':
            del argv[0]

        nose.run(argv=argv)
