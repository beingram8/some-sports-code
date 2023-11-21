import React, { useEffect } from "react";
import Header from "../../Components/Header/index";
import { useHistory } from "react-router-dom";
import { Setting } from "../../Utils/Setting";
import "./styles.scss";
import "../../Styles/common.scss";
import { getWords } from "../../commonFunctions";
import { Paper } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import ThumbUpAltRoundedIcon from "@material-ui/icons/ThumbUpAltRounded";
import MessageRoundedIcon from "@material-ui/icons/MessageRounded";
import _ from "lodash";
import { useDispatch, useSelector } from "react-redux";
import authActions from "../../Redux/reducers/auth/actions";

const { setSelectedTab, setSelectedNews } = authActions;

const AllNews = (props) => {
  const data = props?.location?.state?.data;
  const matches1100 = useMediaQuery("(min-width:1100px)");
  const matches570 = useMediaQuery("(min-width:571px)");
  const dispatch = useDispatch();

  const history = useHistory();

  useEffect(() => {
    document.title = Setting.page_name.ALL_NEWS;
  }, []);
  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      <div className=" CommonContainer allNewsMainContainer">
        <span className="avTitle">{getWords("ALL_NEWS")}</span>
        {_.isArray(data) && !_.isEmpty(data) ? (
          data.length % 3 === 0 || !matches1100 ? (
            <Grid
              container
              justify={!matches570 ? "center" : "space-between"}
              className="newsan"
            >
              {data?.map((item, index) => {
                return (
                  <Grid key={index} item>
                    <Paper
                      className="papernewsstylean"
                      onClick={() => {
                        dispatch(setSelectedNews(item));
                        history.push({
                          pathname: "/news",
                          search: `?news_id=${item?.id}&slug=${item?.slug}`,
                          state: {
                            newsId: item?.id,
                            slug: item?.slug,
                          },
                        });
                      }}
                      elevation={5}
                    >
                      <img
                        src={item?.thumb_img}
                        className="matchan"
                        alt={"match"}
                        loading="lazy"
                      />
                      <div className="newstextcontaineran">
                        <span className="tifaalltitletextnewsan">
                          {item.title.length > 80
                            ? `${item.title.slice(0, 80)}...`
                            : item.title}
                        </span>

                        <div className="newsListDiv1an">
                          <div className="newsListDiv2an">
                            <div className="newsListDiv3an" />
                            <div className="newsListDiv4an">
                              <div style={{ display: "flex" }}>
                                <div className="newsListDiv5an">
                                  <ThumbUpAltRoundedIcon className="likecommenticonan" />
                                  <span className="totalcommentsan">
                                    {item?.total_likes}
                                  </span>
                                </div>

                                <div className="newsListDiv6an">
                                  <MessageRoundedIcon className="likecommenticonan" />
                                  <span className="totalcommentsan">
                                    {item?.total_comments}
                                  </span>
                                </div>
                              </div>
                              <span className="newstextan">
                                {item?.created_at.slice(0, 10)}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </Paper>
                  </Grid>
                );
              })}
            </Grid>
          ) : (
            <Grid container className="news1an">
              {data?.map((item, index) => {
                return (
                  <Grid key={index} item>
                    <Paper
                      className="papernewsstyle1an"
                      onClick={() => {
                        dispatch(setSelectedNews(item));
                        history.push({
                          pathname: "/news",
                          search: `?news_id=${item?.id}&slug=${item?.slug}`,
                          state: {
                            newsId: item?.id,
                            slug: item?.slug,
                          },
                        });
                      }}
                      style={{
                        marginRight: (index + 1) % 3 === 0 ? 0 : "none",
                      }}
                      elevation={5}
                    >
                      <img
                        loading="lazy"
                        src={item?.thumb_img}
                        className="matchan"
                        alt={"match"}
                      />
                      <div className="newstextcontaineran">
                        <span className="tifaalltitletextnewsan">
                          {item.title.length > 80
                            ? `${item.title.slice(0, 80)}...`
                            : item.title}
                        </span>

                        <div className="newsListDiv1an">
                          <div className="newsListDiv2an">
                            <div className="newsListDiv3an" />
                            <div className="newsListDiv4an">
                              <div style={{ display: "flex" }}>
                                <div className="newsListDiv5an">
                                  <ThumbUpAltRoundedIcon className="likecommenticonan" />
                                  <span className="totalcommentsan">
                                    {item?.total_likes}
                                  </span>
                                </div>

                                <div className="newsListDiv6an">
                                  <MessageRoundedIcon className="likecommenticonan" />
                                  <span className="totalcommentsan">
                                    {item?.total_comments}
                                  </span>
                                </div>
                              </div>
                              <span className="newstextan">
                                {item?.created_at.slice(0, 10)}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </Paper>
                  </Grid>
                );
              })}
            </Grid>
          )
        ) : null}
      </div>
    </div>
  );
};

export default AllNews;
