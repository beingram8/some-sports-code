import React from "react";
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import Card from "./Card";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import { withStyles } from "@material-ui/core/styles";
import "./styles.scss";

// const stripePromise = loadStripe(
//   "pk_test_51JDokBBoivOMTJu4orcCGr1EBc0w3PLdbQmnhvVlwZwVdZKR6QZZljK4BbKYEGARD8PVOxqrJoDfSxipE2ThOQJo00ELIEf1oY"
// );

// new key as on 23 aug 2021
const stripePromise = loadStripe(
  "pk_live_51JDokBBoivOMTJu4J4CGTBNkorqxtHZWSX37JoKHcRFoPxHbVTREySVozU0mBalb7aXjUlYQHkkHytXbky52cwRF00o5pygToE"
);

// pk_test_51JDokBBoivOMTJu4orcCGr1EBc0w3PLdbQmnhvVlwZwVdZKR6QZZljK4BbKYEGARD8PVOxqrJoDfSxipE2ThOQJo00ELIEf1oY; // Public Key
// sk_test_51JDokBBoivOMTJu4atDBUHHVjijus9sfFEc7riDlNjvwXhrISqDbBIeNArXuGcK2AhjXdGk30IwTAe09jeEcpKoh00ylRL2rMd // Secret Key

const DialogContent = withStyles((theme) => ({
  root: {
    // width: 400,
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

export default function StripeCard(props) {
  const {
    handleClose,
    selectedPlan,
    openDialog,
    onPaymentClick,
    payLoader,
    from,
  } = props;
  return (
    <Elements stripe={stripePromise}>
      <Dialog
        onClose={handleClose}
        open={openDialog}
        // className="confirmmaindialog"
      >
        <DialogContent className="paymentDialog">
          {from === "UserLevel" ? (
            <Card
              onClose={handleClose}
              selectedPlan={selectedPlan}
              onPaymentClick={(defaultCard) => {
                if (onPaymentClick) {
                  onPaymentClick(defaultCard);
                }
              }}
              payLoader={payLoader}
            />
          ) : (
            <Card onClose={handleClose} selectedPlan={selectedPlan} />
          )}
        </DialogContent>
      </Dialog>
    </Elements>
  );
}
