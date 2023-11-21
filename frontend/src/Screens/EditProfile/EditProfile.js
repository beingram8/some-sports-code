/* eslint-disable react-hooks/rules-of-hooks */
/* eslint-disable react-hooks/exhaustive-deps */
import React, { useState, useEffect, useCallback } from "react";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import Select from "react-dropdown-select";
import Grid from "@material-ui/core/Grid";
import { useDispatch, useSelector } from "react-redux";
import moment from "moment";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { FAN_OCCA, FAN_SEASON_TICKET, FAN_SUBSCRIBER_TV, Setting } from "../../Utils/Setting";
import { getWords, addAnalyticsEvent, getTeamListByLeague } from "../../commonFunctions";
import Protected from "../../Components/Protected";
import { getAPIProgressData, getApiData } from "../../Utils/APIHelper";
import authActions from "../../Redux/reducers/auth/actions";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton";
import NotificationPopup from "../../Components/NotificationPopup";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import TeamSelectionModal from "../../Modals/TeamSelectionModal/index";
import TransferComplete from "../../Modals/TransferComplete";
import AMPAutoAd from "../../Components/Ads/AMPAutoAd";
import { useDropzone } from "react-dropzone";

const { setUserData } = authActions;

const genderOptions = [
  { id: 1, label: getWords("MALE"), value: getWords("MALE") },
  { id: 2, label: getWords("FEMALE"), value: getWords("FEMALE") },
  { id: 3, label: getWords("OTHER"), value: getWords("OTHER") },
];

