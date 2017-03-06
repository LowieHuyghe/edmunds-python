
from tests.testcase import TestCase
import edmunds.support.helpers as helpers
from tests.foundation.syslogserver import SysLogServer


class TestSysLog(TestCase):
    """
    Test the SysLog
    """

    def set_up(self):
        """
        Set up the test case
        """

        super(TestSysLog, self).set_up()

        self._server = SysLogServer()
        self._server.start()


    def tear_down(self):
        """
        Tear down the test case
        """

        super(TestSysLog, self).tear_down()

        self._server.stop()


    def test_sys_log(self):
        """
        Test the sys log
        """

        info_string = 'info_%s' % helpers.random_str(20)
        warning_string = 'warning_%s' % helpers.random_str(20)
        error_string = 'error_%s' % helpers.random_str(20)

        # Write config
        self.write_config([
            "from edmunds.log.drivers.syslog import SysLog \n",
            "from logging import WARNING \n",
            "APP = { \n",
            "   'debug': False, \n",
            "   'log': { \n",
            "       'enabled': True, \n",
            "       'instances': [ \n",
            "           { \n",
            "               'name': 'syslog',\n",
            "               'driver': SysLog,\n",
            "               'level': WARNING,\n",
            "               'address': ('%s', %i),\n" % (self._server.host, self._server.port),
            "           }, \n",
            "       ], \n",
            "   }, \n",
            "} \n",
        ])

        # Create app
        app = self.create_application()

        # Add route
        rule = '/' + helpers.random_str(20)
        @app.route(rule)
        def handleRoute():
            app.logger.info(info_string)
            app.logger.warning(warning_string)
            app.logger.error(error_string)
            return ''

        with app.test_client() as c:

            # Check syslog
            self.assert_not_in(info_string, '\n'.join(self._server.get_data()))
            self.assert_not_in(warning_string, '\n'.join(self._server.get_data()))
            self.assert_not_in(error_string, '\n'.join(self._server.get_data()))

            # Call route
            c.get(rule)

            # Check syslog
            self.assert_not_in(info_string, '\n'.join(self._server.get_data()))
            self.assert_in(warning_string, '\n'.join(self._server.get_data()))
            self.assert_in(error_string, '\n'.join(self._server.get_data()))
