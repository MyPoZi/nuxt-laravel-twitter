const middleware = {}

middleware['guest'] = require('@/middleware/guest.js');
middleware['guest'] = middleware['guest'].default || middleware['guest']

middleware['auth'] = require('@/middleware/auth.js');
middleware['auth'] = middleware['auth'].default || middleware['auth']


export default middleware
