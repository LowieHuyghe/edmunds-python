
import os


class Config(object):
    """
    This class concerns config code for Application to extend from
    """

    def _init_config(self, config_dirs=None):
        """
        Initiate the configuration
        :param config_dirs:     Configuration directories
        :type  config_dirs:     list
        """

        # Configuration directories
        if config_dirs is None:
            # edmunds/edmunds/foundation/concerns
            edmunds_config_dir = os.path.dirname(os.path.realpath(__file__))
            # edmunds/edmunds/foundation/concerns/../../../config
            edmunds_config_dir = os.path.join(edmunds_config_dir, os.pardir, os.pardir, os.pardir, 'config')
            # edmunds/config
            edmunds_config_dir = os.path.abspath(edmunds_config_dir)

            config_dirs = [
                edmunds_config_dir,
                'config',
            ]

        # Load config
        self.config.load_all(config_dirs)
