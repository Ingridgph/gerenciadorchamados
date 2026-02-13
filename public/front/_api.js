/* Simple API helper used by the front pages */
(function () {
    const API_BASE = 'http://localhost:8080/api';

    function getToken() {
        return localStorage.getItem('api_token') || '';
    }

    function setToken(token) {
        if (token) localStorage.setItem('api_token', token);
        else localStorage.removeItem('api_token');
    }

    async function request(path, options = {}) {
        const url = API_BASE + path;
        const opts = Object.assign({}, options);
        opts.headers = opts.headers || {};
        opts.headers['Accept'] = opts.headers['Accept'] || 'application/json';
        opts.headers['X-Requested-With'] = opts.headers['X-Requested-With'] || 'XMLHttpRequest';

        if (!opts.headers['Content-Type'] && opts.body) opts.headers['Content-Type'] = 'application/json';

        const token = getToken();
        if (token) opts.headers['Authorization'] = 'Bearer ' + token;

        const res = await fetch(url, opts);
        let data = null;
        try { data = await res.json(); } catch (e) { /* non-json response */ }

        if (!res.ok) {
            const err = { status: res.status, data };
            throw err;
        }

        return data;
    }

    window.api = { request, getToken, setToken, API_BASE };
})();
