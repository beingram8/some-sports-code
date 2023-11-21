import React, { useEffect, useState } from "react";
import { Redirect } from "react-router-dom";
import { useSelector } from "react-redux";
import _ from "lodash";

export default function Logout() {
  const { userdata } = useSelector((state) => state.auth);
  const [logout, setLogout] = useState(false);

  useEffect(() => {
    const isLogout = _.isEmpty(userdata);
    setLogout(isLogout);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (logout) {
    return <Redirect to="/" />;
  }
  return null;
}
