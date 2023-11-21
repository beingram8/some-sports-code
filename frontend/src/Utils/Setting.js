//const baseUrl = "https://live.fanratingweb.com/";
 const baseUrl = "http://localhost:20080/";
export const Setting = {
  baseUrl,
  api: baseUrl,

  Facebook_Pixel_ID: "3645066035596380",

  endpoints: {
    social_login: "v1/user/social-login",
    social_sign_up: "v1/user/social-sign-up",
    cms_detail: "v1/cms/cms-detail",
    team_list: "v1/team/list",
    list_by_league: "v1/team/list-by-league",
    league_list: "v1/league/leagues-for-guest",
    player_details: "v1/team/player-details",
    login: "v1/user/login",
    signup: "v1/user/signup",
    add_token: "v1/user/add-token",
    edit_profile: "v1/user/edit-profile",
    change_password: "v1/user/change-password",
    contact_us: "v1/user/contact-us",
    photo: "v1/user/photo",
    reset_password: "v1/user/reset-password",
    forgot_password: "v1/user/forgot-password",
    logout: "v1/user/logout",
    buy_vocher: "v1/user/buy-vocher",
    ranking: "v1/user/ranking",
    news_list_for_guest: "v1/news/list",
    news_list_for_user: "v1/news/list-for-user",
    news_detail: "v1/news/detail",
    news_comment: "v1/news/comment",
    news_like: "v1/news/like",
    comment_list: "v1/news/comment-list",
    product_list: "v1/product/list",
    product_Details: "v1/product/details",
    video_list: "v1/video/list",
    video_details: "v1/video/details",
    notification_list: "v1/notification/list",
    remove_all_noti: "v1/notification/remove-all",
    match_details: "v1/match/detail",
    match_vote: "v1/match/vote",
    match_vote_detail: "v1/match/vote-detail",
    city_data: "v1/system/city",
    education_data: "v1/system/education",
    job_data: "v1/system/job",
    matches_for_guest: "v1/match/matches-for-guest",
    matches_for_user: "v1/match/matches-for-user",
    quiz_list: "v1/quiz/question-list",
    quiz_store_answer: "v1/quiz/store-answer",
    quiz_result: "v1/quiz/quiz-result",
    quiz_details: "v1/quiz/quiz-details",
    survey_details: "v1/survey/details",
    survey_list: "v1/survey/list",
    survey_result: "/v1/survey/survey-result",
    store_survey_option: "v1/survey/store-option",
    dropdowns: "v1/system/dropdowns",
    common: "v1/system/common",
    stream_list: "v1/system/stream-list",
    verify_email: "v1/user/verify-email",
    my_team_list: "v1/user/my-team",
    transaction_list: "v1/user/token-transaction",
    token_plan: "v1/user/token-plan",
    payment_card_list: "v1/payment/card-list",
    add_new_card: "v1/payment/create-card",
    delete_card: "v1/payment/delete-card",
    make_payment: "v1/payment/make-payment",
    user_response: "v1/user/user-response",
    detail_for_guest: "v1/news/detail-for-guest",
    refer: "v1/user/refer",
    get_vote_card_share_url: "v1/match/get-vote-card-share-url",
    vote_card: "v1/match/vote-card",
    unlock_match_for_vote: "v1/match/unlock-match-for-vote",
    video_details_users: "/v1/video/details-for-users",
    badgeCount: "v1/notification/get-badge",
    team_winner: "v1/match/team-winners",
    stream_watched: "v1/video/stream-watched",
    user_ranking: "v1/user/user-ranking",
    teasing_post_list: "v1/teasing-room/post-list",
    teasing_comment_list: "v1/teasing-room/comment-list",
    teasing_post_like: "v1/teasing-room/like-post",
    teasing_add_comment: "v1/teasing-room/add-comment",
    teasing_post_detail: "v1/teasing-room/post-detail",
    purchase_level: "v1/payment/purchase-level",
    teasing_add_post: "v1/teasing-room/add-post",
    teasing_delete_post: "v1/teasing-room/delete-post",
    teasing_edit_post: "v1/teasing-room/edit-post",
    teasing_report_post: "v1/teasing-room/report-post",
    upload_document: "v1/user/add-document",
    purchase_level_by_token: "v1/payment/purchase-level-by-token",
    set_language: "v1/user/edit-language",
  },

  page_name: {
    APP_NAME: "Fan Rating",
    HELP: "Fan Rating - Help",
    ABOUT_US: "Fan Rating - About Us",
    BUY_TOKEN: "Fan Rating - Buy Token",
    CONTACT_US: "Fan Rating - Contact Us",
    EDIT_PROFILE: "Fan Rating - Edit Profile",
    INVITE_EARN: "Fan Rating - Invite & Earn",
    MY_TEAM: "Fan Rating - My Team",
    NEWS: "Fan Rating - News",
    NOTIFICATION: "Fan Rating - Notifications",
    OFFLINE: "Fan Rating - Yor are offline",
    PLAYER_PROFILE: "Fan Rating - Player Profile",
    PRIVACY_POLICY: "Fan Rating - Privacy Policy",
    RANKING_FOR_WINNER: "Fan Rating - Winners",
    RANKING: "Fan Rating - Ranking",
    RATE: "Fan Rating - Rate",
    RESET_PASSWORD: "Fan Rating - Reset Password",
    VOTE_DETAILS: "Fan Rating - Vote Details",
    TEAM_DETAILS: "Fan Rating - Team Details",
    TERMS_CONDITIONS: "Fan Rating - Terms & Conditions",
    TIFA: "Fan Rating - Tifa",
    VOTE_LEVEL: "Fan Rating - Vote Level",
    VERIFICATION: "Fan Rating - Verification",
    VINCI: "Fan Rating - Win",
    TEASING_ROOM: "Fan Rating - Teasing Room",
    TEASING_COMMENT: "Fan Rating - Teasing Room",
    TEASING_ROOM_ADD_POST: "Fan Rating - Add Post",
    ALL_NEWS: "Fan Rating - Add News",
    ALL_VIDEOS: "Fan Rating - Add Videos",
    WELCOME: "Fan Rating - Welcome",
    LOGIN: "Fan Rating - Login",
    REGISTER: "Fan Rating - Register",
  },

  ADS_CLIENT_ID: "ca-pub-4901256228757996",

  ads_Units: {
    TEST_BANNER_AD: "5649163024",
    TEST_FEED_AD: "3326252015",
    TEST_ARTICLE_AD: "6066776058",
  },
};

export const LANG_US = "en";
export const LANG_IT = "it";
export const LANG_SP = "es";
export const LANG_GE = "de";
export const LANG_CH = "cn";
export const LANG_AR = "ae";
export const LANG_FR = "fr";

export const FAN_SUBSCRIBER_TV = "Abbonato TV";
export const FAN_SEASON_TICKET = "Abbonato allo stadio";
export const FAN_OCCA = "Occasionale";