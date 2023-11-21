/* eslint-disable import/no-anonymous-default-export */
export default {
  control: {
    backgroundColor: "#fff",
    fontSize: 14,
    fontWeight: "normal",
    borderRadius: 5,
    // border: "2px solid #ED0F18",
    minHeight: 20,
    padding: 0,
    width:
      window.innerWidth <= 450 ? 250 : window.innerWidth <= 750 ? 300 : 500,
  },

  highlighter: {
    overflow: "hidden",
  },

  input: {
    border: 0,
  },

  "&singleLine": {
    control: {
      fontFamily: "monospace",
      //   border: "2px solid #ED0F18",
    },

    highlighter: {
      padding: 5,
      marginTop: 10,
      border: "2px inset transparent",
    },

    input: {
      padding: "17px 10px",
      border: "none",
      outline: 0,
    },
  },

  suggestions: {
    list: {
      backgroundColor: "white",
      border: "1px solid rgba(0,0,0,0.15)",
      fontSize: 14,
    },
    item: {
      padding: "5px 15px",
      borderBottom: "1px solid rgba(0,0,0,0.15)",
      "&focused": {
        backgroundColor: "#ED0F18",
        color: "#fff",
      },
    },
  },
};
