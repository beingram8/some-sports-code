import Help from "./Assets/Images/help.png";
import { getWords } from "./commonFunctions";
import Ranking from "./Assets/Images/ranking.png";
import ContactUs from "./Assets/Images/contact_us.png";
import buytokenlogo from "./Assets/Images/Buy Tokens.png";
import PrivacyPolicy from "./Assets/Images/privacy_policy.png";
import invitefriendslogo from "./Assets/Images/Invite friends.png";
import TermsAndCondition from "./Assets/Images/terms_and_conditions.png";
import Teasingroom from "./Assets/Images/teasingroomRed.png";
import AboutUsIcon from "./Assets/Images/aboutus_red.png";
import DownloadIcon from "./Assets/Images/download_red2.png";
import TifaAnim from "./Assets/Lottie/tifa.json";
import VotaAnim from "./Assets/Lottie/vota.json";
import VinciAnim from "./Assets/Lottie/vinci.json";
import LandingA from "./Assets/Images/LandingA.webp"
import LandingB from "./Assets/Images/LandingB.png"
import LandingC from "./Assets/Images/LandingC.png"

export const WelcomeScreenData = [
  {
    id: 1,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631547741-tifa.mp4",
    url: TifaAnim,
    videoPoster:
      "https://fanrating.fra1.digitaloceanspaces.com/stream/613f712a8d955.jpg",
    description: "News, highlights e dirette da gustare dove e quando vuoi!",
    title: "TIFA",
  },
  {
    id: 2,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631547898-vota.mp4",
    url: VotaAnim,
    videoPoster:
      "https://fanrating.fra1.digitaloceanspaces.com/stream/613f71657ce11.jpg",
    description:
      "Fai le pagelle, ottieni Fan Coins e scala la classifica dei Tifosi!",
    title: "VOTA",
  },
  {
    id: 3,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631548279-vinci.mp4",
    url: VinciAnim,
    videoPoster:
      "https://fanrating.fra1.digitaloceanspaces.com/stream/613f7220b3c99.jpg",
    description:
      "Buoni Amazon e altri fantastici premi ti aspettano nel Catalogo",
    title: "VINCI",
  },
  {
    id: 4,
    url: "https://www.youtube.com/embed/HxhxGZMWM_A",
    description: "Ora è tutto pronto per iniziare: TIFA, VOTA, VINCI!",
    title: "E DAI RETTA AD ARRIGO!",
  },
];

export const RateFilterTab = [
  {
    id: 1,
    title: "IN_PROGRESS",
  },
  {
    id: 2,
    title: "CONCLUDED",
  },
];

export const postOption = [
  {
    id: 1,
    option: "Segnala",
  },
  {
    id: 2,
    option: "Modificare",
  },
  {
    id: 3,
    option: "Elimina",
  },
];

export const TesingFilterTab = [
  {
    id: 1,
    title: "TEASING_ROOM",
  },
  {
    id: 2,
    title: "MY_POST",
  },
];

export const bottomTabData = [
  {
    id: 1,
    icon: "Bar",
    title: "TIFA",
    path: "tifa",
  },
  {
    id: 2,
    icon: "Home",
    title: "RATE_IT",
    path: "rate",
  },
  {
    id: 3,
    icon: "Star",
    title: "WIN",
    path: "winner",
  },
  {
    id: 4,
    icon: "Star",
    title: "FUN_VERSE",
    path: "funverse",
  },
];

export const DrawerData = [
  {
    id: 1,
    title: "RANKING",
    img: Ranking,
    path: "/ranking",
  },
  {
    id: 2,
    title: "MY_TEAM",
    img: Ranking,
    path: "/my-team",
  },
  {
    id: 9,
    title: "INVITE_AND_REFER",
    img: invitefriendslogo,
    path: "/invite-and-earn-tokens",
  },
  {
    id: 8,
    title: "BUY_TOKENS",
    img: buytokenlogo,
    path: "/buy-tokens",
  },
  {
    id: 5,
    title: "HELP",
    img: Help,
    path: "/help",
  },
  {
    id: 7,
    title: "ABOUT_US",
    img: AboutUsIcon,
    path: "/about-us",
  },
  {
    id: 11,
    title: "PRIVACY_POLICY",
    img: PrivacyPolicy,
    path: "/privacy-policy",
  },
  {
    id: 4,
    title: "TERMS_AND_CONDITION",
    img: TermsAndCondition,
    path: "/terms-and-condition",
  },
  {
    id: 6,
    title: "CONTACT_US",
    img: ContactUs,
    path: "/contact-us",
  },
  {
    id: 10,
    title: "INSTALL_APP_ON_IPHONE",
    img: DownloadIcon,
    path: "InstallApp",
  },
  {
    id: 11,
    title: "INSTALL_APP_ON_ANDROID",
    img: DownloadIcon,
    path: "https://play.google.com/store/apps/details?id=com.fanrating.twa",
  },
];

export const LandingPageData = [
  {
    id: 1,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631547741-tifa.mp4",
    url: LandingA,
    videoPoster: "https://fanrating.fra1.digitaloceanspaces.com/stream/613f712a8d955.jpg",
    description: "Support your Champions!",
    title: "",
    round: true
  },
  {
    id: 2,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631547741-tifa.mp4",
    url: LandingB,
    videoPoster: "https://fanrating.fra1.digitaloceanspaces.com/stream/613f712a8d955.jpg",
    description: "Rate the players and win Fan Coins and Points for the ranking!",
    title: "",
    round: false,
  },
  {
    id: 3,
    // url: "https://fanrating.fra1.digitaloceanspaces.com/stream/1631547741-tifa.mp4",
    url: LandingC,
    videoPoster: "https://fanrating.fra1.digitaloceanspaces.com/stream/613f712a8d955.jpg",
    description: "Use your Fan Coins to get fantastic rewards!",
    title: "",
    round: false
  },
]