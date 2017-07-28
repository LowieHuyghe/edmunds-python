
from werkzeug.datastructures import Headers
from edmunds.globals import render_template, redirect, send_file, jsonify, make_response


class ResponseHelper(object):

    def __init__(self):
        """
        Constructor 
        """
        self.status = None
        self.assigns = dict()
        self.headers = Headers()
        self._cookie_response = make_response()

    def status(self, status):
        """
        Status
        :param status:  Key for the value
        :return:        Response
        :rtype:         ResponseHelper
        """
        self.status = status
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

        return render_template(template, template_assigns)

    def json(self):
        """
        Json response
        :return:        Response
        :rtype:         edmunds.http.response.Response
        """
        return self._apply_data(jsonify(**self.assigns))

    def redirect(self, location, code=302):
        """
        Redirect response
        :param location:    Location to redirect to 
        :param code:        Status code
        :return:            Response
        :rtype:             edmunds.http.response.Response
        """
        return self._apply_data(redirect(location, code=code))

    def file(self, filename_or_fp, mimetype=None, as_attachment=False,
             attachment_filename=None, add_etags=True,
             cache_timeout=None, conditional=False, last_modified=None):
        """
        Sends the contents of a file to the client.  This will use the
        most efficient method available and configured.  By default it will
        try to use the WSGI server's file_wrapper support.  Alternatively
        you can set the application's :attr:`~Flask.use_x_sendfile` attribute
        to ``True`` to directly emit an ``X-Sendfile`` header.  This however
        requires support of the underlying webserver for ``X-Sendfile``.
    
        By default it will try to guess the mimetype for you, but you can
        also explicitly provide one.  For extra security you probably want
        to send certain files as attachment (HTML for instance).  The mimetype
        guessing requires a `filename` or an `attachment_filename` to be
        provided.
    
        ETags will also be attached automatically if a `filename` is provided. You
        can turn this off by setting `add_etags=False`.
    
        If `conditional=True` and `filename` is provided, this method will try to
        upgrade the response stream to support range requests.  This will allow
        the request to be answered with partial content response.
    
        Please never pass filenames to this function from user sources;
        you should use :func:`send_from_directory` instead.
    
        .. versionadded:: 0.2
    
        .. versionadded:: 0.5
           The `add_etags`, `cache_timeout` and `conditional` parameters were
           added.  The default behavior is now to attach etags.
    
        .. versionchanged:: 0.7
           mimetype guessing and etag support for file objects was
           deprecated because it was unreliable.  Pass a filename if you are
           able to, otherwise attach an etag yourself.  This functionality
           will be removed in Flask 1.0
    
        .. versionchanged:: 0.9
           cache_timeout pulls its default from application config, when None.
    
        .. versionchanged:: 0.12
           The filename is no longer automatically inferred from file objects. If
           you want to use automatic mimetype and etag support, pass a filepath via
           `filename_or_fp` or `attachment_filename`.
    
        .. versionchanged:: 0.12
           The `attachment_filename` is preferred over `filename` for MIME-type
           detection.
    
        :param filename_or_fp: the filename of the file to send in `latin-1`.
                               This is relative to the :attr:`~Flask.root_path`
                               if a relative path is specified.
                               Alternatively a file object might be provided in
                               which case ``X-Sendfile`` might not work and fall
                               back to the traditional method.  Make sure that the
                               file pointer is positioned at the start of data to
                               send before calling :func:`send_file`.
        :param mimetype: the mimetype of the file if provided. If a file path is
                         given, auto detection happens as fallback, otherwise an
                         error will be raised.
        :param as_attachment: set to ``True`` if you want to send this file with
                              a ``Content-Disposition: attachment`` header.
        :param attachment_filename: the filename for the attachment if it
                                    differs from the file's filename.
        :param add_etags: set to ``False`` to disable attaching of etags.
        :param conditional: set to ``True`` to enable conditional responses.
    
        :param cache_timeout: the timeout in seconds for the headers. When ``None``
                              (default), this value is set by
                              :meth:`~Flask.get_send_file_max_age` of
                              :data:`~flask.current_app`.
        :param last_modified: set the ``Last-Modified`` header to this value,
            a :class:`~datetime.datetime` or timestamp.
            If a file was passed, this overrides its mtime.
        :return:        Response
        :rtype:         edmunds.http.response.Response
        """
        return self._apply_data(send_file(filename_or_fp, mimetype, as_attachment,
                                          attachment_filename, add_etags,
                                          cache_timeout, conditional, last_modified))

    def _apply_data(self, response_obj):
        """
        Apply other response object
        :param response_obj:    Response object
        :type response_obj:     edmunds.http.response.Response
        :return:                Response
        :rtype:                 edmunds.http.response.Response
        """
        # Status
        if self.status is not None:
            response_obj.status = self.status
        # Headers
        response_obj.headers.extend(self.headers)
        # Cookies
        response_obj.headers.extend(self._cookie_response.headers)
