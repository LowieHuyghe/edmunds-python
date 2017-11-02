
from werkzeug.datastructures import Headers
from edmunds.globals import render_template, redirect, send_from_directory, jsonify, make_response
from threading import Lock
from edmunds.application import Application


class ResponseHelper(object):

    def __init__(self):
        """
        Constructor
        """
        self._status = None
        self.assigns = dict()
        self.headers = Headers()
        self.__cookie_response = None
        self.__cookie_response_lock = Lock()

    def status(self, status):
        """
        Status
        :param status:  Key for the value
        :return:        Response
        :rtype:         ResponseHelper
        """
        self._status = status
        return self

    def assign(self, key, value):
        """
        Assign value
        :param key:     Key for the value
        :param value:   Value for the key
        :return:        Response
        :rtype:         ResponseHelper
        """
        self.assigns[key] = value
        return self

    def header(self, key, value, **kw):
        """
        Assign header
        :param key:     The header key
        :param value:   The header value
        :return:        Response
        :rtype:         ResponseHelper
        """
        self.headers.set(key, value, **kw)
        return self

    def delete_header(self, key):
        """
        Delete header
        :param key: Key to delete
        :return:    Response
        :rtype:     Response
        """
        self.headers.remove(key)
        return self

    @property
    def _cookie_response(self):
        """
        Get cookie response
        :return:    Response
        """
        if self.__cookie_response is None:
            with self.__cookie_response_lock:
                if self.__cookie_response is None:
                    response = make_response()
                    response.headers.clear()
                    self.__cookie_response = response
        return self.__cookie_response

    def cookie(self, key, value='', max_age=None, expires=None, path='/', domain=None, secure=False, httponly=False):
        """Sets a cookie. The parameters are the same as in the cookie `Morsel`
        object in the Python standard library but it accepts unicode data, too.

        :param key: the key (name) of the cookie to be set.
        :param value: the value of the cookie.
        :param max_age: should be a number of seconds, or `None` (default) if
                        the cookie should last only as long as the client's
                        browser session.
        :param expires: should be a `datetime` object or UNIX timestamp.
        :param path: limits the cookie to a given path, per default it will
                     span the whole domain.
        :param domain: if you want to set a cross-domain cookie.  For example,
                       ``domain=".example.com"`` will set a cookie that is
                       readable by the domain ``www.example.com``,
                       ``foo.example.com`` etc.  Otherwise, a cookie will only
                       be readable by the domain that set it.
        :param secure: If `True`, the cookie will only be available via HTTPS
        :param httponly: disallow JavaScript to access the cookie.  This is an
                         extension to the cookie standard and probably not
                         supported by all browsers.
        :return:        Response
        :rtype:         ResponseHelper
        """
        self._cookie_response.set_cookie(key, value, max_age, expires, path, domain, secure, httponly)
        return self

    def delete_cookie(self, key, path='/', domain=None):
        """
        Delete a cookie.  Fails silently if key doesn't exist.

        :param key: the key (name) of the cookie to be deleted.
        :param path: if the cookie that should be deleted was limited to a
                     path, the path has to be defined here.
        :param domain: if the cookie that should be deleted was limited to a
                       domain, that domain has to be defined here.
        :return:        Response
        :rtype:         ResponseHelper
        """
        self._cookie_response.delete_cookie(key, path, domain)
        return self

    def raw(self, content):
        """
        Raw response
        :param content: content
        :return:        Response
        :rtype:         edmunds.http.response.Response
        """
        return self._apply_data(make_response(content))

    def render(self, template, extra_assigns=None):
        """
        Render template as response
        :param template:        The template
        :param extra_assigns:   Optional extra assigns
        :return:                Response
        :rtype:                 Response
        """
        return self._apply_data(make_response(self.render_template(template, extra_assigns=extra_assigns)))

    def render_template(self, template, extra_assigns=None):
        """
        Render template
        :param template:        The template
        :param extra_assigns:   Optional extra assigns
        :return:                Rendered template
        :rtype:                 str
        """
        if extra_assigns is None:
            template_assigns = self.assigns
        else:
            template_assigns = self.assigns.copy()
            template_assigns.update(extra_assigns)

        return render_template(template, **template_assigns)

    def json(self):
        """
        Json response
        :return:        Response
        :rtype:         edmunds.http.response.Response
        """
        return self._apply_data(jsonify(**self.assigns))

    def redirect(self, location):
        """
        Redirect response
        :param location:    Location to redirect to
        :return:            Response
        :rtype:             edmunds.http.response.Response
        """
        return self._apply_data(redirect(location, Response=Application.response_class))

    def file(self, directory, filename, **options):
        """Send a file from a given directory with :func:`send_file`.  This
        is a secure way to quickly expose static files from an upload folder
        or something similar.

        Example usage::

            @app.route('/uploads/<path:filename>')
            def download_file(filename):
                return send_from_directory(app.config['UPLOAD_FOLDER'],
                                           filename, as_attachment=True)

        .. admonition:: Sending files and Performance

           It is strongly recommended to activate either ``X-Sendfile`` support in
           your webserver or (if no authentication happens) to tell the webserver
           to serve files for the given path on its own without calling into the
           web application for improved performance.

        .. versionadded:: 0.5

        :param directory: the directory where all the files are stored.
        :param filename: the filename relative to that directory to
                         download.
        :param options: optional keyword arguments that are directly
                        forwarded to :func:`send_file`.
        """
        return self._apply_data(send_from_directory(directory, filename, **options))

    def _apply_data(self, response_obj):
        """
        Apply other response object
        :param response_obj:    Response object
        :type response_obj:     edmunds.http.response.Response
        :return:                Response
        :rtype:                 edmunds.http.response.Response
        """
        # Status
        if self._status is not None:
            response_obj.status_code = self._status
        # Headers
        response_obj.headers.extend(self.headers)
        # Cookies
        response_obj.headers.extend(self._cookie_response.headers)

        return response_obj
