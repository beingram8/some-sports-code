import React, { useEffect } from "react";
import Header from "../../Components/Header/index";
import { useHistory } from "react-router-dom";
import { Setting } from "../../Utils/Setting";
import "./styles.scss";
import "../../Styles/common.scss";
import { Paper } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import PlayCircleFilledWhiteIcon from "@material-ui/icons/PlayCircleFilledWhite";
import { getWords } from "../../commonFunctions";

const AllVideos = (props) => {
  const data = props?.location?.state?.data;
  const matches = useMediaQuery("(min-width:640px)");

  console.log("data ====>>>>>> ", data);

  useEffect(() => {
    document.title = Setting.page_name.ALL_VIDEOS;
  }, []);

  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      <div className="CommonContainer allVideosMainContainer">
        <span className="avTitle">{getWords("ALL_VIDEOS")}</span>
        {matches ? (
          <Grid
            container
            justify="space-between"
            alignContent="space-between"
            className="radiostream2av"
          >
            {data?.slice(0, 6).map((item, index) => {
              return (
                <Grid key={index} item>
                  <Paper
                    className="tifaPapersquare1av"
                    elevation={5}
                    style={{
                      backgroundImage: `url(${item.thumb_img})`,
                    }}
                    onClick={() => {
                      //   sethideView(false);
                      //   if (checkUserLogin) {
                      //     getVideoDetailsUser(item.id, false);
                      //   } else {
                      //     getVideoDetailsGuest(item.id);
                      //   }
                    }}
                  >
                    <div className="tifaiconbuttonstyleav">
                      <PlayCircleFilledWhiteIcon className="tifaplayiconstyleav" />
                    </div>
                  </Paper>
                  <div className="tifaalltitlepaddingav">
                    <span className="tifaalltitletext2av">{item.title}</span>
                  </div>
                </Grid>
              );
            })}
          </Grid>
        ) : (
          // for mobile
          <div className="radiostream2divav">
            {data?.map((item, index) => (
              <Grid key={index} item>
                <Paper
                  className="tifaPapersquare1av"
                  elevation={5}
                  style={{
                    backgroundImage: `url(${item.thumb_img})`,
                  }}
                  onClick={() => {
                    // sethideView(false);
                    // getVideoDetailsGuest(item.id, false);
                  }}
                >
                  <div className="tifaiconbuttonstyleav">
                    <PlayCircleFilledWhiteIcon className="tifaplayiconstyleav" />
                  </div>
                </Paper>
                <div className="tifaalltitlepaddingav">
                  <span className="tifaalltitletext2av">{item.title}</span>
                </div>
              </Grid>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default AllVideos;
