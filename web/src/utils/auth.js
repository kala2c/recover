const key = 'tokenKey'

export const getToken = function () {
  return localStorage.getItem(key)
}

export const setToken = function (token) {
  return localStorage.setItem(key, token)
}

export const removeToken = function () {
  return localStorage.removeItem(key)
}