function EditProfile() {
  const { userdata, leagueList, teamList } = useSelector((state) => state.auth);

  const header = {
    authorization: `Bearer ${userdata?.access_token}`,
  };

  const [uploadedDocument, setUploadedDocument] = useState([]);

  const dispatch = useDispatch();
  const matches = useMediaQuery("(min-width:1051px)");
  const [fnerrmsg, setFnErrMsg] = useState("");
  const [lnerrmsg, setLnErrMsg] = useState("");
  const [emailerrmsg, setEmailErrMsg] = useState("");
  const [doberrmsg, setDOBErrMsg] = useState("");
  const [unameerrmsg, setUNameErrMsg] = useState("");
  // const [fcodeerrmsg, setFCodeErrMsg] = useState('');
  const [eduerrmsg, setEduErrMsg] = useState("");
  const [phoneerrmsg, setPhoneErrMsg] = useState("");
  const [countryerrmsg, setCountryErrMsg] = useState("");
  const [joblevelerrmsg, setJobLevelErrMsg] = useState("");
  const [passworderrmsg, setPasswordErrMsg] = useState("");
  const [confirmpassworderrmsg, setConfirmPasswordErrMsg] = useState("");

  // const [dobval, setDobVal] = useState(0);
  const [fanerrmsg, setFanErrMsg] = useState("");
  const [gendererrmsg, setGenderErrMsg] = useState("");

  // change date format to DD/MM/YYYY
  const userDataDOB = userdata?.birth_date;
  const nd = moment(userDataDOB).format("DD/MM/YYYY");

  const [dob, setDOB] = useState(nd);
  const [displayDOBErr, setDisplayDOBError] = useState(false);

  const [fn, setFn] = useState(userdata?.firstname);
  const [ln, setLn] = useState(userdata?.lastname);
  const [email, setEmail] = useState(userdata?.email);
  const [uname, setUName] = useState(userdata?.username);
  const [edu, setEdu] = useState(userdata?.education);
  const [phone, setPhone] = useState(userdata?.phone);
  const [isteamselectionmodalopen, setIsTeamSelectionModalOpen] =
    useState(false);

  const [password, setPassword] = useState("");
  const [confirmpassword, setConfirmPassword] = useState("");

  const emailregex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  const [gender, setGender] = useState({});

  const [pageLoader, setPageLoader] = useState(true);
  const [fan, setFan] = useState(
    !_.isUndefined(userdata?.fan) ? userdata?.fan : ""
  );
  const [country, setCountry] = useState(
    !_.isUndefined(userdata?.country) ? userdata?.country : ""
  );
  const [joblevel, setJobLevel] = useState(
    !_.isUndefined(userdata?.job) ? userdata?.job : ""
  );

  const [cityData, setCityData] = useState([]);
  const [educationData, setEducationData] = useState([]);
  const [jobData, setJobData] = useState([]);
  const [countryData, setCountryData] = useState([]);
  const [fanData, setFanData] = useState([]);
  const [leagueSelectedData, setLeaugeSelectedData] = useState(userdata.league);
  const [teamErrMsg, setTeamErrMsg] = useState("");
  const [leagueErrMsg, setLeagueErrMsg] = useState("");

  const [saveBtnLoad, setSaveBtnLoad] = useState(false);
  const [savePwdBtnLoad, setsavePwdBtnLoad] = useState(false);
  const [saveDocBtnLoad, setsaveDocBtnLoad] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const [sTem, setSTeam] = useState(userdata?.team);
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  const [displayAnim, setDisplayAnim] = useState(false);

  const [displayERR, setDisplayERR] = useState(false);

  let fnameRef = React.createRef();
  let lnameRef = React.createRef();
  let emailRef = React.createRef();
  let dobRef = React.createRef();
  let usernameRef = React.createRef();
  let professionRef = React.createRef();
  let educationRef = React.createRef();
  let fanRef = React.createRef();
  let phoneRef = React.createRef();
  let CountryRef = React.createRef();

  let newPwdRef = React.createRef();
  let cNewPwdRef = React.createRef();

  useEffect(() => {
    setGenderSelectedData();
    getAllOtherData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.EDIT_PROFILE;
  }, []);
  useEffect(() => {
      if (leagueList.length > 0) {
          getTeamListByLeague(leagueSelectedData.id);
      }
  }, [leagueSelectedData, leagueList])

  useEffect(() => {
      if (teamList.length > 0) {
        if (leagueSelectedData.value === userdata.league.value) {
          setSTeam(userdata?.team)
        } else {
          setSTeam(teamList[0])
        }
      }  else {
        setSTeam([]);
      }
  }, [teamList])
  // uploaded file
  const onDrop = useCallback((acceptedFiles) => {
    // Do something with the files\
    console.log("acceptedFiles ========>>>>>> ", acceptedFiles);
    setUploadedDocument(acceptedFiles);
  }, []);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({ onDrop });

  // for future use
  // function getAge(dateString) {
  //   var today = new Date();
  //   var birthDate = new Date(dateString);
  //   var age = today.getFullYear() - birthDate.getFullYear();
  //   var m = today.getMonth() - birthDate.getMonth();
  //   if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
  //     age--;
  //   }
  //   return age;
  // }

  // upload document api call
  const uploadDocument = async () => {
    if (_.isArray(uploadedDocument) && !_.isEmpty(uploadedDocument)) {
      setsaveDocBtnLoad(true);
      let endPoint = Setting.endpoints.upload_document;
      const query = {
        "ParentConfirmation[document]": uploadedDocument[0],
      };

      try {
        const response = await getAPIProgressData(
          endPoint,
          "POST",
          query,
          true
        );

        if (response?.status) {
          setsaveDocBtnLoad(false);
          setUploadedDocument([]);
          showAlert(true, getWords("SUCCESS"), response?.message);
        } else {
          setsaveDocBtnLoad(false);
          // setUploadedDocument([]);
          showAlert(true, getWords("WARNING"), response?.message);
        }
      } catch (err) {
        console.log("Catch Part", err);
        setsaveDocBtnLoad(false);
        setUploadedDocument([]);
        setPageLoader(false);
        showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
      }
    } else {
      showAlert(true, getWords("WARNING"), getWords("PLEASE_SELECT_FILE"));
    }
  };

  async function getAllOtherData() {
    let endPoint = Setting.endpoints.dropdowns;
    try {
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("User_Check_Profile_Event", true);
      if (response && response.status && response.status === true) {
        if (_.isObject(response.data) && !_.isEmpty(response.data)) {
          const cData =
            response && response.data && response.data.cities
              ? response.data.cities
              : [];
          const eData =
            response && response.data && response.data.educations
              ? response.data.educations
              : [];
          const jData =
            response && response.data && response.data.jobs
              ? response.data.jobs
              : [];
          const countryData =
            response && response.data && response.data.countries
              ? response.data.countries
              : {};

          const fanData = [
            {
              'label': getWords('FAN_SUBSCRIBER_TV'),
              'value': FAN_SUBSCRIBER_TV,
            },
            {
              'label': getWords('FAN_SEASON_TICKET'),
              'value': FAN_SEASON_TICKET,
            },
            {
              'label': getWords('FAN_OCCA'),
              'value': FAN_OCCA,
            },
          ];
          setCityData(cData);
          setEducationData(eData);
          setCountryData(countryData);
          setJobData(jData);
          setPageLoader(false);
          setFanData(fanData);
        } else {
          setPageLoader(false);
        }
      } else {
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
      return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
      return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      return "iOS";
    }

    return "unknown";
  }

  function setGenderSelectedData() {
    if (userdata?.gender === "Male") {
      setGender(genderOptions[0]);
    } else if (userdata?.gender === "Female") {
      setGender(genderOptions[1]);
    } else {
      setGender(genderOptions[2]);
    }
  }

  function savepassword() {
    if (password === "") {
      setPasswordErrMsg(getWords("ENTER_YOUR_PASSWORD"));
      setConfirmPasswordErrMsg("");
    } else if (password.length < 8) {
      setPasswordErrMsg(getWords("PASSWORD_MUST_BE_BETWEEN_8_AND_12"));
      setConfirmPasswordErrMsg("");
    } else if (password !== confirmpassword) {
      setConfirmPasswordErrMsg(getWords("BOTH_PASSWORDS_MUST_BE_SAME"));
      setPasswordErrMsg("");
    } else {
      setConfirmPasswordErrMsg("");
      setPasswordErrMsg("");
      savePwdProcess();
    }
  }

  async function savePwdProcess() {
    setsavePwdBtnLoad(true);
    const pwdData = {
      // "ChangePasswordForm[old_password]": "12345678",
      "ChangePasswordForm[password]": password,
      "ChangePasswordForm[cpassword]": confirmpassword,
    };

    try {
      let endPoint = Setting.endpoints.change_password;
      const response = await getAPIProgressData(
        endPoint,
        "POST",
        pwdData,
        true
      );
      if (response?.status) {
        // Display Success Mesage Here
        showAlert(true, getWords("SUCCESS"), response?.message);
        addAnalyticsEvent("Change_Password_Event", true);
      } else {
        // Display Error Message Here Msg = response?.message
        const ErrorMsg = response?.message;
        setsavePwdBtnLoad(false);
        showAlert(true, getWords("OOPS"), ErrorMsg);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setsavePwdBtnLoad(false);
      // Display Something went wrong Message Here
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  function saveprofile() {
    let valid = true;

    if (fn === "") {
      setDisplayERR(true);
      setFnErrMsg(getWords("ENTER_YOUR_FIRST_NAME"));
      return (valid = false);
    }
    // else if (nameRegex.test(fn)) {
    //   setDisplayERR(true);
    //   setFnErrMsg(getWords("ENTER_VALID_FIRSTNAME"));
    //   return (valid = false);
    // }
    else if (ln === "") {
      setDisplayERR(true);
      setLnErrMsg(getWords("ENTER_YOUR_LAST_NAME"));
      return (valid = false);
    }
    // else if (nameRegex.test(ln)) {
    //   setDisplayERR(true);
    //   setLnErrMsg(getWords("ENTER_VALID_LASTNAME"));
    //   return (valid = false);
    // }
    else if (email === "") {
      setEmailErrMsg(getWords("ENTER_YOUR_EMAIL"));
      return (valid = false);
    } else if (!emailregex.test(email)) {
      setEmailErrMsg(getWords("PLEASE_ENTER_VALID_EMAIL_ADDRESS"));
      return (valid = false);
    } else if (dob === "") {
      setDOBErrMsg(getWords("ENTER_YOUR_DATE_OF_BIRTH"));
      setDisplayDOBError(true);
      return (valid = false);
    }
    // else if (getAge(dob) < 13) {
    //   setDOBErrMsg(getWords("PLEASE_ENTER_VALID_BIRTHDATE"));
    //   setDisplayDOBError(true);
    //   return (valid = false);
    // }
    // else if (todaydate < dobval) {
    //   setDOBErrMsg(getWords("PLEASE_ENTER_VALID_BIRTHDATE"));
    //   return (valid = false);
    // }
    else if (uname === "") {
      setDisplayERR(true);
      setUNameErrMsg(getWords("ENTER_YOUR_USER_NAME"));
      return (valid = false);
    }
    // else if (!usernameRegex.test(uname)) {
    //   setDisplayERR(true);
    //   setUNameErrMsg(getWords("ENTER_VALID_USERNAME"));
    //   return (valid = false);
    // }
    else if (gender === "") {
      setGenderErrMsg(getWords("SELECT_YOUR_GENDER"));
      return (valid = false);
    } else if (joblevel?.label === "") {
      setJobLevelErrMsg(getWords("SELECT_YOUR_JOB_LEVEL"));
      return (valid = false);
    } else if (edu?.label === "") {
      setEduErrMsg(getWords("SELECT_YOUR_EDUCATION"));
      return (valid = false);
    } else if (fan?.label === "") {
      setFanErrMsg(getWords("SELECT_AN_OPTION"));
      return (valid = false);
    }  else if (!(_.isObject(sTem) && _.has(sTem, "id"))) {
        setTeamErrMsg(getWords("SELECT_TEAM"));
        return (valid = false);
    } else if (!(_.isObject(leagueSelectedData) && _.has(leagueSelectedData, "id"))) {
        setLeagueErrMsg(getWords("SELECT_LEAUGE"));
        return (valid = false);
    } else if (phone === "" || phone === undefined || phone === null) {
      setPhoneErrMsg(getWords("ENTER_YOUR_PHONE"));
      return (valid = false);
    } else if (country?.label === "") {
      setCountryErrMsg(getWords("SELECT_YOUR_COUNTRY"));
      return (valid = false);
    } else if (dob.length === 10) {
      const v = ValidateDate();
      if (v) {
        editProfileProcess();
      }
    }
    return valid;
  }

  function editProfileProcess() {
    setFnErrMsg("");
    setLnErrMsg("");
    setEmailErrMsg("");
    setUNameErrMsg("");
    setDOBErrMsg("");
    setPhoneErrMsg("");
    setEduErrMsg("");
    setJobLevelErrMsg("");
    setGenderErrMsg("");
    UpdateProfileProcess();
  }

  // vaidate birthdate
  const ValidateDate = () => {
    let isValid = true;
    const dtArray = dob.split("/");
    const dd = _.toNumber(dtArray[0]);
    const mm = _.toNumber(dtArray[1]);
    const yy = _.toNumber(dtArray[2]);
    const today1 = moment().format("YYYY");

    const age = today1 - yy;
    const listOfDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (mm === 1 || (mm > 2 && mm <= 12)) {
      if (dd > listOfDays[mm - 1]) {
        setDOBErrMsg(getWords("VALID_DATE"));
        setDisplayDOBError(true);
        return (isValid = false);
      }
    } else if (mm === 2) {
      let leapYear = false;
      if ((!(yy % 4) && yy % 100) || !(yy % 400)) {
        leapYear = true;
      } else if (!leapYear && dd >= 29) {
        setDOBErrMsg(getWords("NOT_LEAP_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (leapYear && dd > 29) {
        setDOBErrMsg(getWords("INVLAID_LEAP_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      }
    } else {
      setDOBErrMsg(getWords("INVALID_MONTH"));
      setDisplayDOBError(true);
      return (isValid = false);
    }
    if (isValid) {
      if (today1 < yy && age < 0) {
        setDOBErrMsg(getWords("FUTURE_DATE"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (yy <= 1920) {
        setDOBErrMsg(getWords("INVALID_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (today1 === yy) {
        setDOBErrMsg(getWords("ALTEAST_EIGHTEEN"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (isValid && today1 > yy && age < 13) {
        setDOBErrMsg(getWords("ALTEAST_EIGHTEEN"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else {
        setDisplayDOBError(false);
        return (isValid = true);
      }
    }
    return isValid;
  };

  async function UpdateProfileProcess() {
    setSaveBtnLoad(true);

    const dtArray = dob.split("/");
    const dd = dtArray[0];
    const mm = dtArray[1];
    const yy = dtArray[2];
    const newDOB = `${yy}-${mm}-${dd}`;
    const userUpdatedData = {
      "UserEditForm[first_name]": fn,
      "UserEditForm[last_name]": ln,
      "UserEditForm[username]": uname,
      "UserEditForm[gender]": gender.id,
      "UserEditForm[birth_date]": newDOB,
      "UserEditForm[phone]": phone,
      "UserEditForm[country_id]": country.value,
      "UserEditForm[education_id]": edu.value,
      "UserEditForm[job_id]": joblevel.value,
      "UserEditForm[fan]": fan.value,
      // "UserEditForm[fiscal_code]": "1", // Remove by Client
      "UserEditForm[lang]": "it", // Fixed for Italian Language
      "UserEditForm[team_id]": sTem?.id,
      "UserEditForm[league_id]": _.isObject(leagueSelectedData) && _.has(leagueSelectedData, "id")
                ? leagueSelectedData.id
                : "",
    };

    try {
      let endPoint = Setting.endpoints.edit_profile;
      const response = await getAPIProgressData(
        endPoint,
        "POST",
        userUpdatedData,
        true
      );
      if (response?.status) {
        showAlert(true, getWords("SUCCESS"), getWords("PROFILE_UPDATE_MESSAGE"));
        const uData = response?.data;
        dispatch(setUserData(uData));
        setTimeout(() => {
          addAnalyticsEvent("Update_Profile_Data_Event", true);
        }, 1000);
        if (
          _.isObject(response?.data) &&
          !_.isEmpty(response?.data) &&
          _.has(response?.data, "is_animation") &&
          response?.data?.is_animation
        ) {
          setIsEarnedcoin(true);
        } else {
          setIsEarnedcoin(false);
        }
      } else {
        //display error msg here
        setSaveBtnLoad(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      //display something went wrong error msg here
      setSaveBtnLoad(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  const showAlert = (open, title, message) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
  };

  const clearPwdData = () => {
    setSaveBtnLoad(false);
    setsavePwdBtnLoad(false);
    setPassword("");
    setConfirmPassword("");
  };

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
          clearPwdData();
          if (isearnedcoin) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setDisplayAnim(false);
            }, 3000);
          }
        }}
        onOkay={() => {
          setAlertOpen(false);
          clearPwdData();
          if (isearnedcoin) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setDisplayAnim(false);
            }, 3000);
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // handle back press for date
  function keyPressFunc(e) {
    if (e.which === 8) {
      var val = dob;
      console.log(val);
      if (val.length === 3 || val.length === 6) {
        val = val.slice(0, val.length - 1);
        setDOB(val);
      }
    }
  }

  // handle change for date
  function handleChange(val) {
    if (val.length === 2) {
      val += "/";
    } else if (val.length === 5) {
      val += "/";
    }
    setDOB(val);
  }

  // handle enter key from keyboard
  function handleKeyEnter(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    if (e.which === 13) {
      switch (e.target.id) {
        case "firstname":
          lnameRef.current.focus();
          break;

        case "lastname":
          emailRef.current.focus();
          break;

        case "email":
          dobRef.current.focus();
          break;

        case "dob":
          usernameRef.current.focus();
          break;

        case "username":
          usernameRef.current.blur();
          break;

        default:
          break;
      }
    }
  }

  function handleKeyEnter1(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    if (e.which === 13) {
      switch (e.target.id) {
        case "password":
          cNewPwdRef.current.focus();
          break;

        case "confirmpassword":
          cNewPwdRef.current.blur();
          savepassword();
          break;

        default:
          break;
      }
    }
  }

  // const changeHandler = (event) => {
  //   // setSelectedFile(event.target.files[0]);
  //   // setIsSelected(true);
  //   console.log("file ======>>>> ", event.target.files[0]);
  // };

  if (pageLoader) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <CRequestLoader
            openModal={pageLoader}
            handleClose={() => {
              setPageLoader(false);
            }}
          />
        </div>
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <meta
          name="viewport"
          content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
        />
        <div className="CommonContainer editprofilemain">
          <div
            className={
              getMobileOperatingSystem() === "iOS"
                ? "editprofilemaindiv1"
                : "editprofilemaindiv"
            }
          >
            <div className="editprofilesubmaindiv">
              <div>
                <AMPAutoAd />
              </div>
              <span className="editprofiletext">
                {getWords("EDIT_PROFILE")}
              </span>
              <span className="profiletext">{getWords("YOUR_PROFILE")}</span>
              <div>
                <Grid
                  container
                  justify="space-between"
                  alignContent="space-between"
                  className="epfieldscontainer"
                >
                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("FIRST_NAME")}
                    </span>
                    <div>
                      <input
                        autoComplete="false"
                        type="text"
                        id="firstname"
                        name="firstname"
                        ref={fnameRef}
                        maxLength={10}
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          saveBtnLoad
                            ? null
                            : (val) => {
                              setFn(val.target.value);
                            }
                        }
                        value={fn}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                      />
                    </div>
                    {displayERR ? (
                      <span className="editprofileerrormsg">{fnerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("LAST_NAME")}
                    </span>
                    <div>
                      <input
                        autoComplete="false"
                        type="text"
                        id="lastname"
                        name="lastname"
                        ref={lnameRef}
                        maxLength={10}
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          saveBtnLoad
                            ? null
                            : (val) => {
                              setLn(val.target.value);
                            }
                        }
                        value={ln}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                      />
                    </div>
                    {displayERR ? (
                      <span className="editprofileerrormsg">{lnerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("EMAIL")}</span>
                    <div>
                      <input
                        autoComplete="false"
                        type="text"
                        id="email"
                        name="email"
                        ref={emailRef}
                        readOnly
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          saveBtnLoad
                            ? null
                            : (val) => {
                              setEmail(val.target.value);
                            }
                        }
                        value={email}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                      />
                    </div>
                    {email === "" ? (
                      <span className="editprofileerrormsg">{emailerrmsg}</span>
                    ) : !emailregex.test(email) ? (
                      <span className="editprofileerrormsg">{emailerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("DATE_OF_BIRTH")}
                    </span>
                    <div>
                      <input
                        autoComplete="false"
                        id="dob"
                        name="dob"
                        ref={dobRef}
                        className="editprofiledob"
                        type="text"
                        maxLength={10}
                        placeholder="DD/MM/YYYY"
                        value={dob}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                        onChange={(text) => {
                          const date = text.target.value;
                          handleChange(date);
                        }}
                        onKeyDown={keyPressFunc}
                      />
                    </div>
                    {displayDOBErr ? (
                      <span className="editprofileerrormsg">{doberrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("USERNAME")}</span>
                    <div>
                      <input
                        autoComplete="false"
                        type="text"
                        id="username"
                        name="username"
                        ref={usernameRef}
                        maxLength={10}
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          saveBtnLoad
                            ? null
                            : (val) => {
                              setUName(val.target.value);
                            }
                        }
                        value={uname}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                      />
                    </div>
                    {displayERR ? (
                      <span className="editprofileerrormsg">{unameerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("GENDER")}</span>
                    <Select
                      name="gender"
                      id="gender"
                      className="editprofiledropdownselect"
                      options={genderOptions}
                      color="#ED0F1B"
                      onChange={
                        saveBtnLoad
                          ? null
                          : (values) => {
                            setGender(values[0]);
                          }
                      }
                      values={[gender]}
                    />
                    {gender?.label === "" ? (
                      <span className="editprofileerrormsg">
                        {gendererrmsg}
                      </span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("PROFESSION")}
                    </span>
                    <div>
                      <Select
                        name="joblevel"
                        id="joblevel"
                        ref={professionRef}
                        className="editprofiledropdownselect"
                        options={jobData}
                        color="#ED0F1B"
                        onChange={
                          saveBtnLoad
                            ? null
                            : (values) => {
                              setJobLevel(values[0]);
                            }
                        }
                        values={[joblevel]}
                      />
                    </div>
                    {joblevel?.label === "" ? (
                      <span className="editprofileerrormsg">
                        {joblevelerrmsg}
                      </span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("EDUCATION")}
                    </span>
                    <div>
                      <Select
                        name="education"
                        id="education"
                        className="editprofiledropdownselect"
                        options={educationData}
                        ref={educationRef}
                        color="#ED0F1B"
                        onChange={
                          saveBtnLoad
                            ? null
                            : (values) => {
                              setEdu(values[0]);
                            }
                        }
                        values={[edu]}
                      />
                    </div>
                    {edu?.label === "" ? (
                      <span className="editprofileerrormsg">{eduerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("FAN")}</span>
                    <div>
                      <Select
                        name="fan"
                        id="fan"
                        ref={fanRef}
                        className="editprofiledropdownselect"
                        options={fanData}
                        color="#ED0F1B"
                        onChange={
                          saveBtnLoad
                            ? null
                            : (values) => {
                              setFan(values[0]);
                            }
                        }
                        values={[fan]}
                      />
                    </div>
                    {fan?.label === "" ? (
                      <span className="editprofileerrormsg">{fanerrmsg}</span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("Phone")}</span>
                    <div>
                      <input
                        autoComplete="false"
                        type="text"
                        id="phone"
                        name="phone"
                        ref={phoneRef}
                        maxLength={10}
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          saveBtnLoad
                            ? null
                            : (val) => {
                              setPhone(val.target.value);
                            }
                        }
                        value={phone ?? ""}
                        onKeyPress={(e) => {
                          handleKeyEnter(e);
                        }}
                      />
                      <div>
                        {phoneerrmsg ? (
                          <span className="editprofileerrormsg">{phoneerrmsg}</span>
                        ) : null}
                      </div>
                    </div>
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("COUNTRY")}</span>
                    <div>
                      <Select
                        name="country"
                        id="country"
                        ref={CountryRef}
                        className="editprofiledropdownselect"
                        options={countryData}
                        color="#ED0F1B"
                        onChange={
                          saveBtnLoad
                            ? null
                            : (values) => {
                              setCountry(values[0]);
                              // saveprofile();
                            }
                        }
                        values={[country]}
                      />
                    </div>
                    {country?.label === "" ? (
                      <span className="editprofileerrormsg">
                        {countryerrmsg}
                      </span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">{getWords("LEAGUE")}</span>
                    <div>
                      <Select
                          name="leaugelist"
                          id="leaguelist"
                          multi={false}
                          className="editprofiledropdownselect"
                          options={leagueList}
                          color="#ED0F1B"
                          onChange={
                              saveBtnLoad
                                  ? null
                                  : (values) => {
                                      setLeaugeSelectedData(values[0]);
                                  }
                          }
                          values={[leagueSelectedData]}
                      />
                    </div>
                    {leagueErrMsg !== "" ? (
                      <span className="editprofileerrormsg">
                        {leagueErrMsg}
                      </span>
                    ) : null}
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("CHANGE_TEAM")}
                    </span>
                    <CButton
                      buttonStyle={{
                        position: "unset",
                        marginTop: 10,
                        height: 17,
                        backgroundColor: "transparent",
                        border: "1px solid #ed0f1b",
                        width:
                          window.innerWidth > 1650
                            ? 345
                            : window.innerWidth > 1440
                              ? 294
                              : window.innerWidth > 1280
                                ? 244
                                : window.innerWidth > 1200
                                  ? 294
                                  : window.innerWidth > 1050
                                    ? 254
                                    : "calc(100% - 22px)",
                      }}
                      textcolor="#000"
                      selectedteamicon={sTem.logo}
                      buttonText={
                        !_.isEmpty(sTem.name) || !_.isNull(sTem.name)
                          ? sTem.name
                          : "Select Team"
                      }
                      handleBtnClick={() => {
                        setIsTeamSelectionModalOpen(true);
                      }}
                    />
                    {teamErrMsg !== "" ? (
                      <span className="editprofileerrormsg">
                        {teamErrMsg}
                      </span>
                    ) : null}
                  </div>
                </Grid>
              </div>

              {/* save profile button */}
              <div>
                <CButton
                  btnLoader={saveBtnLoad}
                  boldText={true}
                  buttonStyle={{
                    bottom: -10,
                    width:
                      window.innerWidth > 1650
                        ? 345
                        : window.innerWidth > 1440
                          ? 294
                          : window.innerWidth > 1280
                            ? 244
                            : window.innerWidth > 1200
                              ? 294
                              : window.innerWidth > 1050
                                ? 254
                                : window.innerWidth > 500
                                  ? "calc(46% - 20px)"
                                  : "calc(100% - 20px)",
                    margin: "25px 0px 30px 0px",
                  }}
                  buttonText={getWords("SAVE_PROFILE")}
                  handleBtnClick={() => {
                    if (saveBtnLoad) {
                      return;
                    } else {
                      saveprofile();
                    }
                  }}
                />
              </div>

              {/* change password */}
              <span className="changepasswordtext">
                {getWords("CHANGE_PASSWORD")}
              </span>

              <div>
                <Grid
                  container
                  justify="space-between"
                  alignContent="space-between"
                  className="epfieldscontainer2"
                >
                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("NEW_PASSWORD")}
                    </span>
                    <div>
                      <input
                        autoComplete="false"
                        type="password"
                        id="password"
                        name="password"
                        ref={newPwdRef}
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          savePwdBtnLoad
                            ? null
                            : (val) => {
                              setPassword(val.target.value);
                            }
                        }
                        value={password}
                        onKeyPress={(e) => {
                          handleKeyEnter1(e);
                        }}
                      />
                    </div>
                    <span className="editprofileerrormsg">
                      {passworderrmsg}
                    </span>
                  </div>

                  <div className="divStyle">
                    <span className="titleStyleEP">
                      {getWords("CONFIRM_NEW_PASSWORD")}
                    </span>
                    <div>
                      <input
                        autoComplete="false"
                        type="password"
                        id="confirmpassword"
                        name="confirmpassword"
                        className={
                          getMobileOperatingSystem() === "iOS"
                            ? "editprofileinputtext1"
                            : "editprofileinputtext"
                        }
                        onChange={
                          savePwdBtnLoad
                            ? null
                            : (val) => {
                              setConfirmPassword(val.target.value);
                            }
                        }
                        value={confirmpassword}
                        ref={cNewPwdRef}
                        onKeyPress={(e) => {
                          handleKeyEnter1(e);
                        }}
                      />
                    </div>
                    <span className="editprofileerrormsg">
                      {confirmpassworderrmsg}
                    </span>
                  </div>
                  {matches ? (
                    <div className="divStyle">
                      <div>
                        <input
                          autoComplete="false"
                          type="password"
                          className="editprofileinputtext editprofileborder"
                          disabled
                        />
                      </div>
                    </div>
                  ) : null}
                </Grid>
              </div>

              {/* save password button */}
              <div>
                <CButton
                  boldText={true}
                  btnLoader={savePwdBtnLoad}
                  buttonStyle={{
                    bottom: -10,
                    width:
                      window.innerWidth > 1650
                        ? 345
                        : window.innerWidth > 1440
                          ? 294
                          : window.innerWidth > 1280
                            ? 244
                            : window.innerWidth > 1200
                              ? 294
                              : window.innerWidth > 1050
                                ? 254
                                : window.innerWidth > 500
                                  ? "calc(46% - 20px)"
                                  : "calc(100% - 20px)",
                    margin: "25px 0px 30px 0px",
                  }}
                  buttonText={getWords("SAVE_PASSWORD")}
                  handleBtnClick={() => {
                    if (savePwdBtnLoad) {
                      return;
                    } else {
                      savepassword();
                    }
                  }}
                />
              </div>

              {/* upload document for phase 3 */}
              {/* <span className="changepasswordtext">
                {getWords("UPLOAD_DOCUMENT")}
              </span>
              <div>
                <Grid
                  container
                  justify="space-between"
                  alignContent="space-between"
                  className="epfieldscontainer2"
                >
                  <div className="divStyle">
                    <span className="titleStyleEP">{"Upload file"}</span>
                    <div
                      style={{
                        padding: 10,
                        cursor: "pointer",
                        display: "flex",
                        alignItems: "center",
                      }}
                      className={
                        getMobileOperatingSystem() === "iOS"
                          ? "editprofileinputtext1"
                          : "editprofileinputtext"
                      }
                    >
                      <div {...getRootProps()}>
                        <input {...getInputProps()} />
                        {isDragActive ? (
                          <span
                            style={{
                              color: "#656565",
                            }}
                          >
                            Drop the files here ...
                          </span>
                        ) : !_.isEmpty(uploadedDocument) ? (
                          <span
                            style={{
                              color: "#656565",
                            }}
                          >
                            File to be uploaded: {uploadedDocument[0].name}
                          </span>
                        ) : (
                          <span
                            style={{
                              color: "#656565",
                            }}
                          >
                            Drag 'n' drop some files here, or click to select
                            files
                          </span>
                        )}
                      </div>
                    </div>
                  </div>
                </Grid>
              </div> */}

              {/* save document button */}
              {/* <div>
                <CButton
                  boldText={true}
                  btnLoader={saveDocBtnLoad}
                  buttonStyle={{
                    bottom: -10,
                    width:
                      window.innerWidth > 1650
                        ? 345
                        : window.innerWidth > 1440
                        ? 294
                        : window.innerWidth > 1280
                        ? 244
                        : window.innerWidth > 1200
                        ? 294
                        : window.innerWidth > 1050
                        ? 254
                        : window.innerWidth > 500
                        ? "calc(46% - 20px)"
                        : "calc(100% - 20px)",
                    margin: "25px 0px 30px 0px",
                  }}
                  buttonText={getWords("SAVE_DOCUMENT")}
                  handleBtnClick={() => {
                    if (saveDocBtnLoad) {
                      return;
                    } else {
                      uploadDocument();
                    }
                  }}
                />
              </div> */}
            </div>
          </div>
        </div>
        {renderAlert()}
        <TeamSelectionModal
          sTem={sTem}
          openDialog={isteamselectionmodalopen}
          onSave={(value) => {
            setSTeam(value);
            setIsTeamSelectionModalOpen(false);
          }}
          handleClose={() => {
            setIsTeamSelectionModalOpen(false);
          }}
        />
        <NotificationPopup />
        <TransferComplete
          animationtype="coinrotation"
          openModal={displayAnim}
          handleClose={() => {
            setTimeout(() => {
              setDisplayAnim(false);
            }, 1000);
          }}
        />
      </div>
    </Protected>
  );
}

export default EditProfile;
