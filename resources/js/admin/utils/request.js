import axios from 'axios'
import { message } from 'ant-design-vue'
import { removeToken, getToken } from './auth'

const request = axios.create({
    withCredentials: true,
    crossDomain: true,
    baseURL: '/api/admin',
    timeout: 10000
})

request.interceptors.request.use(
    config => {
        config.params = {
            _t: Date.parse(new Date()) / 1000,
            ...config.params
        }
        config.headers['X-Requested-With'] = 'XMLHttpRequest'
        config.headers['Content-Type'] = 'application/x-www-form-urlencoded'
        config.headers['Authorization'] = 'Bearer ' + getToken()
        config.transformRequest = [function(data) {
            let ret = ''
            for (const it in data) {
                ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
            }
            return ret.substring(0, ret.length - 1)
        }]

        return config
    },
    error => {
        console.log(error) // for debug
        return Promise.reject(error)
    }
)

// response interceptor
request.interceptors.response.use(
    response => {
        const res = response.data
        console.log(response)
        if (res.code !== 1) {
            message.error(res.msg);
        }
        return response.data;
    },
    error => {
        console.log(error)
        if (error && error.response && error.response.status == 401) {
            removeToken()
            location.reload()
        }
        message.error(error.message);
        return Promise.reject(error)
    }
)
export default request