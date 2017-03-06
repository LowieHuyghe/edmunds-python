
from edmunds.foundation.patterns.manager import Manager
from edmunds.profiler.drivers.blackfireio import BlackfireIo
from edmunds.profiler.drivers.callgraph import CallGraph
from edmunds.profiler.drivers.stream import Stream
import os


class ProfilerManager(Manager):
    """
    The Log Manager
    """

    def __init__(self, app):
        """
        Initiate the manager
        :param app:     The application
        :type  app:     Edmunds.Application
        """

        super(ProfilerManager, self).__init__(app, app.config('app.profiler.instances', []))

        self._profile_path = os.path.join(os.sep, 'profs')

    def _create_blackfire_io(self, config):
        """
        Create BlackfireIo instance
        :param config:  The config
        :type  config:  dict
        :return:        BlackfireIo instance
        :rtype:         BlackfireIo
        """

        profile_path = self._profile_path
        if 'directory' in config:
            directory = config['directory']
            # Check if absolute or relative path
            if not directory.startswith(os.sep):
                profile_path = os.path.join(profile_path, directory)
            else:
                profile_path = directory

        options = {}

        if 'prefix' in config:
            options['prefix'] = config['prefix']
        if 'name' in config:
            options['suffix'] = '.%s' % config['name']

        return BlackfireIo(self._app, profile_path, **options)

    def _create_call_graph(self, config):
        """
        Create CallGraph instance
        :param config:  The config
        :type  config:  dict
        :return:        CallGraph instance
        :rtype:         CallGraph
        """

        profile_path = self._profile_path
        if 'directory' in config:
            directory = config['directory']
            # Check if absolute or relative path
            if not directory.startswith(os.sep):
                profile_path = os.path.join(profile_path, directory)
            else:
                profile_path = directory

        options = {}

        if 'prefix' in config:
            options['prefix'] = config['prefix']
        if 'name' in config:
            options['suffix'] = '.%s' % config['name']

        return CallGraph(self._app, profile_path, **options)

    def _create_stream(self, config):
        """
        Create Stream instance
        :param config:  The config
        :type  config:  dict
        :return:        Stream instance
        :rtype:         Stream
        """

        options = {}

        if 'stream' in config:
            options['stream'] = config['stream']
        if 'sort_by' in config:
            options['sort_by'] = config['sort_by']
        if 'restrictions' in config:
            options['restrictions'] = config['restrictions']

        return Stream(self._app, **options)
