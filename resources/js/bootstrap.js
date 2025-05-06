import axios from 'axios';
window.axios = axios;
import './bootstrap';
import 'laravel-datatables-vite';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
