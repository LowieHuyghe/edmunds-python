
from edmunds.console.command import Command
import click
import subprocess
from subprocess import Popen
import os
import tempfile
import shutil
from distutils.dir_util import copy_tree


class PipInstallIntoCommand(Command):
    """
    Install dependencies into directory.
    This fixes the eggs and other issues pip has in combination with a target directory.
    """

    @click.option('--target', '-t', required=True, help='Target directory')
    @click.option('--pip', '-p', required=True, help='Pip executable to use')
    def run(self, target, pip):
        """
        Run the command
        :param target:  Target directory
        :type target:   str
        :param pip:  Target directory
        :type target:   str
        :return:        void
        """

        out, err, returncode = self._command('echo $(dirname $(which %s))/..' % pip)
        if returncode != 0:
            raise RuntimeError('Could not find virtual-environment directory')

        lines = out.split('\n')
        if not lines or not lines[0]:
            raise RuntimeError('Could not extract virtual-environment directory')

        venv = lines[0]
        site_packages_dir = os.path.join(venv, target, 'python2.7', 'site-packages')
        if not os.path.isdir(site_packages_dir):
            raise RuntimeError('You don\'t seem to be working in a virtual environment')

        temp_dir = tempfile.mkdtemp()
        try:
            print('Copying site-packages to temporary directory...')
            copy_tree(site_packages_dir, temp_dir)
            print('Finished copying site-packages to temporary directory')

            # Loop all egg-link dirs
            for egg_link_filename in os.listdir(temp_dir):
                if egg_link_filename.endswith('.egg-link'):
                    print('Processing egg-link "%s"...' % egg_link_filename)

                    # Fetch source directory for link
                    egg_link = os.path.join(temp_dir, egg_link_filename)
                    with open(egg_link, 'r') as egg_link_file:
                        source_directory = egg_link_file.readline().strip()
                    if not source_directory:
                        raise RuntimeError('Unable to find source directory for egg-link "%s"' % egg_link_filename)

                    # Loop all egg-info dirs
                    found_egg_info = False
                    for egg_info_filename in os.listdir(source_directory):
                        if egg_info_filename.endswith('.egg-info'):
                            found_egg_info = True
                            egg_info = os.path.join(source_directory, egg_info_filename)

                            # Process top-level.txt
                            egg_info_top_level = os.path.join(egg_info, 'top_level.txt')
                            if not os.path.isfile(egg_info_top_level):
                                raise RuntimeError('Unable to find top-level.txt for egg-link "%s"' % egg_link_filename)
                            with open(egg_info_top_level, 'r') as egg_info_top_level_file:
                                egg_packages = egg_info_top_level_file.readlines()
                                egg_packages = list(filter(lambda x: x, egg_packages))
                                egg_packages = list(map(lambda x: x.strip(), egg_packages))
                            if not egg_packages:
                                raise RuntimeError('Unable to find packages in top-level.txt for egg-link "%s"' % egg_link_filename)

                            # Copy packages
                            for egg_package in egg_packages:
                                egg_package_dir = os.path.join(source_directory, egg_package)
                                if not os.path.isdir(egg_package_dir):
                                    raise RuntimeError('Could not find package "%s" for egg-link "%s"' % (egg_package, egg_link_filename))
                                print('    Copying "%s"...' % egg_package)
                                shutil.copytree(egg_package_dir, os.path.join(temp_dir, egg_package))

                    if not found_egg_info:
                        raise RuntimeError('Unable to find egg-info for egg-link "%s"' % egg_link_filename)

                    print('Finished processing egg-link "%s"...' % egg_link_filename)

            # Copy to lib
            print('Moving to %s-directory...' % target)
            if os.path.exists(target):
                shutil.rmtree(target)
            shutil.move(temp_dir, target)
            print('Finished moving to %s-directory' % target)

        finally:
            if os.path.exists(temp_dir):
                print('Cleaning up temporary directory...')
                shutil.rmtree(temp_dir)
                print('Finished cleaning up temporary directory')

    def _command(self, command):
        process = Popen(command,
                        stdin=subprocess.PIPE,
                        stdout=subprocess.PIPE,
                        stderr=subprocess.PIPE,
                        shell=True)
        out, err = process.communicate()
        return out, err, process.returncode
