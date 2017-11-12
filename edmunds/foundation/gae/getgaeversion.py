
from edmunds.console.command import Command
import click
import sys
import re


class GetGaeVersion(Command):
    """
    Print Google App Engine version from version
    """

    @click.argument('version')
    def run(self, version):
        """
        Run the command
        :param version:    Version
        :type version:     str
        :return:    void
        """

        #  Version must match '^(?:^(?!-)[a-z\d\-]{0,62}[a-z\d]$)$

        version = version.lower()
        version = re.sub(r'[^a-z\d\-]', '-', version)
        version = re.sub(r'-+', '-', version)
        version = re.sub(r'^-+', '', version)
        version = version[:62]
        version = re.sub(r'-+$', '', version)

        sys.stdout.write(version)
        sys.stdout.flush()
