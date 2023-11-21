import _ from "lodash";
import { Setting } from "./Setting";
import { logoutProcess } from "../commonFunctions";
import { store } from "../Redux/store/configureStore";

function getUserToken() {
  const {
    auth: { userdata },
  } = store.getState();
  return `Bearer ${userdata?.access_token}`;
}

export function getApiData(endpoint, method, data, headers) {
  const isOnline = window.navigator.onLine;
  if (isOnline) {
    return new Promise((resolve, reject) => {
      let query = "";
      let qs = "";

      const params = {};
      params.method = method.toLowerCase() === "get" ? "get" : "post";

      if (headers) {
        const obj = headers;
        obj["cache-control"] =
          "no-store, no-cache, must-revalidate, post-check=0, pre-check=0";

        params.headers = obj;
      } else {
        params.headers = {
          "Content-Type": "application/x-www-form-urlencoded",
        };
      }

      if (params.method === "post") {
        if (
          params.headers &&
          params.headers["Content-Type"] &&
          params.headers["Content-Type"] === "application/json"
        ) {
          params.body = JSON.stringify(data);
        } else {
          params.body = query;
        }
      } else {
        qs = `?${query}`;
      }

      let url = Setting.api + endpoint + qs;
      let length = url.length;
      if (url.charAt(length - 1) === "?") url = url.slice(0, length - 1);
      fetch(url, params)
        .then((response) => response.json())
        .then((resposeJson) => {
          if (
            _.isObject(resposeJson) &&
            _.has(resposeJson, "status") &&
            _.toNumber(resposeJson.status) === 401
          ) {
            logoutProcess();
          } else {
            resolve(resposeJson);
          }
        })
        .catch((err) => {
          console.log("Catch Part", err);
          reject(err);
        });
    });
  }
}

export function getAPIProgressData(
  endpoint,
  method,
  data,
  headers = false,
  onProgress = null
) {
  const isOnline = window.navigator.onLine;
  if (isOnline) {
    return new Promise(async (resolve, reject) => {
      const url = Setting.api + endpoint;
      const oReq = new XMLHttpRequest();
      oReq.upload.addEventListener("progress", (event) => {
        if (event.lengthComputable) {
          const progress = (event.loaded * 100) / event.total;
          if (onProgress) {
            onProgress(progress);
          }
        }
      });

      var FormData = require("form-data");
      var form = new FormData();
      if (data && Object.keys(data).length > 0) {
        Object.keys(data).map((k) => form.append(k, data[k]));
      }

      const hData = {
        "Content-Type": "multipart/form-data",
      };

      if (headers) {
        hData.Authorization = getUserToken();
      }

      let options = {
        method: method,
        headers: hData,
        body: form,
      };

      delete options.headers["Content-Type"];

      fetch(url, options)
        .then(function (res) {
          resolve(res.json());
        })
        .then(function (result) { })
        .catch((err) => {
          console.log("Catch Part", err);
          reject(err);
        });
    });
  }
}
