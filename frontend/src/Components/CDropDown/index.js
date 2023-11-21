import React, { useState } from "react";
import ArrowDropDownIcon from "@material-ui/icons/ArrowDropDown";
import ArrowDropUpIcon from "@material-ui/icons/ArrowDropUp";
import _ from "lodash";
import "./styles.scss";
import CButton from "../CButton/index";

const CDropDown = (props) => {
  const { data, value, placeholder, selectedColor, borderColor, onChange } =
    props;
  const [collapsed, setCollapsed] = useState(false);

  const [arrayData, setArraydata] = useState(data);

  const toggleExpand = () => {
    setCollapsed(!collapsed);
  };

  let status = [];

  // multiple select data
  const setdata = (item, index) => {
    status = arrayData;
    const arrayList = arrayData;
    let updateAry = [...arrayList];
    updateAry[index].check = !updateAry[index].check;
    status = updateAry;
    setArraydata(status);
    dataChange(status);
  };

  // clear dropdown selections
  const clearData = (item, index) => {
    status = arrayData;
    const arrayList = arrayData;
    let updateAry = [...arrayList];
    updateAry[index].check = false;
    status = updateAry;
    setArraydata(status);
    dataChange(status);
  };

  const dataChange = (data) => {
    const data1 = [];
    data.map((item) => {
      return item.check ? data1.push(item) : null;
    });

    onChange(data1);
  };

  return (
    <div className="mainContainerDD">
      <div
        onClick={() => {
          toggleExpand();
        }}
        style={{
          border: `1px solid ${borderColor}`,
          borderRadius: "5px",
        }}
      >
        <div className="DDDiv1">
          <div className="DDDiv2">
            {!_.isEmpty(value?.logo) ? (
              <img
                className="logoStyleDD"
                src={value?.logo}
                alt={"team_logo"}
              />
            ) : null}
            <span
              style={{
                marginLeft: 10,
              }}
            >
              {_.isObject(value) && !_.isEmpty(value)
                ? value?.label
                : placeholder}
            </span>
          </div>
          {collapsed ? (
            <ArrowDropUpIcon
              style={{
                color: selectedColor,
              }}
            />
          ) : (
            <ArrowDropDownIcon
              style={{
                color: selectedColor,
              }}
            />
          )}
        </div>
        {collapsed ? (
          <div className="DDDiv3">
            {_.isArray(arrayData) && !_.isEmpty(arrayData)
              ? arrayData?.map((item, index) => {
                  return (
                    <div
                      className="DDDiv4"
                      onClick={() => {
                        setdata(item, index);
                      }}
                      style={{
                        backgroundColor: item?.check ? selectedColor : "#FFF",
                      }}
                    >
                      <div className="DDDiv5">
                        <img
                          className="logoStyleDD"
                          src={item.logo}
                          alt={"team_logo"}
                        />
                        <span
                          className="DDDiv6"
                          style={{
                            color: item?.check ? "#fff" : "#000",
                          }}
                        >
                          {item?.label}
                        </span>
                      </div>
                    </div>
                  );
                })
              : null}
          </div>
        ) : null}
      </div>
      <div
        style={{
          width: "100%",
        }}
        className="flexCenterDiv"
      >
        <CButton
          buttonText={"Reset"}
          buttonStyle={{
            width: "100%",
            margin: "30px 0px -10px",
          }}
          handleBtnClick={() => {
            console.log("reset filter for team tag");
            arrayData?.map((item, index) => {
              return _.has(item, "check") ? clearData(item, index) : null;
            });
          }}
        />
      </div>
    </div>
  );
};

export default CDropDown;
