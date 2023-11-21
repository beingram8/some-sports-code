import React from "react";
import {
  ReactVideo,
  ReactAudio,
  FacebookPlayer,
  YoutubePlayer,
  Image,
} from "reactjs-media";
import PropTypes from "prop-types";
import ReactPlayer from "react-player";
import { makeStyles } from "@material-ui/core/styles";
import ReactTwitchEmbedVideo from "react-twitch-embed-video";

const useStyles = makeStyles({
  videoDiv: {
    width: "100%",
    height: "200px",
  },
  videoDiv1: {
    width: "500px",
    height: "400px",
  },
});

const CVideoPlayer = (props) => {
  const classes = useStyles();
  const { src, audio, video, videoposter, isStream, onPlayVideo, fromWelcome } =
    props;

  const displayVideo = () => {
    if (src.includes("youtube.com") || src.includes("youtu.be")) {
      return (
        <YoutubePlayer
          className={fromWelcome ? classes.videoDiv1 : classes.videoDiv}
          src={src}
          width={"100%"}
          height={"100%"}
          allowFullScreen
        />
      );
    }

    if (src.includes(".mp4") || video) {
      return (
        <ReactVideo
          className={fromWelcome ? classes.videoDiv1 : classes.videoDiv}
          src={src}
          width={"100%"}
          height={"100%"}
          poster={videoposter}
          primaryColor="#ED0F18"
          onPlay={() => {
            onPlayVideo(true);
          }}
        />
      );
    }

    if (src.includes(".mp4") && audio) {
      return <ReactAudio src={src} />;
    }

    if (src.includes(".mkv")) {
      return (
        <video
          width="100%"
          controls
          src={src}
          onPlay={() => {
            onPlayVideo(true);
          }}
        />
      );
    }

    if (src.includes("www.facebook.com")) {
      return (
        <FacebookPlayer
          src={src}
          width={window.innerWidth > 600 ? 530 : "100%"}
          height={300}
        />
      );
    }

    if (src.includes(".jpg") || src.includes(".jpeg") || src.includes(".png")) {
      return (
        <Image
          src="/image.jpg"
          width={window.innerWidth > 600 ? 530 : "100%"}
          height={300}
        />
      );
    }

    if (isStream) {
      return (
        <div className="CVideoFixedHeight">
          <ReactTwitchEmbedVideo channel="fanrating" />
        </div>
      );
    }

    if (src === "") {
      return (
        <img
          className="CVideoPlayerImgSty"
          src={videoposter}
          alt="videoposter"
          loading="lazy"
        />
      );
    }

    if (src.includes("www.twitch.tv")) {
      return <ReactPlayer width={"100%"} url={src} controls />;
    }
  };

  return <div className="CVideoPlayerCon">{displayVideo()}</div>;
};

CVideoPlayer.propTypes = {
  src: PropTypes.string,
  audio: PropTypes.bool,
  video: PropTypes.bool,
  videoposter: PropTypes.string,
  isStream: PropTypes.bool,
  onPlayVideo: PropTypes.func,
};

CVideoPlayer.defaultProps = {
  src: "",
  audio: false,
  video: false,
  height: 0,
  width: 0,
  borderRadius: 0,
  videoposter: "",
  isStream: false,
  onPlayVideo: () => {},
};

export default CVideoPlayer;
