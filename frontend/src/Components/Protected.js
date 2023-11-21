import React, { useEffect, useState } from "react";
import { Route, Redirect } from "react-router-dom";
import { useSelector } from "react-redux";
import _ from "lodash";

export default function Protected(props) {
  const { children } = props;
  const { userdata } = useSelector((state) => state.auth);
  const [initialized, setInitialized] = useState(false);
  const [allow, setaAllow] = useState(false);

  useEffect(() => {
    const isLoggedIn = _.isObject(userdata) && !_.isEmpty(userdata);
    if (!isLoggedIn) {
      setInitialized(true);
      setaAllow(false);
    } else {
      setInitialized(true);
      setaAllow(true);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (!initialized) {
    return null;
  }

  if (allow) {
    return children;
  }

  return (
    <Route
      render={({ staticContext }) => {
        if (staticContext) staticContext.status = 403;
        return <Redirect to="/rate" />;
      }}
    />
  );
}
